<?php
defined('_JEXEC') or die;

/**
 * pay rebate View
 */

class chargeViewRebate extends JView
{
    function display($tpl = null) 
    {
        $this->msg = JRequest::getVar('no');
        if (empty($this->msg))
        {
            header("Location: ".JRoute::_('index.php?option=com_charge'));
            return;
        }
    	
        parent::display($tpl);
    }
}
?>
