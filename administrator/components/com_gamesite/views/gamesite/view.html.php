<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

/**
 * GameSite View
 */
class gamesiteViewGameSite extends JView
{
	var $game_list;
	var $get_api;
	
    function display($tpl = null) 
    {
    	/// 获得当前已经的游戏列表
    	$this->msg = $this->get('CurGameList');
    	/// 总站上的游戏列表
    	$game_list = $this->get('GameList');
    	
    	$this->get_api['ret'] = $game_list['ret'];
    	$this->get_api['msg'] = $game_list['msg'];
    	
    	$this->game_list = array();
    	if ($this->get_api['ret'] == 1)
    	{
	    	if (count($this->msg) != count($game_list['game']))
	    	{
	    		foreach ($game_list['game'] as $game)
	    		{
	    			if (!$this->is_site($game['id']))
	    			{
	    				//var_dump($game['id']);exit;
	    				$this->game_list[$game['id']] = $game;
	    			}
	    		}
	    	}
    	}
    	
        parent::display($tpl);
    }
    
    function is_site($game_id)
    {
    	foreach ($this->msg as $game)
    	{
    		if ($game->guid == $game_id)
    		{
    			return true;
    		}
    	}
    	
    	return false;
    }
}
?>