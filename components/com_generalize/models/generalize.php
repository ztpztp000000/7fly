<?php
/**
 * Generalize Model for Generalize Component
 * 
 * @package    daxian
 * @subpackage Components
 */
 
defined('_JEXEC') or die();
jimport('joomla.application.component.modellist');
 
/**
 * Generalize Model
 *
 * @package    daxian
 * @subpackage Components
 */
class generalizeModelGeneralize extends JModelList
{
	private $_game_list;
	
    function __construct()
    {
        parent::__construct();
    }

    protected function getListQuery()
    {
        $sp_id = $_SESSION['__default']['user']->id;
        if (0 == $sp_id)
        {
            header('Location: /index.php/login');
            exit;
        }
        
        $query = 'SELECT id,username,registerDate,charge_money,spread_money FROM #__users where spread_user='.$sp_id;
        return $query;
    }
    
    public function &getGameList()
    {
    	if (!empty($this->_game_list))
    	{
    		return $this->_game_list;
    	}
    	
    	require_once JPATH_ROOT.'/libraries/daxian/api.php';
    
        $config = JFactory::getConfig();
        $api = new MainSiteApi($config->get('api_url'), JApplication::getCfg('site_id'), JApplication::getCfg('site_key'));
        $this->_game_list = $api->get_game_list();
        return $this->_game_list;
    }
}