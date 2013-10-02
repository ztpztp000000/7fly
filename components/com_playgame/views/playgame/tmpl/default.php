<?php
defined('_JEXEC') or die;
?>

<?php if(1 != $this->msg['ret']) : ?>
    <li>
    <?php echo $this->msg['msg']; ?>
    </li>
<?php else : ?>
    <div style="display:none;">
    <iframe scrolling="auto" id="q9wanus" frameborder="0" marginwidth="0" marginheight="0" height="100%" width="100%" src="<?php echo $this->msg['pai_url']; ?>"></iframe>
    </div>
    <iframe scrolling="auto" id="ifm" frameborder="0" marginwidth="0" marginheight="0" style="margin:0;" height="100%" width="100%" src="<?php echo $this->msg['url']; ?>" ></iframe>
<?php endif; exit; ?>
