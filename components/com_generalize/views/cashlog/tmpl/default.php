<?php
defined('_JEXEC') or die;
?>

<div>
<h4>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
    <td width="15%" align="center">提现日期</td>
    <td width="10%" align="center">提现方式</td>
    <td width="32%" align="center">银行卡号</td>
    <td width="25%" align="center">电话号码</td>
    <td width="9%" align="center">提现金额</td>
    <td width="9%" align="center">处理状态</td>
    </tr>
</table>
</h4>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<?php foreach ($this->msg as $cash_log) : ?>
        <tr>
        <td width="15%" align="center"><?php echo $cash_log->cashtime; ?></td>
        <td width="10%" align="center"><?php echo (0==$cash_log->cash_type)?'平台币':'现金'; ?></td>
        <td width="34%" align="center"><?php echo $cash_log->bank_id; ?></td>
        <td width="25%" align="center"><?php echo $cash_log->tel; ?></td>
        <td width="8%" align="center"><?php echo $cash_log->cash; ?></td>
        <td width="8%" align="center"><?php echo (0==$cash_log->state)?'未处理':'已处理'; ?></td>
        </tr>
<?php endforeach; ?>
</table>
</div>