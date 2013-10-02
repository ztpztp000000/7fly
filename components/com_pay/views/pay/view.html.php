<?php
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.view');

class payViewPay extends JView
{
	 // Overwriting JView display method
    function display($tpl = null)
    {
    	$this->game = JRequest::GetUrlBase64Query(JRequest::getVar('gi'));
    	if (0 == intval($this->game['id']))
    	{
    		header("Location: ".JRoute::_('index.php/recharge'));
    		exit;
    	}
    	
    	/// 服务器列表
    	require_once JPATH_SITE.'/libraries/daxian/api.php';
        $config = JFactory::getConfig();
        $api = new MainSiteApi($config->get('api_url'), JApplication::getCfg('site_id'), JApplication::getCfg('site_key'));
        $this->server_list = $api->get_server_list($this->game['id']);
        
        $this->_user_id = $_SESSION['__default']['user']->id;
    	
	    // Display the view
	    parent::display($tpl);
    }
}
?>