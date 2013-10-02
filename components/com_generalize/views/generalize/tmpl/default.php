<?php
defined('_JEXEC') or die;
?>

<div>
<form action="<?php echo JRoute::_('index.php?option=com_generalize'); ?>" method="post" name="adminForm">
<h4>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
    <td width="25%" align="center">用户</td>
    <td width="25%" align="center">注册日期</td>
    <td width="25%" align="center">充值金额</td>
    <td width="25%" align="center">推广金额</td>
    </tr>
</table>
</h4>

<ul>
<?php if ($this->_user->user_type == 1) : ?>
    <?php foreach ($this->items as $user) : ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
            <td width="25%" align="center"><?php echo $user->username; ?></td>
            <td width="25%" align="center"><?php echo date("Y-m-d",strtotime($user->registerDate)); ?></td>
            <td width="25%" align="center"><?php echo $user->charge_money; ?></td>
            <td width="25%" align="center"><?php echo $user->spread_money; ?></td>
            </tr>
        </table>
        <?php
        if ($user->charge_money>0 || $user->spread_money>0)
        {
            $this->_charge_count++;
            $this->_money_count += $user->charge_money;
            $this->_money_count += $user->spread_money;
        }
        ?>
    <?php endforeach; ?>
<?php else : ?>
    <?php foreach ($this->items as $user) : ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
            <td width="25%" align="center"><?php echo $user->username; ?></td>
            <td width="25%" align="center"><?php echo date("Y-m-d",strtotime($user->registerDate)); ?></td>
            <td width="25%" align="center"><?php echo $user->charge_money; ?></td>
            <td width="25%" align="center">0</td>
            </tr>
        </table>
        <?php
        if ($user->charge_money > 0)
        {
            $this->_charge_count++;
            $this->_money_count += $user->charge_money;
        }
        ?>
    <?php endforeach; ?>
<?php endif; ?>

<div>
推广数据：注册用户00
    <span style="color:red"><?php echo $this->pagination->total; ?>
    </span>人，付费用户<span style="color:red"><?php echo $this->_charge_count; ?></span>人，付费金额<span style="color:red"><?php echo $this->_money_count; ?></span>元。  
<a href="<?php echo JRoute::_('index.php?option=com_generalize&view=cash'); ?>" >申请提现 </a>
</div>
<?php echo $this->pagination->getListFooter(); ?>
</form>
</div>