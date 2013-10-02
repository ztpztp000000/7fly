<?php
/**
 * pay log Model for charge Component
 * 
 * @package    daxian
 * @subpackage Components
 */
 
defined('_JEXEC') or die();
jimport('joomla.application.component.model');

class chargeModelCharge extends JModel
{
    /// 充值日志
    var $_pay_log;
    var $_last;
    
    function __construct()
    {
        parent::__construct();
    }

    public function &getPayLog()
    {
    	if (empty($_SESSION['__default']['user']->username))
    	{
    		return $this->_pay_log;
    	}
    	
        /// 两分钟刷新一次
        if (!empty($this->_pay_log) && time()<($this->_last+120))
        {
            return $this->_pay_log;
        }

        $db = JFactory::getDBO();
        $query = "SELECT * FROM #__pay WHERE username='".$_SESSION['__default']['user']->username."'";
        $db->setQuery($query);
        $this->_pay_log = $db->loadObjectList();

        return $this->_pay_log;
    }
}