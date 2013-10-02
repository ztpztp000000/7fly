<?php
/**
 * Kunena Component
 * @package Kunena.Framework
 *
 * @copyright (C) 2008 - 2012 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/
defined ( '_JEXEC' ) or die ();

abstract class KunenaFactory {
	static $session = null;

	/**
	 * Get a Kunena configuration object
	 *
	 * Returns the global {@link KunenaConfig} object, only creating it if it doesn't already exist.
	 *
	 * @return object KunenaConfig
	 */
	public static function getConfig() {
		return KunenaConfig::getInstance();
	}

	/**
	 * Get a Kunena template object
	 *
	 * Returns the global {@link KunenaTemplate} object, only creating it if it doesn't already exist.
	 *
	 * @return object KunenaTemplate
	 */
	public static function getTemplate($name = null) {
		return KunenaTemplate::getInstance($name);
	}

	/**
	 * Get Kunena user object
	 *
	 * Returns the global {@link KunenaUser} object, only creating it if it doesn't already exist.
	 *
	 * @param	int	$id	The user to load - Can be an integer or string - If string, it is converted to Id automatically.
	 *
	 * @return object KunenaUser
	 */
	public static function getUser($id = null, $reload = false) {
		return KunenaUserHelper::get($id, $reload);
	}

	/**
	 * Get Kunena session object
	 *
	 * Returns the global {@link KunenaSession} object, only creating it if it doesn't already exist.
	 *
	 * @param array An array containing session options
	 * @return object KunenaSession
	 */
	public static function getSession($update = false) {
		if (!is_object(KunenaFactory::$session)) {
			KunenaFactory::$session = KunenaSession::getInstance($update);
		}
		return KunenaFactory::$session;
	}

	/**
	 * Get Kunena avatar integration object
	 *
	 * Returns the global {@link KunenaAvatar} object, only creating it if it doesn't already exist.
	 *
	 * @return object KunenaAvatar
	 */
	public static function getAvatarIntegration() {
		require_once KPATH_ADMIN . '/libraries/integration/avatar.php';
		return KunenaAvatar::getInstance();
	}

	/**
	 * Get Kunena private message system integration object
	 *
	 * Returns the global {@link KunenaPrivate} object, only creating it if it doesn't already exist.
	 *
	 * @return object KunenaPrivate
	 */
	public static function getPrivateMessaging() {
		require_once KPATH_ADMIN . '/libraries/integration/private.php';
		return KunenaPrivate::getInstance();
	}

	/**
	 * Get Kunena activity integration object
	 *
	 * Returns the global {@link KunenaActivity} object, only creating it if it doesn't already exist.
	 *
	 * @return object KunenaActivity
	 */
	public static function getActivityIntegration() {
		require_once KPATH_ADMIN . '/libraries/integration/activity.php';
		return KunenaActivity::getInstance();
	}

	/**
	 * Get Kunena profile integration object
	 *
	 * Returns the global {@link KunenaProfile} object, only creating it if it doesn't already exist.
	 *
	 * @return object KunenaProfile
	 */
	public static function getProfile() {
		require_once KPATH_ADMIN . '/libraries/integration/profile.php';
		return KunenaProfile::getInstance();
	}

	/**
	 * Load Kunena language file
	 *
	 * Helper function for external modules and plugins to load the main Kunena language file(s)
	 *
	 */
	public static function loadLanguage( $file = 'com_kunena', $client = 'site' ) {
		static $loaded = array();
		KUNENA_PROFILER ? KunenaProfiler::instance()->start('function '.__CLASS__.'::'.__FUNCTION__.'()') : null;

		if ($client == 'site') {
			$lookup1 = JPATH_SITE;
			$lookup2 = KPATH_SITE;
		} else {
			$client = 'admin';
			$lookup1 = JPATH_ADMINISTRATOR;
			$lookup2 = KPATH_ADMIN;
		}
		if (empty($loaded["{$client}/{$file}"])) {
			$lang = JFactory::getLanguage();
			if (version_compare(JVERSION, '1.6','<')) {
				// Joomla 1.5 hack to make languages to load faster
				if ($lang->getTag() != 'en-GB' && !JDEBUG && !$lang->getDebug()
						&& !KunenaFactory::getConfig()->get('debug') && KunenaFactory::getConfig()->get('fallback_english')) {
					$filename = JLanguage::getLanguagePath( $lookup2, 'en-GB')."/en-GB.{$file}.ini";
					$loaded[$file] = self::parseLanguage($lang, $filename);
				}
				$filename = JLanguage::getLanguagePath( $lookup1, $lang->_lang)."/{$lang->_lang}.{$file}.ini";
				$loaded[$file] = self::parseLanguage($lang, $filename);
				if (!$loaded[$file]) {
					$filename = JLanguage::getLanguagePath( $lookup2, $lang->_lang)."/{$lang->_lang}.{$file}.ini";
					$loaded[$file] = self::parseLanguage($lang, $filename);
				}
				if (!$loaded[$file]) {
					$filename = JLanguage::getLanguagePath( $lookup1, $lang->_default)."/{$lang->_default}.{$file}.ini";
					$loaded[$file] = self::parseLanguage($lang, $filename);
				}
				if (!$loaded[$file]) {
					$filename = JLanguage::getLanguagePath( $lookup2, $lang->_default)."/{$lang->_default}.{$file}.ini";
					$loaded[$file] = self::parseLanguage($lang, $filename);
				}
			} else {
				$english = false;
				if ($lang->getTag() != 'en-GB' && !JDEBUG && !$lang->getDebug()
						&& !KunenaFactory::getConfig()->get('debug') && KunenaFactory::getConfig()->get('fallback_english')) {
					$lang->load($file, $lookup2, 'en-GB', true, false);
					$english = true;
				}
				$loaded[$file] = $lang->load($file, $lookup1, null, $english, false)
					|| $lang->load($file, $lookup2, null, $english, false)
					|| $lang->load($file, $lookup1, $lang->getDefault(), $english, false)
					|| $lang->load($file, $lookup2, $lang->getDefault(), $english, false);
			}
		}
		KUNENA_PROFILER ? KunenaProfiler::instance()->stop('function '.__CLASS__.'::'.__FUNCTION__.'()') : null;
		return $loaded[$file];
}

	protected static function parseLanguage($lang, $filename) {
		if (!file_exists($filename)) return false;

		$version = phpversion();

		// Capture hidden PHP errors from the parsing.
		$php_errormsg = null;
		$track_errors = ini_get('track_errors');
		ini_set('track_errors', true);

		if ($version >= '5.3.1') {
			$contents = file_get_contents($filename);
			$contents = str_replace('_QQ_', '"\""', $contents);
			$strings = @parse_ini_string($contents);
		} else {
			$strings = @parse_ini_file($filename);

			if ($version == '5.3.0' && is_array($strings)) {
				foreach ($strings as $key => $string) {
					$strings[$key] = str_replace('_QQ_', '"', $string);
				}
			}
		}

		// Restore error tracking to what it was before.
		ini_set('track_errors', $track_errors);

		if (!is_array($strings)) {
			$strings = array();
		}

		$lang->_strings = array_merge($lang->_strings, $strings);
		return !empty($strings);
	}
}