<?php

class BlissViewPayment extends JViewLegacy{

	public function display($tpl = NULL){

		$this->item=$this->get('Item');

		parent::display();
	}

}

?>