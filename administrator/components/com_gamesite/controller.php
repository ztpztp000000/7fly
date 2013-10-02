<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
  
// import Joomla controller library
jimport('joomla.application.component.controller');
  
/**
 * General Controller of RecommendGame component
 */
class GameSiteController extends JController
{
	function newsite()
	{
		global $mainframe;
		
		$game_id = JRequest::getVar('gid');
		if (0 == $game_id)
		{
			header("Location: ".JURI::base()."index.php?option=com_gamesite");
			return;
		}
		$game_no = JRequest::getVar('gno');
		if (empty($game_no))
		{
			header("Location: ".JURI::base()."index.php?option=com_gamesite");
            return;
		}
		$game_name = JRequest::getVar('gname');
        if (empty($game_name))
        {
            header("Location: ".JURI::base()."index.php?option=com_gamesite");
            return;
        }

		$db=&JFactory::getDBO();
		$param = new stdClass;
		$param->guid = $game_id;
		$param->name = $game_name;
		$param->no = $game_no;
		$db->insertObject('#__games', $param, 'id');
        header("Location: ".JURI::base()."index.php?option=com_gamesite");
	}
	
	function save()
	{
		$game_depict = JRequest::getVar('game_depict', '');
		$bk_img = JRequest::getVar('bk_img', '', 'files');
		$spread_bk = JRequest::getVar('spread_bk', '', 'files');
		$game_no = JRequest::getVar('game_no', '');
		$game_id = JRequest::getVar('game_id', '');
		$bk_color = JRequest::getVar('bk_color', '');
		
		if (empty($game_no) || empty($game_id))
		{
			header("Location: ".JURI::base()."index.php?option=com_gamesite");
			exit;
		}
		
		jimport('joomla.filesystem.file');
		$file = new JFile();
		
		$bUpdate = false;
		$param = new stdClass;
		
		if (!empty($bk_img['name']))
		{
			$file_ext = strtolower($file->getExt($bk_img['name']));
			if ($file_ext!='jpg' && $file_ext!='jpeg' && $file_ext!='png')
			{
				header("Location: ".JURI::base()."index.php?option=com_gamesite");
				exit;
			}
			$upload_file = strtolower(JFile::makeSafe($game_no.'_bk.'.$file_ext));
	        $filepath = $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$upload_file;
	        $upload_file = '/uploads/'.$upload_file;
	        if (!JFile::upload($bk_img['tmp_name'], $filepath))
	        {
	            header("Location: ".JURI::base()."index.php?option=com_gamesite");
	            exit;
	        }
	        $bUpdate = true;
	        $param->bk_img = $upload_file;
		}
		
	   if (!empty($spread_bk['name']))
        {
            $file_ext = strtolower($file->getExt($spread_bk['name']));
            if ($file_ext!='jpg' && $file_ext!='jpeg' && $file_ext!='png')
            {
                header("Location: ".JURI::base()."index.php?option=com_gamesite");
                exit;
            }
            $upload_file = strtolower(JFile::makeSafe($game_no.'_spread_bk.'.$file_ext));
            $filepath = $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$upload_file;
            $upload_file = '/uploads/'.$upload_file;
            if (!JFile::upload($spread_bk['tmp_name'], $filepath))
            {
                header("Location: ".JURI::base()."index.php?option=com_gamesite");
                exit;
            }
            $bUpdate = true;
            $param->spread_bk = $upload_file;
        }

        $db=&JFactory::getDBO();
        
        $param->guid = $game_id;
        if (!empty($game_depict))
        {
        	$bUpdate = true;
            $param->depict = $game_depict;
        }
        if (!empty($bk_color))
        {
        	$bUpdate = true;
        	$param->bk_color = $bk_color;
        }
        
        if ($bUpdate)
        {
            $db->updateObject('#__games', $param, 'guid');
        }
 
        header("Location: ".JURI::base()."index.php?option=com_gamesite");
	}
}
?>