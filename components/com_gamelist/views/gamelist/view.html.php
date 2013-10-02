<?php
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.view');
 
	/**
	 * HTML View class for the charge Component
	*/
	class gamelistViewGameList extends JView
	{
	 // Overwriting JView display method
	 function display($tpl = null) 
	 {
	 	require_once JPATH_ROOT.'/libraries/daxian/api.php';
	
		$config = JFactory::getConfig();
		$api = new MainSiteApi($config->get('api_url'), JApplication::getCfg('site_id'), JApplication::getCfg('site_key'));
		$this->msg = $api->get_game_list();
	 
	    // Display the view
	    parent::display($tpl);
    }
}
?>