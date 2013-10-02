<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_new_server
 * @copyright	Copyright (C) 2013 - 2013 daxian, Inc. All rights reserved.
 */

// no direct access
defined('_JEXEC') or die;
require_once JPATH_ROOT.'/libraries/daxian/api.php';

class modNewServerHelper
{
	static function getNewServer()
	{
		$config = JFactory::getConfig();
		$api = new MainSiteApi($config->get('api_url'), JApplication::getCfg('site_id'), JApplication::getCfg('site_key'));
		$new_server = $api->get_new_server();
//var_dump($new_server);exit;
		return $new_server;
	}
}
