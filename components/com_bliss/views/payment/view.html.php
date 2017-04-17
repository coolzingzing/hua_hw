<?php
class BlissViewPayment extends JViewLegacy{

	public function display($tpl = NULL){

		//$this->item=$this->get('Item');

		//parent::display();

		//$orderid='01_'. uniqid();
		$merchantID = 'MS31005804';
		$orderId='01_'. uniqid();
		$version = "1.2";
		$amount='2500';
		$timeStamp = time();
		$hashIV = 'SOWp2VuGtKbQQMr7';
		$hashKey="daMrxeY4Dxgvn5AAg6BivBuWPvMbhI1u";

		$mer_array = array(
			'MerchantID' => $merchantID,
			'TimeStamp' => $timeStamp,
			'MerchantOrderNo'=>$orderId,
			'Version' => $version,
			'Amt' => $amount,
		);
		ksort($mer_array);
		$check_merstr = http_build_query($mer_array);
		$CheckValue_str = "HashKey=".$hashKey."&$check_merstr&HashIV=".$hashIV;
		$CheckValue = strtoupper(hash("sha256", $CheckValue_str));
	echo $CheckValue;
		$this->merchantID = $merchantID;
		$this->timeStamp = $timeStamp;
		$this->hashKey = $hashKey;
		$this->hashIV = $hashIV;
		$this->version = $version;
		$this->amount = $amount;
		$this->checkValue = $CheckValue;
		$this->orderId = $orderId;

		parent::display();
	}


}

?>