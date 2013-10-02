<?php
defined('_JEXEC') or die;

//$app = JFactory::getApplication();
//$return = base64_encode(JURI::getInstance()->toString());
//var_dump(JURI::getInstance()->toString());exit;
//$login_link = "index.php/login?return=".$return;
?>
<style type="text/css">

#getId{width:780px;height:550px; border:1px solid #999;}
.tabT{width:200px;float:left;padding:0;margin:0px; background:#FF6600;height:550px;}
.tab,.tab li{padding:0;margin:0;border:none}
.tab{height:20px;display:block; width:190px; float:right;}
.tab li{display:block;height:20px;line-height:20px;padding:10px 2px;text-align:center;color:#FFFFFF;cursor:pointer; border-bottom:1px solid #FFCC00;font-family:微软雅黑,宋体,arial,sans-serif; font-size:14px; font-weight:bold;}
.tab li.current{display:block;color:red;background-color:#fff;padding:10px 0px;}
.show,hidden{float:left;width:500px;margin-top:0px;text-align:left;height:550px;}
.hidden{display:none;}
.show{display:block;}
.con{padding:10px;}
.wy_pay{width:520px;margin:20px;}
.wy_pay .title{border-bottom:1px solid #cccccc; overflow:hidden;font-size:14px;}
.wy_pay .title b{color:#FF3300;}
.wy_pay  .main{margin-top:20px; }
.wy_pay  .main .left{float:left; overflow:hidden;}
.wy_pay  .main .left img{width:150px;height:100px;border:1px solid #333333;}
.wy_pay  .main .right{width:350px; float:right;border-bottom: 1px solid #999999; overflow:hidden;}
.wy_pay  .main .right li{ list-style:none;padding-top:10px;}
.wy_pay  .main .right b{color:#666666;}
.wy_pay  .main .right font{color:#FF6600;}
.wy_pay  .main .right input{position: relative; bottom:3px;}
.wy_pay  .main .right .move{padding-left:65px;}
.wy_pay  .main .right strong{position: relative; bottom:5px;}
.wy_pay  .main .right1{width:520px; float:right; overflow:hidden;}
.wy_pay  .main .right1 li{ list-style:none;padding-top:10px;padding-left:135px;}
.wy_pay  .main .right1 b{color:#666666;}
.wy_pay  .main .right1 font{color:#FF6600;}
.wy_pay  .main .right1 input{position: relative; bottom:3px;}
.wy_pay  .main .right1 .move{padding-left:65px;}
.wy_pay  .main .right1 strong{position: relative; bottom:5px;}
</style>
<script type="text/javascript" language="javascript">
<!--
function tab(tabId, tabC){
    var len =document.getElementById('getId').getElementsByTagName('li').length;
    for(i=1; i <= len; i++){
        if ("tabId"+i==tabId){
            document.getElementById(tabId).className="current";
        }else{
            document.getElementById("tabId"+i).className="";
        }
        if ("tabC"+i==tabC){
            document.getElementById(tabC).className="show";
        }else{
            document.getElementById("tabC"+i).className="hidden";
        }

        //var uer_id = "<?php echo $this->_user_id; ?>";
        //if ("tabId2"==tabId && 0==uer_id)
        //{
        //    this.location = "<?php echo $login_link; ?>";
        //}
    }
}
//-->
</script>
</head>
<body>
<!--把下面代码加到<body>与</body>之间-->
<div id="getId">
    <div class="tabT">
        <ul class="tab">
             <li id="tabId1" class="current" onClick="tab('tabId1','tabC1');">网银支付</li>
             <li id="tabId2" onClick="tab('tabId2','tabC2');">平台币支付</li>
             <li id="tabId3" onClick="tab('tabId3','tabC3');">支付宝支付</li>
             <li id="tabId4" onClick="tab('tabId4','tabC4');">财付通支付</li>
             <li id="tabId5" onClick="tab('tabId5','tabC5');">电信充值卡支付</li>
             <li id="tabId6" onClick="tab('tabId6','tabC6');">联通充值卡支付</li>
             <li id="tabId7" onClick="tab('tabId7','tabC7');">移动充值卡支付</li>
             <li id="tabId8" onClick="tab('tabId8','tabC8');">骏网支付</li>
        </ul>
    </div>
    
    
<!--网银充值界面-->
<div class="show" id="tabC1">
    <div class="con">
        <form name="to_pay_0" method="post" id="to_pay_0" target="_blank" action="<?php echo JRoute::_('index.php?option=com_pay&task=pay&t=0'); ?>">
        <div class="wy_pay">
            <div class="title">您选择了<b>网银支付</b>方式充值<b><?php echo $this->game['name']; ?>游戏</b><?php echo $this->game['mn']; ?>，请填写以下信息：</div>
            <div class="main">
            <div class="left">
            <img src="<?php echo $this->game['logo']; ?>">
            </div>
            <div class="right">
                <li><b>充值游戏：</b><font><?php echo $this->game['name']; ?></font> - <?php echo $this->game['mn']; ?></li>
                <li><b>产品价格：</b>1元=<?php echo $this->game['mp'].$this->game['mn']; ?></li>
                <li><b>充值金额：</b><select name="select_money_0" id="select_money_0" style="width:135px;height:30px;">
                                        <option  value="0" selected="selected">请选择充值金额</option>
                                        <option value="10">10元</option>
                                        <option value="30">30元</option>
                                        <option value="50">50元</option>
                                        <option value="100">100元</option>
                                        <option value="200">200元</option>
                                        <option value="300">300元</option>
                                        <option value="500">500元</option>
                                        <option value="1000">1000元</option>
                                        <option value="1">1元</option>
                                    </select>
                                    <span id="n_select_money_0"></span>
                </li>
                                    
                <li><Strong>其他金额：</Strong><input style="width:120px;height:16px;" type="text" id="money_0" name="money_0" ><font> 元</li>
            </div>
            <div class="right1">
                <li>
                <b>您要充值的账号：</b><input type="text" name="username_0" id="username_0" style="width:120px;height:16px;" value="<?php echo $_SESSION['__default']['user']->username; ?>">
                <span id="input_username_0"></span>
                </li>
                <li>
                <b>确认充值的账号：</b><input type="text" name="username_v_0" id="username_v_0" style="width:120px;height:16px;" value="<?php echo $_SESSION['__default']['user']->username; ?>">
                <span id="input_username_v_0"></span>
                </li>
                <li><b>请选择服务器：</b><select name="sid_0" id="sid_0" style="width:150px;height:30px;">
                    <option value="0">请选择服务器</option>
                    <?php foreach ($this->server_list['server'] as $server) : ?>
                    <option value="<?php echo $server['id']; ?>"><?php echo $server['name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <span id="select_server_0"></span>
                </li>
                <li style="border-top:1px  solid #999999;margin-left:50px;">
                <input type="button" onClick="submit_pay();" style="width:186px;height:55px;margin-top:30px;background:url(/templates/ja_mitius/images/chonzhi_btn.png) no-repeat; border:0;"  ></li>
            </div>
            </div>
            </div>
        <input type="hidden" name="pmid" value="1"/>
        <input type="hidden" name="pmname" value="网银"/>
        <input type="hidden" name="gname" value="<?php echo $this->game['name']; ?>"/>
        <input type="hidden" name="curl" value="<?php echo JURI::getInstance()->toString(); ?>"/>
        <?php foreach ($this->server_list['server'] as $server) : ?>
            <input type="hidden" name="<?php echo 'si_'.$server['id']; ?>" value="<?php echo $server['name']; ?>"/>
        <?php endforeach; ?>
    </form>
    </div>
</div>
<!--网银充值界面结束-->

<!--平台币-->
<div class="hidden" id="tabC2">
    <div class="con">
        <form name="to_pay_1" method="post" id="to_pay_1" target="_blank" action="<?php echo JRoute::_('index.php?option=com_pay&task=pay&t=1'); ?>">
        <div class="wy_pay">
            <div class="title">您选择了<b>平台币支付</b>方式充值<b><?php echo $this->game['name']; ?>游戏</b><?php echo $this->game['mn']; ?>，请填写以下信息：</div>
            <div class="main">
            <div class="left">
            <img src="<?php echo $this->game['logo']; ?>">
            </div>
            <div class="right">
                <li><b>充值游戏：</b><font><?php echo $this->game['name']; ?></font> - <?php echo $this->game['mn']; ?></li>
                <li><b>产品价格：</b>1元=<?php echo $this->game['mp'].$this->game['mn']; ?></li>
                <li><b>充值金额：</b><select name="select_money_1" id="select_money_1" style="width:135px;height:30px;">
                                        <option  value="0" selected="selected">请选择充值金额</option>
                                        <option value="10">10元</option>
                                        <option value="30">30元</option>
                                        <option value="50">50元</option>
                                        <option value="100">100元</option>
                                        <option value="200">200元</option>
                                        <option value="300">300元</option>
                                        <option value="500">500元</option>
                                        <option value="1000">1000元</option>
                                        <option value="1">1元</option>
                                    </select>
                                    <span id="n_select_money_1"></span>
                </li>
                                    
                <li><Strong>其他金额：</Strong><input style="width:120px;height:16px;" type="text" id="money_1" name="money_1" ><font> 元</li>
            </div>
            <div class="right1">
                <li>
                <b>您要充值的账号：</b><input type="text" name="username_1" id="username_1" style="width:120px;height:16px;" value="<?php echo $_SESSION['__default']['user']->username; ?>">
                <span id="input_username_1"></span>
                </li>
                <li>
                <b>确认充值的账号：</b><input type="text" name="username_v_1" id="username_v_1" style="width:120px;height:16px;" value="<?php echo $_SESSION['__default']['user']->username; ?>">
                <span id="input_username_v_1"></span>
                </li>
                <li><b>请选择服务器：</b><select name="sid_1" id="sid_1" style="width:150px;height:30px;">
                    <option value="0">请选择服务器</option>
                    <?php foreach ($this->server_list['server'] as $server) : ?>
                    <option value="<?php echo $server['id']; ?>"><?php echo $server['name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <span id="select_server_1"></span>
                </li>
                <li style="border-top:1px  solid #999999;margin-left:50px;">
                <input type="button" onClick="submit_pay_1();" style="width:186px;height:55px;margin-top:30px;background:url(/templates/ja_mitius/images/chonzhi_btn.png) no-repeat; border:0;"  ></li>
            </div>
            </div>
            </div>
        <input type="hidden" name="pmid" value="6"/>
        <input type="hidden" name="pmname" value="平台币"/>
        <input type="hidden" name="gname" value="<?php echo $this->game['name']; ?>"/>
        <input type="hidden" name="curl" value="<?php echo JURI::getInstance()->toString(); ?>"/>
        <?php foreach ($this->server_list['server'] as $server) : ?>
            <input type="hidden" name="<?php echo 'si_'.$server['id']; ?>" value="<?php echo $server['name']; ?>"/>
        <?php endforeach; ?>
    </form>
    </div>
</div>
<!--平台币-->

<!--支付宝-->
<div class="hidden" id="tabC3">
    <div class="con">
        <form name="to_pay_2" method="post" id="to_pay_2" target="_blank" action="<?php echo JRoute::_('index.php?option=com_pay&task=pay&t=2'); ?>">
        <div class="wy_pay">
            <div class="title">您选择了<b>支付宝支付</b>方式充值<b><?php echo $this->game['name']; ?>游戏</b><?php echo $this->game['mn']; ?>，请填写以下信息：</div>
            <div class="main">
            <div class="left">
            <img src="<?php echo $this->game['logo']; ?>">
            </div>
            <div class="right">
                <li><b>充值游戏：</b><font><?php echo $this->game['name']; ?></font> - <?php echo $this->game['mn']; ?></li>
                <li><b>产品价格：</b>1元=<?php echo $this->game['mp'].$this->game['mn']; ?></li>
                <li><b>充值金额：</b><select name="select_money_2" id="select_money_2" style="width:135px;height:30px;">
                                        <option  value="0" selected="selected">请选择充值金额</option>
                                        <option value="10">10元</option>
                                        <option value="30">30元</option>
                                        <option value="50">50元</option>
                                        <option value="100">100元</option>
                                        <option value="200">200元</option>
                                        <option value="300">300元</option>
                                        <option value="500">500元</option>
                                        <option value="1000">1000元</option>
                                        <option value="1">1元</option>
                                    </select>
                                    <span id="n_select_money_2"></span>
                </li>
                                    
                <li><Strong>其他金额：</Strong><input style="width:120px;height:16px;" type="text" id="money_2" name="money_2" ><font> 元</li>
            </div>
            <div class="right1">
                <li>
                <b>您要充值的账号：</b><input type="text" name="username_2" id="username_2" style="width:120px;height:16px;" value="<?php echo $_SESSION['__default']['user']->username; ?>">
                <span id="input_username_2"></span>
                </li>
                <li>
                <b>确认充值的账号：</b><input type="text" name="username_v_2" id="username_v_2" style="width:120px;height:16px;" value="<?php echo $_SESSION['__default']['user']->username; ?>">
                <span id="input_username_v_2"></span>
                </li>
                <li><b>请选择服务器：</b><select name="sid_2" id="sid_2" style="width:150px;height:30px;">
                    <option value="0">请选择服务器</option>
                    <?php foreach ($this->server_list['server'] as $server) : ?>
                    <option value="<?php echo $server['id']; ?>"><?php echo $server['name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <span id="select_server_2"></span>
                </li>
                <li style="border-top:1px  solid #999999;margin-left:50px;">
                <input type="button" onClick="submit_pay_2();" style="width:186px;height:55px;margin-top:30px;background:url(/templates/ja_mitius/images/chonzhi_btn.png) no-repeat; border:0;"  ></li>
            </div>
            </div>
            </div>
        <input type="hidden" name="pmid" value="7"/>
        <input type="hidden" name="pmname" value="支付宝"/>
        <input type="hidden" name="gname" value="<?php echo $this->game['name']; ?>"/>
        <input type="hidden" name="curl" value="<?php echo JURI::getInstance()->toString(); ?>"/>
        <?php foreach ($this->server_list['server'] as $server) : ?>
            <input type="hidden" name="<?php echo 'si_'.$server['id']; ?>" value="<?php echo $server['name']; ?>"/>
        <?php endforeach; ?>
    </form>
    </div>
</div>
<!--支付宝结束-->

<!--财付通-->
<div class="hidden" id="tabC4">
    <div class="con">
        <form name="to_pay_3" method="post" id="to_pay_3" target="_blank" action="<?php echo JRoute::_('index.php?option=com_pay&task=pay&t=3'); ?>">
        <div class="wy_pay">
            <div class="title">您选择了<b>财付通支付</b>方式充值<b><?php echo $this->game['name']; ?>游戏</b><?php echo $this->game['mn']; ?>，请填写以下信息：</div>
            <div class="main">
            <div class="left">
            <img src="<?php echo $this->game['logo']; ?>">
            </div>
            <div class="right">
                <li><b>充值游戏：</b><font><?php echo $this->game['name']; ?></font> - <?php echo $this->game['mn']; ?></li>
                <li><b>产品价格：</b>1元=<?php echo $this->game['mp'].$this->game['mn']; ?></li>
                <li><b>充值金额：</b><select name="select_money_3" id="select_money_3" style="width:135px;height:30px;">
                                        <option  value="0" selected="selected">请选择充值金额</option>
                                        <option value="10">10元</option>
                                        <option value="30">30元</option>
                                        <option value="50">50元</option>
                                        <option value="100">100元</option>
                                        <option value="200">200元</option>
                                        <option value="300">300元</option>
                                        <option value="500">500元</option>
                                        <option value="1000">1000元</option>
                                        <option value="1">1元</option>
                                    </select>
                                    <span id="n_select_money_3"></span>
                </li>
                                    
                <li><Strong>其他金额：</Strong><input style="width:120px;height:16px;" type="text" id="money_3" name="money_3" ><font> 元</li>
            </div>
            <div class="right1">
                <li>
                <b>您要充值的账号：</b><input type="text" name="username_3" id="username_3" style="width:120px;height:16px;" value="<?php echo $_SESSION['__default']['user']->username; ?>">
                <span id="input_username_3"></span>
                </li>
                <li>
                <b>确认充值的账号：</b><input type="text" name="username_v_3" id="username_v_3" style="width:120px;height:16px;" value="<?php echo $_SESSION['__default']['user']->username; ?>">
                <span id="input_username_v_3"></span>
                </li>
                <li><b>请选择服务器：</b><select name="sid_3" id="sid_3" style="width:150px;height:30px;">
                    <option value="0">请选择服务器</option>
                    <?php foreach ($this->server_list['server'] as $server) : ?>
                    <option value="<?php echo $server['id']; ?>"><?php echo $server['name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <span id="select_server_3"></span>
                </li>
                <li style="border-top:1px  solid #999999;margin-left:50px;">
                <input type="button" onClick="submit_pay_3();" style="width:186px;height:55px;margin-top:30px;background:url(/templates/ja_mitius/images/chonzhi_btn.png) no-repeat; border:0;"  ></li>
            </div>
            </div>
            </div>
        <input type="hidden" name="pmid" value="8"/>
        <input type="hidden" name="pmname" value="财付通"/>
        <input type="hidden" name="gname" value="<?php echo $this->game['name']; ?>"/>
        <input type="hidden" name="curl" value="<?php echo JURI::getInstance()->toString(); ?>"/>
        <?php foreach ($this->server_list['server'] as $server) : ?>
            <input type="hidden" name="<?php echo 'si_'.$server['id']; ?>" value="<?php echo $server['name']; ?>"/>
        <?php endforeach; ?>
    </form>
    </div>
</div>
<!--财付通结束-->


<!--电信卡-->
<div class="hidden" id="tabC5">
    <div class="con">
        <form name="to_pay_4" method="post" id="to_pay_4" target="_blank" action="<?php echo JRoute::_('index.php?option=com_pay&task=pay&t=4'); ?>">
        <div class="wy_pay">
            <div class="title">您选择了<b>电信充值卡支付</b>方式充值<b><?php echo $this->game['name']; ?>游戏</b><?php echo $this->game['mn']; ?>，请填写以下信息：</div>
            <div class="main">
            <div class="left">
            <img src="<?php echo $this->game['logo']; ?>">
            </div>
            <div class="right">
                <li><b>充值游戏：</b><font><?php echo $this->game['name']; ?></font> - <?php echo $this->game['mn']; ?></li>
                <li><b>产品价格：</b>1元=<?php echo $this->game['mp'].$this->game['mn']; ?></li>
                <li><b>充值金额：</b><select name="select_money_4" id="select_money_4" style="width:135px;height:30px;">
                                        <option  value="0" selected="selected">请选择充值金额</option>
                                        <option value="10">10元</option>
                                        <option value="30">30元</option>
                                        <option value="50">50元</option>
                                        <option value="100">100元</option>
                                        <option value="200">200元</option>
                                        <option value="300">300元</option>
                                        <option value="500">500元</option>
                                        <option value="1000">1000元</option>
                                        <option value="1">1元</option>
                                    </select>
                                    <span id="n_select_money_4"></span>
                </li>
                                    
                <li><Strong>其他金额：</Strong><input style="width:120px;height:16px;" type="text" id="money_4" name="money_4" ><font> 元</li>
            </div>
            <div class="right1">
                <li>
                <b>您要充值的账号：</b><input type="text" name="username_4" id="username_4" style="width:120px;height:16px;" value="<?php echo $_SESSION['__default']['user']->username; ?>">
                <span id="input_username_4"></span>
                </li>
                <li>
                <b>确认充值的账号：</b><input type="text" name="username_v_4" id="username_v_4" style="width:120px;height:16px;" value="<?php echo $_SESSION['__default']['user']->username; ?>">
                <span id="input_username_v_4"></span>
                </li>
                <li><b>请选择服务器：</b><select name="sid_4" id="sid_4" style="width:150px;height:30px;">
                    <option value="0">请选择服务器</option>
                    <?php foreach ($this->server_list['server'] as $server) : ?>
                    <option value="<?php echo $server['id']; ?>"><?php echo $server['name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <span id="select_server_4"></span>
                </li>
                <li style="border-top:1px  solid #999999;margin-left:50px;">
                <input type="button" onClick="submit_pay_4();" style="width:186px;height:55px;margin-top:30px;background:url(/templates/ja_mitius/images/chonzhi_btn.png) no-repeat; border:0;"  ></li>
            </div>
            </div>
            </div>
        <input type="hidden" name="pmid" value="5"/>
        <input type="hidden" name="pmname" value="电信充值卡"/>
        <input type="hidden" name="gname" value="<?php echo $this->game['name']; ?>"/>
        <input type="hidden" name="curl" value="<?php echo JURI::getInstance()->toString(); ?>"/>
        <?php foreach ($this->server_list['server'] as $server) : ?>
            <input type="hidden" name="<?php echo 'si_'.$server['id']; ?>" value="<?php echo $server['name']; ?>"/>
        <?php endforeach; ?>
    </form>
    </div>
</div>
<!--电信卡-->

<!--联通卡-->
<div class="hidden" id="tabC6">
    <div class="con">
        <form name="to_pay_5" method="post" id="to_pay_5" target="_blank" action="<?php echo JRoute::_('index.php?option=com_pay&task=pay&t=5'); ?>">
        <div class="wy_pay">
            <div class="title">您选择了<b>联通充值卡支付</b>方式充值<b><?php echo $this->game['name']; ?>游戏</b><?php echo $this->game['mn']; ?>，请填写以下信息：</div>
            <div class="main">
            <div class="left">
            <img src="<?php echo $this->game['logo']; ?>">
            </div>
            <div class="right">
                <li><b>充值游戏：</b><font><?php echo $this->game['name']; ?></font> - <?php echo $this->game['mn']; ?></li>
                <li><b>产品价格：</b>1元=<?php echo $this->game['mp'].$this->game['mn']; ?></li>
                <li><b>充值金额：</b><select name="select_money_5" id="select_money_5" style="width:135px;height:30px;">
                                        <option  value="0" selected="selected">请选择充值金额</option>
                                        <option value="10">10元</option>
                                        <option value="30">30元</option>
                                        <option value="50">50元</option>
                                        <option value="100">100元</option>
                                        <option value="200">200元</option>
                                        <option value="300">300元</option>
                                        <option value="500">500元</option>
                                        <option value="1000">1000元</option>
                                        <option value="1">1元</option>
                                    </select>
                                    <span id="n_select_money_5"></span>
                </li>
                                    
                <li><Strong>其他金额：</Strong><input style="width:120px;height:16px;" type="text" id="money_5" name="money_5" ><font> 元</li>
            </div>
            <div class="right1">
                <li>
                <b>您要充值的账号：</b><input type="text" name="username_5" id="username_5" style="width:120px;height:16px;" value="<?php echo $_SESSION['__default']['user']->username; ?>">
                <span id="input_username_5"></span>
                </li>
                <li>
                <b>确认充值的账号：</b><input type="text" name="username_v_5" id="username_v_5" style="width:120px;height:16px;" value="<?php echo $_SESSION['__default']['user']->username; ?>">
                <span id="input_username_v_5"></span>
                </li>
                <li><b>请选择服务器：</b><select name="sid_5" id="sid_5" style="width:150px;height:30px;">
                    <option value="0">请选择服务器</option>
                    <?php foreach ($this->server_list['server'] as $server) : ?>
                    <option value="<?php echo $server['id']; ?>"><?php echo $server['name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <span id="select_server_5"></span>
                </li>
                <li style="border-top:1px  solid #999999;margin-left:50px;">
                <input type="button" onClick="submit_pay_5();" style="width:186px;height:55px;margin-top:30px;background:url(/templates/ja_mitius/images/chonzhi_btn.png) no-repeat; border:0;"  ></li>
            </div>
            </div>
            </div>
        <input type="hidden" name="pmid" value="3"/>
        <input type="hidden" name="pmname" value="联通充值卡"/>
        <input type="hidden" name="gname" value="<?php echo $this->game['name']; ?>"/>
        <input type="hidden" name="curl" value="<?php echo JURI::getInstance()->toString(); ?>"/>
        <?php foreach ($this->server_list['server'] as $server) : ?>
            <input type="hidden" name="<?php echo 'si_'.$server['id']; ?>" value="<?php echo $server['name']; ?>"/>
        <?php endforeach; ?>
    </form>
    </div>
</div>
<!--联通卡结束-->

<!--移动卡-->
<div class="hidden" id="tabC7">
    <div class="con">
        <form name="to_pay_6" method="post" id="to_pay_6" target="_blank" action="<?php echo JRoute::_('index.php?option=com_pay&task=pay&t=6'); ?>">
        <div class="wy_pay">
            <div class="title">您选择了<b>移动充值卡支付</b>方式充值<b><?php echo $this->game['name']; ?>游戏</b><?php echo $this->game['mn']; ?>，请填写以下信息：</div>
            <div class="main">
            <div class="left">
            <img src="<?php echo $this->game['logo']; ?>">
            </div>
            <div class="right">
                <li><b>充值游戏：</b><font><?php echo $this->game['name']; ?></font> - <?php echo $this->game['mn']; ?></li>
                <li><b>产品价格：</b>1元=<?php echo $this->game['mp'].$this->game['mn']; ?></li>
                <li><b>充值金额：</b><select name="select_money_6" id="select_money_6" style="width:135px;height:30px;">
                                        <option  value="0" selected="selected">请选择充值金额</option>
                                        <option value="10">10元</option>
                                        <option value="30">30元</option>
                                        <option value="50">50元</option>
                                        <option value="100">100元</option>
                                        <option value="200">200元</option>
                                        <option value="300">300元</option>
                                        <option value="500">500元</option>
                                        <option value="1000">1000元</option>
                                        <option value="1">1元</option>
                                    </select>
                                    <span id="n_select_money_6"></span>
                </li>
                                    
                <li><Strong>其他金额：</Strong><input style="width:120px;height:16px;" type="text" id="money_6" name="money_6" ><font> 元</li>
            </div>
            <div class="right1">
                <li>
                <b>您要充值的账号：</b><input type="text" name="username_6" id="username_6" style="width:120px;height:16px;" value="<?php echo $_SESSION['__default']['user']->username; ?>">
                <span id="input_username_6"></span>
                </li>
                <li>
                <b>确认充值的账号：</b><input type="text" name="username_v_6" id="username_v_6" style="width:120px;height:16px;" value="<?php echo $_SESSION['__default']['user']->username; ?>">
                <span id="input_username_v_6"></span>
                </li>
                <li><b>请选择服务器：</b><select name="sid_6" id="sid_6" style="width:150px;height:30px;">
                    <option value="0">请选择服务器</option>
                    <?php foreach ($this->server_list['server'] as $server) : ?>
                    <option value="<?php echo $server['id']; ?>"><?php echo $server['name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <span id="select_server_6"></span>
                </li>
                <li style="border-top:1px  solid #999999;margin-left:50px;">
                <input type="button" onClick="submit_pay_6();" style="width:186px;height:55px;margin-top:30px;background:url(/templates/ja_mitius/images/chonzhi_btn.png) no-repeat; border:0;"  ></li>
            </div>
            </div>
            </div>
        <input type="hidden" name="pmid" value="2"/>
        <input type="hidden" name="pmname" value="移动充值卡"/>
        <input type="hidden" name="gname" value="<?php echo $this->game['name']; ?>"/>
        <input type="hidden" name="curl" value="<?php echo JURI::getInstance()->toString(); ?>"/>
        <?php foreach ($this->server_list['server'] as $server) : ?>
            <input type="hidden" name="<?php echo 'si_'.$server['id']; ?>" value="<?php echo $server['name']; ?>"/>
        <?php endforeach; ?>
    </form>
    </div>
</div>
<!--移动卡-->

<!--骏网-->
<div class="hidden" id="tabC8">
    <div class="con">
        <form name="to_pay_7" method="post" id="to_pay_7" target="_blank" action="<?php echo JRoute::_('index.php?option=com_pay&task=pay&t=7'); ?>">
        <div class="wy_pay">
            <div class="title">您选择了<b>骏网一卡通支付</b>方式充值<b><?php echo $this->game['name']; ?>游戏</b><?php echo $this->game['mn']; ?>，请填写以下信息：</div>
            <div class="main">
            <div class="left">
            <img src="<?php echo $this->game['logo']; ?>">
            </div>
            <div class="right">
                <li><b>充值游戏：</b><font><?php echo $this->game['name']; ?></font> - <?php echo $this->game['mn']; ?></li>
                <li><b>产品价格：</b>1元=<?php echo $this->game['mp'].$this->game['mn']; ?></li>
                <li><b>充值金额：</b><select name="select_money_7" id="select_money_7" style="width:135px;height:30px;">
                                        <option  value="0" selected="selected">请选择充值金额</option>
                                        <option value="10">10元</option>
                                        <option value="30">30元</option>
                                        <option value="50">50元</option>
                                        <option value="100">100元</option>
                                        <option value="200">200元</option>
                                        <option value="300">300元</option>
                                        <option value="500">500元</option>
                                        <option value="1000">1000元</option>
                                        <option value="1">1元</option>
                                    </select>
                                    <span id="n_select_money_7"></span>
                </li>
                                    
                <li><Strong>其他金额：</Strong><input style="width:120px;height:16px;" type="text" id="money_7" name="money_7" ><font> 元</li>
            </div>
            <div class="right1">
                <li>
                <b>您要充值的账号：</b><input type="text" name="username_7" id="username_7" style="width:120px;height:16px;" value="<?php echo $_SESSION['__default']['user']->username; ?>">
                <span id="input_username_7"></span>
                </li>
                <li>
                <b>确认充值的账号：</b><input type="text" name="username_v_7" id="username_v_7" style="width:120px;height:16px;" value="<?php echo $_SESSION['__default']['user']->username; ?>">
                <span id="input_username_v_7"></span>
                </li>
                <li><b>请选择服务器：</b><select name="sid_7" id="sid_7" style="width:150px;height:30px;">
                    <option value="0">请选择服务器</option>
                    <?php foreach ($this->server_list['server'] as $server) : ?>
                    <option value="<?php echo $server['id']; ?>"><?php echo $server['name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <span id="select_server_7"></span>
                </li>
                <li style="border-top:1px  solid #999999;margin-left:50px;">
                <input type="button" onClick="submit_pay_7();" style="width:186px;height:55px;margin-top:30px;background:url(/templates/ja_mitius/images/chonzhi_btn.png) no-repeat; border:0;"  ></li>
            </div>
            </div>
            </div>
        <input type="hidden" name="pmid" value="4"/>
        <input type="hidden" name="pmname" value="骏网一卡通"/>
        <input type="hidden" name="gname" value="<?php echo $this->game['name']; ?>"/>
        <input type="hidden" name="curl" value="<?php echo JURI::getInstance()->toString(); ?>"/>
        <?php foreach ($this->server_list['server'] as $server) : ?>
            <input type="hidden" name="<?php echo 'si_'.$server['id']; ?>" value="<?php echo $server['name']; ?>"/>
        <?php endforeach; ?>
    </form>
    </div>
</div>
<!--骏网-->
</div>

<script>
function check_pay()
{
    msg = document.getElementById("input_username_0");
    msg.textContent = "";
    msg = document.getElementById("input_username_v_0");
    msg.textContent = "";
    msg = document.getElementById("select_server_0");
    msg.textContent = "";
    var msg = document.getElementById("n_select_money_0");
    msg.textContent = "";
    
    var money = Number(document.all.money_0.value);
    if (0 == money)
    {
        var select_money = document.all.select_money_0;
        money = Number(select_money.options[select_money.selectedIndex].value);
    }

    if (!money)
    {
        msg.textContent = "请选择充值金额";
        return false;
    }
    
    /// 用户名判断
    msg = document.getElementById("input_username_0");
    if (!document.all.username_0.value)
    {
        msg.textContent = "请输入用户名";
        return false;
    }
    msg = document.getElementById("input_username_v_0");
    if (!document.all.username_v_0.value)
    {
        msg.textContent = "请确认用户";
        return false;
    }
    if (document.all.username_v_0.value != document.all.username_0.value)
    {
        msg.textContent = "两次输入的用户名不同";
        return false;
    }

    /// 服务器选择
    msg = document.getElementById("select_server_0");
    var select_server = document.all.sid_0;
    var sid = Number(select_server.options[select_server.selectedIndex].value);
    if (0 == sid)
    {
        msg.textContent = "请选择要充值的服务器";
        return false;
    }
    
    return true;
}

function submit_pay()
{
    if (!check_pay())
    {
        return;
    }
    document.getElementById("to_pay_0").submit();

}

function check_pay_1()
{
    msg = document.getElementById("input_username_1");
    msg.textContent = "";
    msg = document.getElementById("input_username_v_1");
    msg.textContent = "";
    msg = document.getElementById("select_server_1");
    msg.textContent = "";
    var msg = document.getElementById("n_select_money_1");
    msg.textContent = "";
    
    var money = Number(document.all.money_1.value);
    if (0 == money)
    {
        var select_money = document.all.select_money_1;
        money = Number(select_money.options[select_money.selectedIndex].value);
    }

    if (!money)
    {
        msg.textContent = "请选择充值金额";
        return false;
    }
    
    /// 用户名判断
    msg = document.getElementById("input_username_1");
    if (!document.all.username_1.value)
    {
        msg.textContent = "请输入用户名";
        return false;
    }
    msg = document.getElementById("input_username_v_1");
    if (!document.all.username_v_1.value)
    {
        msg.textContent = "请确认用户";
        return false;
    }
    if (document.all.username_v_1.value != document.all.username_1.value)
    {
        msg.textContent = "两次输入的用户名不同";
        return false;
    }

    /// 服务器选择
    msg = document.getElementById("select_server_1");
    var select_server = document.all.sid_1;
    var sid = Number(select_server.options[select_server.selectedIndex].value);
    if (0 == sid)
    {
        msg.textContent = "请选择要充值的服务器";
        return false;
    }
    
    return true;
}

function submit_pay_1()
{
    if (!check_pay_1())
    {
        return;
    }
    document.getElementById("to_pay_1").submit();

}

function check_pay_2()
{
    msg = document.getElementById("input_username_2");
    msg.textContent = "";
    msg = document.getElementById("input_username_v_2");
    msg.textContent = "";
    msg = document.getElementById("select_server_2");
    msg.textContent = "";
    var msg = document.getElementById("n_select_money_2");
    msg.textContent = "";
    
    var money = Number(document.all.money_2.value);
    if (0 == money)
    {
        var select_money = document.all.select_money_2;
        money = Number(select_money.options[select_money.selectedIndex].value);
    }

    if (!money)
    {
        msg.textContent = "请选择充值金额";
        return false;
    }
    
    /// 用户名判断
    msg = document.getElementById("input_username_2");
    if (!document.all.username_2.value)
    {
        msg.textContent = "请输入用户名";
        return false;
    }
    msg = document.getElementById("input_username_v_2");
    if (!document.all.username_v_2.value)
    {
        msg.textContent = "请确认用户";
        return false;
    }
    if (document.all.username_v_2.value != document.all.username_2.value)
    {
        msg.textContent = "两次输入的用户名不同";
        return false;
    }

    /// 服务器选择
    msg = document.getElementById("select_server_2");
    var select_server = document.all.sid_2;
    var sid = Number(select_server.options[select_server.selectedIndex].value);
    if (0 == sid)
    {
        msg.textContent = "请选择要充值的服务器";
        return false;
    }
    
    return true;
}

function submit_pay_2()
{
    if (!check_pay_2())
    {
        return;
    }
    document.getElementById("to_pay_2").submit();

}

function check_pay_3()
{
    msg = document.getElementById("input_username_3");
    msg.textContent = "";
    msg = document.getElementById("input_username_v_3");
    msg.textContent = "";
    msg = document.getElementById("select_server_3");
    msg.textContent = "";
    var msg = document.getElementById("n_select_money_3");
    msg.textContent = "";
    
    var money = Number(document.all.money_3.value);
    if (0 == money)
    {
        var select_money = document.all.select_money_3;
        money = Number(select_money.options[select_money.selectedIndex].value);
    }

    if (!money)
    {
        msg.textContent = "请选择充值金额";
        return false;
    }
    
    /// 用户名判断
    msg = document.getElementById("input_username_3");
    if (!document.all.username_3.value)
    {
        msg.textContent = "请输入用户名";
        return false;
    }
    msg = document.getElementById("input_username_v_3");
    if (!document.all.username_v_3.value)
    {
        msg.textContent = "请确认用户";
        return false;
    }
    if (document.all.username_v_3.value != document.all.username_3.value)
    {
        msg.textContent = "两次输入的用户名不同";
        return false;
    }

    /// 服务器选择
    msg = document.getElementById("select_server_3");
    var select_server = document.all.sid_3;
    var sid = Number(select_server.options[select_server.selectedIndex].value);
    if (0 == sid)
    {
        msg.textContent = "请选择要充值的服务器";
        return false;
    }
    
    return true;
}

function submit_pay_3()
{
    if (!check_pay_3())
    {
        return;
    }
    document.getElementById("to_pay_3").submit();

}

function check_pay_4()
{
    msg = document.getElementById("input_username_4");
    msg.textContent = "";
    msg = document.getElementById("input_username_v_4");
    msg.textContent = "";
    msg = document.getElementById("select_server_4");
    msg.textContent = "";
    var msg = document.getElementById("n_select_money_4");
    msg.textContent = "";
    
    var money = Number(document.all.money_4.value);
    if (0 == money)
    {
        var select_money = document.all.select_money_4;
        money = Number(select_money.options[select_money.selectedIndex].value);
    }

    if (!money)
    {
        msg.textContent = "请选择充值金额";
        return false;
    }
    
    /// 用户名判断
    msg = document.getElementById("input_username_4");
    if (!document.all.username_4.value)
    {
        msg.textContent = "请输入用户名";
        return false;
    }
    msg = document.getElementById("input_username_v_4");
    if (!document.all.username_v_4.value)
    {
        msg.textContent = "请确认用户";
        return false;
    }
    if (document.all.username_v_4.value != document.all.username_4.value)
    {
        msg.textContent = "两次输入的用户名不同";
        return false;
    }

    /// 服务器选择
    msg = document.getElementById("select_server_4");
    var select_server = document.all.sid_4;
    var sid = Number(select_server.options[select_server.selectedIndex].value);
    if (0 == sid)
    {
        msg.textContent = "请选择要充值的服务器";
        return false;
    }
    
    return true;
}

function submit_pay_4()
{
    if (!check_pay_4())
    {
        return;
    }
    document.getElementById("to_pay_4").submit();

}

function check_pay_5()
{
    msg = document.getElementById("input_username_5");
    msg.textContent = "";
    msg = document.getElementById("input_username_v_5");
    msg.textContent = "";
    msg = document.getElementById("select_server_5");
    msg.textContent = "";
    var msg = document.getElementById("n_select_money_5");
    msg.textContent = "";
    
    var money = Number(document.all.money_5.value);
    if (0 == money)
    {
        var select_money = document.all.select_money_5;
        money = Number(select_money.options[select_money.selectedIndex].value);
    }

    if (!money)
    {
        msg.textContent = "请选择充值金额";
        return false;
    }
    
    /// 用户名判断
    msg = document.getElementById("input_username_5");
    if (!document.all.username_5.value)
    {
        msg.textContent = "请输入用户名";
        return false;
    }
    msg = document.getElementById("input_username_v_5");
    if (!document.all.username_v_5.value)
    {
        msg.textContent = "请确认用户";
        return false;
    }
    if (document.all.username_v_5.value != document.all.username_5.value)
    {
        msg.textContent = "两次输入的用户名不同";
        return false;
    }

    /// 服务器选择
    msg = document.getElementById("select_server_5");
    var select_server = document.all.sid_5;
    var sid = Number(select_server.options[select_server.selectedIndex].value);
    if (0 == sid)
    {
        msg.textContent = "请选择要充值的服务器";
        return false;
    }
    
    return true;
}

function submit_pay_5()
{
    if (!check_pay_5())
    {
        return;
    }
    document.getElementById("to_pay_5").submit();

}

function check_pay_6()
{
    msg = document.getElementById("input_username_6");
    msg.textContent = "";
    msg = document.getElementById("input_username_v_6");
    msg.textContent = "";
    msg = document.getElementById("select_server_6");
    msg.textContent = "";
    var msg = document.getElementById("n_select_money_6");
    msg.textContent = "";
    
    var money = Number(document.all.money_6.value);
    if (0 == money)
    {
        var select_money = document.all.select_money_6;
        money = Number(select_money.options[select_money.selectedIndex].value);
    }

    if (!money)
    {
        msg.textContent = "请选择充值金额";
        return false;
    }
    
    /// 用户名判断
    msg = document.getElementById("input_username_6");
    if (!document.all.username_6.value)
    {
        msg.textContent = "请输入用户名";
        return false;
    }
    msg = document.getElementById("input_username_v_6");
    if (!document.all.username_v_6.value)
    {
        msg.textContent = "请确认用户";
        return false;
    }
    if (document.all.username_v_6.value != document.all.username_6.value)
    {
        msg.textContent = "两次输入的用户名不同";
        return false;
    }

    /// 服务器选择
    msg = document.getElementById("select_server_6");
    var select_server = document.all.sid_6;
    var sid = Number(select_server.options[select_server.selectedIndex].value);
    if (0 == sid)
    {
        msg.textContent = "请选择要充值的服务器";
        return false;
    }
    
    return true;
}

function submit_pay_6()
{
    if (!check_pay_6())
    {
        return;
    }
    document.getElementById("to_pay_6").submit();

}

function check_pay_7()
{
    msg = document.getElementById("input_username_7");
    msg.textContent = "";
    msg = document.getElementById("input_username_v_7");
    msg.textContent = "";
    msg = document.getElementById("select_server_7");
    msg.textContent = "";
    var msg = document.getElementById("n_select_money_7");
    msg.textContent = "";
    var money = Number(document.all.money_7.value);
    
    if (0 == money)
    {
        var select_money = document.all.select_money_7;
        money = Number(select_money.options[select_money.selectedIndex].value);
    }

    if (!money)
    {
        msg.textContent = "请选择充值金额";
        return false;
    }

    /// 用户名判断
    msg = document.getElementById("input_username_7");
    if (!document.all.username_7.value)
    {
        msg.textContent = "请输入用户名";
        return false;
    }
    msg = document.getElementById("input_username_v_7");
    if (!document.all.username_v_7.value)
    {
        msg.textContent = "请确认用户";
        return false;
    }
    if (document.all.username_v_7.value != document.all.username_7.value)
    {
        msg.textContent = "两次输入的用户名不同";
        return false;
    }

    /// 服务器选择
    msg = document.getElementById("select_server_7");
    var select_server = document.all.sid_7;
    var sid = Number(select_server.options[select_server.selectedIndex].value);
    if (0 == sid)
    {
        msg.textContent = "请选择要充值的服务器";
        return false;
    }
    
    return true;
}

function submit_pay_7()
{
    if (!check_pay_7())
    {
        return;
    }
    document.getElementById("to_pay_7").submit();

}
</script>

</body>
</html>