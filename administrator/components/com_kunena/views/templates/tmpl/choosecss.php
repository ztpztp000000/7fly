<?php
/**
 * Kunena Component
 * @package Kunena.Administrator.Template
 * @subpackage Templates
 *
 * @copyright (C) 2008 - 2012 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/
defined ( '_JEXEC' ) or die ();

$document = JFactory::getDocument();
$document->addStyleSheet ( JURI::base(true).'/components/com_kunena/media/css/admin.css' );
if (JFactory::getLanguage()->isRTL()) $document->addStyleSheet ( JURI::base().'components/com_kunena/media/css/admin.rtl.css' );
?>
<div id="kadmin">
	<div class="kadmin-left"><?php include KPATH_ADMIN.'/views/common/tmpl/menu.php'; ?></div>
	<div class="kadmin-right">
	<div class="kadmin-functitle icon-editcss"><?php echo JText::_('COM_KUNENA_A_TEMPLATE_MANAGER_CHOOSE_CSS_TEMPLATE'); ?></div>
		<form action="<?php echo KunenaRoute::_('administrator/index.php?option=com_kunena') ?>" method="post" id="adminForm" name="adminForm">
		<input type="hidden" name="view" value="templates" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="id" value="<?php echo $this->escape($this->templatename); ?>" />
		<input type="hidden" name="cid[]" value="<?php echo $this->escape($this->templatename); ?>" />
		<input type="hidden" name="boxchecked" value="0" />

		<table>
		<tr>
			<td width="220"><span class="componentheading">&nbsp;</span></td>
		</tr>
		</table>
		<table class="adminlist">
		<tr>
			<th width="1%" align="left"> </th>
			<th width="85%" align="left"><?php echo $this->escape($this->dir); ?></th>
			<th width="10%"><?php echo JText::_( 'COM_KUNENA_A_TEMPLATE_MANAGER_PARAMSWRITABLE' ); ?>/<?php echo JText::_( 'COM_KUNENA_A_TEMPLATE_MANAGER_PARAMSUNWRITABLE' ); ?></th>
		</tr>
		<?php
		$k = 0;
		foreach( $this->files as $id => $file ) {
			?>
			<tr class="<?php echo 'row'. $k; ?>">
				<td width="5%"><input type="radio" id="cb<?php echo $id;?>" name="filename" value="<?php echo $this->escape($file); ?>" onclick="isChecked(this.checked);" /></td>
				<td width="85%"><?php echo $this->escape($file); ?></td>
				<td width="10%"><?php echo is_writable($this->dir.'/'.$file) ? '<font color="green"> '. JText::_( 'COM_KUNENA_A_TEMPLATE_MANAGER_PARAMSWRITABLE' ) .'</font>' : '<font color="red"> '. JText::_( 'COM_KUNENA_A_TEMPLATE_MANAGER_PARAMSUNWRITABLE' ) .'</font>' ?></td>
			</tr>
		<?php
			$k = 1 - $k; } ?>
		</table>
	</form>
	</div>
	<div class="kadmin-footer">
		<?php echo KunenaVersion::getLongVersionHTML (); ?>
	</div>
</div>