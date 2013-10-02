<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_login
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
require_once JPATH_ROOT.'/libraries/daxian/api.php';
//JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_gamesite/models', 'gamesite');

class modHotGameHelper
{
	static function getHotGame()
	{
		$config = JFactory::getConfig();
		$api = new MainSiteApi($config->get('api_url'), JApplication::getCfg('site_id'), JApplication::getCfg('site_key'));
		$game_list = $api->get_newgame_list();
		
		return $game_list;
	}
	
    static public function getSiteBKImg()
    {
        $db = JFactory::getDBO();
        
        $query = 'SELECT guid,bk_img '.' FROM #__games';
        $db->setQuery($query);
        $site_param = $db->loadObjectList();
        
        $bk_img = array();
        foreach ($site_param as $param)
        {
        	//$bk_img[$param->guid]['id'] = $param->guid;
        	$bk_img[$param->guid]['bk_img'] = urlencode($param->bk_img);
        }
        
        return $bk_img;
    }
}
