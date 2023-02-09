<?php
namespace goit_prmcode\controller;

class lead
{

	private $W4P_TOKEN;
	private $input;
	private $inputPost;
	public $status;

	private $parameters = array();

	function __construct()
	{
		$this->W4P_TOKEN = GOIT_PRMCODE()->config['W4P_TOKEN'];
		$this->inputPost = $_POST;
	}

	function new (array $data)
	{
		/*
		"first_name": "ihor TEST",
		"phone": "+380930008429",
		"email": "i.fostiak@goit.ua",
		"correct_whois": "kid",
		"utm_source": "",
		"utm_medium": "",
		"utm_term": "",
		"utm_campaign": "",
		"utm_content": "",
		"land": "python",
		"pdf_lang": "ru",
		"correct_course": "uaminijs",
		"city": "",
		"ip": "109.251.24.253",
		"productID": 1819773000312352055,
		"productName": "GoITeens_UA_AutoFE_wishbord",
		"fopID": 1819773000064300183,
		"returnURL":  get_template_directory_uri() . "/assets/crm/autofewishbord/lead.php",
		"productPrice": 399,
		"productCurrency": "UAH",
		"promoKey": "",
		"promoDiscount": 0,
		"promo": ""
		*/
		$this->parameters = array(
			"first_name"      => (string) $data['first_name'],
			"phone"           => (string) $data['phone'],
			"email"           => (string) $data['email'],
			"productID"       => (int) $data['productID'], // айді продукту з зохо
			"productName"     => (string) $data['productName'], // назва продукту
			"productPrice"    => (string) $data['productPrice'],
			"productCurrency" => (string) isset($data['productCurrency']) ? $data['productCurrency'] : "UAH", // валюта продукту
			"promo"           => (string) $data['promo'], // промокод 
			"promoID"         => (string) $data['promoID'], // промокод ID
			"promoDiscount"   => (int) $data['promoDiscount'], // знижка
			"fopID"           => (string) $data['fopID'], // айді фопа з зохо
			"returnURL"       => (string) site_url("wp-content/plugins/goit-promocodes/app/crm/return_url.php"), // перевірка статусу оплати
			"utm_content"     => (string) isset($data['utm_content ']) ? $data['utm_content '] : '',
			"utm_medium"      => (string) isset($data['utm_medium ']) ? $data['utm_medium '] : '',
			"utm_source"      => (string) isset($data['utm_source ']) ? $data['utm_source '] : '',
			"utm_term"        => (string) isset($data['utm_term ']) ? $data['utm_term '] : '',
			"utm_campaign"    => (string) isset($data['utm_campaign ']) ? $data['utm_campaign '] : '',
			"ip"              => $_SERVER['REMOTE_ADDR'],
		);
		$this->input = $this->createOutputArray($this->inputPost, $this->parameters);
		return $this->send($this->W4P_TOKEN, ['Lead' => $this->input], $this->input);
	}


	function send($token, $data, $input)
	{
		$ch = curl_init();
		$curl_options = [];
		$url = "https://w4ppaymnew.goiteens.ua/loader.php";
		$curl_options[CURLOPT_URL] = $url;
		$curl_options[CURLOPT_RETURNTRANSFER] = true;
		$curl_options[CURLOPT_HEADER] = 0;
		$curl_options[CURLOPT_CUSTOMREQUEST] = "POST";
		$curl_options[CURLOPT_POSTFIELDS] = json_encode($data);
		$headersArray = [];
		$headersArray[] = "Authorization" . ":" . "Bearer " . $token;
		$headersArray[] = "Content-Type: application/json";
		$curl_options[CURLOPT_HTTPHEADER] = $headersArray;

		curl_setopt_array($ch, $curl_options);
		$return = json_decode(curl_exec($ch), true);
		curl_close($ch);

		/* This is a function that adds data to the database. */
		if ($return['invoice_id']) {
			$data_order = [
				'promocod_id'     => $this->parameters['promoID'],
				'invoice'         => $return['invoice_id'],
				'date_order'      => wp_date('Y-m-d'),
				'product_price'   => $this->parameters['productPrice'],
				'discount'        => $this->parameters['promoDiscount'],
				'discount_tariff' => $this->parameters['productCurrency'],
			];
			GOIT_PRMCODE()->model->order->add($data_order);
		}

		return array('status' => $return['status'], 'url' => $return['url']);

	}

	function createOutputArray($postArray, $additionalParametersArray)
	{
		foreach ($additionalParametersArray as $key => $value) {
			$postArray[$key] = $value;
		}
		return $postArray;
	}

}