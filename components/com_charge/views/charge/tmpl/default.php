<?php
defined('_JEXEC') or die;
?>

<?php if(1 != $this->msg['ret']) : ?>
    <li>
    <?php echo $this->msg['msg']; ?>
    </li>
<?php else : ?>
	<?php foreach ($this->msg['paymode'] as $mode) :  ?>
		<li>
        <?php echo $mode['id']; echo $mode['name']; ?>
		</li>
	<?php endforeach;?>
<?php endif; ?>