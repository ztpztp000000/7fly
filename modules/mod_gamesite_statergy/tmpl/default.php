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

<?php foreach ($game_statergy as $statergy) : ?>
    <li><a href="/index.php/strategy/<?php echo $statergy->id; ?>-<?php echo $statergy->alias; ?>"><?php echo $statergy->title; ?></a></li>
<?php endforeach; ?>