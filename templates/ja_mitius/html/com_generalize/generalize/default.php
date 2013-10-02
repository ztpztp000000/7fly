<?php
defined('_JEXEC') or die;
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="30%">
			<select id="game_id" name="game_id" onchange="changeGame(this.options[selectedIndex].value)">
			<option value="0">请选择游戏</option>
			<?php foreach ($this->_game_list['game'] as $game) : ?>
			<option value="<?php echo $game['id']; ?>" ><?php echo $game['name'] ?></option>
			<?php endforeach; ?>
			</select>
		</td>
		<td width="30%">
			<select id="sid" name="sid"">
			<option value="0">请选择服务器</option>
			</select>
		</td>
		<td width="40%">
			<input type="button" onclick="GetUrl();" value="生成推广链接" style="width:150px;height:30px;" />
		</td>
	</tr>
</table>

<div>您的推广链接: <textarea id="sp_url" name="sp_url" style="width:500px;height:30px;margin-left:10px;border:1px solid #999;"></textarea></div>
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
推广数据：注册用户
    <span style="color:red"><?php echo $this->pagination->total; ?>
    </span>人，付费用户<span style="color:red"><?php echo $this->_charge_count; ?></span>人，付费金额<span style="color:red"><?php echo $this->_money_count; ?></span>元。  
<a href="<?php echo JRoute::_('index.php?option=com_generalize&view=cash'); ?>" >申请提现 </a>
</div>
<?php echo $this->pagination->getListFooter(); ?>
</form>
</div>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript">
    function changeGame(id){
    	if (0 == id)
    	{
        	return;
    	}
    	$.ajax({
            type: "get",
            cache: false,
            url: "/?option=com_generalize&task=server_list&g="+id,
            success: function (server_list) {
    		$("#sid").empty();
    		$("#sid").append('<option value="0">请选择服务器</option>');
            $("#sid").append(server_list);
            },
        });
    }
    function GetUrl()
    {
    	if($("#game_id")[0].value==0){
            $("#sp_url").html("请选择游戏");
            return false;
        }
        
    	if($("#sid")[0].value==0){
    		$("#sp_url").html("请选择服务器");
    		return false;
    	}

    	$("#sp_url").html("<?php echo JURI::root().'?option=com_spread&u='.$_SESSION['__default']['user']->id.'&g='; ?>"+$("#game_id")[0].value+"&s="+$("#sid")[0].value);
    }
</script>
