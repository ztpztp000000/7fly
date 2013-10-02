<?php
/**
 * Kunena Component
 * @package Kunena.Administrator
 * @subpackage Controllers
 *
 * @copyright (C) 2008 - 2012 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/
defined ( '_JEXEC' ) or die ();

/**
 * Kunena Attachments Controller
 *
 * @since 2.0
 */
class KunenaAdminControllerAttachments extends KunenaController {
	protected $baseurl = null;

	public function __construct($config = array()) {
		parent::__construct($config);
		$this->baseurl = 'administrator/index.php?option=com_kunena&view=attachments';
	}

	function delete() {
		$db = JFactory::getDBO ();

		if (! JRequest::checkToken ()) {
			$this->app->enqueueMessage ( JText::_ ( 'COM_KUNENA_ERROR_TOKEN' ), 'error' );
			$this->app->redirect ( KunenaRoute::_($this->baseurl, false) );
		}

		$cids = JRequest::getVar ( 'cid', array (), 'post', 'array' );

		if (! $cids) {
			$this->app->enqueueMessage ( JText::_ ( 'COM_KUNENA_NO_ATTACHMENTS_SELECTED' ), 'error' );
			$this->app->redirect ( KunenaRoute::_($this->baseurl, false) );
		}

		foreach( $cids as $id ) {
			$attachment = KunenaForumMessageAttachmentHelper::get($id);
			$attachment->delete();
		}

		$this->app->enqueueMessage ( JText::_('COM_KUNENA_ATTACHMENTS_DELETED_SUCCESSFULLY') );
		$this->app->redirect ( KunenaRoute::_($this->baseurl, false) );
	}
}
