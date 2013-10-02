<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_gamesite_statergy
 * @copyright   大贤网络, Inc. All rights reserved.
 * @license     daxian
 */

// no direct access
defined('_JEXEC') or die;

class modGameSiteStatergyHelper
{
    public function getGameStatergy()
    {
        $db = JFactory::getDBO();
        $query = 'SELECT id,title,alias '.' FROM #__content where catid=89 and gameid='.$_SESSION['__default']['game_site']->gid;
        $db->setQuery($query);
        $statergy = $db->loadObjectList();
        
        return $statergy;
    }
}

