<?php
defined('_JEXEC') or die;

if(!defined('T3')){
	throw new Exception(JText::_('T3_MISSING_T3_PLUGIN'));
}

$T3 = T3::getApp($this);
$layout = $T3->getLayout();

$T3->loadLayout ($layout);
?>
