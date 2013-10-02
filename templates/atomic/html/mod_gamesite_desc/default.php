<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_gamesite
 * @copyright	大贤网络, Inc. All rights reserved.
 * @license		daxian
 */

// no direct access
defined('_JEXEC') or die;
?>


<div class="gamesite_desc">
<div class="gamesite_desc_title"><b>游戏资料</b><font>更多>></font></div>
<div class="main">
	<div class="gamesite_desc_content">
	<?php foreach ($game_desc as $board) : ?>
    	<li><font style="color:#FFFFFF;margin-right:5px;"></font><a href="/index.php/gamedata/<?php echo $board->id; ?>-<?php echo $board->alias; ?>"><?php echo $board->title; ?></a></li>
	<?php endforeach; ?>
	</div>
</div>
