<?php
defined('_JEXEC') or die;

/**
 * pay log View
 */

class chargeViewCharge extends JViewLegacy
{
	protected $pagination;
	protected $items;
    protected $state;
    
    function display($tpl = null) 
    {
    	$this->state       = $this->get('State');
        $this->items        = $this->get('Items');
        $this->pagination   = $this->get('Pagination');

        //var_dump($this->items);exit;
        
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        parent::display($tpl);
    }
}
?>
