// JavaScript Document
function tabChange(obj,id)
{
 var arrayli = obj.parentNode.getElementsByTagName("li"); //获取li数组
 var arrayul = document.getElementById(id).getElementsByTagName("ul"); //获取ul数组
 for(i=0;i<arrayul.length;i++)
 {
  if(obj==arrayli[i])
  {
   arrayli[i].className = "cli";
   arrayul[i].className = "";
  }
  else
  {
   arrayli[i].className = "";
   arrayul[i].className = "hidden";
  }
 }
}

//礼包js 
	function game_ajax(){ 
		var str2 = document.getElementById('game_id');
		
		$.ajax({
			url:'?m=libao&action=game_ajax',
			type:'GET',
			data:{game_id:str2.value},
			timeout:5000,
			error:function(){
				alert('远程数据读取错误！');
				},
			success:function(result)
			{
				document.getElementById('g_info_id').innerHTML=result;
			}
		});
	}

function newcard(){
	var str3 = document.getElementById('u_member_id').value;
	var str1 = document.getElementById('game_id').value;
	var str2 = document.getElementById('district_id').value;
	if(str3 == ''){
		alert('你没有登录！请登录后再领取！');
		return false;
		}
	if(str1 =='') {
		alert('请选择游戏！');
		return false;
		}
	if(str2 ==''){
		alert('请选择游戏分区！');
		return false;
		}	
			$.ajax({
			url:'?m=libao&action=newcard_ajax',
			type:'GET',
			data:{game_id:str1,district_id:str2,username:str3},
			timeout:5000,
			error:function(){
				alert('远程数据读取错误！');
				},
			success:function(result)
			{
				document.getElementById('card').style.display='block';
				if(result=='no'){
				document.getElementById('newcard').innerHTML=' 新手卡已经领完啦！';
				}else{
				document.getElementById('newcard').innerHTML='你的新手卡号：'+result;
				}
			}
		});
	
}