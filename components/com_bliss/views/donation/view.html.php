<?php
class BlissViewDonation extends JViewLegacy{

	public function display($tpl = NULL){

		//$this->item=$this->get('Item');
		$app = JFactory::getApplication();
		$result = $app->login([
			//'username'=>'popo123@test.com',
			'username'=>'popo123',
			'password'=>'testtest123'
		]);

		if($result === false){
			$messages=$app->getMessageQueue();
			print_r($messages);
		}

		return parent::display();
	}


}

?>