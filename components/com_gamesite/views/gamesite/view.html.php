<?php
defined('_JEXEC') or die();
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the game site Component
*/
class gamesiteViewGameSite extends JView
{
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
    /// 官网信息
    var $site_param;
    
    // Overwriting JView display method
    function display($tpl = null) 
    {
        $this->get('Content');
        
        $this->_notice_board = $this->get('NoticeBoard');
        $this->_statergy = $this->get('Statergy');
        $this->_greed_hand = $this->get('Greedhand');
        $this->_master_hand = $this->get('MasterHand');
        $this->_feature = $this->get('Feature');
        $this->site_param = $this->get('SiteParam');
        
        //var_dump($this->site_param->bk_img);exit;
        
        // Display the view
        parent::display($tpl);
    }
}
?>