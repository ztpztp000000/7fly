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
<div>
<?php foreach ($game_list['game'] as $game) :  ?>
<div class="hotgame">
<li><img src=<?php echo $game['logo_new']; ?> /></li>
<li>
<div class="hotgame_right">
	<ul>
	<li class="hotgame_font">
	<strong><?php echo $game['name']; ?></strong></li>
	</ul>
	<ul>
	
    <li>
    <a href="<?php echo JRoute::_('index.php/listgame?view=serverlist'); ?>&gid=<?php echo $game['id']; ?>&gname=<?php echo $game['name'] ?>">进入游戏 |</a>
    <a href="/index.php/home/?gid=<?php echo $game['id']; ?>" target="_blank">官网 |</a>
    <a href="/index.php?option=com_playercard&gid=<?php echo $game['id']; ?>"> 礼包</a>
	</li></ul>
</div>
</li>
</div>
    
    
<?php endforeach; ?>
</div>