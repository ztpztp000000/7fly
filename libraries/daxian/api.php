<?php

class MainSiteApi
{
	private $m_ApiUrl;
	private $m_SiteId;
	private $m_SiteKey;
	
	public function __construct($api_url, $site_id, $site_key)
	{
		$this->m_ApiUrl = $api_url;
		$this->m_SiteId = $site_id;
		$this->m_SiteKey = $site_key;
	}
	
	/// 注册用户
	public function register($username, $pwd, $email, $sp_id)
	{
		$pwd = strtoupper(sha1(trim($pwd)));
		$strKey=$username."_".$pwd."_".$this->m_SiteId."_".$this->m_SiteKey;
		$strKey=md5($strKey);
	
		$url = $this->m_ApiUrl."register.php?user=".$username."&passwd=".$pwd."&email=".$email."&site=".$this->m_SiteId."&sign=".$strKey;
		//var_dump($url);exit;
		return json_decode($this->curl_request($url), true);
	}
	
	/// 检查用户名或邮箱是否重复
	public function check_user($check, $value)
	{
		$strKey=$check."_".$this->m_SiteId."_".$this->m_SiteKey;
		$strKey=md5($strKey);
		
		if ('username' == $check)
		{
			$url =  $this->m_ApiUrl."check_user.php?check=username&user=".$value."&site=".$this->m_SiteId."&sign=".$strKey;
		}
		else if ('email' == $check)
		{
			$url =  $this->m_ApiUrl."check_user.php?check=email&email=".$value."&site=".$this->m_SiteId."&sign=".$strKey;
		}
		//var_dump($url);exit;
		$ret = $this->curl_request($url);
		return $ret;
	}
	
	// 用户登录验证
	function verify_user($username, $pwd)
	{
		$time = time();
		$pwd = strtoupper(sha1(trim($pwd)));
		$strKey=$username."_".$pwd."_".$time."_".$this->m_SiteId."_".$this->m_SiteKey;
		$strKey=md5($strKey);
		
		$url = $this->m_ApiUrl."verify_user.php?user=".$username."&passwd=".$pwd."&time=".$time."&site=".$this->m_SiteId."&sign=".$strKey;
		//var_dump($url);exit;
		$ret = $this->curl_request($url);
		if (NULL == $ret)
		{
			return 255;
		}
		else
		{
			return $ret;
		}
	}
	// 修改密码
	function update_pwd($username, $pwd)
	{
		$time = time();
		$pwd = strtoupper(sha1(trim($pwd)));
		$strKey = $username."_".$pwd."_".$time."_".$this->m_SiteId."_".$this->m_SiteKey;
		$strKey=md5($strKey);
		
		$url = $this->m_ApiUrl."update_user.php?user=".$username."&passwd=".$pwd."&time=".$time."&site=".$this->m_SiteId."&sign=".$strKey;
		//var_dump($url);exit;
		return json_decode($this->curl_request($url), true);
	}
	
	/// 获取登录游戏的url
	function login_game_url($user, $server_id, $extra='')
	{
		$time = time();
		$strKey=$user."_".$server_id."_".$time."_".$this->m_SiteId."_".$this->m_SiteKey;
		$strKey=md5($strKey);
		
		$url = $this->m_ApiUrl."login_game_url.php?user=".$user."&server=".$server_id."&time=".$time."&extra=".$extra."&site=".$this->m_SiteId."&sign=".$strKey;
		//var_dump($url);exit;
		return json_decode($this->curl_request($url), true);
	}
	// 请求登录服务器
	function req_login_game($user, $server_id, $old, $extra)
	{
		$time = time();
		$strKey=$user."_".$time."_".$server_id."_".$old."_".$this->m_SiteId."_".$this->m_SiteKey;
		$strKey=md5($strKey);
		
		$url = $this->m_ApiUrl."login.php?user=".$user."&site=".$this->m_SiteId."&time=".$time."&server=".$server_id."&old=".$old."&extra=".$extra."&sign=".$strKey;
		//header("Location: ".$url);
		return json_decode($this->curl_request($url), true);
	}
	// 请求一个服务器的信息
	function server_info($server_id)
	{
		$time = time();
		$strKey=$time."_".$server_id."_".$this->m_SiteId."_".$this->m_SiteKey;
		$strKey=md5($strKey);
		
		$url = $this->m_ApiUrl."server_info.php?time=".$time."&site=".$this->m_SiteId."&server=".$server_id."&sign=".$strKey;
		//var_dump($url);exit;
		return json_decode($this->curl_request($url), true);
	}
	// 获取新开服的服务器
	function get_new_server(){
		$time = time();
		$strKey=$time."_".$this->m_SiteId."_".$this->m_SiteKey;
		$strKey=md5($strKey);
		
		$url = $this->m_ApiUrl."new_server.php?&site=".$this->m_SiteId."&time=".$time."&sign=".$strKey;
		//var_dump($url);exit;
		return json_decode($this->curl_request($url), true);
	}
	// 获取新游戏(火爆)列表
	function get_newgame_list(){
		$time = time();
		$strKey=$time."_".$this->m_SiteId."_".$this->m_SiteKey;
		$strKey=md5($strKey);
		
		$url = $this->m_ApiUrl."new_game.php?&site=".$this->m_SiteId."&time=".$time."&sign=".$strKey;
		$game_list = json_decode($this->curl_request($url), true);
		
		return $game_list;
	}
	// 获取游戏的服务器列表
	function get_server_list($game_id){
		$time = time();
		$strKey=$game_id."_".$time."_".$this->m_SiteId."_".$this->m_SiteKey;
		$strKey=md5($strKey);
		
		$url = $this->m_ApiUrl."server_list.php?&gameid=".$game_id."&site=".$this->m_SiteId."&time=".$time."&sign=".$strKey;
		$server_list = json_decode($this->curl_request($url), true);
		return $server_list;
	}
	// 获取游戏名称
	function get_game_name($game_id){
		$time = time();
		$strKey=$game_id."_".$time."_".$this->m_SiteId."_".$this->m_SiteKey;
		$strKey=md5($strKey);
		
		$url = $this->m_ApiUrl."game_name.php?&game_id=".$game_id."&site=".$this->m_SiteId."&time=".$time."&sign=".$strKey;
		$game = json_decode($this->curl_request($url), true);
		return $game['name'];
	}
	/// 游戏的人民币充值比例
	function game_per($game_id)
	{
		$time = time();
		$strKey=$game_id."_".$time."_".$this->m_SiteId."_".$this->m_SiteKey;
		$strKey=md5($strKey);
		
		$url = $this->m_ApiUrl."game_per.php?&game_id=".$game_id."&site=".$this->m_SiteId."&time=".$time."&sign=".$strKey;
		$game = json_decode($this->curl_request($url), true);
		return $game;
	}
	// 获取游戏列表
	function get_game_list(){
		$time = time();
		$strKey=$time."_".$this->m_SiteId."_".$this->m_SiteKey;
		$strKey=md5($strKey);
		
		$url = $this->m_ApiUrl."game_list.php?&site=".$this->m_SiteId."&time=".$time."&sign=".$strKey;
		//var_dump($url);exit;
		return json_decode($this->curl_request($url), true);
	}
	// 获取所有服务器基本信息
	function get_all_server(){
		$time = time();
		$strKey=$time."_".$this->m_SiteId."_".$this->m_SiteKey;
		$strKey=md5($strKey);
		
		$url = $this->m_ApiUrl."all_server.php?&site=".$this->m_SiteId."&time=".$time."&sign=".$strKey;
		//var_dump($this->curl_request($url));exit;
		$all_server = json_decode($this->curl_request($url), true);
		
		return $all_server;
	}
	// 获取充值模式列表
	function get_paymode(){
		$time = time();
		$strKey=$time."_".$this->m_SiteId."_".$this->m_SiteKey;
		$strKey=md5($strKey);
		
		$url = $this->m_ApiUrl."paymode_list.php?&site=".$this->m_SiteId."&time=".$time."&sign=".$strKey;
		
		$paymode = json_decode($this->curl_request($url), true);

		return $paymode;
	}
	/// 请求充值
	function req_pay($user, $to_user, $server_id, $rmb, $ip, $pay_mode, $order_no)
	{
		$time = time();
	
		$strKey=$user."_".$to_user."_".$time."_".$server_id."_".$rmb."_".$pay_mode."_".$order_no."_".$this->m_SiteId."_".$this->m_SiteKey;
		$strKey=md5($strKey);

		$url = $this->m_ApiUrl."charge.php?user=".$user."&to_user=".$to_user."&orderno=".$order_no."&site=".$this->m_SiteId."&time=".$time."&server=".$server_id."&money=".$rmb."&mode=".$pay_mode."&ip=".$ip."&sign=".$strKey;
		//var_dump($url);exit;
		return json_decode($this->curl_request($url), true);
	}
	/// 补单
	function repair_bill($order_no)
	{
		$time = time();
		$strKey = $order_no."_".$time."_".$this->m_SiteId."_".$this->m_SiteKey;
		$strKey=md5($strKey);
		$url = $this->m_ApiUrl."repair_bill.php?orderno=".$order_no."&site=".$this->m_SiteId."&time=".$time."&sign=".$strKey;
		return $this->curl_request($url);
	}
	/// 使用平台币充值
	function req_pay_vc($user, $to_user, $server_id, $rmb, $ip, $pay_mode, $order_no)
	{
		$time = time();
	
		$strKey=$user."_".$to_user."_".$time."_".$server_id."_".$rmb."_".$pay_mode."_".$order_no."_".$this->m_SiteId."_".$this->m_SiteKey;
		$strKey=md5($strKey);
	
		$url = $this->m_ApiUrl."charge.php?user=".$user."&to_user=".$to_user."&orderno=".$order_no."&site=".$this->m_SiteId."&time=".$time."&server=".$server_id."&money=".$rmb."&mode=".$pay_mode."&ip=".$ip."&sign=".$strKey;
		$this->curl_request($url);
	}
	// 返利
	function rebates($order_no, $money)
	{
		$time = time();
	
		$strKey=$order_no."_".$money."_".$time."_".$this->m_SiteId."_".$this->m_SiteKey;
		$strKey=md5($strKey);
	
		$url = $this->m_ApiUrl."rebates.php?orderno=".$order_no."&money=".$money."&time=".$time."&site=".$this->m_SiteId."&sign=".$strKey;
		//var_dump($url);exit;
		return json_decode($this->curl_request($url), true);
	}
	// 最近玩过的服务器
	function get_recently_server($user)
	{
		$time = time();
		$strKey=$user."_".$time."_".$this->m_SiteId."_".$this->m_SiteKey;
		$strKey=md5($strKey);
		
		$url = $this->m_ApiUrl."user_game.php?user=".$user."&site=".$this->m_SiteId."&time=".$time."&sign=".$strKey;
	    //var_dump($url);exit;
		return json_decode($this->curl_request($url), true);
	}
/// 最近登录过的游戏
    function last_login_game($user)
    {
        $time = time();
        $strKey=$user."_".$time."_".$this->m_SiteId."_".$this->m_SiteKey;
        $strKey=md5($strKey);
        
        $url = $this->m_ApiUrl."user_last_game.php?user=".$user."&site=".$this->m_SiteId."&time=".$time."&sign=".$strKey;
        //var_dump($url);exit;
        return json_decode($this->curl_request($url), true);
    }
	// 用户平台币
	function get_user_vc($user)
	{
		$time = time();
		$strKey=$user."_".$time."_".$this->m_SiteId."_".$this->m_SiteKey;
		$strKey=md5($strKey);
		
		$url = $this->m_ApiUrl."vc.php?user=".$user."&site=".$this->m_SiteId."&time=".$time."&sign=".$strKey;
		//var_dump($url);exit;
		return $this->curl_request($url);
	}
	// 获取用户充值金额
	function get_user_charge($user)
	{
		$time = time();
		$strKey=$user."_".$time."_".$this->m_SiteId."_".$this->m_SiteKey;
		$strKey=md5($strKey);
		
		$url = $this->m_ApiUrl."user_charge_total.php?user=".$user."&site=".$this->m_SiteId."&time=".$time."&sign=".$strKey;
		
		return $this->curl_request($url);
	}
	// 新手卡列表
	function get_card_list($game_id)
	{
		$time = time();
		$strKey=$game_id."_".$time."_".$this->m_SiteId."_".$this->m_SiteKey;
		$strKey=md5($strKey);
		
		$url = $this->m_ApiUrl."card_list.php?gid=".$game_id."&site=".$this->m_SiteId."&time=".$time."&sign=".$strKey;
		//var_dump($url);exit;
		return json_decode($this->curl_request($url), true);
	}
    // 通过服务器id领取新手卡
    function getcardbyserver($user, $server_id)
    {
        $time = time();
        $strKey=$user."_".$server_id."_".$time."_".$this->m_SiteId."_".$this->m_SiteKey;
        $strKey=md5($strKey);
        
        $url = $this->m_ApiUrl."server_card.php?user=".$user."&sid=".$server_id."&site=".$this->m_SiteId."&time=".$time."&sign=".$strKey;
        //var_dump($url);exit;
        return json_decode($this->curl_request($url), true);
    }
	// 领取新手卡
	function get_card($user, $card_id)
	{
		$time = time();
		$strKey=$user."_".$card_id."_".$time."_".$this->m_SiteId."_".$this->m_SiteKey;
		$strKey=md5($strKey);
		
		$url = $this->m_ApiUrl."get_card.php?user=".$user."&id=".$card_id."&site=".$this->m_SiteId."&time=".$time."&sign=".$strKey;
		//var_dump($url);exit;
		return json_decode($this->curl_request($url), true);
	}
	// 已经领取的新手卡
	function get_my_card($user, $from, $to)
	{
		$time = time();
		$strKey=$user."_".$from."_".$to."_".$time."_".$this->m_SiteId."_".$this->m_SiteKey;
		$strKey=md5($strKey);
		
		$url = $this->m_ApiUrl."user_card.php?user=".$user."&from=".$from."&to=".$to."&site=".$this->m_SiteId."&time=".$time."&sign=".$strKey;
		//var_dump($url);exit;
		return json_decode($this->curl_request($url), true);
	}
	/// 热血海贼王新手卡
	function rxhzw_card($user, $server_id)
	{
		$time = time();
		$strKey = $user."_".$server_id."_".$time."_".$this->m_SiteId."_".$this->m_SiteId;
		$strKey = md5($strKey);
		
		$url = $this->m_ApiUrl."rxhzw_card.php?user=".$user."&sid=".$server_id."&time=".$time."&site=".$this->m_SiteId."&sign=".$strKey;
		//var_dump($url);exit;
		return json_decode($this->curl_request($url), true);
	}
	
	private function curl_request($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_HEADER, false); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$ret = curl_exec($ch);
		
		return $ret;
	}
}
?>
