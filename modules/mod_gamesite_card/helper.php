<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_gamesite
 * @copyright   大贤网络, Inc. All rights reserved.
 * @license     daxian
 */

// no direct access
defined('_JEXEC') or die;
require_once JPATH_ROOT.'/libraries/daxian/api.php';

class modGameSiteCardHelper
{
    static function getServerList()
    {
        $config = JFactory::getConfig();
        $api = new MainSiteApi($config->get('api_url'), JApplication::getCfg('site_id'), JApplication::getCfg('site_key'));
        $server_list = $api->get_server_list($_SESSION['__default']['game_site']->gid);
        return $server_list;
    }
}
