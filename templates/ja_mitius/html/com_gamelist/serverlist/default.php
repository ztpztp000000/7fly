<?php
defined('_JEXEC') or die;
?>

<div style="height:40px;line-height:40px; text-align:center;background:#FF9900;">
    <p style="font-size:16px;"><strong><span><?php echo JRequest::getVar('gname'); ?></span>服务器列表</strong></p>
</div>


<?php if(1 != $this->msg['ret']) : ?>
    <?php echo $this->msg['msg']; ?>
<?php else : ?>
<?php foreach ($this->msg['server'] as $server) :  ?>
 <div style="float:left;width:220px;height:50px;line-height:50px;margin-top:20px;margin-left:20px;background:#333333; text-align:center;">
<a title="进入游戏" href="/?option=com_playgame&sid=<?php echo $server['id']; ?>" target="_blank"><?php echo $server['name']; ?></a>
</div>
    <?php endforeach;?>
<?php endif; ?>
 
