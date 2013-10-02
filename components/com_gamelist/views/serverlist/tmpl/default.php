<?php
defined('_JEXEC') or die;
?>

<div>
<div>
    <p><strong><span><?php echo JRequest::getVar('gname'); ?></span>服务器列表</strong></p>
</div>

<div>
<ul>
<?php if(1 != $this->msg['ret']) : ?>
    <li>
    <?php echo $this->msg['msg']; ?>
    </li>
<?php else : ?>
    <?php foreach ($this->msg['server'] as $server) :  ?>
    <li>
    <a title="进入游戏" href="/?option=com_playgame&sid=<?php echo $server['id']; ?>" target="_blank"><?php echo $server['name']; ?></a>
    <?php endforeach;?>
<?php endif; ?>
</ul>
</div>
</div>
          
