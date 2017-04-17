<?php

$input=JFactory::getApplication()->input;

$controller = JControllerLegacy::getInstance('bliss');

$task=$input->get('task');

$controller->execute($task);

$controller->redirect();

?>
