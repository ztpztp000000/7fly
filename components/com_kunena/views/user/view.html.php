<?php
/**
 * Kunena Component
 * @package Kunena.Site
 * @subpackage Views
 *
 * @copyright (C) 2008 - 2012 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/
defined ( '_JEXEC' ) or die ();

// FIXME: convert to full MVC

/**
 * User View
 */
class KunenaViewUser extends KunenaView {
	function displayDefault($tpl = null) {
		$this->displayCommon($tpl);
	}

	function displayModerate($tpl = null) {
		$this->layout = 'default';
		$this->setLayout($this->layout);
		$this->displayCommon($tpl);
	}

	function displayEdit($tpl = null) {
		$userid = JRequest::getInt('userid');
		$this->usernamechange = $this->config->usernamechange && (version_compare(JVERSION, '2.5.5','<') || JComponentHelper::getParams('com_users')->get('change_login_name', 1));

		if ($userid && $this->me->userid != $userid) {
			$user = KunenaFactory::getUser( $userid );
			$this->app->enqueueMessage ( JText::sprintf('COM_KUNENA_VIEW_USER_EDIT_AUTH_FAILED', $user->getName()), 'notice' );
			return;
		}
		$this->displayCommon($tpl);
	}

	function displayList($tpl = null) {
		$this->total = $this->get ( 'Total' );
		$this->count = $this->get ( 'Count' );
		$this->users = $this->get ( 'Items' );
		// TODO: Deprecated:
		$this->pageNav = $this->getPagination(7);

		$this->_prepareDocument('list');

		parent::display($tpl);
	}

	function getPagination($maxpages) {
		$pagination = new KunenaHtmlPagination ( $this->count, $this->state->get('list.start'), $this->state->get('list.limit') );
		$pagination->setDisplay($maxpages);
		return $pagination->getPagesLinks();
	}

	protected function displayCommon($tpl = null) {
		$userid = JRequest::getInt('userid');

		$this->_db = JFactory::getDBO ();
		$this->do = JRequest::getWord('layout');

		if (!$userid) {
			$this->user = JFactory::getUser ();
		} else {
			$this->user = JFactory::getUser( $userid );
		}
		if ($this->user->id == 0|| ($this->me->userid == 0 && !$this->config->pubprofile)) {
			$this->app->enqueueMessage ( JText::_('COM_KUNENA_PROFILEPAGE_NOT_ALLOWED_FOR_GUESTS'), 'notice' );
			return;
		}

		$integration = KunenaFactory::getProfile();
		$activityIntegration = KunenaFactory::getActivityIntegration();
		$template = KunenaFactory::getTemplate();
		$this->params = $template->params;

		if (get_class($integration) == 'KunenaProfileNone') {
			$this->app->enqueueMessage ( JText::_('COM_KUNENA_PROFILE_DISABLED'), 'notice' );
			return;
		}

		$this->allow = true;

		$this->profile = KunenaFactory::getUser ( $this->user->id );
		if (!$this->profile->exists()) {
			$this->profile->save();
		}
		if ($this->profile->userid == $this->me->userid) {
			if ($this->do != 'edit') $this->editLink = CKunenaLink::GetMyProfileLink ( $this->profile->userid, JText::_('COM_KUNENA_EDIT').' &raquo;', 'nofollow', 'edit', 'kheader-link' );
			else $this->editLink = CKunenaLink::GetMyProfileLink ( $this->profile->userid, JText::_('COM_KUNENA_BACK').' &raquo;', 'nofollow', '', 'kheader-link' );

			// TODO: Deprecated
			if ($this->do != 'edit') $this->editlink = CKunenaLink::GetMyProfileLink ( $this->profile->userid, JText::_('COM_KUNENA_EDIT'), 'nofollow', 'edit' );
			else $this->editlink = CKunenaLink::GetMyProfileLink ( $this->profile->userid, JText::_('COM_KUNENA_BACK'), 'nofollow' );
		}
		$this->name = $this->user->username;
		if ($this->config->userlist_name) $this->name = $this->user->name . ' (' . $this->name . ')';
		if ($this->config->showuserstats) {
			if ($this->config->userlist_usertype) $this->usertype = $this->user->usertype;
			$this->rank_image = $this->profile->getRank (0, 'image');
			$this->rank_title = $this->profile->getRank (0, 'title');
			$this->posts = $this->profile->posts;
			$this->thankyou = $this->profile->thankyou;
			$this->userpoints = $activityIntegration->getUserPoints($this->profile->userid);
			$this->usermedals = $activityIntegration->getUserMedals($this->profile->userid);
		}
		if ($this->config->userlist_joindate || $this->me->isModerator()) $this->registerdate = $this->user->registerDate;
		if ($this->config->userlist_lastvisitdate || $this->me->isModerator()) $this->lastvisitdate = $this->user->lastvisitDate;
		if (!isset($this->lastvisitdate) || $this->lastvisitdate == "0000-00-00 00:00:00") $this->lastvisitdate = null;
		$this->avatarlink = $this->profile->getAvatarImage('kavatar','profile');
		$this->personalText = $this->profile->personalText;
		$this->signature = $this->profile->signature;
		$this->signatureHtml = KunenaHtmlParser::parseBBCode($this->signature, null, $this->config->maxsig);
		$this->localtime = KunenaDate::getInstance('now', $this->user->getParam('timezone', $this->app->getCfg ( 'offset', 0 )));
		$this->localtime->setOffset($this->user->getParam('timezone', $this->app->getCfg ( 'offset', 0 )));
		$this->moderator = KunenaAccess::getInstance()->getModeratorStatus($this->profile);
		$this->admin = $this->profile->isAdmin();
		switch ($this->profile->gender) {
			case 1:
				$this->genderclass = 'male';
				$this->gender = JText::_('COM_KUNENA_MYPROFILE_GENDER_MALE');
				break;
			case 2:
				$this->genderclass = 'female';
				$this->gender = JText::_('COM_KUNENA_MYPROFILE_GENDER_FEMALE');
				break;
			default:
				$this->genderclass = 'unknown';
				$this->gender = JText::_('COM_KUNENA_MYPROFILE_GENDER_UNKNOWN');
		}
		if ($this->profile->location)
			$this->locationlink = '<a href="http://maps.google.com?q='.$this->escape($this->profile->location).'" target="_blank">'.$this->escape($this->profile->location).'</a>';
		else
			$this->locationlink = JText::_('COM_KUNENA_LOCATION_UNKNOWN');

		$this->online = $this->profile->isOnline();
		$this->showUnusedSocial = true;

		$avatar = KunenaFactory::getAvatarIntegration();
		$this->editavatar = ($avatar instanceof KunenaAvatarKunena) ? true : false;

		$this->banInfo = KunenaUserBan::getInstanceByUserid($userid, true);
		$this->canBan = $this->banInfo->canBan();
		if ( $this->config->showbannedreason ) $this->banReason = $this->banInfo->reason_public;

		// Which tabs to show?
		$this->showUserPosts = true;
		$this->showSubscriptions = $this->config->allowsubscriptions && $this->me->userid == $this->profile->userid;
		$this->showFavorites = $this->config->allowfavorites && $this->me->userid == $this->profile->userid;
		$this->showThankyou = $this->config->showthankyou && $this->me->exists();
		$this->showUnapprovedPosts = $this->me->isAdmin() || KunenaAccess::getInstance()->getModeratorStatus(); // || $this->me->userid == $this->profile->userid;
		$this->showAttachments = $this->canManageAttachments() && ($this->me->isModerator() || $this->me->userid == $this->profile->userid);
		$this->showBanManager = $this->me->isModerator() && $this->me->userid == $this->profile->userid;
		$this->showBanHistory = $this->me->isModerator() && $this->me->userid != $this->profile->userid;
		$this->showBanUser = $this->canBan;

		if ($this->me->userid != $this->profile->userid) {
			$this->profile->uhits++;
			$this->profile->save();
		}

		$private = KunenaFactory::getPrivateMessaging();
		if ($this->me->userid == $this->user->id) {
			$this->pmCount = $private->getUnreadCount($this->me->userid);
			$this->pmLink = $private->getInboxLink($this->pmCount ? JText::sprintf('COM_KUNENA_PMS_INBOX_NEW', $this->pmCount) : JText::_('COM_KUNENA_PMS_INBOX'));
		} else {
			$this->pmLink = $this->profile->profileIcon('private');
		}

		$this->_prepareDocument('common');
		parent::display();
	}

	function displayUnapprovedPosts() {
		$params = array(
			'topics_categories' => 0,
			'topics_catselection' => 1,
			'userid' => $this->user->id,
			'mode' => 'unapproved',
			'sel' => -1,
			'limit' => 6,
			'filter_order' => 'time',
			'limitstart' => 0,
			'filter_order_Dir' => 'desc',
		);
		KunenaForum::display('topics', 'posts', 'embed', $params);
	}

	function displayUserPosts() {
		$params = array(
			'topics_categories' => 0,
			'topics_catselection' => 1,
			'userid' => $this->user->id,
			'mode' => 'latest',
			'sel' => 8760,
			'limit' => 6,
			'filter_order' => 'time',
			'limitstart' => 0,
			'filter_order_Dir' => 'desc',
		);
		KunenaForum::display('topics', 'posts', 'embed', $params);
	}

	function displayGotThankyou() {
		$params = array(
			'topics_categories' => 0,
			'topics_catselection' => 1,
			'userid' => $this->user->id,
			'mode' => 'mythanks',
			'sel' => -1,
			'limit' => 6,
			'filter_order' => 'time',
			'limitstart' => 0,
			'filter_order_Dir' => 'desc',
		);
		KunenaForum::display('topics', 'posts', 'embed', $params);
	}

	function displaySaidThankyou() {
		$params = array(
			'topics_categories' => 0,
			'topics_catselection' => 1,
			'userid' => $this->user->id,
			'mode' => 'thankyou',
			'sel' => -1,
			'limit' => 6,
			'filter_order' => 'time',
			'limitstart' => 0,
			'filter_order_Dir' => 'desc',
		);
		KunenaForum::display('topics', 'posts', 'embed', $params);
	}

	function displayFavorites() {
		$params = array(
			'topics_categories' => 0,
			'topics_catselection' => 1,
			'userid' => $this->user->id,
			'mode' => 'favorites',
			'sel' => -1,
			'limit' => 6,
			'filter_order' => 'time',
			'limitstart' => 0,
			'filter_order_Dir' => 'desc',
		);
		KunenaForum::display('topics', 'user', 'embed', $params);
	}

	function displaySubscriptions() {
		if ($this->config->topic_subscriptions == 'disabled') return;
		$params = array(
			'topics_categories' => 0,
			'topics_catselection' => 1,
			'userid' => $this->user->id,
			'mode' => 'subscriptions',
			'sel' => -1,
			'limit' => 6,
			'filter_order' => 'time',
			'limitstart' => 0,
			'filter_order_Dir' => 'desc',
		);
		KunenaForum::display('topics', 'user', 'embed', $params);
	}

	function displayCategoriesSubscriptions() {
		if ($this->config->category_subscriptions == 'disabled') return;
		$params = array(
			'userid' => $this->user->id,
			'limit' => 6,
			'filter_order' => 'time',
			'limitstart' => 0,
			'filter_order_Dir' => 'desc',
		);
		KunenaForum::display('category', 'user', 'embed', $params);
	}

	function displayBanUser() {
		$this->baninfo = KunenaUserBan::getInstanceByUserid($this->profile->userid, true);
		echo $this->loadTemplateFile('ban');
	}

	function displayBanHistory() {
		$this->banhistory = KunenaUserBan::getUserHistory($this->profile->userid);
		echo $this->loadTemplateFile('history');
	}

	function displayBanManager() {
		// TODO: move ban manager somewhere else and add pagination
		$this->bannedusers = KunenaUserBan::getBannedUsers(0, 50);
		if ( !empty($this->bannedusers) ) KunenaUserHelper::loadUsers(array_keys($this->bannedusers));
		echo $this->loadTemplateFile('banmanager');
	}

	function displaySummary() {
		echo $this->loadTemplateFile('summary');
	}

	function displayTab() {
		$this->email = null;
		if ( $this->config->showemail && ( !$this->profile->hideEmail || $this->me->isModerator() ) ) {
			$this->email = JHTML::_('email.cloak', $this->user->email);
		} else if ( $this->me->isAdmin() ) {
			$this->email = JHTML::_('email.cloak', $this->user->email);
		}

		switch ($this->do) {
			case 'edit':
				$user = JFactory::getUser();
				if ($user->id == $this->user->id) echo $this->loadTemplateFile('tab');
				break;
			default:
				echo $this->loadTemplateFile('tab');
		}
	}

	function displayKarma() {
		$userkarma = '';
		if ($this->config->showkarma && $this->profile->userid) {
			$userkarma = '<strong>'. JText::_('COM_KUNENA_KARMA') . "</strong>: " . $this->profile->karma;

			if ($this->me->userid && $this->me->userid != $this->profile->userid) {
				$userkarma .= ' '.CKunenaLink::GetKarmaLink ( 'decrease', '', '', $this->profile->userid, '<span class="kkarma-minus" title="' . JText::_('COM_KUNENA_KARMA_SMITE') . '"> </span>' );
				$userkarma .= ' '.CKunenaLink::GetKarmaLink ( 'increase', '', '', $this->profile->userid, '<span class="kkarma-plus" title="' . JText::_('COM_KUNENA_KARMA_APPLAUD') . '"> </span>' );
			}
		}

		return $userkarma;
	}

	function getAvatarGallery($path) {
		jimport('joomla.filesystem.folder');
		$files = JFolder::files($path,'(\.gif|\.png|\.jpg|\.jpeg)$');
		return $files;
	}

	// This function was modified from the one posted to PHP.net by rockinmusicgv
	// It is available under the readdir() entry in the PHP online manual
	function getAvatarGalleries($path, $select_name) {
		jimport('joomla.filesystem.folder');
		jimport('joomla.utilities.string');
		$folders = JFolder::folders($path,'.',true, true);
		foreach ($folders as $key => $folder) {
			$folder = substr($folder, strlen($path)+1);
			$folders[$key] = $folder;
		}

		$selected = JString::trim($this->gallery);
		$str =  "<select name=\" {$this->escape($select_name)}\" id=\"avatar_category_select\">\n";
		$str .=  "<option value=\"default\"";

		if ($selected == "") {
			$str .=  " selected=\"selected\"";
		}

		$str .=  ">" . JText::_ ( 'COM_KUNENA_DEFAULT_GALLERY' ) . "</option>\n";

		asort ( $folders );

		foreach ( $folders as $key => $val ) {
			$str .=  '<option value="' . urlencode($val) . '"';

			if ($selected == $val) {
				$str .=  " selected=\"selected\"";
			}

			$str .=  ">{$this->escape(JString::ucwords(str_replace('/', ' / ', $val)))}</option>\n";
		}

		$str .=  "</select>\n";
		return $str;
	}

	function displayEditUser() {
		$this->user = JFactory::getUser();

		// check to see if Frontend User Params have been enabled
		if (version_compare(JVERSION, '1.6','>') && JComponentHelper::getParams('com_users')->get('frontend_userparams')) {
			// Joomla 1.6
			$usersConfig = JComponentHelper::getParams( 'com_users' );
			if ($usersConfig->get('frontend_userparams', 0)) {
				$lang = JFactory::getLanguage();
				$lang->load('com_users', JPATH_ADMINISTRATOR);

				jimport( 'joomla.form.form' );
				JForm::addFormPath(JPATH_ROOT.'/components/com_users/models/forms');
				JForm::addFieldPath(JPATH_ROOT.'/components/com_users/models/fields');
				JPluginHelper::importPlugin('user');
				$registry = new JRegistry($this->user->params);
				$form = JForm::getInstance('com_users.profile','frontend');
				$data = new StdClass();
				$data->params = $registry->toArray();
				$dispatcher = JDispatcher::getInstance();
				$dispatcher->trigger('onContentPrepareForm', array($form, $data));
				$form->bind($data);
				// this get only the fields for user settings (template, editor, language...)
				$this->userparameters = $form->getFieldset('params');
			}
		} elseif (version_compare(JVERSION, '1.6','<') && JComponentHelper::getParams('com_users')->get('frontend_userparams')) {
			// Joomla 1.5
			$lang = JFactory::getLanguage();
			$lang->load('com_user', JPATH_SITE);
			$params = $this->user->getParameters(true);
			// Legacy template support:
			$this->userparams = $params->renderToArray();
			$i=0;
			// New templates use this:
			foreach ($this->userparams as $userparam) {
				$this->userparameters[$i]->input = $userparam[1];
				$this->userparameters[$i]->label = '<label for="params'.$userparam[5].'" title="'.$userparam[2].'">'.$userparam[0].'</label>';
				$i++;
			}
		}
		echo $this->loadTemplateFile('user');
	}

	function displayEditProfile() {
		$bd = @explode("-" , $this->profile->birthdate);

		$this->birthdate["year"] = $bd[0];
		$this->birthdate["month"] = $bd[1];
		$this->birthdate["day"] = $bd[2];

		$this->genders[] = JHTML::_('select.option', '0', JText::_('COM_KUNENA_MYPROFILE_GENDER_UNKNOWN'));
		$this->genders[] = JHTML::_('select.option', '1', JText::_('COM_KUNENA_MYPROFILE_GENDER_MALE'));
		$this->genders[] = JHTML::_('select.option', '2', JText::_('COM_KUNENA_MYPROFILE_GENDER_FEMALE'));

		$this->social = array('twitter', 'facebook', 'myspace', 'skype', 'linkedin', 'delicious',
			'friendfeed', 'digg', 'yim', 'aim', 'gtalk', 'icq', 'msn', 'blogspot', 'flickr', 'bebo');

		echo $this->loadTemplateFile('profile');
	}

	function displayEditAvatar() {
		if (!$this->editavatar) return;
		$this->gallery = JRequest::getVar('gallery', 'default');
		if ($this->gallery == 'default') {
			$this->gallery = '';
		}
		$path = JPATH_ROOT . '/media/kunena/avatars/gallery';
		$this->galleryurl = JURI::root(true) . '/media/kunena/avatars/gallery';
		$this->galleries = $this->getAvatarGalleries($path, 'gallery');
		$this->galleryimg = $this->getAvatarGallery($path . '/' . $this->gallery);

		$this->row(true);
		echo $this->loadTemplateFile('avatar');
	}

	function displayEditSettings() {
		$item = new StdClass();
		$item->name = 'messageordering';
		$item->label = JText::_('COM_KUNENA_USER_ORDER');
		$options = array();
		$options[] = JHTML::_('select.option', 0, JText::_('COM_KUNENA_USER_ORDER_KUNENA_GLOBAL'));
		$options[] = JHTML::_('select.option', 2, JText::_('COM_KUNENA_USER_ORDER_ASC'));
		$options[] = JHTML::_('select.option', 1, JText::_('COM_KUNENA_USER_ORDER_DESC'));
		$item->field = JHTML::_('select.genericlist', $options, 'messageordering', 'class="kinputbox" size="1"', 'value', 'text', $this->escape($this->profile->ordering), 'kmessageordering');
		$this->settings[] = $item;

		$item = new StdClass();
		$item->name = 'hidemail';
		$item->label = JText::_('COM_KUNENA_USER_HIDEEMAIL');
		$options = array();
		$options[] = JHTML::_('select.option', 0, JText::_('COM_KUNENA_NO'));
		$options[] = JHTML::_('select.option', 1, JText::_('COM_KUNENA_YES'));
		$item->field = JHTML::_('select.genericlist', $options, 'hidemail', 'class="kinputbox" size="1"', 'value', 'text', $this->escape($this->profile->hideEmail), 'khidemail');
		$this->settings[] = $item;

		$item = new StdClass();
		$item->name = 'showonline';
		$item->label = JText::_('COM_KUNENA_USER_SHOWONLINE');
		$options = array();
		$options[] = JHTML::_('select.option', 0, JText::_('COM_KUNENA_NO'));
		$options[] = JHTML::_('select.option', 1, JText::_('COM_KUNENA_YES'));
		$item->field = JHTML::_('select.genericlist', $options, 'showonline', 'class="kinputbox" size="1"', 'value', 'text', $this->escape($this->profile->showOnline), 'kshowonline');
		$this->settings[] = $item;

		$this->row(true);
		echo $this->loadTemplateFile('settings');
	}

	function displayUserList() {
		echo $this->loadTemplateFile('list');
	}

	function displayUserRow($user) {
		$this->user = KunenaFactory::getUser($user->id);
		if ($this->config->userlist_email && (!$this->user->hideEmail || $this->me->isModerator())) {
			$this->email = JHTML::_('email.cloak', $this->user->email);
		}
		$this->rank_image = $this->user->getRank (0, 'image');
		$this->rank_title = $this->user->getRank (0, 'title');
		echo $this->loadTemplateFile('row');
	}

	function getLastvisitdate($date) {
		if (version_compare(JVERSION, '1.6','>')) {
			// Joomla 1.6+
			$lastvisit = JHTML::_('date', $date, 'Y-m-d\TH:i:sP ');
		} else {
			// Joomla 1.5
			$lastvisit = JHTML::_('date', $date, '%Y-%m-%d %H:%M:%S');
		}

		return $lastvisit;
	}

	function canManageAttachments () {
		if ( $this->config->show_imgfiles_manage_profile ) {
			$params = array('file' => '1', 'image' => '1', 'orderby' => 'desc', 'limit' => '30');
			$this->userattachs = KunenaForumMessageAttachmentHelper::getByUserid($this->profile, $params);

			if ($this->userattachs) {
				 if ( $this->me->isModerator() || $this->profile->userid == $this->me->userid ) return true;
				 else return false;
			} else {
				return false;
			}
		}
	}

	function displayAttachments() {
		$this->title = JText::_('COM_KUNENA_MANAGE_ATTACHMENTS');
		$this->items = $this->userattachs;

		if (!empty($this->userattachs)) {
			// Preload messages
			$attach_mesids = array();
			foreach ($this->userattachs as $attach) {
				$attach_mesids[] = (int)$attach->mesid;
			}
			$messages = KunenaForumMessageHelper::getMessages($attach_mesids, 'none');
			// Preload topics
			$topic_ids = array();
			foreach ($messages as $message ) {
				$topic_ids[] = $message->thread;
			}
			$topics = KunenaForumTopicHelper::getTopics($topic_ids, 'none');
		}

		echo $this->loadTemplateFile('attachments');
	}

	protected function _prepareDocument($type){
		if ( $type == 'list' ) {

			$page = intval($this->state->get('list.start')/$this->state->get('list.limit'))+1;
			$pages = intval(($this->total-1)/$this->state->get('list.limit'))+1;

			$title = JText::_('COM_KUNENA_VIEW_USER_LIST'). " ({$page}/{$pages})";
			$this->setTitle($title);
			// TODO: set keywords and description

		} elseif ( $type == 'common' ) {

			$title = JText::sprintf('COM_KUNENA_VIEW_USER_DEFAULT', $this->profile->getName());
			$this->setTitle($title);
			// TODO: set keywords and description

		}
	}
}
