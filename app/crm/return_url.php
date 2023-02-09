<?php
namespace goit_prmcode\crm;

echo json_encode($_POST);

/* {
"merchantAccount":"test_merch_n1",
"merchantSignature":"9d19bddd251a4ccd5e6868b83df38624",
"orderReference":"_t_xl77asl2v4",
"amount":"1",
"currency":"UAH",
"authCode":"347016",
"email":"empat@gmail.com",
"phone":"38095595660",
"createdDate":"167444781",
"processingDate":"164485038",
"cardPan":"53****6002",
"cardType":"MasterCard",
"issuerBankCountry":"Ukraine",
"issuerBankName":"MONObank",
"recToken":"63ce7a0c-471e-b6f5-04679cb36dc2",
"transactionStatus":"Approved",
"reason":"Ok",
"reasonCode":"1100",
"fee":"0.02",
"paymentSystem":"card"
};
*/


class return_url
{
	private $post;
	private $postdata;
	private $credentialsURL = array(
		"sucessURL" => "https://g.goit.global/003pxx", // перенаправлення після успішної оплати
		"errorURL"  => "https://g.goit.global/003pxx", // перенаправлення після помилки оплати
	);

	public function __construct()
	{
		$this->postdata = json_encode($_POST);

		if (!empty($postdata)) {
			if ($this->checkanswer(json_decode($this->postdata, true))) {
				header('Location: ' . $this->credentialsURL['sucessURL']);
			} else {
				header('Location: ' . $this->credentialsURL['errorURL']);
			}
		}
	}

	function checkanswer(array $post)
	{
		return array_key_exists('reasonCode', $post) && $post['reasonCode'] == '1100' ? true : false;
	}
}