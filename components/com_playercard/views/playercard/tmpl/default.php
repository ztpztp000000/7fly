<?php
defined('_JEXEC') or die;
?>

<?php if(1 != $this->msg['ret']) : ?>
    <li>
    <?php echo $this->msg['msg']; ?>
    </li>
<?php else : ?>
	<?php foreach ($this->msg['cards'] as $card) :  ?>
		<li>
        <?php echo $card['id']; echo $card['name']; ?>
		</li>
	<?php endforeach;?>
<?php endif; exit; ?>