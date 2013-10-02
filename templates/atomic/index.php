<?php
/**
 * @package		Joomla.Site
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/* The following line loads the MooTools JavaScript Library */
JHtml::_('behavior.framework', true);

/* The following line gets the application object for things like displaying the site name */
$app = JFactory::getApplication();

$game_id = JRequest::getVar('gid');
if (!empty($game_id))
{
    $db = JFactory::getDBO();
    $query = 'SELECT bk_img,bk_color '.' FROM #__games where guid='.$game_id;
    $db->setQuery($query);
    $site_info = $db->loadObjectList();
    
    $_SESSION['__default']['game_site']->gid = $game_id;
    $_SESSION['__default']['game_site']->bk_img = $site_info[0]->bk_img;
    $_SESSION['__default']['game_site']->bk_color = $site_info[0]->bk_color;
}    
?>
<?php echo '<?'; ?>xml version="1.0" encoding="<?php echo $this->_charset ?>"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
	<head>
		<jdoc:include type="head" />

		<!-- The following five lines load the Blueprint CSS Framework (http://blueprintcss.org). If you don't want to use this framework, delete these lines. -->
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/screen.css" type="text/css" media="screen, projection" />

		<!-- The following line loads the template CSS file located in the template folder. -->
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/template.css" type="text/css" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	</head>
	<body style="background:url(<?php echo $_SESSION['__default']['game_site']->bk_img; ?>) no-repeat center top; background-color:<?php echo $_SESSION['__default']['game_site']->bk_color; ?>;">
	
	<div class="headerline">
		<div class="main">
			<div class="span6 append-1">
			<a href="/"><img src="/templates/atomic/images/weblogo.png" /></a>
			</div>
			<div class="span-13">
			<jdoc:include type="modules" name="web-1" />
			</div>		
			<div class="span6 fav"><jdoc:include type="modules" name="web-2" /></div>
		</div>
	</div>



		<?php if($this->countModules('atomic-topmenu') or $this->countModules('position-2') ) : ?>
			<div class="mainnav"><jdoc:include type="modules" name="atomic-topmenu" style="nav" /></div>
		<?php endif; ?>
		<p class="margin-top"></p>
		<div class="container">		
			<?php if($this->countModules('position-left')) : ?>
			 	<div class="span-6 ">
					<jdoc:include type="modules" name="position-left" />

	        	</div>
	        <?php endif; ?>
			
	        <?php if($this->countModules('position-9') or $this->countModules('position-10')) : ?>

				<div class="span-17 first">
				<jdoc:include type="component" />
				<jdoc:include type="message" />
				
				<div class="span-12">
				<jdoc:include type="modules" name="position-9" style="position-9" />
				</div>
				<div class="span-6 right">
				<jdoc:include type="modules" name="position-10" style="position-10" />
				</div>
				</div>
			<?php endif; ?>
			<?php if($this->countModules('position-11') or $this->countModules('position-12')) : ?>

				<div class="span_main">
					<table>
						<tr>
						<td><jdoc:include type="modules" name="position-11" style="position-11" /></td>
						<td><jdoc:include type="modules" name="position-12" style="position-12" /></td>
						</tr>
					</table>
				</div>
			<?php endif; ?>
			</div>
			<?php if($this->countModules('position-bottom') || $this->countModules('position-bottom1')): ?>
				<div class="span-23 last">
					<jdoc:include type="modules" name="position-bottom" style="sidebar" />
					<jdoc:include type="modules" name="position-bottom1" style="sidebar" />

				</div>
			<?php endif; ?>
<div class="gamesite_bottom">
	<div class="main">
		<div class="link_list">
			<li>关于我们</li><li>商务合作</li><li>客服中心</li><li>家长监护</li><li>用户协议</li>
		</div>
		<div class="bq_info">
			<li>增值电信业务经营许可证 </li>
			<li>网络文化经营许可证</li>
		</div>
		<div class="copy_right">
			<li>上海渣富网络科技有限公司</li><li>copyright 2008 - 2013 版权所有</li>
		</div>
	</div>
</div>
		</div>
	</body>
</html>
