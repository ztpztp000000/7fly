<?php
defined('_JEXEC') or die('Restricted access');
 
//jimport('joomla.application.component.view');

/**
 * HTML View class for the generalize Component
*/
class generalizeViewGeneralize extends JViewLegacy
{
    protected $pagination;
    protected $items;
    protected $state;
    protected $_user;
    protected $_game_list;
    
    /// 充值用户
    protected $_charge_count = 0;
    /// 充值总金额
    protected $_money_count = 0;
    
    function display($tpl = null) 
	{
	 	$this->state       = $this->get('State');
        $this->items        = $this->get('Items');
        $this->pagination   = $this->get('Pagination');
        $this->_user = JFactory::getUser();
        
        $this->_game_list = $this->get('GameList');

        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }
 
        parent::display($tpl);
    }
}
?>