<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_gamesite_card
 * @copyright	大贤网络, Inc. All rights reserved.
 * @license		daxian
 */

// no direct access
defined('_JEXEC') or die;
?>

<div class="gamesite_card">
<div class="title">领取新手卡</div>
<div class="main">
<li><select name="sid" id="sid">
    <option value="0">请选择服务器</option>
    <?php foreach ($server_list['server'] as $server) : ?>
    <option value="<?php echo $server['id']; ?>"><?php echo $server['name']; ?></option>
    <?php endforeach; ?>
</select>
</li>
<li class="mycard">您的新手卡为:</li>
<li >
<textarea class="cardinput" id="card_no" disabled="disabled"></textarea>
</li>
<input type="button"  onclick="getcard();"  class="inputbtn" id="newcard">

</div>
</div>

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