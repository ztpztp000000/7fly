<?php
/**
 * Kunena Component
 * @package Kunena.Installer
 *
 * @copyright (C) 2008 - 2012 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/
defined ( '_JEXEC' ) or die ();

jimport ( 'joomla.application.component.model' );
jimport ( 'joomla.filesystem.folder' );
jimport ( 'joomla.filesystem.file' );
jimport ( 'joomla.filesystem.path' );
jimport ( 'joomla.filesystem.archive' );
jimport ( 'joomla.installer.installer' );

require_once JPATH_ADMINISTRATOR . '/components/com_kunena/api.php';

/**
 * Install Model for Kunena
 *
 * @since		1.6
 */
class KunenaModelInstall extends JModel {
	/**
	 * Flag to indicate model state initialization.
	 *
	 * @var		boolean
	 * @since	1.6
	 */
	protected $__state_set = false;

	protected $_versionprefix = false;
	protected $_installed = array();
	protected $_versions = array();
	protected $_action = false;

	protected $_errormsg = null;

	protected $_versiontablearray = null;
	protected $_versionarray = null;

	public $steps = null;

	public function __construct() {
		// Load installer language file only from the component
		$lang = JFactory::getLanguage();
		$lang->load('com_kunena.install',KPATH_ADMIN, 'en-GB');
		$lang->load('com_kunena.install',KPATH_ADMIN);
		$lang->load('com_kunena.libraries',KPATH_ADMIN, 'en-GB');
		$lang->load('com_kunena.libraries',KPATH_ADMIN);

		parent::__construct ();
		$this->db = JFactory::getDBO ();

		ignore_user_abort ( true );
		$this->setState ( 'default_max_time', @ini_get ( 'max_execution_time' ) );
		@set_time_limit ( 300 );
		$this->setState ( 'max_time', @ini_get ( 'max_execution_time' ) );

		$this->_versiontablearray = array (array ('prefix' => 'kunena_', 'table' => 'kunena_version' ), array ('prefix' => 'fb_', 'table' => 'fb_version' ) );

		$this->_kVersions = array (
			array ('component' => null, 'prefix' => null, 'version' => null, 'date' => null ) );

		$this->_fbVersions = array (
			array ('component' => 'FireBoard', 'prefix' => 'fb_', 'version' => '1.0.4', 'date' => '2007-12-23', 'table' => 'fb_sessions', 'column' => 'currvisit' ),
			array ('component' => 'FireBoard', 'prefix' => 'fb_', 'version' => '1.0.3', 'date' => '2007-09-04', 'table' => 'fb_categories', 'column' => 'headerdesc' ),
			array ('component' => 'FireBoard', 'prefix' => 'fb_', 'version' => '1.0.2', 'date' => '2007-08-03', 'table' => 'fb_users', 'column' => 'rank' ),
			array ('component' => 'FireBoard', 'prefix' => 'fb_', 'version' => '1.0.1', 'date' => '2007-05-20', 'table' => 'fb_users', 'column' => 'uhits' ),
			array ('component' => 'FireBoard', 'prefix' => 'fb_', 'version' => '1.0.0', 'date' => '2007-04-15', 'table' => 'fb_messages' ),
			array ('component' => null, 'prefix' => null, 'version' => null, 'date' => null ) );

		$this->_sbVersions = array (
			array('component'=>'JoomlaBoard','prefix'=> 'sb_', 'version' =>'v1.0.5', 'date' => '0000-00-00', 'table' => 'sb_messages'),
			array ('component' => null, 'prefix' => null, 'version' => null, 'date' => null ) );

		$this->steps = array (
			array ('step' => '', 'menu' => JText::_('COM_KUNENA_INSTALL_STEP_INSTALL') ),
			array ('step' => 'Prepare', 'menu' => JText::_('COM_KUNENA_INSTALL_STEP_PREPARE') ),
			array ('step' => 'Extract', 'menu' => JText::_('COM_KUNENA_INSTALL_STEP_EXTRACT') ),
			array ('step' => 'Plugins', 'menu' => JText::_('COM_KUNENA_INSTALL_STEP_PLUGINS') ),
			array ('step' => 'Database', 'menu' => JText::_('COM_KUNENA_INSTALL_STEP_DATABASE') ),
			array ('step' => 'Finish', 'menu' => JText::_('COM_KUNENA_INSTALL_STEP_FINISH') ),
			array ('step' => '', 'menu' => JText::_('COM_KUNENA_INSTALL_STEP_COMPLETE') ) );
	}

	/**
	 * Initialise Kunena, run from Joomla installer.
	 */
	public function install() {
		// Make sure that we are using the latest English language files
		$this->installLanguage('en-GB');

		$this->setStep(0);
	}

	/**
	 * Uninstall Kunena, run from Joomla installer.
	 */
	public function uninstall() {
		$this->uninstallPlugin('kunena', 'alphauserpoints');
		$this->uninstallPlugin('kunena', 'community');
		$this->uninstallPlugin('kunena', 'comprofiler');
		$this->uninstallPlugin('kunena', 'gravatar');
		$this->uninstallPlugin('kunena', 'joomla');
		$this->uninstallPlugin('kunena', 'kunena');
		$this->uninstallPlugin('kunena', 'uddeim');
		$this->uninstallPlugin('finder', 'kunena');
		$this->uninstallPlugin('quickicon', 'kunena');
		$this->uninstallPlugin('system', 'kunena');

		$this->uninstallModule('mod_kunenamenu');

		// Remove all Kunena related menu items, including aliases
		if (class_exists('KunenaMenuFix')) {
			$items = KunenaMenuFix::getAll();
			foreach ($items as $item) {
				KunenaMenuFix::delete($item->id);
			}
		}
		$this->deleteMenu();
		return true;
	}

	public function getModel() {
		return $this;
	}

	/**
	 * Overridden method to get model state variables.
	 *
	 * @param	string	Optional parameter name.
	 * @param	mixed	The default value to use if no state property exists by name.
	 * @return	object	The property where specified, the state object where omitted.
	 * @since	1.6
	 */
	public function getState($property = null, $default = null) {
		// if the model state is uninitialized lets set some values we will need from the request.
		if ($this->__state_set === false) {
			$app = JFactory::getApplication ();
			$this->setState ( 'action', $step = $app->getUserState ( 'com_kunena.install.action', null ) );
			$this->setState ( 'step', $step = $app->getUserState ( 'com_kunena.install.step', 0 ) );
			$this->setState ( 'task', $task = $app->getUserState ( 'com_kunena.install.task', 0 ) );
			$this->setState ( 'version', $task = $app->getUserState ( 'com_kunena.install.version', null ) );
			if ($step == 0)
				$app->setUserState ( 'com_kunena.install.status', array () );
			$this->setState ( 'status', $app->getUserState ( 'com_kunena.install.status' ) );

			$this->__state_set = true;
		}

		$value = parent::getState ( $property );
		return (is_null ( $value ) ? $default : $value);
	}

	public function getStatus() {
		return $this->getState ( 'status', array() );
	}

	public function getAction() {
		return $this->getState ( 'action', null );
	}

	public function getStep() {
		return $this->getState ( 'step', 0 );
	}

	public function getTask() {
		return $this->getState ( 'task', 0 );
	}

	public function getVersion() {
		return $this->getState ( 'version', null );
	}

	public function setAction($action) {
		$this->setState ( 'action', $action );
		$app = JFactory::getApplication ();
		$app->setUserState ( 'com_kunena.install.action', $action );
	}

	public function setStep($step) {
		$this->setState ( 'step', ( int ) $step );
		$app = JFactory::getApplication ();
		$app->setUserState ( 'com_kunena.install.step', ( int ) $step );
		$this->setTask(0);
	}

	public function setTask($task) {
		$this->setState ( 'task', ( int ) $task );
		$app = JFactory::getApplication ();
		$app->setUserState ( 'com_kunena.install.task', ( int ) $task );
	}

	public function setVersion($version) {
		$this->setState ( 'version', $version );
		$app = JFactory::getApplication ();
		$app->setUserState ( 'com_kunena.install.version', $version );
	}

	public function addStatus($task, $result = false, $msg = '', $id = null) {
		$status = $this->getState ( 'status' );
		$step = $this->getStep();
		if ($id === null) {
			$status [] = array ('step' => $step, 'task'=>$task, 'success' => $result, 'msg' => $msg );
		} else {
			unset($status [$id]);
			$status [$id] = array ('step' => $step, 'task'=>$task, 'success' => $result, 'msg' => $msg );
		}
		$this->setState ( 'status', $status );
		$app = JFactory::getApplication ();
		$app->setUserState ( 'com_kunena.install.status', $status );
	}

	function getInstallError() {
		$status = $this->getState ( 'status', array () );
		$error = 0;
		foreach ( $status as $cur ) {
			$error = ! $cur ['success'];
			if ($error)
				break;
		}
		return $error;
	}

	public function getSteps() {
		return $this->steps;
	}

	public function extract($path, $filename, $dest = null, $silent = false) {
		$success = null;
		if (! $dest)
			$dest = $path;
		$file = "{$path}/{$filename}";

		$text = '';

		if (file_exists ( $file )) {
			$success = true;
			if (!JFolder::exists($dest)) {
				$success = JFolder::create($dest);
			}
			if ($success) $success = JArchive::extract ( $file, $dest );
			if (! $success) {
				$text .= JText::sprintf('COM_KUNENA_INSTALL_EXTRACT_FAILED', $file);

				$text .= $this->_getJoomlaArchiveError($file);
			}
		} else {
			$success = true;
			$text .= JText::sprintf('COM_KUNENA_INSTALL_EXTRACT_MISSING', $file);
		}
		if ($success !== null && !$silent)
			$this->addStatus ( JText::sprintf('COM_KUNENA_INSTALL_EXTRACT_STATUS', $filename), $success, $text );

		return $success;
	}

	function installLanguage($tag, $name = '') {
		$exists = false;
		$success = true;
		$destinations = array(
			'site'=>JPATH_SITE . '/components/com_kunena',
			'admin'=>JPATH_ADMINISTRATOR . '/components/com_kunena'
		);

		foreach ($destinations as $key=>$dest) {
			if ($success != true) continue;
			$installdir = "{$dest}/language/{$tag}";

			// Install language from dest/language/xx-XX
			if (is_dir($installdir)) {
				$exists = $success;
				// Older versions installed language files into main folders
				// Those files need to be removed to bring language up to date!
				jimport('joomla.filesystem.folder');
				$files = JFolder::files($installdir, '\.ini$');
				foreach ($files as $filename) {
					if (file_exists(JPATH_SITE."/language/{$tag}/{$filename}")) JFile::delete(JPATH_SITE."/language/{$tag}/{$filename}");
					if (file_exists(JPATH_ADMINISTRATOR."/language/{$tag}/{$filename}")) JFile::delete(JPATH_ADMINISTRATOR."/language/{$tag}/{$filename}");
				}
			}
		}
		if ($exists && $name) $this->addStatus ( JText::sprintf('COM_KUNENA_INSTALL_LANGUAGE', $name), $success);
		return $success;
	}

	function loadPlugin($group, $element) {
		if (version_compare(JVERSION, '1.6','>')) {
			// Joomla 1.6+
			$query = $this->db->getQuery(true);
			$query->select($query->qn('extension_id'))->from($query->qn('#__extensions'));
			$query->where($query->qn('folder') . ' = ' . $query->q($group));
			$query->where($query->qn('element') . ' = ' . $query->q($element));
			$plugin = JTable::getInstance('extension');
		} else {
			// Joomla 1.5
			$query = "SELECT id FROM #__plugins WHERE folder='{$group}' AND element='{$element}'";
			$plugin = JTable::getInstance('plugin');
		}
		$this->db->setQuery($query);
		$id = $this->db->loadResult();
		if ($this->db->getErrorNum ())
			throw new KunenaInstallerException ( $this->db->getErrorMsg (), $this->db->getErrorNum () );
		$plugin->load($id);
		return $plugin;
	}


	function installModule($path, $name) {
		$success = false;

		$dest = JPATH_ROOT."/tmp/kinstall_mod_{$name}";
		if (file_exists($dest)) JFolder::delete($dest);
			if (is_dir(KPATH_ADMIN .'/'. $path)) {
			// Copy path
			$success = JFolder::copy(KPATH_ADMIN .'/'. $path, $dest);
		} elseif (is_file(KPATH_ADMIN .'/'. $path)) {
			// Extract file
			$success = $this->extract ( KPATH_ADMIN, $path, $dest );
		}

		// We need to have only one manifest which is named as kunena.xml
		if (version_compare(JVERSION, '1.6','>')) {
			// Joomla 2.5+
			if ($success && is_file("{$dest}/mod_{$name}.j15.xml")) {
				$success = JFile::delete("{$dest}/mod_{$name}.j15.xml");
			}
			if ($success && is_file("{$dest}/mod_{$name}.j25.xml")) {
				$success = JFile::move("{$dest}/mod_{$name}.j25.xml", "{$dest}/mod_{$name}.xml");
			}
		} else {
			// Joomla 1.5
			if ($success && is_file("{$dest}/mod_{$name}.j25.xml")) {
				$success = JFile::delete("{$dest}/mod_{$name}.j25.xml");
			}
			if ($success && is_file("{$dest}/mod_{$name}.j15.xml")) {
				$success = JFile::move("{$dest}/mod_{$name}.j15.xml", "{$dest}/mod_{$name}.xml");
			}
		}

		if ($success) $success = JFolder::create($dest.'/language/en-GB');
		if ($success) $success = JFile::copy(KPATH_SITE."/language/index.html", "{$dest}/language/en-GB/index.html");
		if ($success && is_file(KPATH_SITE."/language/en-GB/en-GB.mod_{$name}.ini")) {
			$success = JFile::copy(KPATH_SITE."/language/en-GB/en-GB.mod_{$name}.ini", "{$dest}/language/en-GB/en-GB.mod_{$name}.ini");
		}
		if ($success && is_file(KPATH_SITE."/language/en-GB/en-GB.mod_{$name}.sys.ini")) {
			$success = JFile::copy(KPATH_SITE."/language/en-GB/en-GB.mod_{$name}.sys.ini", "{$dest}/language/en-GB/en-GB.mod_{$name}.sys.ini");
		}

		// Only install module if it can be used in current Joomla version (manifest exists)
		if ($success && is_file("{$dest}/mod_{$name}.xml")) {
			$installer = new JInstaller ( );
			$success = $installer->install ( $dest );
			$this->addStatus ( JText::sprintf('COM_KUNENA_INSTALL_MODULE_STATUS', ucfirst($name)), $success);
		} elseif (!$success) {
			$this->addStatus ( JText::sprintf('COM_KUNENA_INSTALL_MODULE_STATUS', ucfirst($name)), $success);
		}
		JFolder::delete($dest);
		return $success;
	}

	function installPlugin($path, $group, $name, $publish, $ordering=0) {
		$success = false;

		$dest = JPATH_ROOT."/tmp/kinstall_plg_{$group}_{$name}";
		if (file_exists($dest)) JFolder::delete($dest);
		if (is_dir(KPATH_ADMIN .'/'. $path)) {
			// Copy path
			$success = JFolder::copy(KPATH_ADMIN .'/'. $path, $dest);
		} elseif (is_file(KPATH_ADMIN .'/'. $path)) {
			// Extract file
			$success = $this->extract ( KPATH_ADMIN, $path, $dest );
		}

		// We need to have only one manifest which is named as xxx.xml
		if (version_compare(JVERSION, '1.6','>')) {
			// Joomla 1.6+
			if ($success && is_file("{$dest}/{$name}.j15.xml")) {
				$success = JFile::delete("{$dest}/{$name}.j15.xml");
			}
			if ($success && is_file("{$dest}/{$name}.j25.xml")) {
				$success = JFile::move("{$dest}/{$name}.j25.xml", "{$dest}/{$name}.xml");
			}
		} else {
			// Joomla 1.5
			if ($success && is_file("{$dest}/{$name}.j25.xml")) {
				$success = JFile::delete("{$dest}/{$name}.j25.xml");
			}
			if ($success && is_file("{$dest}/{$name}.j15.xml")) {
				$success = JFile::move("{$dest}/{$name}.j15.xml", "{$dest}/{$name}.xml");
			}
		}

		if ($success) $success = JFolder::create($dest.'/language/en-GB');
		if ($success) $success = JFile::copy(KPATH_ADMIN."/language/index.html", "{$dest}/language/en-GB/index.html");
		if ($success && is_file(KPATH_ADMIN."/language/en-GB/en-GB.plg_{$group}_{$name}.ini")) {
			$success = JFile::copy(KPATH_ADMIN."/language/en-GB/en-GB.plg_{$group}_{$name}.ini", "{$dest}/language/en-GB/en-GB.plg_{$group}_{$name}.ini");
		}
		if ($success && is_file(KPATH_ADMIN."/language/en-GB/en-GB.plg_{$group}_{$name}.sys.ini")) {
			$success = JFile::copy(KPATH_ADMIN."/language/en-GB/en-GB.plg_{$group}_{$name}.sys.ini", "{$dest}/language/en-GB/en-GB.plg_{$group}_{$name}.sys.ini");
		}

		// Only install plugin if it can be used in current Joomla version (manifest exists)
		if ($success && is_file("{$dest}/{$name}.xml")) {
			$installer = new JInstaller ( );
			$success = $installer->install ( $dest );
			if ($success) {
				// First change plugin ordering
				$plugin = $this->loadPlugin($group, $name);
				if ($ordering && !$plugin->ordering) {
					$plugin->ordering = $ordering;
				}
				if ($publish) {
					if (version_compare(JVERSION, '1.6','>')) {
						// Joomla 1.6+
						$plugin->enabled = $publish;
					} else {
						// Joomla 1.5
						$plugin->published = $publish;
					}
				}
				$success = $plugin->store();
			}
			$this->addStatus ( JText::sprintf('COM_KUNENA_INSTALL_PLUGIN_STATUS', ucfirst($group).' - '.ucfirst($name)), $success);
		} elseif (!$success) {
			$this->addStatus ( JText::sprintf('COM_KUNENA_INSTALL_PLUGIN_STATUS', ucfirst($group).' - '.ucfirst($name)), $success);
		}
		JFolder::delete($dest);
		return $success;
	}

	function uninstallModule($name) {
		if (version_compare(JVERSION, '1.6','>')) {
			// Joomla 1.6+
			$query = "SELECT extension_id FROM #__extensions WHERE type='module' AND element='{$name}'";
		} else {
			// Joomla 1.5
			$query = "SELECT id FROM #__modules WHERE module='{$name}'";
		}
		$this->db->setQuery ( $query );
		$moduleid = $this->db->loadResult ();
		if ($moduleid) {
			$installer = new JInstaller ( );
			$installer->uninstall ( 'module', $moduleid );
		}
	}

	function uninstallPlugin($folder, $name) {
		if (version_compare(JVERSION, '1.6','>')) {
			// Joomla 1.6+
			$query = "SELECT extension_id FROM #__extensions WHERE type='plugin' AND folder='{$folder}' AND element='{$name}'";
		} else {
			// Joomla 1.5
			$query = "SELECT id FROM #__plugins WHERE folder='{$folder}' AND element='{$name}'";
		}
		$this->db->setQuery ( $query );
		$pluginid = $this->db->loadResult ();
		if ($pluginid) {
			$installer = new JInstaller ( );
			$installer->uninstall ( 'plugin', $pluginid );
		}
	}

	public function deleteFiles($path, $ignore=array()) {
		$ignore = array_merge($ignore, array('.git', '.svn', 'CVS','.DS_Store','__MACOSX'));
		if ( JFolder::exists($path) ) foreach (JFolder::files($path, '.', false, true, $ignore) as $file) {
			if ( JFile::exists($file) ) {
				JFile::delete($file);
			}
		}
	}

	public function deleteFolders($path, $ignore=array()) {
		$ignore = array_merge($ignore, array('.git', '.svn', 'CVS','.DS_Store','__MACOSX'));
		if ( JFolder::exists($path) ) foreach (JFolder::folders($path, '.', false, true, $ignore) as $folder) {
			if ( JFolder::exists($folder) ) {
				JFolder::delete($folder);
			}
		}
	}

	public function deleteFolder($path, $ignore=array()) {
		$this->deleteFiles($path, $ignore);
		$this->deleteFolders($path, $ignore);
	}

	public function stepPrepare() {
		$results = array ();

		$this->setVersion(null);
		$this->setAvatarStatus();
		$this->setAttachmentStatus();
		$this->addStatus ( JText::_('COM_KUNENA_INSTALL_STEP_PREPARE'), true );

		$action = $this->getAction();
		if ($action == 'install' || $action == 'migrate') {
			// Let's start from clean database
			$this->deleteTables('kunena_');
			$this->deleteMenu();
		}
		$installed = $this->getDetectVersions();
		if ($action == 'migrate' && $installed['fb']->component) {
			$version = $installed['fb'];
			$results [] = $this->migrateTable ( $version->prefix, $version->prefix . 'version', 'kunena_version' );
		} else {
			$version = $installed['kunena'];
		}
		$this->setVersion($version);

		require_once KPATH_ADMIN.'/install/schema.php';
		$schema = new KunenaModelSchema();
		$results[] = $schema->updateSchemaTable('kunena_version');

		// Insert data from the old version, if it does not exist in the version table
		if ($version->id == 0 && $version->component) {
			$this->insertVersionData ( $version->version, $version->versiondate, $version->versionname, null );
		}

/*
		foreach ( $results as $result )
			if (!empty($result['action']) && empty($result['success']))
				$this->addStatus ( JText::_('COM_KUNENA_INSTALL_'.strtoupper($result['action'])) . ' ' . $result ['name'], $result ['success'] );
*/
		$this->insertVersion ( 'migrateDatabase' );
		if (! $this->getInstallError ())
			$this->setStep ( $this->getStep()+1 );
		$this->checkTimeout(true);
	}

	public function stepExtract() {
		$path = JPATH_ADMINISTRATOR . '/components/com_kunena/archive';
		if (KunenaForum::isDev() || !is_file("{$path}/fileformat")) {
			// Git install
			$dir = JPATH_ADMINISTRATOR.'/components/com_kunena/media/kunena';
			if (is_dir($dir)) {
				JFolder::copy($dir, KPATH_MEDIA, false, true);
			}
			$this->setStep($this->getStep()+1);
			return;
		}
		$ext = file_get_contents("{$path}/fileformat");
		static $files = array(
			array('name'=>'com_kunena-admin', 'dest'=>KPATH_ADMIN),
			array('name'=>'com_kunena-site', 'dest'=>KPATH_SITE),
			array('name'=>'com_kunena-media', 'dest'=>KPATH_MEDIA)
		);
		static $ignore = array(
			KPATH_ADMIN => array('index.html', 'kunena.xml', 'kunena.j25.xml', 'admin.kunena.php', 'api.php', 'archive', 'install', 'language'),
			KPATH_SITE => array('index.html', 'kunena.php', 'router.php', 'COPYRIGHT.php', 'template', 'language')
		);
		$task = $this->getTask();

		// Extract archive files
		if (isset($files[$task])) {
			$file = $files[$task];
			if (file_exists ( "{$path}/{$file['name']}{$ext}" )) {
				$dest = $file['dest'];
				if (!empty($ignore[$dest])) {
					// Delete all files and folders (cleanup)
					$this->deleteFolder($dest, $ignore[$dest]);
					if ($dest == KPATH_SITE) {
						$this->deleteFolder("$dest/template/blue_eagle", array('params.ini'));
						$this->deleteFolder("$dest/template/mirage", array('params.ini'));
					}
				}
				// Copy new files into folder
				$this->extract ( $path, $file['name'] . $ext, $dest, KunenaForum::isDev() );
			}
			$this->setTask($task+1);
		} else {
			if (function_exists('apc_clear_cache')) apc_clear_cache('system');

			// Force page reload to avoid MySQL timeouts after extracting
			$this->checkTimeout(true);
			if (! $this->getInstallError ())
				$this->setStep($this->getStep()+1);
		}
	}

	public function stepPlugins() {
		$this->installPlugin('install/plugins/plg_system_kunena', 'system', 'kunena', true);
		$this->installPlugin('install/plugins/plg_quickicon_kunena', 'quickicon', 'kunena', true);
		// TODO: Complete smart search support
		$this->uninstallPlugin('finder', 'kunena');
		//$this->installPlugin('install/plugins/plg_finder_kunena', 'finder', 'kunena', false, 1);
		$this->installPlugin('install/plugins/plg_kunena_alphauserpoints', 'kunena', 'alphauserpoints', false, 1);
		$this->installPlugin('install/plugins/plg_kunena_community', 'kunena', 'community', false, 2);
		$this->installPlugin('install/plugins/plg_kunena_comprofiler', 'kunena', 'comprofiler', false, 3);
		$this->installPlugin('install/plugins/plg_kunena_gravatar', 'kunena', 'gravatar', false, 4);
		$this->installPlugin('install/plugins/plg_kunena_uddeim', 'kunena', 'uddeim', false, 5);
		$this->installPlugin('install/plugins/plg_kunena_kunena', 'kunena', 'kunena', true, 6);
		$this->installPlugin('install/plugins/plg_kunena_joomla15', 'kunena', 'joomla', true, 7);
		$this->installPlugin('install/plugins/plg_kunena_joomla25', 'kunena', 'joomla', true, 7);

		// TODO: install also menu module
		$this->uninstallModule('mod_kunenamenu');
		//$this->installModule('install/modules/mod_kunenamenu', 'kunenamenu');

		if (function_exists('apc_clear_cache')) apc_clear_cache('system');

		if (! $this->getInstallError ())
			$this->setStep ( $this->getStep()+1 );
	}

	public function stepDatabase() {
		$task = $this->getTask();
		switch ($task) {
			case 0:
				if ($this->migrateDatabase ())
					$this->setTask($task+1);
				break;
			case 1:
				if ($this->installDatabase ())
					$this->setTask($task+1);
				break;
			case 2:
				if ($this->upgradeDatabase ())
					$this->setTask($task+1);
				break;
			case 3:
				if ($this->installSampleData ())
					$this->setTask($task+1);
				break;
			case 4:
				if ($this->migrateCategoryImages ())
					$this->setTask($task+1);
				break;
			case 5:
				if ($this->migrateAvatars ())
					$this->setTask($task+1);
				break;
			case 6:
				if ($this->migrateAvatarGalleries ())
					$this->setTask($task+1);
				break;
			case 7:
				if ($this->migrateAttachments ())
					$this->setTask($task+1);
				break;
			case 8:
				if ($this->recountCategories ())
					$this->setTask($task+1);
				break;
			case 9:
				if ($this->recountThankyou ())
					$this->setTask($task+1);
				break;
			default:
				if (! $this->getInstallError ())
					$this->setStep ( $this->getStep()+1 );
		}
	}

	public function stepFinish() {
		KunenaForum::setup();

		$entryfiles = array(
			array(KPATH_ADMIN, 'api', 'php'),
			array(KPATH_ADMIN, 'admin.kunena', 'php'),
			array(KPATH_SITE, 'router', 'php'),
			array(KPATH_SITE, 'kunena', 'php'),
		);

		$lang = JFactory::getLanguage();
		$lang->load('com_kunena', JPATH_SITE) || $lang->load('com_kunena', KPATH_SITE);

		$this->createMenu(false);

		// Fix broken category aliases (workaround for < 2.0-DEV12 bug)
		$count = KunenaForumCategoryHelper::fixAliases();

		$md5 = md5_file(KPATH_ADMIN.'/api.new.php');
		foreach ($entryfiles as $fileparts) {
			list($path, $filename, $ext) = $fileparts;
			if (is_file("{$path}/{$filename}.new.{$ext}")) {
				$success = JFile::delete("{$path}/{$filename}.{$ext}");
				if (!$success) $this->addStatus ( JText::_('COM_KUNENA_INSTALL_DELETE_STATUS_FAIL')." {$filename}.{$ext}", false, '' );
				$success = JFile::move("{$path}/{$filename}.new.{$ext}", "{$path}/{$filename}.{$ext}");
				if (!$success) $this->addStatus ( JText::_('COM_KUNENA_INSTALL_RENAMING_FAIL')." {$filename}.new.{$ext}", false, '' );
			}
		}

		// Clean cache, just in case
		KunenaMenuHelper::cleanCache();
		JFactory::getCache('com_kunena')->clean();

		// Test if api file has been fully copied
		$this->waitFile(KPATH_ADMIN."/api.php", $md5);

		if (! $this->getInstallError ()) {
			$this->updateVersionState ( '' );
			$this->addStatus ( JText::_('COM_KUNENA_INSTALL_SUCCESS'), true, '' );

			$this->setStep ( $this->getStep()+1 );
		}
	}

	protected function waitFile($file, $md5) {
		// Test if file has been fully copied and wait if not
		for ($i=0; $i<10; $i++) {
			if (is_file($file) && md5_file($file) == $md5) return true;
			sleep(1);
			clearstatcache();
		}
		return false;
	}

	public function migrateDatabase() {
		$version = $this->getVersion();
		if (! empty ( $version->prefix )) {
			// Migrate all tables from old installation

			$app = JFactory::getApplication ();
			$state = $app->getUserState ( 'com_kunena.install.dbstate', null );

			// First run: find tables that potentially need migration
			if ($state === null) {
				$state = $this->listTables ( $version->prefix );
			}

			// Handle only first table in the list
			$oldtable = array_shift($state);
			if ($oldtable) {
				$newtable = preg_replace ( '/^' . $version->prefix . '/i', 'kunena_', $oldtable );
				$result = $this->migrateTable ( $version->prefix, $oldtable, $newtable );
				if ($result) {
					$this->addStatus ( ucfirst($result ['action']) . ' ' . $result ['name'], true );
				}
				// Save user state with remaining tables
				$app->setUserState ( 'com_kunena.install.dbstate', $state );

				// Database migration continues
				return false;
			} else {
				// Reset user state
				$this->updateVersionState ( 'installDatabase' );
				$app->setUserState ( 'com_kunena.install.dbstate', null );
			}
		}
		// Database migration complete
		return true;
	}

	public function installDatabase() {
		static $schema = null;
		static $create = null;
		static $tables = null;
		if ($schema === null) {
			// Run only once: get table creation SQL and existing tables
			require_once KPATH_ADMIN.'/install/schema.php';
			$schema = new KunenaModelSchema();
			$create = $schema->getCreateSQL();
			$tables = $this->listTables ( 'kunena_', true );
		}

		$app = JFactory::getApplication ();
		$state = $app->getUserState ( 'com_kunena.install.dbstate', null );

		// First run: get all tables
		if ($state === null) {
			$state = array_keys($create);
		}

		// Handle only first table in the list
		$table = array_shift($state);
		if ($table) {
			$query = $create[$table];
			if (!isset($tables[$table])) {
				$result = $schema->updateSchemaTable($table);
				if ($result) {
					$this->addStatus ( JText::_('COM_KUNENA_INSTALL_CREATE') . ' ' . $result ['name'], $result ['success'] );
				}
			}
			// Save user state with remaining tables
			$app->setUserState ( 'com_kunena.install.dbstate', $state );

			// Database install continues
			return false;
		} else {
			// Reset user state
			$this->updateVersionState ( 'upgradeDatabase' );
			$app->setUserState ( 'com_kunena.install.dbstate', null );
		}
		// Database install complete
		return true;
	}

	function migrateConfig() {
		$config = KunenaFactory::getConfig();
		$version = $this->getVersion();
		// Migrate configuration from FB < 1.0.5
		if (version_compare ( $version->version, '1.0.4', "<=" ) ) {
			$file = JPATH_ADMINISTRATOR . '/components/com_fireboard/fireboard_config.php';
			if (is_file($file)) {
				require_once $file;
				$fbConfig = (array)$fbConfig;
				$config->bind($fbConfig);
				$config->id = 1;
			}
		}
		// Migrate configuration from FB 1.0.5 and Kunena 1.0-1.7
		if (!$config->id && !empty($version->prefix)) {
			$tables = $this->listTables ( $version->prefix );
			$cfgtable = "{$version->prefix}config";
			if (isset($tables[$cfgtable])) {
				$this->db->setQuery ( "SELECT * FROM #__{$cfgtable}" );
				$config->bind((array) $this->db->loadAssoc ());
				$config->id = 1;
			}
		}
		$config->save ();
	}

	public function upgradeDatabase() {
		static $xml = null;

		// If there's no version installed, there's nothing to do
		$curversion = $this->getVersion();
		if (!$curversion->component) return true;

		if ($xml === null) {
			// Run only once: Get migration SQL from our XML file
			$xml = simplexml_load_file(KPATH_ADMIN.'/install/kunena.install.upgrade.xml');
		}
		if ($xml === false) $this->addStatus ( JText::_('COM_KUNENA_INSTALL_DB_UPGRADE_FAILED_XML'), false, '', 'upgrade' );

		$app = JFactory::getApplication ();
		$state = $app->getUserState ( 'com_kunena.install.dbstate', null );

		// First run: initialize state and migrate configuration
		if ($state === null) {
			$state = array();

			// Migrate configuration from FB <1.0.5, otherwise update it
			$this->migrateConfig();
		}

		// Allow queries to fail
		if (version_compare(JVERSION, '1.7', '>')) {
			// Joomla 1.7+
			$this->db->setDebug(false);
		} else {
			// Joomla 1.5 and 1.6
			$this->db->debug(0);
		}

		$results = array();
		foreach ($xml->upgrade[0] as $version) {
			// If we have already upgraded to this version, continue to the next one
			$vernum = (string) $version['version'];
			if (!empty($state[$vernum]))
				continue;

			// Update state
			$state[$vernum] = 1;

			if ($version['version'] == '@'.'kunenaversion'.'@') {
				$git = 1;
				$vernum = KunenaForum::version();
			}
			if(isset($git) || version_compare(strtolower($version['version']), strtolower($curversion->version), '>')) {
				foreach ($version as $action) {
					$result = $this->processUpgradeXMLNode($action);
					if ($result) $this->addStatus ( $result ['action'] . ' ' . $result ['name'], $result ['success'] );
				}

				$this->addStatus ( JText::sprintf('COM_KUNENA_INSTALL_VERSION_UPGRADED',$vernum), true, '', 'upgrade' );

				// Save user state with remaining tables
				$app->setUserState ( 'com_kunena.install.dbstate', $state );

				// Database install continues
				return false;
			}
		}
		// Reset user state
		$this->updateVersionState ( 'InstallSampleData' );
		$app->setUserState ( 'com_kunena.install.dbstate', null );

		// Database install complete
		return true;
	}

	function processUpgradeXMLNode($action)
	{
		$result = null;
		$nodeName = $action->getName();
		$mode = strtolower((string) $action['mode']);
		$success = false;
		switch($nodeName) {
			case 'phpfile':
				$filename = $action['name'];
				$include = KPATH_ADMIN . "/install/upgrade/$filename.php";
				$function = 'kunena_'.strtr($filename, array('.'=>'', '-'=>'_'));
				if(file_exists($include)) {
					require( $include );
					if (is_callable($function)) {
						$result = call_user_func($function, $this);
						if (is_array($result)) $success = $result['success'];
						else $success = true;
					}
				}
				if (!$success && !$result) {
					$result = array('action'=>JText::_('COM_KUNENA_INSTALL_INCLUDE_STATUS'), 'name'=>$filename.'.php', 'success'=>$success);
				}
				break;
			case 'query':
				$query = (string)$action;
				$this->db->setQuery($query);
				$this->db->query();
				if (!$this->db->getErrorNum()) {
					$success = true;
				}
				if ($action['mode'] == 'silenterror' || !$this->db->getAffectedRows() || $success)
					$result = null;
				else
					$result = array('action'=>'SQL Query: '.$query, 'name'=>'', 'success'=>$success);
				break;
			default:
				$result = array('action'=>'fail', 'name'=>$nodeName, 'success'=>false);
		}
		return $result;
	}
/*
	public function upgradeDatabase() {
		$schema = new KunenaModelSchema ();
		$results = $schema->updateSchema ();
		foreach ( $results as $i => $r )
			if ($r)
				$this->addStatus ( $r ['action'] . ' ' . $r ['name'], true );
		$this->updateVersionState ( 'installSampleData' );
	}
*/

	public function installSampleData() {
		require_once ( KPATH_ADMIN.'/install/data/sampledata.php' );
		if (installSampleData ())
			$this->addStatus ( JText::_('COM_KUNENA_INSTALL_SAMPLEDATA'), true );
		return true;
	}

	protected function setAvatarStatus($stats = null) {
		if (!$stats) {
			$stats = new stdClass();
			$stats->current = $stats->migrated = $stats->failed = $stats->missing = 0;
		}
		$app = JFactory::getApplication ();
		$app->setUserState ( 'com_kunena.install.avatars', $stats );
	}

	protected function getAvatarStatus() {
		$app = JFactory::getApplication ();
		$stats = new stdClass();
		$stats->current = $stats->migrated = $stats->failed = $stats->missing = 0;
		$stats = $app->getUserState ( 'com_kunena.install.avatars', $stats );
		return $stats;
	}

	public function migrateAvatars() {
		$app = JFactory::getApplication ();
		$stats = $this->getAvatarStatus();

		static $dirs = array (
			'media/kunena/avatars',
			'images/fbfiles/avatars',
			'components/com_fireboard/avatars'
		);

		$query = "SELECT COUNT(*) FROM #__kunena_users
			WHERE userid>{$this->db->quote($stats->current)} AND avatar != '' AND avatar NOT LIKE 'gallery/%' AND avatar NOT LIKE 'users/%'";
		$this->db->setQuery ( $query );
		$count = $this->db->loadResult ();
		if ($this->db->getErrorNum ())
			throw new KunenaInstallerException ( $this->db->getErrorMsg (), $this->db->getErrorNum () );
		if (!$stats->current && !$count) return true;

		$query = "SELECT userid, avatar FROM #__kunena_users
			WHERE userid>{$this->db->quote($stats->current)} AND avatar != '' AND avatar NOT LIKE 'gallery/%' AND avatar NOT LIKE 'users/%'";
		$this->db->setQuery ( $query, 0, 1023 );
		$users = $this->db->loadObjectList ();
		if ($this->db->getErrorNum ())
			throw new KunenaInstallerException ( $this->db->getErrorMsg (), $this->db->getErrorNum () );
		foreach ($users as $user) {
			$userid = $stats->current = $user->userid;
			$avatar = $user->avatar;
			$count--;

			$file = $newfile = '';
			foreach ($dirs as $dir) {
				if (!JFile::exists(JPATH_ROOT . "/$dir/$avatar")) continue;
				$file = JPATH_ROOT . "/$dir/$avatar";
				break;
			}
			$success = false;
			if ($file) {
				$file = JPath::clean($file, '/');
				// Make sure to copy only supported fileformats
				$match = preg_match('/\.(gif|jpg|jpeg|png)$/ui', $file, $matches);
				if ($match) {
					$ext = JString::strtolower($matches[1]);
					// Use new format: users/avatar62.jpg
					$newfile = "users/avatar{$userid}.{$ext}";
					$destpath = (KPATH_MEDIA ."/avatars/{$newfile}");
					if (JFile::exists($destpath)) {
						$success = true;
					} else {
						@chmod($file, 0644);
						$success = JFile::copy($file, $destpath);
					}
					if ($success) {
						$stats->migrated++;
					} else {
						$this->addStatus ( "User: {$userid}, Avatar copy failed: {$file} to {$destpath}", true );
						$stats->failed++;
					}
				} else {
					$this->addStatus ( "User: {$userid}, Avatar type not supported: {$file}", true );
					$stats->failed++;
					$success = true;
				}
			} else {
				// $this->addStatus ( "User: {$userid}, Avatar file was not found: {$avatar}", true );
				$stats->missing++;
				$success = true;
			}
			if ($success) {
				$query = "UPDATE #__kunena_users SET avatar={$this->db->quote($newfile)} WHERE userid={$this->db->quote($userid)}";
				$this->db->setQuery ( $query );
				$this->db->query ();
				if ($this->db->getErrorNum ())
					throw new KunenaInstallerException ( $this->db->getErrorMsg (), $this->db->getErrorNum () );
			}
			if ($this->checkTimeout()) break;
		}
		$this->setAvatarStatus($stats);
		if ($count) {
			$this->addStatus ( JText::sprintf('COM_KUNENA_MIGRATE_AVATARS',$count), true, '', 'avatar' );
		} else {
			$this->addStatus ( JText::sprintf('COM_KUNENA_MIGRATE_AVATARS_DONE', $stats->migrated, $stats->missing, $stats->failed), true, '', 'avatar' );
		}
		return !$count;
	}

	public function migrateAvatarGalleries() {
		$action = $this->getAction();
		if ($action != 'migrate') return true;

		$srcpath = JPATH_ROOT.'/images/fbfiles/avatars/gallery';
		$dstpath = KPATH_MEDIA.'/avatars/gallery';
		if (JFolder::exists($srcpath)) {
			if (!JFolder::delete($dstpath) || !JFolder::copy($srcpath, $dstpath)) {
				$this->addStatus ( "Could not copy avatar galleries from $srcpath to $dstpath", true );
			} else {
				$this->addStatus ( JText::_('COM_KUNENA_MIGRATE_AVATAR_GALLERY'), true );
			}
		}
		return true;
	}

	public function migrateCategoryImages() {
		$action = $this->getAction();
		if ($action != 'migrate') return true;

		$srcpath = JPATH_ROOT.'/images/fbfiles/category_images';
		$dstpath = KPATH_MEDIA.'/category_images';
		if (JFolder::exists($srcpath)) {
			if (!JFolder::delete($dstpath) || !JFolder::copy($srcpath, $dstpath)) {
				$this->addStatus ( "Could not copy category images from $srcpath to $dstpath", true );
			} else {
				$this->addStatus ( JText::_('COM_KUNENA_MIGRATE_CATEGORY_IMAGES'), true );
			}
		}
		return true;
	}

	protected function setAttachmentStatus($stats = null) {
		if (!$stats) {
			$stats = new stdClass();
			$stats->current = $stats->migrated = $stats->failed = $stats->missing = 0;
		}
		$app = JFactory::getApplication ();
		$app->setUserState ( 'com_kunena.install.attachments', $stats );
	}

	protected function getAttachmentStatus() {
		$app = JFactory::getApplication ();
		$stats = new stdClass();
		$stats->current = $stats->migrated = $stats->failed = $stats->missing = 0;
		$stats = $app->getUserState ( 'com_kunena.install.attachments', $stats );
		return $stats;
	}

	public function migrateAttachments() {
		// Only perform this stage if we are upgrading from older version
		$version = $this->getVersion();
		if (version_compare ( $version->version, '1.7.0', ">" )) {
			return true;
		}

		$app = JFactory::getApplication ();
		$stats = $this->getAttachmentStatus();

		static $dirs = array (
			'images/fbfiles/attachments',
			'components/com_fireboard/uploaded',
			'media/kunena/attachments/legacy',
			);

		$query = "SELECT COUNT(*) FROM #__kunena_attachments
			WHERE id>{$this->db->quote($stats->current)} AND hash IS NULL";
		$this->db->setQuery ( $query );
		$count = $this->db->loadResult ();
		if ($this->db->getErrorNum ())
			throw new KunenaInstallerException ( $this->db->getErrorMsg (), $this->db->getErrorNum () );
		if (!$stats->current && !$count) return true;

		$destpath = KPATH_MEDIA . '/attachments/legacy';
		if (!JFolder::exists($destpath.'/images')) {
			if (!JFolder::create($destpath.'/images')) {
				$this->addStatus ( "Could not create directory for legacy attachments in {$destpath}/images", true );
				return true;
			}
		}
		if (!JFolder::exists($destpath.'/files')) {
			if (!JFolder::create($destpath.'/files')) {
				$this->addStatus ( "Could not create directory for legacy attachments in {$destpath}/files", true );
				return true;
			}
		}

		$query = "SELECT * FROM #__kunena_attachments
			WHERE id>{$this->db->quote($stats->current)} AND hash IS NULL";
		$this->db->setQuery ( $query, 0, 251 );
		$attachments = $this->db->loadObjectList ();
		if ($this->db->getErrorNum ())
			throw new KunenaInstallerException ( $this->db->getErrorMsg (), $this->db->getErrorNum () );

		foreach ($attachments as $attachment) {
			$stats->current = $attachment->id;
			$count--;

			if (preg_match('|/images$|', $attachment->folder)) {
				$lastpath = 'images';
				$attachment->filetype = 'image/'.strtolower($attachment->filetype);
			} else if (preg_match('|/files$|', $attachment->folder)) {
				$lastpath = 'files';
			} else {
				// Only process files in legacy locations, either in original folders or manually copied into /media/kunena/attachments/legacy
				continue;
			}

			$file = '';
			if (JFile::exists(JPATH_ROOT . "/{$attachment->folder}/{$attachment->filename}")) {
				$file = JPATH_ROOT . "/{$attachment->folder}/{$attachment->filename}";
			} else {
				foreach ($dirs as $dir) {
					if (JFile::exists(JPATH_ROOT . "/{$dir}/{$lastpath}/{$attachment->filename}")) {
						$file = JPATH_ROOT . "/{$dir}/{$lastpath}/{$attachment->filename}";
						break;
					}
				}
			}
			$success = false;
			if ($file) {
				$file = JPath::clean ( $file, '/' );
				$destfile = "{$destpath}/{$lastpath}/{$attachment->filename}";
				if (JFile::exists ( $destfile )) {
					$success = true;
				} else {
					@chmod ( $file, 0644 );
					$success = JFile::copy ( $file, $destfile );
				}
				if ($success) {
					$stats->migrated ++;
				} else {
					$this->addStatus ( "Attachment copy failed: {$file} to {$destfile}", true );
					$stats->failed ++;
				}
			} else {
				// $this->addStatus ( "Attachment file was not found: {$file}", true );
				$stats->missing++;
			}
			if ($success) {
				clearstatcache();
				$stat = stat($destfile);
				$size = (int)$stat['size'];
				$hash = md5_file ( $destfile );
				$query = "UPDATE #__kunena_attachments SET folder='media/kunena/attachments/legacy/{$lastpath}', size={$this->db->quote($size)}, hash={$this->db->quote($hash)}, filetype={$this->db->quote($attachment->filetype)}
					WHERE id={$this->db->quote($attachment->id)}";
				$this->db->setQuery ( $query );
				$this->db->query ();
				if ($this->db->getErrorNum ())
					throw new KunenaInstallerException ( $this->db->getErrorMsg (), $this->db->getErrorNum () );
			}
			if ($this->checkTimeout()) break;
		}
		$this->setAttachmentStatus($stats);
		if ($count) {
			$this->addStatus ( JText::sprintf('COM_KUNENA_MIGRATE_ATTACHMENTS',$count), true, '', 'attach' );
		} else {
			// Note: com_fireboard has been replaced by com_kunena during 1.0.8 upgrade, use it instead
			$query = "UPDATE #__kunena_messages_text SET message = REPLACE(REPLACE(message, '/images/fbfiles', '/media/kunena/attachments/legacy'), '/components/com_kunena/uploaded', '/media/kunena/attachments/legacy');";
			$this->db->setQuery ( $query );
			$this->db->query ();
			if ($this->db->getErrorNum ())
				throw new KunenaInstallerException ( $this->db->getErrorMsg (), $this->db->getErrorNum () );
			$this->addStatus ( JText::sprintf('COM_KUNENA_MIGRATE_ATTACHMENTS_DONE', $stats->migrated, $stats->missing, $stats->failed), true, '', 'attach' );
		}
		return !$count;
	}

	function recountCategories() {
		$app = JFactory::getApplication ();
		$state = $app->getUserState ( 'com_kunena.install.recount', null );

		// Only perform this stage if database needs recounting (upgrade from older version)
		$version = $this->getVersion();
		if (version_compare ( $version->version, '2.0.0-DEV', ">" )) {
			return true;
		}

		if ($state === null) {
			// First run
			$query = "SELECT MAX(id) FROM #__kunena_messages";
			$this->db->setQuery ( $query );
			$state = new StdClass();
			$state->step = 0;
			$state->maxId = (int) $this->db->loadResult ();
			$state->start = 0;
		}

		while (1) {
			$count = mt_rand(95000, 105000);
			switch ($state->step) {
				case 0:
					// Update topic statistics
					KunenaForumTopicHelper::recount(false, $state->start, $state->start+$count);
					$state->start += $count;
					$this->addStatus ( JText::sprintf('COM_KUNENA_MIGRATE_RECOUNT_TOPICS', min($state->start, $state->maxId), $state->maxId), true, '', 'recount' );
					break;
				case 1:
					// Update usertopic statistics
					KunenaForumTopicUserHelper::recount(false, $state->start, $state->start+$count);
					$state->start += $count;
					$this->addStatus ( JText::sprintf('COM_KUNENA_MIGRATE_RECOUNT_USERTOPICS', min($state->start, $state->maxId), $state->maxId), true, '', 'recount' );
					break;
				case 2:
					// Update user statistics
					KunenaUserHelper::recount();
					$this->addStatus ( JText::sprintf('COM_KUNENA_MIGRATE_RECOUNT_USER'), true, '', 'recount' );
					break;
				case 3:
					// Update category statistics
					KunenaForumCategoryHelper::recount();
					$this->addStatus ( JText::sprintf('COM_KUNENA_MIGRATE_RECOUNT_CATEGORY'), true, '', 'recount' );
					break;
				default:
					$app->setUserState ( 'com_kunena.install.recount', null );
					$this->addStatus ( JText::_('COM_KUNENA_MIGRATE_RECOUNT_DONE'), true, '', 'recount' );
					return true;
			}
			if (!$state->start || $state->start > $state->maxId) {
				$state->step++;
				$state->start = 0;
			}
			if ($this->checkTimeout()) break;
		}
		$app->setUserState ( 'com_kunena.install.recount', $state );
		return false;
	}

	public function getVersionPrefix() {
		if ($this->_versionprefix !== false) {
			return $this->_versionprefix;
		}

		$match = $this->detectTable ( $this->_versiontablearray );
		if (isset ( $match ['prefix'] ))
			$this->_versionprefix = $match ['prefix'];
		else
			$this->_versionprefix = null;

		return $this->_versionprefix;
	}

	public function getDetectVersions() {
		if (!empty($this->_versions)) {
			return $this->_versions;
		}
		$kunena = $this->getInstalledVersion('kunena_', $this->_kVersions);
		$fireboard = $this->getInstalledVersion('fb_', $this->_fbVersions);
		if (!empty($kunena->state)) {
			$this->_versions['failed'] = $kunena;
			$kunena = $this->getInstalledVersion('kunena_', $this->_kVersions, true);
			if (version_compare ( $kunena->version, '1.6.0-ALPHA', "<" ) ) $kunena->ignore = true;
		}
		if ($kunena->component && empty($kunena->ignore)) {
			$this->_versions['kunena'] = $kunena;
			$migrate = false;
		} else {
			$migrate = $this->isMigration($kunena, $fireboard);
		}
		if (!empty($fireboard->component) && $migrate) $this->_versions['fb'] = $fireboard;
		if (empty($kunena->component)) $this->_versions['kunena'] = $kunena;
		else if (!empty($fireboard->component)) {
			$uninstall = clone $fireboard;
			$uninstall->action = 'RESTORE';
			$this->_versions['uninstall'] = $uninstall;
		} else {
			$uninstall = clone $kunena;
			$uninstall->action = 'UNINSTALL';
			$this->_versions['uninstall'] = $uninstall;
		}
		foreach ($this->_versions as $version) {
			$version->label = $this->getActionText($version);
			$version->description = $this->getActionText($version, 'desc');
			$version->hint = $this->getActionText($version, 'hint');
			$version->warning = $this->getActionText($version, 'warn');
			$version->link = JURI::root(true).'/administrator/index.php?option=com_kunena&view=install&task='.strtolower($version->action).'&'.JUtility::getToken() .'=1';
		}
		if ($migrate) {
			$kunena->warning = $this->getActionText($fireboard, 'warn', 'upgrade');
		} else {
			$kunena->warning = '';
		}

		return $this->_versions;
	}

	public function isMigration($new, $old) {
		// If K1.6 not installed: migrate
		if (!$new->component || !$this->detectTable ( $new->prefix . 'messages' )) return true;
		// If old not installed: upgrade
		if (!$old->component || !$this->detectTable ( $old->prefix . 'messages' )) return false;
		// If K1.6 is installed and old is not Kunena: upgrade
		if ($old->component != 'Kunena') return false;
		// User is currently using K1.6: upgrade
		if (strtotime($new->installdate) > strtotime($old->installdate)) return false;
		// User is currently using K1.0/K1.5: migrate
		if (strtotime($new->installdate) < strtotime($old->installdate)) return true;
		// Both K1.5 and K1.6 were installed during the same day.. Not going to be easy choice..

		// Let's assume that this could be migration
		return true;
	}

	public function getInstalledVersion($prefix, $versionlist, $state = false) {
		if (!$state && isset($this->_installed[$prefix])) {
			return $this->_installed[$prefix];
		}

		if ($prefix === null) {
			$versionprefix = $this->getVersionPrefix ();
		} else if ($this->detectTable ( $prefix . 'version') ) {
			$versionprefix = $prefix;
		} else {
			$versionprefix = null;
		}

		if ($versionprefix) {
			// Version table exists, try to get installed version
			$state = $state ? " WHERE state=''" : "";
			$this->db->setQuery ( "SELECT * FROM " . $this->db->nameQuote ( $this->db->getPrefix () . $versionprefix . 'version' ) . $state . " ORDER BY `id` DESC", 0, 1 );
			$version = $this->db->loadObject ();
			if ($this->db->getErrorNum ())
				throw new KunenaInstallerException ( $this->db->getErrorMsg (), $this->db->getErrorNum () );

			if ($version) {
				$version->version = strtolower ( $version->version );
				$version->prefix = $versionprefix;
				if (version_compare ( $version->version, '1.0.5', ">" ))
					$version->component = 'Kunena';
				else
					$version->component = 'FireBoard';
				$version->version = strtoupper ( $version->version );

				// Version table may contain dummy version.. Ignore it
				if (! $version || version_compare ( $version->version, '0.1.0', "<" ))
					unset ( $version );
			}
		}

		if (!isset ( $version )) {
			// No version found -- try to detect version by searching some missing fields
			$match = $this->detectTable ( $versionlist );

			// Clean install
			if (empty ( $match ))
				return $this->_installed = null;

			// Create version object
			$version = new StdClass ();
			$version->id = 0;
			$version->component = $match ['component'];
			$version->version = strtoupper ( $match ['version'] );
			$version->versiondate = $match ['date'];
			$version->installdate = '';
			$version->versionname = '';
			$version->prefix = $match ['prefix'];
		}
		$version->action = $this->getInstallAction($version);
		return $this->_installed[$prefix] = $version;
	}

	protected function insertVersion($state = 'beginInstall') {
		// Insert data from the new version
		$this->insertVersionData ( KunenaForum::version(), KunenaForum::versionDate(), KunenaForum::versionName(), $state );
	}

	protected function updateVersionState($state) {
		// Insert data from the new version
		$this->db->setQuery ( "UPDATE " . $this->db->nameQuote ( $this->db->getPrefix () . 'kunena_version' ) . " SET state = " . $this->db->Quote ( $state ) . " ORDER BY id DESC LIMIT 1" );
		$this->db->query ();
		if ($this->db->getErrorNum ())
			throw new KunenaInstallerException ( $this->db->getErrorMsg (), $this->db->getErrorNum () );
	}

	function getActionText($version, $type='', $action=null) {
		/* Translations generated:

		Installation types: COM_KUNENA_INSTALL_UPGRADE, COM_KUNENA_INSTALL_DOWNGRADE, COM_KUNENA_INSTALL_REINSTALL,
		COM_KUNENA_INSTALL_MIGRATE, COM_KUNENA_INSTALL_INSTALL, COM_KUNENA_INSTALL_UNINSTALL, COM_KUNENA_INSTALL_RESTORE

		Installation descriptions: COM_KUNENA_INSTALL_UPGRADE_DESC, COM_KUNENA_INSTALL_DOWNGRADE_DESC, COM_KUNENA_INSTALL_REINSTALL_DESC,
		COM_KUNENA_INSTALL_MIGRATE_DESC, COM_KUNENA_INSTALL_INSTALL_DESC, COM_KUNENA_INSTALL_UNINSTALL_DESC, COM_KUNENA_INSTALL_RESTORE_DESC

		Installation hints: COM_KUNENA_INSTALL_UPGRADE_HINT, COM_KUNENA_INSTALL_DOWNGRADE_HINT, COM_KUNENA_INSTALL_REINSTALL_HINT,
		COM_KUNENA_INSTALL_MIGRATE_HINT, COM_KUNENA_INSTALL_INSTALL_HINT, COM_KUNENA_INSTALL_UNINSTALL_HINT, COM_KUNENA_INSTALL_RESTORE_HINT

		Installation warnings: COM_KUNENA_INSTALL_UPGRADE_WARN, COM_KUNENA_INSTALL_DOWNGRADE_WARN,
		COM_KUNENA_INSTALL_MIGRATE_WARN, COM_KUNENA_INSTALL_UNINSTALL_WARN, COM_KUNENA_INSTALL_RESTORE_WARN

		 */

		static $search = array ('#COMPONENT_OLD#','#VERSION_OLD#','#VERSION#');
		$replace = array ($version->component, $version->version, KunenaForum::version());
		if (!$action) $action = $version->action;
		if ($type == 'warn' && ($action == 'INSTALL' || $action == 'REINSTALL')) return '';
		$str = '';
		if ($type == 'hint' || $type == 'warn') {
			$str .= '<strong class="k'.$type.'">'.JText::_('COM_KUNENA_INSTALL_'.$type).'</strong> ';
		}
		if ($action && $type) $type = '_'.$type;
		$str .= str_replace($search, $replace, JText::_('COM_KUNENA_INSTALL_'.$action.$type));
		return $str;
	}

	public function getInstallAction($version = null) {
		if ($version->component === null)
			$this->_action = 'INSTALL';
		else if ($version->prefix != 'kunena_')
			$this->_action = 'MIGRATE';
		else if (version_compare ( strtolower(KunenaForum::version()), strtolower($version->version), '>' ))
			$this->_action = 'UPGRADE';
		else if (version_compare ( strtolower(KunenaForum::version()), strtolower($version->version), '<' ))
			$this->_action = 'DOWNGRADE';
		else
			$this->_action = 'REINSTALL';

		return $this->_action;
	}

	protected function detectTable($detectlist) {
		// Cache
		static $tables = array ();
		static $fields = array ();

		$found = 0;
		if (is_string($detectlist)) $detectlist = array(array('table'=>$detectlist));
		foreach ( $detectlist as $detect ) {
			// If no detection is needed, return current item
			if (! isset ( $detect ['table'] ))
				return $detect;

			$table = $this->db->getPrefix () . $detect ['table'];

			// Match if table exists
			if (! isset ( $tables [$table] )) // Not cached
{
				$this->db->setQuery ( "SHOW TABLES LIKE " . $this->db->quote ( $table ) );
				$result = $this->db->loadResult ();
				if ($this->db->getErrorNum ())
					throw new KunenaInstallerException ( $this->db->getErrorMsg (), $this->db->getErrorNum () );
				$tables [$table] = $result;
			}
			if (! empty ( $tables [$table] ))
				$found = 1;

			// Match if column in a table exists
			if ($found && isset ( $detect ['column'] )) {
				if (! isset ( $fields [$table] )) // Not cached
				{
					$this->db->setQuery ( "SHOW COLUMNS FROM " . $this->db->nameQuote ( $table ) );
					$result = $this->db->loadObjectList ( 'Field' );
					if ($this->db->getErrorNum ())
						throw new KunenaInstallerException ( $this->db->getErrorMsg (), $this->db->getErrorNum () );
					$fields [$table] = $result;
				}
				if (! isset ( $fields [$table] [$detect ['column']] ))
					$found = 0; // Sorry, no match
			}
			if ($found)
				return $detect;
		}
		return array ();
	}

	// helper function to migrate table
	protected function migrateTable($oldprefix, $oldtable, $newtable) {
		$tables = $this->listTables ( 'kunena_' );
		$oldtables = $this->listTables ( $oldprefix );
		if ($oldtable == $newtable || !isset ( $oldtables [$oldtable] ) || isset ( $tables [$newtable] ))
			return; // Nothing to migrate

		// Make identical copy from the table with new name
		$create = array_pop($this->db->getTableCreate($this->db->getPrefix () . $oldtable));
		$collation = $this->db->getCollation ();
		if (!strstr($collation, 'utf8')) $collation = 'utf8_general_ci';
		if (!$create) return;
		$create = preg_replace('/(DEFAULT )?CHARACTER SET [\w\d]+/', '', $create);
		$create = preg_replace('/(DEFAULT )?CHARSET=[\w\d]+/', '', $create);
		$create = preg_replace('/COLLATE [\w\d_]+/', '', $create);
		$create = preg_replace('/TYPE\s*=?/', 'ENGINE=', $create);
		$create .= " DEFAULT CHARACTER SET utf8 COLLATE {$collation}";
		$query = preg_replace('/'.$this->db->getPrefix () . $oldtable.'/', $this->db->getPrefix () . $newtable, $create);
		$this->db->setQuery ( $query );
		$this->db->query ();
		if ($this->db->getErrorNum ())
			throw new KunenaInstallerException ( $this->db->getErrorMsg (), $this->db->getErrorNum () );

		$this->tables ['kunena_'] [$newtable] = $newtable;

		// And copy data into it
		$sql = "INSERT INTO " . $this->db->nameQuote ( $this->db->getPrefix () . $newtable ) . ' ' . $this->selectWithStripslashes($this->db->getPrefix () . $oldtable );
		$this->db->setQuery ( $sql );
		$this->db->query ();
		if ($this->db->getErrorNum ())
			throw new KunenaInstallerException ( $this->db->getErrorMsg (), $this->db->getErrorNum () );
		return array ('name' => $oldtable, 'action' => 'migrate', 'sql' => $sql );
	}

	function selectWithStripslashes($table) {
		$fields = array_pop($this->db->getTableFields($table));
		$select = array();
		foreach ($fields as $field=>$type) {
			$isString = preg_match('/text|char/', $type);
			$select[] = ($isString ? "REPLACE(REPLACE(REPLACE({$this->db->nameQuote($field)}, {$this->db->Quote('\\\\')}, {$this->db->Quote('\\')}),{$this->db->Quote('\\\'')} ,{$this->db->Quote('\'')}),{$this->db->Quote('\"')} ,{$this->db->Quote('"')}) AS " : '') . $this->db->nameQuote($field);
		}
		$select = implode(', ', $select);
		return "SELECT {$select} FROM {$table}";
	}

	function createVersionTable()
	{
		$tables = $this->listTables ( 'kunena_' );
		if (isset ( $tables ['kunena_version'] ))
			return; // Nothing to migrate
		$collation = $this->db->getCollation ();
		if (!strstr($collation, 'utf8')) $collation = 'utf8_general_ci';
		$query = "CREATE TABLE IF NOT EXISTS `".$this->db->getPrefix()."kunena_version` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`version` varchar(20) NOT NULL,
		`versiondate` date NOT NULL,
		`installdate` date NOT NULL,
		`build` varchar(20) NOT NULL,
		`versionname` varchar(40) DEFAULT NULL,
		`state` varchar(32) NOT NULL,
		PRIMARY KEY (`id`)
		) DEFAULT CHARACTER SET utf8 COLLATE {$collation};";
		$this->db->setQuery($query);
		$this->db->query();
		if ($this->db->getErrorNum ())
			throw new KunenaInstallerException ( $this->db->getErrorMsg (), $this->db->getErrorNum () );
		$this->tables ['kunena_'] ['kunena_version'] = 'kunena_version';
		return array('action'=>JText::_('COM_KUNENA_INSTALL_CREATE'), 'name'=>'kunena_version', 'sql'=>$query);
	}

	// also insert old version if not in the table
	protected function insertVersionData($version, $versiondate, $versionname, $state = '') {
		$this->db->setQuery ( "INSERT INTO `#__kunena_version` SET
			`version` = {$this->db->quote($version)},
			`versiondate` = {$this->db->quote($versiondate)},
			`installdate` = CURDATE(),
			`versionname` = {$this->db->quote($versionname)},
			`state` = {$this->db->quote($state)}");
		$this->db->query ();
		if ($this->db->getErrorNum ())
			throw new KunenaInstallerException ( $this->db->getErrorMsg (), $this->db->getErrorNum () );
	}

	protected function listTables($prefix, $reload = false) {
		if (isset ( $this->tables [$prefix] ) && ! $reload) {
			return $this->tables [$prefix];
		}
		$this->db->setQuery ( "SHOW TABLES LIKE " . $this->db->quote ( $this->db->getPrefix () . $prefix . '%' ) );
		$list = (array)$this->db->loadResultArray ();
		if ($this->db->getErrorNum ())
			throw new KunenaInstallerException ( $this->db->getErrorMsg (), $this->db->getErrorNum () );
		$this->tables [$prefix] = array ();
		foreach ( $list as $table ) {
			$table = preg_replace ( '/^' . $this->db->getPrefix () . '/', '', $table );
			$this->tables [$prefix] [$table] = $table;
		}
		return $this->tables [$prefix];
	}

	function deleteTables($prefix) {
		$tables = $this->listTables($prefix);
		foreach ($tables as $table) {
			$this->db->setQuery ( "DROP TABLE IF EXISTS " . $this->db->nameQuote ( $this->db->getPrefix () . $table ) );
			$this->db->query ();
			if ($this->db->getErrorNum ())
				throw new KunenaInstallerException ( $this->db->getErrorMsg (), $this->db->getErrorNum () );
		}
		unset($this->tables [$prefix]);
	}

	/**
	 * Create a Joomla menu for the main
	 * navigation tab and publish it in the Kunena module position kunena_menu.
	 * In addition it checks if there is a link to Kunena in any of the menus
	 * and if not, adds a forum link in the mainmenu.
	 */
	function createMenu() {
		$menu = array('name'=>JText::_ ( 'COM_KUNENA_MENU_ITEM_FORUM' ), 'alias'=>KunenaRoute::stringURLSafe(JText::_ ( 'COM_KUNENA_MENU_FORUM_ALIAS' ), 'forum'),
			'link'=>'index.php?option=com_kunena&view=home', 'access'=>0, 'params'=>array('catids'=>0));
		$submenu = array(
			'index'=>array('name'=>JText::_ ( 'COM_KUNENA_MENU_ITEM_INDEX' ), 'alias'=>KunenaRoute::stringURLSafe(JText::_ ( 'COM_KUNENA_MENU_INDEX_ALIAS' ), 'index'),
				'link'=>'index.php?option=com_kunena&view=category&layout=list', 'access'=>0, 'default'=>'categories', 'params'=>array()),
			'recent'=>array('name'=>JText::_ ( 'COM_KUNENA_MENU_ITEM_RECENT' ), 'alias'=>KunenaRoute::stringURLSafe(JText::_ ( 'COM_KUNENA_MENU_RECENT_ALIAS' ), 'recent'),
				'link'=>'index.php?option=com_kunena&view=topics&mode=replies', 'access'=>0, 'default'=>'recent', 'params'=>array('topics_catselection'=>'', 'topics_categories'=>'', 'topics_time'=>720)),
			'newtopic'=>array('name'=>JText::_ ( 'COM_KUNENA_MENU_ITEM_NEWTOPIC' ), 'alias'=>KunenaRoute::stringURLSafe(JText::_ ( 'COM_KUNENA_MENU_NEWTOPIC_ALIAS' ), 'newtopic'),
				'link'=>'index.php?option=com_kunena&view=topic&layout=create', 'access'=>1, 'params'=>array()),
			'noreplies'=>array('name'=>JText::_ ( 'COM_KUNENA_MENU_ITEM_NOREPLIES' ), 'alias'=>KunenaRoute::stringURLSafe(JText::_ ( 'COM_KUNENA_MENU_NOREPLIES_ALIAS' ), 'noreplies'),
				'link'=>'index.php?option=com_kunena&view=topics&mode=noreplies', 'access'=>1, 'params'=>array('topics_catselection'=>'', 'topics_categories'=>'', 'topics_time'=>-1)),
			'mylatest'=>array('name'=>JText::_ ( 'COM_KUNENA_MENU_ITEM_MYLATEST' ), 'alias'=>KunenaRoute::stringURLSafe(JText::_ ( 'COM_KUNENA_MENU_MYLATEST_ALIAS' ), 'mylatest'),
				'link'=>'index.php?option=com_kunena&view=topics&layout=user&mode=default', 'access'=>1, 'default'=>'my' , 'params'=>array('topics_catselection'=>'1', 'topics_categories'=>'0', 'topics_time'=>-1)),
			'profile'=>array('name'=>JText::_ ( 'COM_KUNENA_MENU_ITEM_PROFILE' ), 'alias'=>KunenaRoute::stringURLSafe(JText::_ ( 'COM_KUNENA_MENU_PROFILE_ALIAS' ), 'profile'),
				'link'=>'index.php?option=com_kunena&view=user', 'access'=>1, 'params'=>array('integration'=>1)),
			'help'=>array('name'=>JText::_ ( 'COM_KUNENA_MENU_ITEM_HELP' ), 'alias'=>KunenaRoute::stringURLSafe(JText::_ ( 'COM_KUNENA_MENU_HELP_ALIAS' ), 'help'),
				'link'=>'index.php?option=com_kunena&view=misc', 'access'=>2, 'params'=>array('body'=>JText::_ ( 'COM_KUNENA_MENU_HELP_BODY' ), 'body_format'=>'bbcode')),
			'search'=>array('name'=>JText::_ ( 'COM_KUNENA_MENU_ITEM_SEARCH' ), 'alias'=>KunenaRoute::stringURLSafe(JText::_ ( 'COM_KUNENA_MENU_SEARCH_ALIAS' ), 'search'),
				'link'=>'index.php?option=com_kunena&view=search', 'access'=>0, 'params'=>array()),
		);

		$lang = JFactory::getLanguage();
		$debug = $lang->setDebug(false);
		if (version_compare(JVERSION, '1.6','>')) {
			// Joomla 1.6+
			$this->createMenuJ25($menu, $submenu);
		} else {
			// Joomla 1.5
			$this->createMenuJ15($menu, $submenu);
		}
		KunenaMenuHelper::cleanCache();
		$lang->setDebug($debug);
	}

	function createMenuJ15($menu, $submenu) {
		jimport( 'joomla.utilities.string' );
		jimport( 'joomla.application.component.helper' );

		$config = KunenaFactory::getConfig();

		$component_id = JComponentHelper::getComponent('com_kunena')->id;

		// First fix all broken menu items
		$query = "UPDATE #__menu SET componentid={$this->db->quote($component_id)} WHERE type = 'component' AND link LIKE '%option=com_kunena%'";
		$this->db->setQuery ( $query );
		$this->db->query ();
		if ($this->db->getErrorNum ())
			throw new KunenaInstallerException ( $this->db->getErrorMsg (), $this->db->getErrorNum () );

		// Find out if menu exists
		$query = "SELECT id FROM `#__menu_types` WHERE `menutype`='kunenamenu';";
		$this->db->setQuery ( $query );
		$moduleid = ( int ) $this->db->loadResult ();
		if ($this->db->getErrorNum ())
			throw new KunenaInstallerException ( $this->db->getErrorMsg (), $this->db->getErrorNum () );

		// Do not touch existing menu
		if ($moduleid) {
			return;
		}

		// Create new Joomla menu for Kunena
		if (! $moduleid) {
			// Create a menu type for the Kunena menu
			$query = "REPLACE INTO `#__menu_types` (`id`, `menutype`, `title`, `description`) VALUES
							($moduleid, 'kunenamenu', {$this->db->Quote( JText::_ ( 'COM_KUNENA_MENU_TITLE' ))} , {$this->db->Quote(JText::_ ( 'COM_KUNENA_MENU_TITLE_DESC' ))} )";
			$this->db->setQuery ( $query );
			$this->db->query ();
			if ($this->db->getErrorNum ())
				throw new KunenaInstallerException ( $this->db->getErrorMsg (), $this->db->getErrorNum () );
		}

		// Forum
		$query = "SELECT id FROM `#__menu` WHERE `link`={$this->db->quote($menu['link'])} AND `menutype`='kunenamenu';";
		$this->db->setQuery ( $query );
		$parentid = ( int ) $this->db->loadResult ();
		if ($this->db->getErrorNum ())
			throw new KunenaInstallerException ( $this->db->getErrorMsg (), $this->db->getErrorNum () );
		if (! $parentid) {
			$params = new JParameter('');
			$params->bind($menu['params']);
			$query = "REPLACE INTO `#__menu` (`id`, `menutype`, `name`, `alias`, `link`, `type`, `published`, `parent`, `componentid`, `sublevel`, `ordering`, `checked_out`, `checked_out_time`, `pollid`, `browserNav`, `access`, `utaccess`, `params`, `lft`, `rgt`, `home`) VALUES
							($parentid, 'kunenamenu', {$this->db->quote($menu['name'])}, {$this->db->quote($menu['alias'])}, {$this->db->quote($menu['link'])}, 'component', 1, 0, $component_id, 0, 1, 0, '0000-00-00 00:00:00', 0, 0, {$menu['access']}, 0, {$this->db->quote($params->toString('INI'))}, 0, 0, 0);";
			$this->db->setQuery ( $query );
			$this->db->query ();
			if ($this->db->getErrorNum ())
				throw new KunenaInstallerException ( $this->db->getErrorMsg (), $this->db->getErrorNum () );
			$parentid = ( int ) $this->_db->insertId ();
		}

		// Submenu (shown in Kunena)
		$defaultmenu = 0;
		$ordering = 0;
		foreach ($submenu as $menuitem) {
			$ordering++;
//			$query = "SELECT id FROM `#__menu` WHERE `link`={$this->db->quote($menuitem['link'])} AND `menutype`='kunenamenu';";
//			$this->db->setQuery ( $query );
//			$id = ( int ) $this->db->loadResult ();
//			if ($this->db->getErrorNum ())
//				throw new KunenaInstallerException ( $this->db->getErrorMsg (), $this->db->getErrorNum () );
			$id = 0;
			if (! $id) {
				$params = new JParameter('');
				$params->bind($menuitem['params']);
				$query = "REPLACE INTO `#__menu` (`id`, `menutype`, `name`, `alias`, `link`, `type`, `published`, `parent`, `componentid`, `sublevel`, `ordering`, `checked_out`, `checked_out_time`, `pollid`, `browserNav`, `access`, `utaccess`, `params`, `lft`, `rgt`, `home`) VALUES
								($id, 'kunenamenu', {$this->db->quote($menuitem['name'])}, {$this->db->quote($menuitem['alias'])}, {$this->db->quote($menuitem['link'])},'component', 1, $parentid, $component_id, 1, $ordering, 0, '0000-00-00 00:00:00', 0, 0, {$menuitem['access']}, 0, {$this->db->quote($params->toString('INI'))}, 0, 0, 0);";
				$this->db->setQuery ( $query );
				$this->db->query ();
				if ($this->db->getErrorNum ())
					throw new KunenaInstallerException ( $this->db->getErrorMsg (), $this->db->getErrorNum () );
				$id = ( int ) $this->_db->insertId ();
				if (!$defaultmenu || (isset($menuitem['default']) && $config->defaultpage == $menuitem['default'])) {
					$defaultmenu = $id;
				}
			}
		}
		if ($defaultmenu) {
			$query = "UPDATE `#__menu` SET `link`={$this->db->quote($menu['link']."&defaultmenu=$defaultmenu")} WHERE id={$parentid}";
			$this->db->setQuery ( $query );
			$this->db->query ();
		if ($this->db->getErrorNum ())
			throw new KunenaInstallerException ( $this->db->getErrorMsg (), $this->db->getErrorNum () );
		}

		// Finally add forum menu link to default menu
		$jmenu = JMenu::getInstance('site');
		$dmenu = $jmenu->getDefault();
		$query = "SELECT id, name, type, link, published FROM `#__menu` WHERE `alias` IN ('forum', 'kunenaforum', {$this->db->quote(JText::_ ( 'COM_KUNENA_MENU_FORUM_ALIAS' ))}) AND `menutype`={$this->db->quote($dmenu->menutype)}";
		$this->db->setQuery ( $query, 0, 1 );
		$menualias = $this->db->loadObject ();
		if ($this->db->getErrorNum ())
			throw new KunenaInstallerException ( $this->db->getErrorMsg (), $this->db->getErrorNum () );
		// We do not want to replace users own menu items (just alias or deprecated link to Kunena)
		if (!$menualias || $menualias->type == 'menulink' || $menualias->link == 'index.php?option=com_kunena') {
			$id = $menualias ? intval($menualias->id) : 0;
			// Keep state (default=unpublished) and name (default=Forum)
			$published = $menualias ? intval($menualias->published) : 0;
			$name = $menualias ? $menualias->name : $menu['name'];
			$query = "REPLACE INTO `#__menu` (`id`, `menutype`, `name`, `alias`, `link`, `type`, `published`, `parent`, `componentid`, `sublevel`, `checked_out`, `checked_out_time`, `pollid`, `browserNav`, `access`, `utaccess`, `params`, `lft`, `rgt`, `home`) VALUES
						($id, {$this->db->quote($dmenu->menutype)}, {$this->db->quote($name)}, 'kunenaforum', 'index.php?Itemid={$parentid}', 'menulink', {$published}, 0, 0, 0, 0, '0000-00-00 00:00:00', 0, 0, {$menu['access']}, 0, 'menu_item=$parentid{$menu['params']}\r\n\r\n', 0, 0, 0);";
			$this->db->setQuery ( $query );
			$this->db->query ();
			if ($this->db->getErrorNum ())
				throw new KunenaInstallerException ( $this->db->getErrorMsg (), $this->db->getErrorNum () );
		}
	}

	function createMenuJ25($menu, $submenu) {
		jimport ( 'joomla.utilities.string' );
		jimport ( 'joomla.application.component.helper' );

		$config = KunenaFactory::getConfig ();

		$component_id = JComponentHelper::getComponent ( 'com_kunena' )->id;

		// First fix all broken menu items
		$query = "UPDATE #__menu SET component_id={$this->db->quote($component_id)} WHERE type = 'component' AND link LIKE '%option=com_kunena%'";
		$this->db->setQuery ( $query );
		$this->db->query ();
		if ($this->db->getErrorNum ())
			throw new KunenaInstallerException ( $this->db->getErrorMsg (), $this->db->getErrorNum () );

		$table = JTable::getInstance ( 'menutype' );
		$data = array (
			'menutype' => 'kunenamenu',
			'title' => JText::_ ( 'COM_KUNENA_MENU_TITLE' ),
			'description' => JText::_ ( 'COM_KUNENA_MENU_TITLE_DESC' )
		);
		if (! $table->bind ( $data ) || ! $table->check ()) {
			// Menu already exists, do nothing
			return true;
		}
		if (! $table->store ()) {
			throw new KunenaInstallerException ( $table->getError () );
		}

		$table = JTable::getInstance ( 'menu' );
		$table->load(array('menutype'=>'kunenamenu', 'link'=>$menu ['link']));
		$paramdata = array ('menu-anchor_title'=>'',
				'menu-anchor_css'=>'',
				'menu_image'=>'',
				'menu_text'=>1,
				'page_title'=>'',
				'show_page_heading'=>0,
				'page_heading'=>'',
				'pageclass_sfx'=>'',
				'menu-meta_description'=>'',
				'menu-meta_keywords'=>'',
				'robots'=>'',
				'secure'=>0);

		$gparams = new JRegistry($paramdata);

		$params = clone $gparams;
		$params->loadArray($menu['params']);
		$data = array (
			'menutype' => 'kunenamenu',
			'title' => $menu ['name'],
			'alias' => $menu ['alias'],
			'link' => $menu ['link'],
			'type' => 'component',
			'published' => 1,
			'parent_id' => 1,
			'component_id' => $component_id,
			'access' => $menu ['access'] + 1,
			'params' => (string) $params,
			'home' => 0,
			'language' => '*',
			'client_id' => 0
		);
		if (! $table->setLocation ( 1, 'last-child' ) || ! $table->bind ( $data ) || ! $table->check () || ! $table->store ()) {
			$table->alias = 'kunena';
			if (! $table->check () || ! $table->store ()) {
				throw new KunenaInstallerException ( $table->getError () );
			}
		}
		$parent = $table;
		$defaultmenu = 0;
		foreach ( $submenu as $menuitem ) {
			$params = clone $gparams;
			$params->loadArray($menuitem['params']);
			$table = JTable::getInstance ( 'menu' );
			$table->load(array('menutype'=>'kunenamenu', 'link'=>$menuitem ['link']));
			$data = array (
				'menutype' => 'kunenamenu',
				'title' => $menuitem ['name'],
				'alias' => $menuitem ['alias'],
				'link' => $menuitem ['link'],
				'type' => 'component',
				'published' => 1,
				'parent_id' => $parent->id,
				'component_id' => $component_id,
				'access' => $menuitem ['access'] + 1,
				'params' => (string) $params,
				'home' => 0,
				'language' => '*',
				'client_id' => 0
			);

			if (! $table->setLocation ( $parent->id, 'last-child' ) || ! $table->bind ( $data ) || ! $table->check () || ! $table->store ()) {
				throw new KunenaInstallerException ( $table->getError () );
			}
			if (! $defaultmenu || (isset ( $menuitem ['default'] ) && $config->defaultpage == $menuitem ['default'])) {
				$defaultmenu = $table->id;
			}
		}

		// Update forum menuitem to point into default page
		$parent->link .= "&defaultmenu={$defaultmenu}";
		if (! $parent->check () || ! $parent->store ()) {
			throw new KunenaInstallerException ( $table->getError () );
		}

		// Finally create alias
		$defaultmenu = JMenu::getInstance('site')->getDefault();
		if (!$defaultmenu) return true;
		$table = JTable::getInstance ( 'menu' );
		$table->load(array('menutype'=>$defaultmenu->menutype, 'type'=>'alias', 'title'=>JText::_ ( 'COM_KUNENA_MENU_ITEM_FORUM' )));
		if (!$table->id) {
			$data = array (
				'menutype' => $defaultmenu->menutype,
				'title' => JText::_ ( 'COM_KUNENA_MENU_ITEM_FORUM' ),
				'alias' => 'kunena-'.JFactory::getDate()->format('Y-m-d'),
				'link' => 'index.php?Itemid='.$parent->id,
				'type' => 'alias',
				'published' => 0,
				'parent_id' => 1,
				'component_id' => 0,
				'access' => 1,
				'params' => '{"aliasoptions":"'.(int)$parent->id.'","menu-anchor_title":"","menu-anchor_css":"","menu_image":""}',
				'home' => 0,
				'language' => '*',
				'client_id' => 0
			);
			if (! $table->setLocation ( 1, 'last-child' )) {
				throw new KunenaInstallerException ( $table->getError () );
			}
		} else {
			$data = array (
				'alias' => 'kunena-'.JFactory::getDate()->format('Y-m-d'),
				'link' => 'index.php?Itemid='.$parent->id,
				'params' => '{"aliasoptions":"'.(int)$parent->id.'","menu-anchor_title":"","menu-anchor_css":"","menu_image":""}',
			);
		}
		if (! $table->bind ( $data ) || ! $table->check () || ! $table->store ()) {
			throw new KunenaInstallerException ( $table->getError () );
		}
	}

	function deleteMenu() {
		if (version_compare(JVERSION, '1.6','>')) {
			// Joomla 1.6+
			$this->DeleteMenuJ25();
		} else {
			// Joomla 1.5
			$this->DeleteMenuJ15();
		}

		// Can be called before installation starts (don't use library):
		if (version_compare(JVERSION, '1.6', '>')) {
			$cache = JFactory::getCache('mod_menu');
			$cache->clean();
		} else {
			// clean system cache
			$cache = JFactory::getCache('_system');
			$cache->clean();

			// clean mod_mainmenu cache
			$cache = JFactory::getCache('mod_mainmenu');
			$cache->clean();
		}
	}

	function deleteMenuJ25() {
		$table = JTable::getInstance ( 'menutype' );
		$table->load(array('menutype'=>'kunenamenu'));
		if ($table->id) {
			$success = $table->delete();
			if (!$success) {
				JFactory::getApplication()->enqueueMessage($table->getError(), 'error');
			}
		}
	}

	function deleteMenuJ15() {
		$query = "SELECT id FROM `#__menu_types` WHERE `menutype`='kunenamenu';";
		$this->db->setQuery ( $query );
		$menuid = $this->db->loadResult ();
		if ($this->db->getErrorNum ())
			throw new KunenaInstallerException ( $this->db->getErrorMsg (), $this->db->getErrorNum () );
		if (!$menuid) return;

		require_once (JPATH_ADMINISTRATOR . '/components/com_menus/helpers/helper.php');
		require_once (JPATH_ADMINISTRATOR . '/components/com_menus/models/menutype.php');
		$menuhelper = new MenusModelMenutype ();
		$menuhelper->delete ( $menuid );
	}

	function checkTimeout($stop = false) {
		static $start = null;
		if ($stop) $start = 0;
		$time = microtime (true);
		if ($start === null) {
			$start = $time;
			return false;
		}
		if ($time - $start < 1)
			return false;

		return true;
	}

	public function recountThankyou() {
		//Only perform this action if upgrading form previous version
		$version = $this->getVersion();
		if (version_compare ( $version->version, '2.0.0-BETA2', ">" )) {
			return true;
		}

		// If the migration is from previous version thant 1.6.0 doesn't need to recount
		if (version_compare ( $version->version, '1.6.0', "<" )) {
			return true;
		}

		KunenaForumMessageThankyouHelper::recount();

		return true;
	}

	protected function _getJoomlaArchiveError($archive) {
		$error = '';
		if (version_compare(JVERSION, '1.6','<')) {
			// Joomla 1.5: Unfortunately we need this rather ugly hack to get the error message
			$ext = JFile::getExt(strtolower($archive));
			$adapter = null;

			switch ($ext)
			{
				case 'zip':
					$adapter = JArchive::getAdapter('zip');
					break;
				case 'tar':
					$adapter = JArchive::getAdapter('tar');
					break;
				case 'tgz'  :
				case 'gz'   :	// This may just be an individual file (e.g. sql script)
				case 'gzip' :
					$adapter = JArchive::getAdapter('gzip');
					break;
				case 'tbz2' :
				case 'bz2'  :	// This may just be an individual file (e.g. sql script)
				case 'bzip2':
					$adapter = JArchive::getAdapter('bzip2');
					break;
				default:
					$adapter = null;
					break;
			}

			if ($adapter) {
				$error .= $adapter->get('error.message'). ': ' . $archive;
			}

			// End of Joomla 1.5 error message hackathon
		} else {
			// J1.6 and beyond - Not yet implemented
		}

		return $error;
	}

}
class KunenaInstallerException extends Exception {
}
