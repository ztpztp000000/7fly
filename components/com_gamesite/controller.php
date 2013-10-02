<?php
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.controller');
 
/**
 * GameSite Component Controller
 */
class GameSiteController extends JController
{
	function getcard()
	{
		if (empty($_SESSION['__default']['user']->username))
		{
			echo '请先登录';
			exit;
		}
		require_once JPATH_ROOT.'/libraries/daxian/api.php';
    
        $config = JFactory::getConfig();
        $api = new MainSiteApi($config->get('api_url'), JApplication::getCfg('site_id'), JApplication::getCfg('site_key'));
        $card = $api->getcardbyserver($_SESSION['__default']['user']->username, JRequest::getVar('sid'));
        if (1 == $card['ret'])
        {
        	echo $card['no'];
        }
        else
        {
        	echo '失败';
        }
        exit;
	}
}
?>