<?php
/**
 * Kunena System Plugin
 * @package Kunena.Plugins
 * @subpackage System
 *
 * @copyright (C) 2008 - 2012 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/
defined ( '_JEXEC' ) or die ();

class plgSystemKunena extends JPlugin {

	function __construct(& $subject, $config) {
		// Check if Kunena API exists
		$api = JPATH_ADMINISTRATOR . '/components/com_kunena/api.php';
		if (! is_file ( $api ))
			return false;

		jimport ( 'joomla.application.component.helper' );
		// Check if Kunena component is installed/enabled
		if (! JComponentHelper::isEnabled ( 'com_kunena', true )) {
			return false;
		}

		// Load Kunena API
		require_once $api;

		// Do not load if Kunena version is not supported or Kunena is not installed
		if (!(class_exists('KunenaForum') && KunenaForum::isCompatible('2.0') && KunenaForum::installed())) return false;

		$this->loadLanguage('plg_system_kunena.sys', JPATH_ADMINISTRATOR) || $this->loadLanguage('plg_system_kunena.sys', KPATH_ADMIN);

		if (version_compare(JVERSION, '1.6','<')) {
			// Joomla 1.5: Fix bugs and bad performance
			$lang = JFactory::getLanguage();
			if (JFactory::getApplication()->isAdmin()) {
				// Load the missing language files in administration
				$lang->load('com_kunena.menu', JPATH_ADMINISTRATOR) || $lang->load('com_kunena.menu', KPATH_ADMIN);
				$lang->load('com_kunena.sys', JPATH_ADMINISTRATOR) || $lang->load('com_kunena.sys', KPATH_ADMIN);
				if (JRequest::getCmd('option')=='com_plugins' && JRequest::getCmd('view')=='plugin' && JRequest::getCmd('task')=='edit') {
					// Support for J!1.7 .sys language files
					$cid = JRequest::getVar( 'cid', array(0), '', 'array' );
					$row = JTable::getInstance('plugin');
					$row->load( (int) $cid[0] );
					$lang->load( 'plg_' . trim( $row->folder ) . '_' . trim( $row->element ) . '.sys', JPATH_ADMINISTRATOR )
						|| $lang->load( 'plg_' . trim( $row->folder ) . '_' . trim( $row->element ) . '.sys', KPATH_ADMIN );
				}
			} else {
				// Never load language file
				$filename = JLanguage::getLanguagePath( JPATH_BASE, $lang->_lang)."/{$lang->_lang}.com_kunena.ini";
				$lang->_paths['com_kunena'][$filename] = 1;
			}
		}

		parent::__construct ( $subject, $config );
	}

	/**
	 * @internal
	 */
	public function onKunenaGetConfiguration($context, &$params) {
		if ($context == 'kunena.configuration') {
			$params["plg_{$this->_type}_{$this->_name}"] = $this->params;
		}
	}

	/**
	 * Map Kunena's ContentPrepare to Joomla's ContentPrepare event
	 *
	 * This is done to be able to use Joomla plugins on Kunena postings.
	 * Option to enable or disable this, is found as plugin parameter.
	 *
	 * @access public
	 * @see self::runJoomlaContentEvent()
	 * @since Kunena 2.0
	 * @todo Make an object to array conversion, to support also single postings
	 *
	 * @param	string	$context	In which context were event called?
	 * @param	array	$items		Array of multiple KunenaForumMessage objects
	 * @param	object	$params		JRegistry object holding eventual parameters
	 * @param	int		$page		An integer holding page number
	 *
	 * @return array of KunenaForumMessage objects
	 */
// FIXME: function below was totally broken, so it's currently turned off
/*
	public function onKunenaPrepare($context, &$items, &$params, $page = 0) {
		$jcontentevent			= (int) $this->params->get('jcontentevents', false);
		$jcontentevent_target	= (array) $this->params->get('jcontentevent_target', array('body'));

		if ( $jcontentevent ) {
			switch ( $context ) {

				// Object KunenaForumTopic
				case 'kunena.topic':
					if ( in_array('title', $jcontentevent_target) ) {
						$this->runJoomlaContentEvent( $item->subject, $params, $page );
					}
					if ( in_array('body', $jcontentevent_target) ) {
						$this->runJoomlaContentEvent( $item->first_post_message, $params, $page );
						$this->runJoomlaContentEvent( $item->last_post_message, $params, $page );
					}
					break;

				// Array of KunenaForumTopic
				case 'kunena.topics':
					if ( !is_array( $items )) {
						break;
					}
					// Run events on all objects
					foreach ( $items as $item ) {
						if ( in_array('title', $jcontentevent_target) ) {
							$this->runJoomlaContentEvent( $item->subject, $params, $page );
						}
						if ( in_array('body', $jcontentevent_target) ) {
							$this->runJoomlaContentEvent( $item->first_post_message, $params, $page );
							$this->runJoomlaContentEvent( $item->last_post_message, $params, $page );
						}
					}
					break;

				// Object KunenaForumMessage
				case 'kunena.message':
					if ( in_array('title', $jcontentevent_target) ) {
						$this->runJoomlaContentEvent( $items->subject, $params, $page );
					}
					if ( in_array('body', $jcontentevent_target) ) {
						$this->runJoomlaContentEvent( $items->message, $params, $page );
					}
					break;

				// Array of KunenaForumMessage
				case 'kunena.messages':
					if ( !is_array( $items )) {
						break;
					}
					// Run events on all objects
					foreach ( $items as $item ) {
						if ( in_array('title', $jcontentevent_target) ) {
							$this->runJoomlaContentEvent( $item->subject, $params, $page );
						}
						if ( in_array('body', $jcontentevent_target) ) {
							$this->runJoomlaContentEvent( $item->message, $params, $page );
						}
					}

					break;
				default:
			}
		}
		return $items;
	}
*/


	/**
	 * Runs all Joomla content plugins on a single KunenaForumMessage
	 *
	 * @access protected
	 * @see self::onKunenaPrepare()
	 * @since Kunena 2.0
	 *
	 * @param	string	$text		String to run events on
	 * @param	object	$params		JRegistry object holding eventual parameters
	 * @param	int		$page		An integer holding page number
	 *
	 * @return object KunenaForumMessage
	 */
	protected function runJoomlaContentEvent( &$text, &$params, $page = 0 ) {
		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin('content');

		$row = new stdClass();
		$row->text =& $text;

		if (version_compare(JVERSION, '1.6','>')) {
			// Joomla 1.6+
			$results = $dispatcher->trigger('onContentPrepare', array ('text', &$row, &$params, 0));

		} else {
			// Joomla 1.5
			$results = $dispatcher->trigger('onPrepareContent', array (&$row, &$params, 0));
		}

		$text =& $row->text;

		return $text;
	}

	// Joomla 1.5 support
	public function onAfterStoreUser($user, $isnew, $success, $msg) {
		if (version_compare(JVERSION, '1.6', '>')) return;
		return $this->onUserAfterSave($user, $isnew, $success, $msg);
	}
	// Joomla 1.6+ support
	public function onUserAfterSave($user, $isnew, $success, $msg) {
		//Don't continue if the user wasn't stored succesfully
		if (! $success) {
			return;
		}
		if ($isnew && intval($user ['id'])) {
			$kuser = KunenaFactory::getUser(intval($user ['id']));
			$kuser->save();
		}

		/*
		// See: http://www.kunena.org/forum/159-k-16-common-questions/63438-category-subscriptions-default-subscribed#63554
		// TODO: Subscribe user to every category if he is new and Kunena is configured to do so
		if ($isnew) {
			$subscribedCategories = '1,2,3,4,5,6,7,8,9,10';
			$db = Jfactory::getDBO();
			$query = "INSERT INTO #__kunena_user_categories (user_id,category_id,subscribed)
				SELECT {{$db->quote($user->userid)} AS user_id, c.id as category_id, 1
				FROM #__kunena_categories AS c
				LEFT JOIN #__kunena_user_categories AS s ON c.id=s.category_id AND s.user_id={{$db->quote($user->userid)}
				WHERE c.parent>0 AND c.id IN ({$subscribedCategories}) AND s.user_id IS NULL";
			$db->setQuery ( $query );
			$db->query ();
			KunenaError::checkDatabaseError();

			// Here's also query to subscribe all users (including blocked) to all existing cats:
			$query = "INSERT INTO #__kunena_user_categories (user_id,category_id,subscribed)
				SELECT u.id AS user_id, c.id AS category_id, 1
				FROM #__users AS u
				JOIN #__kunena_categories AS c ON c.parent>0
				LEFT JOIN #__kunena_user_categories AS s ON u.id=s.user_id
				WHERE c.id IN ({$subscribedCategories}) AND s.user_id IS NULL";
		}
		*/
	}

	/**
	 * Prevent downgrades to Kunena 1.7 and older releases
	 */
	public function onExtensionBeforeInstall($method, $type, $manifest, $eid) {
		// We don't want to handle discover install (where there's no manifest provided)
		if (!$manifest) return;
		return $this->onExtensionBeforeUpdate($type, $manifest);
	}
	/**
	 * Prevent downgrades to Kunena 1.7 and older releases
	 */
	public function onExtensionBeforeUpdate($type, $manifest) {
		if ($type != 'component') return true;

		// Generate component name
		$name = strtolower(JFilterInput::getInstance()->clean((string) $manifest->name, 'cmd'));
		$element = (substr($name, 0, 4) == "com_") ? $name : "com_{$name}";
		if ($element != 'com_kunena') return true;

		// Kunena 2.0.0-BETA2 and later support this feature in their installer
		if (version_compare($manifest->version, '2.0.0', '>=')) return true;

		// Check if we can downgrade to the current version
		if (class_exists('KunenaInstaller') && KunenaInstaller::canDowngrade($manifest->version)) {
			return true;
		}

		// Old version detected: emulate failed installation
		$app = JFactory::getApplication();
		$app->enqueueMessage(sprintf('Sorry, it is not possible to downgrade Kunena %s to version %s.', KunenaForum::version(), $manifest->version), 'warning');
		$app->enqueueMessage(JText::_('JLIB_INSTALLER_ABORT_COMP_INSTALL_CUSTOM_INSTALL_FAILURE'), 'error');
		$app->enqueueMessage(JText::sprintf('COM_INSTALLER_MSG_UPDATE_ERROR', JText::_('COM_INSTALLER_TYPE_TYPE_'.strtoupper($type))));
		$app->redirect('index.php?option=com_installer');
	}
}