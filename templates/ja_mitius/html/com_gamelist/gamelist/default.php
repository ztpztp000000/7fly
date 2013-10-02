<?php
defined('_JEXEC') or die;
?>
<?php if(1 != $this->msg['ret']) : ?>
<?php echo $this->msg['msg']; ?>
<?php else : ?>

<div class="glist">
<div class="title">游戏列表</div>
<div class="main">
<?php foreach ($this->msg['game'] as $game) :  ?> 
	<div class="g_list" style="background:url(<?php echo $game['logo_list']; ?>);">
		<div style="height:119px;width:290px;"></div>
		<div style=" width:290px;height:30px;background:#000000; opacity:.8; border-top:1px solid  #FF3300;line-height:30px;">
		<font style="color:#FF3300;margin-left:10px;">《<?php echo $game['name']; ?>》</font>
			<a href="<?php echo JRoute::_('index.php/listgame?view=serverlist'); ?>&gid=<?php echo $game['id']; ?>&gname=<?php echo $game['name'] ?>">进入游戏</a>
	    <a href="/index.php/home/?gid=<?php echo $game['id']; ?>" target="_blank">官 网</a>
	    <a href="<?php echo JRoute::_('index.php/?option=com_playercard'); ?>&gid=<?php echo $game['id']; ?>"> 礼 包</a>
		</div>
	</div>
	<?php endforeach;?>
<?php endif; ?>	

</div>
</div>
</div>

	    
