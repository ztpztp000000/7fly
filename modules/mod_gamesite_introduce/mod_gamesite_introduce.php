<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_gamesite
 * @copyright   大贤网络, Inc. All rights reserved.
 * @license     daxian
 */

// no direct access
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';


$info = modGameSiteIntroduceHelper::LoadInfo();

require( JModuleHelper::getLayoutPath('mod_gamesite_introduce'));
