<?php

class BlissController extends JControllerLegacy{

	public function display($cachable = false, $urlparams = array()){

		$viewName=$this->input->get('view','payment');

		$viewLayout=$this->input->get('layout');

		$view=$this->getView($viewName,'html');

		$model=$this->getModel($viewName);

		if($model){

			$view->setModel($model,true);
		}
		$view->setLayout($viewLayout);

		$view->display();

	}

	public function spReturn(){ //CREDIT WEBATM will return OR error
		echo '<pre>'. print_r($this->input->post->getArry()).'</pre>';
		$status=$this->input->post->get('Status');
		if($status !== 'SUCCESS'){
			$this->setRedirect(
					JRoute::_('index.php?options=com_bliss&view=payment')
					,"錯誤碼是:".$status,
					warning);

		return false;
		}
	}
	public function spNotify(){

	}
	public function spCustomer(){

	}

	protected function validateChackCode(){
		$merchantID = 'MS31005804';
		$hashIV = 'SOWp2VuGtKbQQMr7';
		$hashKey="daMrxeY4Dxgvn5AAg6BivBuWPvMbhI1u";

		$orderId = $this->input->post->get('MerchantOrderNo');
		$tradeNo =  $this->input->post->get('TradeNo');
		$amount =  $this->input->post->get('Amt');

		$check_code = array(
			'MerchantID' => $merchantID,
			'Amt' => $amount,
			'MerchantOrderNo'=>$orderId,
			'TradeNo' => $tradeNo
		);
		ksort($check_code);
		$check_str = http_build_query($check_code);
		$CheckCode = "HashIV=".$hashIV."&$check_str&HashKey=".$hashKey;
		$CheckCode = strtoupper(hash("sha256", $CheckCode));

	}

	function registerAjax(){
		$data=[
			'name'=>'abcname',
			'username'=>'abc',
			'password1'=>'1234',
			'email'=>'test@gmail.com'
		];

		//validate

		$data = $this->input->get('user',[],'array');
		$user = JUser::getInstance();
		$user->bind($data);
		$user->save();
		echo json_encode($user->getProperties());
	}


}

?>