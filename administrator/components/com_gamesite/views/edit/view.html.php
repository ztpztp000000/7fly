<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

/**
 * GameSite View
 */
class gamesiteViewEdit extends JView
{
	var $game_info;

    function display($tpl = null) 
    {
    	$this->game_info = $this->get('GameInfo');
    	if (count($this->game_info) == 0)
    	{
    		header("Location: ".JURI::base()."index.php?option=com_gamesite");
    		exit;
    	}
    	
    	$this->game_info = $this->game_info[0];
    	
    	$session = & JFactory::getSession();  
        $this->assignRef('session',$session);  
  
        parent::display($tpl);
    }
}
?>