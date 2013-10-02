<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_gamesite
 * @copyright   大贤网络, Inc. All rights reserved.
 * @license     daxian
 */

// no direct access
defined('_JEXEC') or die;

class modGameSiteDescHelper
{
    public function getGameDesc()
    {
        $db = JFactory::getDBO();
        $query = 'SELECT id,title,alias '.' FROM #__content where catid=90 and gameid='.$_SESSION['__default']['game_site']->gid;
        $db->setQuery($query);
        $all_desc = $db->loadObjectList();
        
        return $all_desc;
    }
}

