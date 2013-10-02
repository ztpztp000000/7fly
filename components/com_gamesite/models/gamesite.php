<?php
/**
 * Game Site Model for GameSite Component
 * 
 * @package    daxian
 * @subpackage Components
 */
 
defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.model' );
 
class gamesiteModelGameSite extends JModel
{
	var $_data;
	/// 公告信息
    var $_notice_board;
    /// 攻略
    var $_statergy;
    /// 新手
    var $_greed_hand;
    /// 高手
    var $_master_hand;
    /// 特色
    var $_feature;
    
    function __construct()
    {
        parent::__construct();
    }
    
    
    public function &getContent()
    {
    	if (!empty($this->_data))
    	{
    		return;
    	}
    	/// 获取数据并分类保存
        $query = 'SELECT id,title,catid '.' FROM #__content where gameid='.JRequest::getVar('gid');
        $this->_data = $this->_getList($query);
        
        foreach ($this->_data as $content)
        {
        	switch ($content->catid)
        	{
        	case 88:
        		// 公告
        		$this->_notice_board['id'] = $content->id;
        		$this->_notice_board['title'] = $content->title;
        	case 89:
        		/// 攻略
        		$this->_statergy['id'] = $content->id;
                $this->_statergy['title'] = $content->title;
        	case 90:
        		/// 新手
        		$this->_greed_hand['id'] = $content->id;
                $this->_greed_hand['title'] = $content->title;
        	case 91:
        		/// 高手
        		$this->_master_hand['id'] = $content->id;
                $this->_master_hand['title'] = $content->title;
        	case 92:
        		/// 特色
        		$this->_feature['id'] = $content->id;
                $this->_feature['title'] = $content->title;
        	default:
        		break;
        	}
        }
    }
    
    public function &getNoticeBoard()
    {
    	return $this->_notice_board;
    }
    public function &getStatergy()
    {
        return $this->_statergy;
    }
    public function &getGreedhand()
    {
        return $this->_greed_hand;
    }
    public function &getMasterHand()
    {
        return $this->_master_hand;
    }
    public function &getFeature()
    {
        return $this->_feature;
    }
    
    public function &getSiteParam()
    {
    	/// 获取数据并分类保存
        $query = 'SELECT `name`,`no`,depict,bk_img FROM '.'#__games where guid='.JRequest::getVar('gid');
        $site_param = $this->_getList($query);
        return $site_param[0];
    }
}