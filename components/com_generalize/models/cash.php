<?php
/**
 * Generalize Model for Generalize Component
 * 
 * @package    daxian
 * @subpackage Components
 */
 
defined('_JEXEC') or die();
jimport('joomla.application.component.model');

class generalizeModelCash extends JModel
{
    /// 推广成绩
    var $_sp_money;
    var $_last;
    
    function __construct()
    {
        parent::__construct();
    }
    
    /// 获取已经提现金额
    public function &getCurCash()
    {
        $sp_id = intval($_SESSION['__default']['user']->id);
        if (0 == $sp_id)
        {
            header('Location: /index.php/login');
            exit;
        }
        /// 两分钟刷新一次
        if (!empty($this->_sp_money) && time()<($this->_last+120))
        {
        	return $this->_sp_money;
        }
        
        /// 自己推广的用户充值金额
        $db = JFactory::getDBO();
        $query = 'SELECT SUM(charge_money) as money,SUM(spread_money) as sp_money FROM #__users WHERE spread_user='.$sp_id;
        $db->setQuery($query);
        $charge_money = $db->loadObjectList();
        $this->_sp_money = floatval($charge_money[0]->money);
        
        /// 如果是推广经理, 获取推广员的推广金额
        if ($_SESSION['__default']['user']->user_type == 1)
        {
            $this->_sp_money += floatval($charge_money[0]->sp_money);
        }
        return $this->_sp_money;
    }
}