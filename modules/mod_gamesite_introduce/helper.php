<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_gamesite
 * @copyright   大贤网络, Inc. All rights reserved.
 * @license     daxian
 */

// no direct access
defined('_JEXEC') or die;

class modGameSiteIntroduceHelper
{
    public function LoadInfo()
    {
        $db = JFactory::getDBO();
        $query = 'SELECT depict '.' FROM #__games where guid='.$_SESSION['__default']['game_site']->gid;
        $db->setQuery($query);
        
        $info = $db->loadObjectList();
        $info = $info[0]->depict;
        
        return $info;
    }
}
