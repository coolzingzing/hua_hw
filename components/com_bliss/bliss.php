<?php

$params = JComponentHelper::getParams('com_bliss');
$params->loadArray(include JPATH_ROOT . '/env.php');

$input=JFactory::getApplication()->input;

$controller = JControllerLegacy::getInstance('bliss');

$task=$input->get('task');

$controller->execute($task);

$controller->redirect();

?>
