<?php
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.view');
 
	/**
	 * HTML View class for the charge Component
	*/
	class chargeViewCharge extends JView
	{
	 // Overwriting JView display method
	 function display($tpl = null) 
	 {
	 	require_once JPATH_SITE.'/libraries/daxian/api.php';
	 	$config = JFactory::getConfig();
        $api = new MainSiteApi($config->get('api_url'), JApplication::getCfg('site_id'), JApplication::getCfg('site_key'));
        
	 	$game_id = intval(JRequest::getVar('gid'));

	 	/// 所有游戏
        $this->msg = $api->get_game_list();
        
        if (0 != $game_id)
        {
            $game = $this->msg['game'][$game_id];
            if ($game_id == $game['id'])
            {
            	header('Location: '.JRoute::_('index.php?option=com_pay&gi='.base64_encode(urlencode('id='.$game['id'].'&name='.$game['name'].'&mn='.$game['money_name'].'&mp='.$game['money_per'].'&logo='.$game['logo_list']))));
            }
        }
		
	    // 最近玩过的游戏
        if (!empty($_SESSION['__default']['user']->username))
        {
            $this->last_game = $api->last_login_game($_SESSION['__default']['user']->username);
            if (1 != $this->last_game['ret'])
            {
            	$this->last_game = NULL;
            }
            else
            {
            	$this->last_game = $this->last_game['game'];
            }
        }
	    
        /// 充值记录
        $this->_user_id = intval($_SESSION['__default']['user']->id);
        if (0 != $this->_user_id)
        {
        	$this->_pay_log = $this->get('PayLog');
        }
	 
	    // Display the view
	    parent::display($tpl);
    }
}
?>