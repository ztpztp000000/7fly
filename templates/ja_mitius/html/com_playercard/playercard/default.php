<?php
defined('_JEXEC') or die;
?>
<style type="text/css">
.pcd_main{width:950px;height:500px;border:1px solid #999999;margin:0 auto;margin-top:100px;background:#fafafa;}
.pcd_loct{width:700px;height:30px;line-height:30px;border:1px solid #CCCCCC;background:#FFFFFF;margin:10px;float:left;overflow:hidden;}
div{font-size:12px; overflow:hidden;}
li{ list-style-type:none;}
.pcd_backbtn{margin:10px;float:right;}
.pcd_backbtn input{width:100px;height:30px;}
.pcd_content{width:800px;height:300px;margin:0 auto; border:1px solid #CCCCCC;background:#FFFFFF;}
.pcd_content li{padding-top:20px;padding-left:100px;}
</style>
<div class="pcd_main">
	<div class="pcd_loct"><div style="margin-left:20px;">您的位置：新手卡领取</div></div>
	<div class="pcd_backbtn"><a href="">返回首页</a></div>
	
	<div class="pcd_content">
		<li style="padding-bottom:10px;margin-left:20px;border-bottom:1px solid #FF6600;width:500px;">点击<b>领取</b>按钮领取<b>新手卡</b></li>
		<li><select name="sid" id="sid" style="width:180px;height:35px;line-height:35px; border:1px solid #FF9900;">
			<option value="0">请选择服务器</option>
		    <?php foreach ($this->msg['server'] as $server) : ?>
		    <option value="<?php echo $server['id']; ?>"><?php echo $server['name']; ?></option>
		    <?php endforeach; ?>
		</select>
		</li>
		<li>您的新手卡为:</li>
		<li><div style="width:300px;height:30px; line-height:30px;color:#FFFFFF;border:1px solid #999999;background:#666666; text-align:center;"><font id="card_no"></font></div></li>
		<li><input type="button" onclick="getcard();" style="width:147px;height:37px; background:url(/templates/ja_mitius/images/pcd_lq.jpg);border:0; cursor:pointer;"/></li>
		
</div>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>
function getcard()
{
    var server = document.all.sid;
    var sid = server.options[server.selectedIndex].value;
    var msg = document.getElementById("card_no");
    
    if (0 == sid)
    {
        msg.textContent = "请选择服务器";
    }
    else
    {
        $.ajax({
            type: "get",
            cache: false,
            url: "/index.php/home/?task=getcard&sid="+sid,
            success: function (no) {
               msg.textContent = no;
            },
        });
    }
}
</script>