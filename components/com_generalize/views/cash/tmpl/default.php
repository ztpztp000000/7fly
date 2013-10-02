<?php
defined('_JEXEC') or die;
?>
 
 <div class="msg">
    <h3><strong>申请提现  现金提现请填写正确的真实姓名,电话号码, 及银行信息</strong></h3>
</div>

<div>
<table width="100%" border="0" cellspacing="2" cellpadding="2">
    <tr>
    <td width="20%" align="right">推广业绩：</td>
    <td><?php echo $this->msg; ?>元</td>
    </tr>
    
    <tr>
    <td align="right">分成比例：</td>
    <td><?php echo $_SESSION['__default']['user']->search_size; ?>%</td>
    </tr>
    
    <tr>
    <td align="right">已提现金额：</td>
    <td><?php echo $_SESSION['__default']['user']->cashed_money; ?>元&nbsp;&nbsp;<a href="<?php echo JRoute::_('index.php?option=com_generalize&view=cashlog'); ?>">(查看记录)</a></td>
    </tr>
    
    <tr>
    <td align="right">可提现金额：</td>
    <td><?php echo $this->free_cash; ?>元</td>
    </tr>
</table>

<br />
<form id="form1" name="form1" method="post" action="<?php echo JRoute::_('index.php?option=com_generalize&task=req_cash'); ?>">
<table width="100%" border="0" cellspacing="2" cellpadding="2">
    <tr>
    <td width="20%" align="right">姓名：</td>
    <td><input type="text" name="name" value="<?php echo $_SESSION['__default']['user']->name; ?>" /> *提现资料必须填写完整</td>
    </tr>
    
    <tr>
    <td align="right">身份证：</td>
    <td><input type="text" name="card_id" value="<?php echo $_SESSION['__default']['user']->card_id; ?>" /></td>
    </tr>
    
    <tr>
    <td align="right">开户银行：</td>
    <td><input type="text" name="bank_name" value="<?php echo $_SESSION['__default']['user']->bank_name; ?>" /></td>
    </tr>
    
    <tr>
    <td align="right">银行卡号：</td>
    <td><input type="text" name="bank_id" value="<?php echo $_SESSION['__default']['user']->bank_id; ?>" /></td>
    </tr>

    <tr>
    <td align="right">联系电话：</td>
    <td><input type="text" name="tel" value="<?php echo $_SESSION['__default']['user']->tel; ?>" /></td>
    </tr>
    
    <tr>
    <td align="right">提现金额： </td>
    <td><input type="text" name="cash" value="<?php echo $this->free_cash; ?>" /></td>
    </tr>
    
    <tr>
    <td align="right">提现方式： </td>
    <td>
    <input type="radio" name="cash_type" checked value="0"/> 平台币<input type="radio" name="cash_type" value="1"/> 现金
    </td>
    </tr>
    
    <tr>
    <td align="right">&nbsp;</td>
    <td><input type="submit" name="Submit" value="申请提现" /></td>
    </tr>
</table>
    <input type="hidden" name="free_cash" value="<?php echo $this->free_cash; ?>"/>
</form>

<br />
<table width="100%" border="0" cellspacing="2" cellpadding="2">
    <tr>
    <td>个人收入所得税说明：</td>
    </tr>
    
    <tr>
    <td><textarea name="textarea" cols="75" rows="15">根据“财政部国家税务总局《关于个人提供非有形商品推销代理等服务活动取得收人征收营业税和个人所得税有关问题的通知 》”
对非雇员的税务处理非本企业雇员为企业提供非有形商品推销、代理等服务活动取得的佣金、奖励和劳务费等名目的收入，无论该收入采用何种计取方法和支付方式，均应计入个人从事服务业应税劳务的营业额，按照《中华人民共和国营业税暂行条例》及其实施细则和其他有关规定计算征收营业税；上述收入扣除已缴纳的营业税税款后，应计入个人的劳务报酬所得，按照《中华人民共和国个人所得税法》及其实施条例和其他有关规定计算征收个人所得税。
税 项： 税前金额、营业税、附加费、所得税、税后金额。 
 1、营业税、附加费计算公式 : 
 5000元以上（含5000元）才需缴交
营业税 = 税前金额  x  5.55% 
附加费 = 税前金额  x  5.55% 
2、个人所得税计算公式：
小于4000元：个人所得税 = 税前金额 x 20% 
大于4000元（含4000元），同时小于25000元： 个人所得税=税前金额  x  16% 
大于25000元（含25000元），同时小于62500元：个人所得税=税前金额  x  24% 
大于62500元（含62500元），个人所得税=税前金额  x  32% 
3、税后金额计算公式：
税后金额 = 税前金额 — 营业税 — 所得税与附加费

参考：
《中华人民共和国个人所得税法实施条例（2008修订）》第十一条和第二十一条第（一）点分别规定：
第十一条　税法第三条第四项所说的劳务报酬所得一次收入畸高，是指个人一次取得劳务报酬，其应纳税所得额超过2万元。 对前款应纳税所得额超过2万元至5万元的部分，依照税法规定计算应纳税额后再按照应纳税额加征五成；超过5万元的部分，加征十成。
第二十一条（一）劳务报酬所得，属于一次性收入的，以取得该项收入为一次；属于同一项目连续性收入的，以一个月内取得的收入为一次。</textarea></td>
    </tr>
</table>
<br />
</div>