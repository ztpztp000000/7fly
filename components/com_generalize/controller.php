<?php
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.controller');
 
/**
 * Generalize Component Controller
 */
class GeneralizeController extends JController
{
	function req_cash()
	{
		$user_id = intval($_SESSION['__default']['user']->id);
        if (0 == $user_id)
        {
            header('Location: /index.php/login');
            exit;
        }
        
        $name = JRequest::getVar('name');
        $card_id = JRequest::getVar('card_id');
        $bank_name = JRequest::getVar('bank_name');
        $bank_id = JRequest::getVar('bank_id');
        $tel = JRequest::getVar('tel');
        $cash = JRequest::getVar('cash');
        empty($_POST['site_state'])?'no':trim($_POST['site_state']);
        $cash_type = JRequest::getVar('cash_type');
        /// 最大可提现的金额
        $free_cash = JRequest::getVar('free_cash');
        
	   if (empty($tel) || $cash<=0)
       {
            /// 信息不完整
            header("Location: ".JRoute::_('index.php?option=com_generalize&view=cash'));
            exit;
       }
        
        if ($cash > $free_cash)
        {
        	/// 提现金额大于可提现金额
        	header("Location: ".JRoute::_('index.php?option=com_generalize&view=cash'));
        	exit;
        }
        
        if ($cash_type == 1)
        {
        	if (empty($name) || empty($card_id) || empty($bank_name) || empty($bank_id))
        	{
        		/// 信息不完整
        		header("Location: ".JRoute::_('index.php?option=com_generalize&view=cash'));
        		exit;
        	}
        }

        /// 保存提现申请
		$db=&JFactory::getDBO();
        $param = new stdClass;
        $param->userid = $user_id;
        $param->username = $_SESSION['__default']['user']->username;
        $param->truename = $name;
        $param->card_id = $card_id;
        $param->bank_name = $bank_name;
        $param->bank_id = $bank_id;
        $param->tel = $tel;
        $param->cash = $cash;
        $param->cash_type = $cash_type;
        $param->user_ip = $_SERVER['REMOTE_ADDR'];
        $param->cash_time = time();
        $db->insertObject('#__cashlog', $param);
        
        $_SESSION['__default']['user']->cashed_money += $cash;
        
        /// 更新已经提现金额
        $db->setQuery("UPDATE #__users SET cashed_money=cashed_money+".$cash." WHERE id=".$user_id);
        $db->execute();
            
        header("Location: ".JRoute::_('index.php?option=com_generalize&view=cashlog'));
	}
	
	function server_list()
	{
		require_once JPATH_ROOT.'/libraries/daxian/api.php';
    
        $config = JFactory::getConfig();
        $api = new MainSiteApi($config->get('api_url'), JApplication::getCfg('site_id'), JApplication::getCfg('site_key'));
        $server_list = $api->get_server_list(JRequest::getVar('g'));
        
		foreach($server_list['server'] as $server){
	        echo '<option value="'.$server['id'].'">'.$server['name'].'</option>';
	    }
	    exit;
	}
}
?>