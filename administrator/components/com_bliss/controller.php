<?php

class BlissController extends JControllerLegacy{

	public function display($cachable = false, $urlparams = array()){

		$viewName=$this->input->get('view','payment');

		$viewLayout=$this->input->get('layout');

		$view=$this->getView($viewName,'html');

		$model=$this->getModel($viewName);

		$view->setModel($model,true);

		$view->setLayout($viewLayout);

		$view->display();

	}

}

?>