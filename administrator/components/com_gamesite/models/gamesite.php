<?php
defined('_JEXEC') or die();
jimport( 'joomla.application.component.model' );
 

class gamesiteModelGameSite extends JModel
{
	var $_game_list;
    function __construct()
    {
        parent::__construct();
    }
    
    public function &getCurGameList()
    {
        $query = 'SELECT guid,`name` '.' FROM #__games';
        return $this->_getList($query);
    }
    
    public function &getGameList()
    {
    	require_once JPATH_ROOT.'/libraries/daxian/api.php';
    	
    	$config = JFactory::getConfig();
        $api = new MainSiteApi($config->get('api_url'), JApplication::getCfg('site_id'), JApplication::getCfg('site_key'));
        $this->_game_list = $api->get_game_list();
        
        return $this->_game_list;
    }
}