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


<div class="gamesite_statergy">
<div class="gamesite_statergy_title"><b>游戏攻略</b><font style="float:right;color:#FF6600;padding-right:10px;">更多>></font></div>
<div class="main">
	<div class="gamesite_statergy_content">
	<?php foreach ($game_statergy as $statergy) : ?>
    	<li><font style="color:#FFFFFF;margin-right:5px;"></font><a href="/index.php/strategy/<?php echo $statergy->id; ?>-<?php echo $statergy->alias; ?>"><?php echo $statergy->title; ?></a></li>
		<?php endforeach; ?>
	</div>
</div>

