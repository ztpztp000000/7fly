<?php
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.controller');
 
/**
 * charge Component Controller
 */
class PayController extends JController
{
	function pay()
	{
		$tab = JRequest::getVar('t');
		$server_id = intval(JRequest::getVar('sid_'.$tab));
		$money = intval(JRequest::getVar('money_'.$tab));
		$select_money = JRequest::getVar('select_money_'.$tab);
		$to_username = JRequest::getVar('username_'.$tab);
		//$username_v = JRequest::getVar('username_v');
		
		$app = JFactory::getApplication();
		$curl = JRequest::getVar('curl');
		
		if (0 == $money)
		{
			$money = $select_money;
		}

		/*if (0 == $server_id)
		{
			JFactory::getApplication()->enqueueMessage('请选择服务器', 'error');
			$app->redirect($curl);
			return;
		}
		
		if (empty($username))
		{
			JFactory::getApplication()->enqueueMessage('请输入帐号', 'error');
            $app->redirect($curl);
			return;
		}
		
		if($username != $username_v)
		{
            JFactory::getApplication()->enqueueMessage('两次输入的帐号不同', 'error');
            $app->redirect($curl);
            return;
		}*/
		$username = $to_username;
		
		/// 充值帐号的信息
		$user_info = $_SESSION['__default']['user'];
		if (!empty($user_info->username))
		{
			$username = $_SESSION['__default']['user']->username;
		}

		/// 保存充值日志
		$db=&JFactory::getDBO();
        $param = new stdClass;
        $param->order_no = str_replace("-","",date("Y-m-dH-i-s")).rand(1000,2000);
        $param->mode_id = intval(JRequest::getVar('pmid'));
        $param->mode_name = JRequest::getVar('pmname');
        $param->username = $username;
        $param->server_id = $server_id;
        $param->game_name = JRequest::getVar('gname');
        $param->server_name = JRequest::getVar('si_'.$server_id);
        $param->to_username = $to_username;
        $param->money = $money;
        $param->pay_time = time();
        $param->user_ip = $_SERVER['REMOTE_ADDR'];
        $db->insertObject('#__pay', $param);
	$order_no = $param->order_no;
        
        /// 跳转到充值页面
        require_once JPATH_SITE.'/libraries/daxian/api.php';
        $config = JFactory::getConfig();
        $api = new MainSiteApi($config->get('api_url'), JApplication::getCfg('site_id'), JApplication::getCfg('site_key'));
	    $ret = $api->req_pay($username, $to_username, $server_id, $money, $_SERVER['REMOTE_ADDR'], $param->mode_id, $param->order_no);
	    if (1 == $ret['ret'])
	    {
	    	if (JApplication::getCfg('site_money_mid') == $param->mode_id)
	    	{
			$param = new stdClass;
			$param->state = 2;
			$param->order_no = $order_no;
			$db->updateObject('#__pay', $param, 'order_no');

	    		/// 充值成功
	    		JFactory::getApplication()->enqueueMessage('充值成功', 'error');
			$app->redirect($curl);
			exit;
	    	}
	    	else
	    	{
	    		$url = $ret['url'];
	    	    header("Location: ".$ret['url']);
	    	    exit;
	    	}
	    }
	    else
	    {
	       JFactory::getApplication()->enqueueMessage('充值失败, 请联系客服: error code: '.$ret['ret'], 'error');
           $app->redirect($curl);
	    }
	}
	
	function check_username()
	{
		$user_info = $_SESSION['__default']['user'];
		$username = JRequest::getVar('u');
		
        if ($username == $user_info->username)
        {
        	echo '1';
        }
        else
        {
        	$user_info = $this->getUserInfo($username);
        	 if (empty($user_info))
        	 {
        	 	echo '0';
        	 }
        }
        exit;
	}
	
    private function getUserInfo($username)
    {
        $db = JFactory::getDBO();
        $query = "SELECT * FROM #__users WHERE username='".$username."'";
        $db->setQuery($query);
        $user_info = $db->loadObjectList();
        
        return $user_info[0];
    }
}
?>
