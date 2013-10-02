<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_login
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
//var_dump($game_list);exit;
?>

<ul>
<?php foreach ($game_list['game'] as $game) :  ?>
    <div>
    <div> <img src=<?php echo $game['logo_new']; ?> /> </div>
    <strong><?php echo $game['name']; ?></strong>
    <div>
    <a href="/index.php?option=com_serverlist&gid=<?php echo $game['id']; ?>">进入游戏 |</a>
    <a href="/index.php/home/?gid=<?php echo $game['id']; ?>" target="_blank">官网 |</a>
    <a href="/index.php?option=com_playercard&gid=<?php echo $game['id']; ?>"> 礼包</a></div>
    </div>
<?php endforeach; ?>
</ul>
