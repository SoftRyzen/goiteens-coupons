<?php
namespace goit_prmcode\helper;

class connector
{
	private $zohoCrmApi;
	public $data = array();


	private $prices = array(
		"1819773000501969001" => array(
			"price" => 1,
			"fopid" => 'test'
		)
	);

	public function __construct($dataArr, $zoho)
	{
		//Create zohoCrmApi object
		$this->zohoCrmApi = $zoho;

		//Set data from request
		$this->setData($dataArr['Lead']);
		//Execute
		$this->run();
	}

	public function setData($dataArr)
	{
		$this->data['name'] = isset($dataArr['name']) ? $dataArr['name'] : '';
		$this->data['Description'] = isset($dataArr['Comments']) ? $dataArr['Comments'] : '';
		$this->data['phone'] = isset($dataArr['phone']) ? $this->cleanPhone($dataArr['phone']) : '';
		$this->data['email'] = isset($dataArr['email']) ? $dataArr['email'] : '';
		$this->data['product_id'] = isset($dataArr['product_id']) ? $dataArr['product_id'] : '';
		$this->data['product_name'] = isset($dataArr['product_name']) ? $dataArr['product_name'] : '';
		$this->data['product_name'] = str_replace("/", "-", $this->data['product_name']);
	}


	public function run()
	{
		//search contact and set contact_id
		$this->searchContact();

		if (empty($this->data['contact_id'])) {
			//create contact
			$this->createContact();
		}
		//create deal
		$this->createDeal();
		$this->getPrice($this->data['product_id']);
		$this->createInvoice();
	}

	public function searchContact()
	{
		if (!empty($this->data['email']) && !empty($this->data['phone'])) {
			$criteria = "select Phone, Email from Contacts where Email = '" . $this->data['email'] . "' or Phone = '" . $this->data['phone'] . "' limit 1";

		} else if (!empty($this->data['email']) && empty($this->data['phone'])) {
			$criteria = "select Phone, Email from Contacts where Email = '" . $this->data['email'] . "' limit 1";

		} else if (empty($this->data['email']) && !empty($this->data['phone'])) {
			$criteria = "select Phone, Email from Contacts where Phone = '" . $this->data['phone'] . "' limit 1";
		}

		if (!empty($criteria)) {
			$this->data['contact_id'] = $this->zohoCrmApi->searchByCriteria('Contacts', $criteria, 'id');
		}
		echo "contact_id <pre> ";
		var_dump($this->data['contact_id']);
		echo "</pre> ";
	}

	private function getPrice($prodID)
	{
		if (isset($this->prices[$prodID])) {
			$this->data['price'] = $this->prices[$prodID]['price'];
			$this->data['fopid'] = $this->prices[$prodID]['fopid'];
		}
	}

	//Create contact and set id
	public function createContact()
	{
		$contact_data = array(
			"First_Name"  => (string) $this->data['name'],
			"Last_Name"   => "_",
			"Phone"       => (string) $this->data['phone'],
			"Email"       => $this->data['email'],
			"Layout"      => 1819773000000091033,
			"Owner"       => "1819773000001861117",
			"Description" => $this->data['Description']
		);
		$contact_data['First_Name'] = (string) $this->data['name'];
		$this->data['contact_id'] = $this->zohoCrmApi->createRecord('Contacts', $contact_data, 'id', 'approval, workflow, blueprint');
	}


	//Create deal and set id
	public function createDeal()
	{
		$deal_data = array(
			"Deal_Name"    => sprintf('%s - %s', $this->data['name'], $this->data['product_name']),
			"Contact_Name" => $this->data['contact_id'],
			"Owner"        => "1819773000001861117",
			"Phone"        => (string) $this->data['phone'],
			"Email"        => $this->data['email'],
			"Layout"       => 1819773000048296005,
			"Stage"        => "Distribution",
			"Course"       => $this->data['product_id'],
			"Description"  => (string) $this->data['Description'],
			"google_id"    => (string) $this->data['google_id'],
			"utm_content"  => (string) $this->data['utm_content'],
			"utm_medium"   => (string) $this->data['utm_medium'],
			"utm_source"   => (string) $this->data['utm_source'],
			"utm_term"     => (string) $this->data['utm_term'],
			"utm_campaign" => (string) $this->data['utm_campaign'],
			"fill17"       => (string) "Way4Pay",
		);
		$this->data['deal_id'] = $this->zohoCrmApi->createRecord('Deals', $deal_data, 'id', 'approval, workflow, blueprint');
	}

	private function createInvoice()
	{
		$Inv_data = array(
			"Name"           => sprintf('%s - %s', $this->data['name'], $this->data['product_name']),
			"Way_of_payment" => (string) "W4P",
			"Course"         => $this->data['product_id'],
			"Owner"          => "1819773000001861117",
			"Deal"           => $this->data['deal_id'],
			"Contact"        => $this->data['contact_id'],
			"Status"         => "Expected",
			"Layout"         => 1819773000000091045,
			"Due_Date"       => wp_date("Y-m-d", strtotime("+2 days")),
			"Grand_Total"    => $this->data['price']
		);
		$this->data['invoice_new_id'] = $this->zohoCrmApi->createRecord('Invoices_New', $Inv_data, 'id', 'approval, workflow, blueprint');
	}

	private function cleanPhone($phone)
	{
		return preg_replace("/[^0-9]/", '', $phone);
	}

}