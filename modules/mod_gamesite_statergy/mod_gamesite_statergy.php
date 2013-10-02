<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_gamesite_statergy
 * @copyright   大贤网络, Inc. All rights reserved.
 * @license     daxian
 */

// no direct access
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';
$game_statergy = modGameSiteStatergyHelper::getGameStatergy();
require( JModuleHelper::getLayoutPath('mod_gamesite_statergy'));
