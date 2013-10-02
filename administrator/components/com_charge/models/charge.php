<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');
 
class chargeModelCharge extends JModelList
{
	var $_pay_log;
	var $_time;
	
    function __construct()
    {
        parent::__construct();
        $this->_time = 0;
    }

    public function &getPayLog()
    {
    	$this->t = 1;
    	$curTime = time();
    	if ($curTime-$this->_time < 30 && !empty($this->_pay_log))
    	{
    		return $this->_pay_log;
    	}
    	$this->_time = $curTime;
    	
        $query = 'SELECT * FROM #__pay order by guid desc';
        return $this->_getList($query);
    }
    
    protected function getListQuery()
    {
    	$query = 'SELECT * FROM #__pay order by guid desc';
    	return $query;
    }
}