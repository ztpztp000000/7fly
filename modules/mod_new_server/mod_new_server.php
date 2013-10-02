<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_new_sever
 * @copyright	Copyright (C) 2013 - 2013 daxian, Inc. All rights reserved.
 */

// no direct access
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';

$server_list = modNewServerHelper::getNewServer();

require( JModuleHelper::getLayoutPath('mod_new_server'));
