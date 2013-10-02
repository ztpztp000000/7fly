<?php
defined('_JEXEC') or die;
?>

<?php if(1 != $this->msg['ret']) : ?>
    <li>
    <?php echo $this->msg['msg']; ?>
    </li>
<?php else : ?>
	<?php foreach ($this->msg['game'] as $game) :  ?>
	   <div>
	    <div> <img src=<?php echo $game['logo_new']; ?> /> </div>
	    <strong><?php echo $game['name']; ?></strong>
	    <div>
	    <a href="<?php echo JRoute::_('index.php/listgame?view=serverlist'); ?>&gid=<?php echo $game['id']; ?>&gname=<?php echo $game['name'] ?>">进入游戏 |</a>
	    <a href="/index.php/home/?gid=<?php echo $game['id']; ?>" target="_blank">官网 |</a>
	    <a href="<?php echo JRoute::_('index.php/?option=com_playercard'); ?>&gid=<?php echo $game['id']; ?>"> 礼包</a></div>
	    </div>
	<?php endforeach;?>
<?php endif; ?>