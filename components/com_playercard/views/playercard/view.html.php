<?php
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.view');
 
	/**
	 * HTML View class for the new player cards Component
	*/
	class playercardViewPlayerCard extends JView
	{
	 // Overwriting JView display method
	 function display($tpl = null) 
	 {
	 	require_once JPATH_ROOT.'/libraries/daxian/api.php';
	
		$config = JFactory::getConfig();
		$api = new MainSiteApi($config->get('api_url'), JApplication::getCfg('site_id'), JApplication::getCfg('site_key'));

		//$game_id = JRequest::getVar('gid');
		$this->msg = $api->get_server_list(JRequest::getVar('gid'));
		
		/*$card_list = array();
		if (4 == $game_id)
		{
			/// 热血海贼王新手卡
	        $server_list = $api->get_server_list($game_id);
	        foreach($server_list as $server)
	        {
	            $card_list[$server['id']]['id']= $server['id'];
	            $card_list[$server['id']]['name']= $server['name'];
	            $card_list[$server['id']]['free'] = 1000;
	        }
		}
		else
		{
			$card_list = $api->get_card_list($game_id);
		}
    
	    // Assign data to the view
	    $this->msg = $card_list;*/
	 
	    // Display the view
	    parent::display($tpl);
    }
}
?>