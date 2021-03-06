<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_login
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>

<ul>
<?php foreach ($server_list['server'] as $server) :  ?>
    <div>
		<ul>
			<li><img src="<?php echo $server['logo']; ?>"></li>
			<li><?php echo $server['trunon_date']; ?></li>
			
			<?php if(isset($_SESSION['auth']["username"])) : ?>
				<li>
				<a title="进入游戏" href="/?option=com_playgame&sid=<?php echo $server['id']; ?>" target="_blank">进入游戏</a>
				</li>
			<?php else : ?>
			    <li>
                <a title="进入游戏" href="/index/login">进入游戏</a>
                </li>
			<?php endif; ?>
			<li><?php echo $server['trunon_hour']; ?></li>
			<li><?php echo $server['name']; ?></strong><a title="领取礼包" href="?option=com_playercard.php?action=card_list&sid=<?php echo $server['id']; ?>">领取礼包</a></li>
		</ul>
	</div>
    </div>
<?php endforeach; ?>
</ul>