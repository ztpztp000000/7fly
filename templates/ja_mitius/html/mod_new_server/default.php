<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_login
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
//var_dump($server_list);exit;
?>


<?php foreach ($server_list['server'] as $server) :  ?>
<div class="gamenewserver">
<ul>
<li>
<img src="<?php echo $server['logo']; ?>"> 
<b><?php echo $server['name']; ?></b> 
<b><?php echo $server['trunon_date']; ?> </b>
<b><?php echo $server['trunon_hour']; ?></b>
</li>
<li>
	<div class="inner_game">
	<ul>
	<li>
        <a title="领取礼包" href="/index.php?option=com_playercard&gid=<?php echo $server['game_id']; ?>&sid=<?php echo $server['id']; ?>">
		<img src="templates/ja_mitius/images/hotsr_btn2.png" />
		</a>
        <a title="进入游戏" href="/?option=com_playgame&sid=<?php echo $server['id']; ?>" target="_blank">
		<img src="templates/ja_mitius/images/hotsr_btn1.png" />
		</a>
    </li>
			</ul></div>
	</li>
	</ul>
</div>
<?php endforeach; ?>

