<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
  
/**
 * General Controller of RecommendGame component
 */
class ChargeController extends JController
{
	function repair_bill()
	{
		$order_no = JRequest::getVar('no');
		if (empty($order_no))
		{
			header("Location: ".JRoute::_('index.php?option=com_charge'));
			return;
		}

		require_once JPATH_ROOT.'/libraries/daxian/api.php';
        $config = JFactory::getConfig();
        $api = new MainSiteApi($config->get('api_url'), JApplication::getCfg('site_id'), JApplication::getCfg('site_key'));
        $ret = $api->repair_bill($order_no);
		if (0 != $ret)
	    {
	    	header("Location: ".JRoute::_('index.php?option=com_charge'));
	    	return;
	    }
	    
	    /// 更新订单状态
        $db=&JFactory::getDBO();
        $param = new stdClass;
        $param->order_no = $order_no;
        $param->state = 2;
        $db->updateObject('#__pay', $param, 'order_no');
        header("Location: ".JRoute::_('index.php?option=com_charge'));
	}
	
    function rebate()
    {
        $order_no = JRequest::getVar('no');
        if (empty($order_no))
        {
            header("Location: ".JRoute::_('index.php?option=com_charge'));
            return;
        }
        $money = intval(JRequest::getVar('rebate_money'));
        if (0 == $money)
        {
        	header("Location: ".JRoute::_('index.php?option=com_charge'));
            return;
        }

        require_once JPATH_ROOT.'/libraries/daxian/api.php';
        $config = JFactory::getConfig();
        $api = new MainSiteApi($config->get('api_url'), JApplication::getCfg('site_id'), JApplication::getCfg('site_key'));
        $ret = $api->rebates($order_no, $money);
        if (1 != $ret['ret'])
        {
            header("Location: ".JRoute::_('index.php?option=com_charge'));
            return;
        }
        
        /// 更新订单返利
        $db=&JFactory::getDBO();
        $param = new stdClass;
        $param->order_no = $order_no;
        $param->rebate = $money;
        $db->updateObject('#__pay', $param, 'order_no');
        header("Location: ".JRoute::_('index.php?option=com_charge'));
    }
}
?>
