<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');
  

class usercensusViewUserCensus extends JView
{
	var $_user_count;
    var $_month_reg;
    var $_day_reg;
    var $_total_charge;
    var $_month_charge;
    var $_day_charge;
    var $_dau;
    var $_mau;
	
    function display($tpl = null) 
    {
    	$this->_user_count = $this->get('UserCount');
    	$this->_month_reg = $this->get('MonthReg');
    	
    	$this->_day_reg = $this->get('DayReg');
    	$this->_total_charge = $this->get('TotalCharge');
    	$this->_month_charge = $this->get('MonthCharge');
    	$this->_day_charge = $this->get('DayCharge');
    	$this->_dau = $this->get('DAU');
    	$this->_mau = $this->get('MAU');
    	
        parent::display($tpl);
    }
}
?>