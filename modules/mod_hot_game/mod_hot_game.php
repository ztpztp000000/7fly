<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_hot_game
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';

//$params->def('greeting', 1);

$game_list	= modHotGameHelper::getHotGame();
$site_bk_img = modHotGameHelper::getSiteBKImg();
require( JModuleHelper::getLayoutPath('mod_hot_game'));
