<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
JHtml::_('behavior.tooltip');
?>

<form action="<?php echo JRoute::_('index.php?option=com_charge'); ?>" method="post" name="adminForm">
<div class="my_charge_detial_title">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
    <td width="15%" align="center">订单号</td>
    <td width="9%" align="center">充值方式</td>
    <td width="9%" align="center">充值状态</td>
    <td width="9%" align="center">充值账号</td>
    <td width="9%" align="center">目标账号</td>
    <td width="5%" align="center">金额</td>
    <td width="10%" align="center">充值时间</td>
    <td width="15%" align="center">充值游戏</td>
    <td width="5%" align="center">返利</td>
    <td width="8%" align="center">操作</td>
    </tr>
</table>
</div>

<div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<?php foreach ($this->items as $pay_log) : ?>
    <tr>
    <td width="15%" align="center"><?php echo $pay_log->order_no; ?></td>
    <td width="9%" align="center"><?php echo $pay_log->mode_name; ?></td>
    <?php if (0 == $pay_log->state) : ?>
        <td width="9%" align="center">未付款</td>
    <?php elseif(1 == $pay_log->state) : ?>
        <td width="9%" align="center"><span style="color:red">失败</span></td>
    <?php else : ?>
        <td width="9%" align="center"><span style="color:green">成功</span></td>
    <?php endif; ?>
    <td width="9%" align="center"><?php echo $pay_log->username; ?></td>
    <td width="9%" align="center"><?php echo $pay_log->to_username; ?></td>
    <td width="5%" align="center"><?php echo $pay_log->money; ?></td>
    <td width="10%" align="center"><?php echo $pay_log->pay_time; ?></td>
    <td width="15%" align="center"><?php echo $pay_log->game_name.'-'.$pay_log->server_name; ?></td>
    <td width="5%" align="center"><?php echo $pay_log->rebate; ?></td>
    <?php if (1 == $pay_log->state) : ?>
        <td width="8%" align="center"><a href="<?php echo JRoute::_('index.php?option=com_charge&task=repair_bill&no='.$pay_log->order_no); ?>">补单</a></td>
    <?php elseif (2==$pay_log->state && 0==$pay_log->rebate) : ?>
        <td width="8%" align="center"><a href="<?php echo JRoute::_('index.php?option=com_charge&view=rebate&no='.$pay_log->order_no); ?>">返利</a></td>
    <?php endif; ?>
    </tr>
<?php endforeach; ?>
</table>

<?php echo $this->pagination->getListFooter(); ?>
</div>
</form>
