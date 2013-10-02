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
<Div class="gamesite_main">
<div class="gamesite_board">
<div class="title"><b>新闻公告</b></div>
<?php foreach ($boards as $board) : ?>
    <li><font>[公告]</font><a href="/index.php/web-news/<?php echo $board->id; ?>-<?php echo $board->alias; ?>"><?php echo $board->title; ?></a></li>
<?php endforeach; ?></div>
</div>