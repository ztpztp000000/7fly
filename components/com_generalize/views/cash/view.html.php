<?php
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.view');

/**
 * HTML View class for the cash Component
*/
class generalizeViewCash extends JView
{
    function display($tpl = null) 
	{
		$this->msg = $this->get('CurCash');
		$this->free_cash = floor($this->msg - $_SESSION['__default']['user']->cashed_money);
		
        parent::display($tpl);
    }
}
?>