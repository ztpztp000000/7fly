<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
if(version_compare(JVERSION, '3.0', 'ge')){
	JHtml::_('bootstrap.tooltip');
}

function get_cur_money()
{
	require_once JPATH_ROOT.'/libraries/daxian/api.php';
	$config = JFactory::getConfig();
    $ms_api = new MainSiteApi($config->get('api_url'), JApplication::getCfg('site_id'), JApplication::getCfg('site_key'));
    $cur_money = $ms_api->get_user_vc($_SESSION['__default']['user']->username);
                
	return $cur_money;
}

function get_recently_server()
{
	require_once JPATH_ROOT.'/libraries/daxian/api.php';
    $config = JFactory::getConfig();
    $ms_api = new MainSiteApi($config->get('api_url'), JApplication::getCfg('site_id'), JApplication::getCfg('site_key'));
	return $ms_api->get_recently_server($_SESSION['__default']['user']->username);
	
	//var_dump($gamelog);exit;
}

?>
<?php if ($type == 'logout') : ?>
<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form" class="form-vertical">
<?php if ($params->get('greeting')) : ?>
	<div class="login-greeting">
	<li><?php echo JText::sprintf('MOD_LOGIN_HINAME', htmlspecialchars($user->get('username'))); ?></li>
	<li><?php echo JText::sprintf('MOD_LOGIN_CURMONEY', get_cur_money()); ?></li>
	<li><?php echo JText::sprintf('MOD_LOGIN_GENERALIZE', 0); ?></li>
	<li><?php echo JText::sprintf('MOD_RECENTLY_GAME'); ?></li>
	<?php $game_log = get_recently_server(); ?>
	<?php if(1 != $game_log['ret']) : ?>
        <li><?php echo $game_log['msg']; ?></li>
    <?php else : ?>
	    <?php foreach ($game_log['server'] as $server) :  ?>
	       <div>
	       <ul>
	       <?php echo $server['game_name']; ?>
	       <?php echo $server['server_name']; ?>
	       <a href="/?option=com_playgame&sid=<?php echo $server['server_id']; ?>" target="_blank">进入游戏</a>
	       </ul>
	       </div>
	    <?php endforeach;?>
	<?php endif; ?>
	</div>
<?php endif; ?>
	<div class="logout-button">
		<input type="submit" name="Submit" class="btn btn-primary" value="<?php echo JText::_('JLOGOUT'); ?>" />
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.logout" />
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
<?php else : ?>
<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form" >
	<?php if ($params->get('pretext')): ?>
		<div class="pretext">
		<p><?php echo $params->get('pretext'); ?></p>
		</div>
	<?php endif; ?>
	<fieldset class="userdata">
	<div id="form-login-username" class="control-group">
		<div class="controls">
		<?php if (!$params->get('usetext')) : ?>
			<div class="input-prepend">
				<span class="add-on"><i class="icon-user tip" title="<?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?>"></i></span>
				<input id="modlgn-username" type="text" name="username" class="input" tabindex="0" size="18" placeholder="<?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?>" />
			</div>
		<?php else: ?>
			<label for="modlgn-username"><?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?></label>
			<input id="modlgn-username" type="text" name="username" class="input-small" tabindex="0" size="18" placeholder="<?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?>" />
		<?php endif; ?>
		</div>
	</div>
	<div id="form-login-password" class="control-group">
		<div class="controls">
		<?php if (!$params->get('usetext')) : ?>
			<div class="input-prepend">
				<span class="add-on"><i class="icon-lock tip" title="<?php echo JText::_('JGLOBAL_PASSWORD') ?>"></i></span>
				<input id="modlgn-passwd" type="password" name="password" class="input" tabindex="0" size="18" placeholder="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" />
			</div>
		<?php else: ?>
			<label for="modlgn-passwd"><?php echo JText::_('JGLOBAL_PASSWORD') ?></label>
			<input id="modlgn-passwd" type="password" name="password" class="input-small" tabindex="0" size="18" placeholder="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" />
		<?php endif; ?>
		</div>
	</div>
	<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
	<div id="form-login-remember" class="control-group">
		<label for="modlgn-remember" class="checkbox"><input id="modlgn-remember" type="checkbox" name="remember" class="inputbox" value="yes"/> <?php echo JText::_('MOD_LOGIN_REMEMBER_ME') ?></label>
	</div>
	<?php endif; ?>
	<div class="control-group">
		<input type="submit" name="Submit" class="btn btn-primary" value="<?php echo JText::_('JLOGIN') ?>" />
	</div>

	<?php
		$usersConfig = JComponentHelper::getParams('com_users');
		if ($usersConfig->get('allowUserRegistration')) : ?>
		<ul class="unstyled">
			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">
				<?php echo JText::_('MOD_LOGIN_REGISTER'); ?> <span class="icon-arrow-right"></span></a>
			</li>
			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
				  <?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_USERNAME'); ?></a>
			</li>
			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>"><?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?></a>
			</li>

		</ul>
	<?php endif; ?>

	<input type="hidden" name="option" value="com_users" />
	<input type="hidden" name="task" value="user.login" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />
	<?php echo JHtml::_('form.token'); ?>
	</fieldset>
	<?php if ($params->get('posttext')): ?>
		<div class="posttext">
		<p><?php echo $params->get('posttext'); ?></p>
		</div>
	<?php endif; ?>
</form>
<?php endif; ?>
