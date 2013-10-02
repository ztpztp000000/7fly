<?php
/**
 * Generalize Model for Generalize Component
 * 
 * @package    daxian
 * @subpackage Components
 */
 
defined('_JEXEC') or die();
jimport('joomla.application.component.model');
 
/**
 * cash_log Model
 *
 * @package    daxian
 * @subpackage Components
 */
class generalizeModelCashLog extends JModel
{
	/// 推广成绩
    var $_cash_log;
    var $_last;
    
    function __construct()
    {
        parent::__construct();
    }

    public function &getCashLog()
    {
        $user_id = $_SESSION['__default']['user']->id;
        if (0 == $user_id)
        {
            header('Location: /index.php/login');
            exit;
        }

        /// 两分钟刷新一次
        if (!empty($this->_cash_log) && time()<($this->_last+120))
        {
            return $this->_cash_log;
        }

        /// 提现日志
        $db = JFactory::getDBO();
        $query = 'SELECT bank_name,bank_id,tel,cash,cash_type,state,from_unixtime(cash_time) as cashtime FROM #__cashlog WHERE userid='.$user_id;
        $db->setQuery($query);
        $this->_cash_log = $db->loadObjectList();

        return $this->_cash_log;
    }
}