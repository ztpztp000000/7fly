<?php
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.controller');
 
/**
 * pay callback api Component Controller
 */
class PayiController extends JController
{
    function pay()
    {
        $order_no = JRequest::getVar('order_no');
        $user = JRequest::getVar('user');
        $ret = JRequest::getVar('ret');
        $sign = JRequest::getVar('sign');
        
        if (empty($order_no) || empty($user) || empty($sign))
        {
            //var_dump($order_no."_".$user."_".$ret."_".sign);exit;
            exit;
        }
        
        $config = JFactory::getConfig();
        $strKey = $user."_".$order_no."_".$ret."_".JApplication::getCfg('site_key');
        if ($sign != md5($strKey))
        {
            exit;
        }

	$state = 0;
        switch ($ret)
        {
            case 1:
                /// 成功
		$state = 2;
                break;
            case 9:
		$state = 2;
                break;
            case 8:
                // 付款但未处理
		$state = 1;
                break;
            default:
                /// 失败
                exit;
        }
        //update_sp_money($order_no, $user);
	$db=&JFactory::getDBO();
        $param = new stdClass;
        $param->order_no = $order_no;
        $param->state = $state;
        $db->updateObject('#__pay', $param, 'order_no');

	/// update spread money
	 $query = "SELECT charge_money FROM #__pay WHERE order_no='".$order_no."'";
        $db->setQuery($query);
        $order = $db->loadObjectList();

        $money = intval($order[0]->charge_money);
        if (0 == $money)
        {
            /// 没找到订单
            exit;
        }

        /// 找到用户的信息
        $query = "SELECT spread_user FROM #__users where username='".$username."'";
        $db->setQuery($query);
        $user = $db->loadObjectList();

        /// 没有上线
        $sp_id = intval($user[0]->spread_user);
        if (0 == $sp_id)
        {
            exit;
        }

        /// 更新上线的推广金额
        $param = new stdClass;
        $param->spread_money = $money;
        $param->id = $sp_id;
        $db->updateObject('#__users', $param, 'id');

        exit;
    }
}
?>
