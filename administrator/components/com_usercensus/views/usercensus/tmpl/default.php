<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>

<li>总注册人数: <?php echo $this->_user_count; ?></li>
<li>本月注册人数: <?php echo $this->_month_reg; ?></li>
<li>本日注册人数: <?php echo $this->_day_reg; ?></li>
<li>总充值金额: <?php echo $this->_total_charge; ?></li>
<li>本月充值金额: <?php echo $this->_month_charge; ?></li>
<li>今日充值金额: <?php echo $this->_day_charge; ?></li>
<li>日活跃用户: <?php echo $this->_dau; ?></li>
<li>今月活跃用户: <?php echo $this->_mau; ?></li>
