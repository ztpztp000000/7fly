<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

$controller = JController::getInstance('UserCensus');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
?>