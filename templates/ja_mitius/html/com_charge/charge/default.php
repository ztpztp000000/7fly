<?php
defined('_JEXEC') or die;

$app = JFactory::getApplication();
$return = base64_encode(JURI::getInstance()->toString());
$login_link = "index.php/login?return=".$return;
?>
<script type=text/javascript>
		<!--
		function setTab(name,cursel,n){
			for(i=1;i<=n;i++){
			var menu=document.getElementById(name+i);
			var con=document.getElementById("con_"+name+"_"+i);
			menu.className=i==cursel?"hover":"";
			con.style.display=i==cursel?"block":"none";
			var uer_id = "<?php echo $this->_user_id; ?>";
			if (2==cursel && 0==uer_id)
			{
				this.location = "<?php echo $login_link; ?>";
			}
		}
	}
	//-->
	</script>
	<div id="lib_Tab1">
		<div class="lib_Menubox lib_tabborder">
			<ul>
				<li class="tit_bj_left"></li>
   				<li id="one1" onclick="setTab('one',1,2)" class="hover"><span>我要充值</span></li>
   				<li id="one2" onclick="setTab('one',2,2)" ><span>我的充值记录</span></li>
			</ul>
		</div>
		<div class="lib_Contentbox lib_tabborder"> 
		<!--我要充值页面--> 
			<div id="con_one_1" class="text_div">
			 <?php if (!empty($this->last_game)) : ?>
				<div class="text_div_text">我最近玩过的游戏:</div>
				<div class="my_last_play">
				<?php foreach ($this->last_game as $game) : ?>
					<li><a href="<?php echo JRoute::_('index.php?option=com_pay&gi='.base64_encode(urlencode('id='.$this->msg['game'][$game['id']]['id'].'&name='.$this->msg['game'][$game['id']]['name'].'&mn='.$this->msg['game'][$game['id']]['money_name'].'&mp='.$this->msg['game'][$game['id']]['money_per'].'&logo='.$this->msg['game'][$game['id']]['logo_list']))); ?>"><?php echo $this->msg['game'][$game['id']]['name']; ?></a></li>
				<?php endforeach; ?>
				</div>
			 <?php endif; ?>
				
				<div class="text_div_text">选择要充值的游戏:</div>
				<div class="my_new_play">
				<?php foreach ($this->msg['game'] as $game) : ?>
					<li><a href="<?php echo JRoute::_('index.php?option=com_pay&gi='.base64_encode(urlencode('id='.$game['id'].'&name='.$game['name'].'&mn='.$game['money_name'].'&mp='.$game['money_per'].'&logo='.$game['logo_list']))); ?>"><?php echo $game['name']; ?></a></li>
				<?php endforeach; ?>
				</div>
			</div>

		<!--我要充值页面结束-->
			<div id="con_one_2" class="text_div" style="display:none">
			<?php
        $t2 = "<script type=text/javascript>document.write(con)</script>";
        if ("con_one_2" == $t2 && 0 == intval($_SESSION['__default']['user']->id))
        {
            $app = JFactory::getApplication();
            $return = base64_encode(JURI::getInstance()->toString()."?tab=2");
            //$url = base64_encode(JRoute::_('index.php?option=com_charge'));
            $link = "index.php/login?return=".$return;
            $app->redirect($link);
        } 
        ?> 
				
				<div class="my_charge_detial_title">
					<li>日期</li>
					<li>充值方式</li>
					<li>金额</li>
					<li>游戏</li>
					<li>区服</li>
				</div>
				<div class="my_charge_detial">
				<?php foreach ($this->_pay_log as $pay) : ?>
					<li><?php echo date('Y-m-d H:i:s', $pay->pay_time); ?></li>
					<li><?php echo $pay->mode_name; ?></li>
					<li><?php echo $pay->money; ?></li>
					<li><?php echo $pay->game_name; ?></li>
					<li><?php echo $pay->server_name; ?></li>
				<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>