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
<Div style="width:310px;height:310px;background:#333333; border-left:1px solid #666666;border-right:1px solid #666666;">
<div style="padding-top:30px;color:#999999; text-align:right; border-bottom:1px solid #666666; overflow:hidden;padding-right:10px;">
<font color="#CCCCCC"><?php echo JText::sprintf('MOD_LOGIN_HINAME', htmlspecialchars($user->get('username'))); ?></font>
</div>
<div style="padding-left:30px;padding-right:30px; color:#ccc; list-style:none;">

	<li style="border-bottom:1px solid #666;height:30px;line-height:30px;"><?php echo JText::sprintf('MOD_LOGIN_CURMONEY', get_cur_money()); ?>
	<font style="padding-left:20px;">我的推广：<a href="/?option=com_generalize"><?php echo JText::sprintf('MOD_LOGIN_GENERALIZE'); ?></a></font></li>
	<li style="border-bottom:1px solid #999;height:30px;line-height:30px;"><?php echo JText::sprintf('MOD_RECENTLY_GAME'); ?></li>

	<?php $game_log = get_recently_server(); ?>
	<?php if(1 != $game_log['ret']) : ?>
        <li><?php echo $game_log['msg']; ?></li>
    <?php else : ?>
<div class="login_in_zj">
	    <?php foreach ($game_log['server'] as $server) :  ?>
		<div class="login_in_gamelist">
	      <li><?php echo $server['game_name']; ?>  <font style="margin-left:25px;"><?php echo $server['server_name']; ?> </font> <font style="margin-left:25px;"><a href="/?option=com_playgame&sid=<?php echo $server['server_id']; ?>" target="_blank">进入游戏</a></font></li>
		 </div>
	    <?php endforeach;?>
</div>
	<?php endif; ?>
	</div>
<?php endif; ?>
	<div class="btn_logt">
		<input type="submit" name="Submit" class="btn_reg"  style="width:93px;" value="<?php echo JText::_('JLOGOUT'); ?>" />
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.logout" />
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</Div>


</form>

<?php else : ?>
<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form" >
<Div style="width:310px;height:310px;background:#333333; border-left:1px solid #666666;border-right:1px solid #666666;">
	<div style="padding-top:30px;color:#999999; text-align:right; border-bottom:1px solid #666666; overflow:hidden;padding-right:10px;">
			<?php if ($params->get('pretext')): ?>
			<?php echo $params->get('pretext'); ?>
			<?php endif; ?>
	</div>
	<div style="padding-left:30px;margin-top:30px; color:#999;">
	<?php if (!$params->get('usetext')) : ?>

				账户：<input style="height:16px; border:1px solid #666666;" id="modlgn-username" type="text" name="username" class="input" tabindex="0" size="18" placeholder="<?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?>" />
		<?php endif; ?>
	</div>
	<div style="padding-left:30px;color:#999;">
		<?php if (!$params->get('usetext')) : ?>

				密码：<input style="height:16px; border:1px solid #666666;" id="modlgn-passwd" type="password" name="password" class="input" tabindex="0" size="18" placeholder="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" />

		<?php endif; ?>
	</div>

<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
	<div id="form-login-remember" style="padding-left:30px;color:#999999;">
		<label for="modlgn-remember" class="checkbox"><input id="modlgn-remember" type="checkbox" name="remember" class="inputbox" value="yes"/> <?php echo JText::_('MOD_LOGIN_REMEMBER_ME') ?>
		
		</label>
	</div>
	<?php endif; ?>
<div class="btn_list">
<li>
<input type="submit" name="Submit" class="btn_login" style="width:138px;" value="<?php echo JText::_('JLOGIN') ?>" /></li>
<li><a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>" style="width:93px;" class="btn_reg">
			<?php echo JText::_('MOD_LOGIN_REGISTER'); ?> <span class="icon-arrow-right"></span></a></li>
</div>

	<?php
		$usersConfig = JComponentHelper::getParams('com_users');
		if ($usersConfig->get('allowUserRegistration')) : ?>
		<ul class="btn_find">
			<li>
				<a style="color:#999999;" href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>"><?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?></a>
			</li>
			<li style="padding-left:20px;">
				<a  style="color:#999999;" href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
				  <?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_USERNAME'); ?></a>
			</li>
		</ul>
	<?php endif; ?>

	<input type="hidden" name="option" value="com_users" />
	<input type="hidden" name="task" value="user.login" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />
	<?php echo JHtml::_('form.token'); ?>
	<?php if ($params->get('posttext')): ?>
		<div class="posttext">
		<p><?php echo $params->get('posttext'); ?></p>
		</div>
	<?php endif; ?>
</Div>
</form>
<?php endif; ?>
