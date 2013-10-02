<?php
defined('_JEXEC') or die();
jimport( 'joomla.application.component.model' );
 
class gamesiteModelEdit extends JModel
{
    function __construct()
    {
        parent::__construct();
    }
    
    public function &getGameInfo()
    {
        $query = 'SELECT * '.' FROM #__games where guid='.JRequest::getVar('gid');
        return $this->_getList($query);
    }
}