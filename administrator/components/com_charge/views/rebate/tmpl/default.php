<?php
defined('_JEXEC') or die;
?>

<div>
<form action="<?php echo JRoute::_('index.php?option=com_charge&task=rebate'); ?>" method="post" name="adminForm">
<table width="100%" border="0" cellspacing="2" cellpadding="2">
    <tr>
    <td align="right">返利金额：</td>
    <td><input type="text" name="rebate_money" value="1" /></td>
    </tr>
    
    <tr>
    <td align="right">&nbsp;</td>
    <td><input type="submit" name="Submit" value="返利" /></td>
    </tr>
</table>
    <input type="hidden" name="no" value="<?php echo $this->msg; ?>"/>
</form>
</div>