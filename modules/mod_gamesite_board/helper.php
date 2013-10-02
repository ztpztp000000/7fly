<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_gamesite
 * @copyright   大贤网络, Inc. All rights reserved.
 * @license     daxian
 */

// no direct access
defined('_JEXEC') or die;

class modGameSiteBoardHelper
{
    public function getBoard()
    {
        $db = JFactory::getDBO();
    	$query = 'SELECT id,title,alias '.' FROM #__content where catid=88 and gameid='.$_SESSION['__default']['game_site']->gid;
    	$db->setQuery($query);
    	$board = $db->loadObjectList();
    	
    	return $board;
    }
}
