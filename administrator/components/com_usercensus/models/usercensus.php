<?php
defined('_JEXEC') or die();
jimport( 'joomla.application.component.model' );
 

class usercensusModelUserCensus extends JModel
{
	var $_last;
	var $_user_count;
	var $_month_reg;
	var $_day_reg;
	var $_total_charge;
	var $_month_charge;
	var $_day_charge;
	var $_dau;
	var $_mau;
	
    function __construct()
    {
        parent::__construct();
    }
    
    /// 总注册人数
    public function &getUserCount()
    {
    	/// 30秒刷新一次
        if (!empty($this->_user_count) && time()-$this->_last>30)
        {
            return $this->_user_count;
        }
        $this->_last = time();
        
        /// 总注册人数
        $query = "SELECT COUNT(id) as user_count FROM #__users";
        $rlt = $this->_getList($query);
        $this->_user_count = $rlt[0]->user_count;
        
        return $this->_user_count;
    }
    
    /// 本月注册人数
    public function &getMonthReg()
    {
    	/// 10分钟可刷新一次
    	if (!empty($this->_month_reg) && time()-$this->_last>600)
    	{
    		return $this->_month_reg;
    	}
    	
    	$query = "SELECT COUNT(id) as month_reg FROM #__users WHERE DATE_FORMAT(registerDate,'%Y-%m')=DATE_FORMAT(NOW(),'%Y-%m')";
	    $rlt = $this->_getList($query);
    	$this->_month_reg = $rlt[0]->month_reg;
    	return $this->_month_reg;
    }
    
    // 本日注册人数
    public function &getDayReg()
    {
        /// 30秒刷新一次
        if (!empty($this->_day_reg) && time()-$this->_last>30)
        {
            return $this->_day_reg;
        }
        
    	$query = "SELECT COUNT(id) as day_reg FROM #__users WHERE DATE_FORMAT(registerDate,'%Y-%m-%d')=DATE_FORMAT(NOW(),'%Y-%m-%d')";
        $rlt = $this->_getList($query);
        $this->_day_reg = $rlt[0]->day_reg;
        return $this->_day_reg;
    }
    
    // 总充值金额
    public function &getTotalCharge()
    {
        /// 30秒刷新一次
        if (!empty($this->_total_charge) && time()-$this->_last>30)
        {
            return $this->_total_charge;
        }
        
    	$query = "SELECT SUM(charge_money) AS total_charge FROM #__pay WHERE mode_id!=".JApplication::getCfg('site_money_mid')." AND state=2";
        $rlt = $this->_getList($query);
        $this->_total_charge = $rlt[0]->total_charge;
        if (NULL == $this->_total_charge)
        {
            $this->_total_charge = 0;
        }
        return $this->_total_charge;
    }
    
    // 本月充值金额
    public function &getMonthCharge()
    {
        if (!empty($this->_month_charge) && time()-$this->_last>600)
        {
            return $this->_month_charge;
        }
        
    	$query = "SELECT SUM(charge_money) AS month_charge FROM #__pay WHERE mode_id!=".JApplication::getCfg('site_money_mid')." AND state=2 and DATE_FORMAT(FROM_UNIXTIME(pay_time),'%Y-%m')=DATE_FORMAT(NOW(),'%Y-%m')";
        $rlt = $this->_getList($query);
        $this->_month_charge = $rlt[0]->month_charge;
        if (NULL == $this->_month_charge)
        {
        	$this->_month_charge = 0;
        }
        return $this->_month_charge;
    }
    
    // 今日充值金额
    public function &getDayCharge()
    {
        /// 30秒刷新一次
        if (!empty($this->_day_charge) && time()-$this->_last>30)
        {
            return $this->_day_charge;
        }
        
    	$query = "SELECT SUM(charge_money) AS day_charge FROM #__pay WHERE mode_id!=".JApplication::getCfg('site_money_mid')." AND state=2 and DATE_FORMAT(FROM_UNIXTIME(pay_time),'%Y-%m-%d')=DATE_FORMAT(NOW(),'%Y-%m-%d')";
        $rlt = $this->_getList($query);
        $this->_day_charge = $rlt[0]->day_charge;
        if (NULL == $this->_day_charge)
        {
            $this->_day_charge = 0;
        }
        return $this->_day_charge;
    }
    
    /// 日活跃用户
    public function &getDAU()
    {
        /// 30秒刷新一次
        if (!empty($this->dau) && time()-$this->_last>30)
        {
            return $this->dau;
        }
        
    	$query = "SELECT COUNT(id) as dau FROM #__users WHERE DATE_FORMAT(registerDate,'%Y-%m-%d')=DATE_FORMAT(NOW(),'%Y-%m-%d' or DATE_FORMAT(lastvisitDate,'%Y-%m-%d')=DATE_FORMAT(NOW(),'%Y-%m-%d'))";
        $rlt = $this->_getList($query);
        $this->_dau = $rlt[0]->dau;
        return $this->_dau;
    }
    
    /// 月活跃用户
    public function &getMAU()
    {
        if (!empty($this->_mau) && time()-$this->_last>600)
        {
            return $this->_mau;
        }
        
    	$query = "SELECT COUNT(id) as mau FROM #__users WHERE DATE_FORMAT(registerDate,'%Y-%m')=DATE_FORMAT(NOW(),'%Y-%m' or DATE_FORMAT(lastvisitDate,'%Y-%m')=DATE_FORMAT(NOW(),'%Y-%m'))";
        $rlt = $this->_getList($query);
        $this->_mau = $rlt[0]->mau;
        return $this->_mau;
    }
}