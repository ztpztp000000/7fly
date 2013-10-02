<?php
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.view');
 
	/**
	 * HTML View class for the charge Component
	*/
	class playgameViewPlayGame extends JView
	{
	    // Overwriting JView display method
    function display($tpl = null) 
	{
	    if (0 == $_SESSION['__default']['user']->id)
		{
		    header('Location: /index.php/login');
		}
	
	    $user =& JFactory::getUser();
	    if (empty($user->username))
	    {
	        $app = JFactory::getApplication();
	        $return = base64_encode(JURI::getInstance()->toString());
	        $login_link = "index.php/login?return=".$return;
	        $app->redirect($login_link);
	    }
	
	    require_once JPATH_ROOT.'/libraries/daxian/api.php';
		$config = JFactory::getConfig();
		$api = new MainSiteApi($config->get('api_url'), JApplication::getCfg('site_id'), JApplication::getCfg('site_key'));

		$this->msg = $api->login_game_url($user->username, JRequest::getVar('sid'));
		$this->msg['pai_url'] = $config->get('api_url').'pay/user.php?user='.$user->username.'&site='.JApplication::getCfg('site_id');
		
	    // Display the view
	    parent::display($tpl);
    }
}
?>