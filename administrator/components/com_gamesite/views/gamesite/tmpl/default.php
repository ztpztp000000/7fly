<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
//var_dump($this->game_list);exit;
?>

<div>
<ul>

<?php if ($this->get_api['ret'] != 1) : ?>
    <li> <?php echo $this->get_api['msg']; ?> </li>
<?php else : ?>

	<?php foreach ($this->msg as $game) : ?>
	   <li><?php echo $game->guid.' 名称: '; ?><a href="<?php echo JURI::base(); ?>index.php?option=com_gamesite&view=edit&gid=<?php echo $game->guid; ?>"><?php echo $game->name ?></a></li>
	<?php endforeach;; ?>

    <br/><br/>

	<?php foreach ($this->game_list as $game) : ?>
	   <li><?php echo $game['id'].'    '.$game['name']; ?><a href="<?php echo JURI::base(); ?>index.php?option=com_gamesite&task=newsite&gid=<?php echo $game['id']; ?>&gno=<?php echo $game['no']; ?>&gname=<?php echo $game['name']; ?>">创建官网</a></li>
	<?php endforeach; ?>
<?php endif; ?>
</ul>
</div>
