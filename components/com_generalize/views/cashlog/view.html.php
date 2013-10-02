<?php
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.view');

/**
 * HTML View class for the cash Component
*/
class generalizeViewCashlog extends JView
{
    function display($tpl = null) 
	{
		$this->msg = $this->get('CashLog');
        parent::display($tpl);
    }
}
?>