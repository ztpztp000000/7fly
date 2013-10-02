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

<?php foreach ($boards as $board) : ?>
    <li><a href="/index.php/web-news/<?php echo $board->id; ?>-<?php echo $board->alias; ?>"><?php echo $board->title; ?></a></li>
<?php endforeach; ?>