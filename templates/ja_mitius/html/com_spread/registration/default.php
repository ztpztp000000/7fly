<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.6
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
//var_dump($this->_bk_img);exit;
?>
<div style="background:url(<?php echo $this->_bk_img; ?>) no-repeat center top; height:700px;">

	<form id="member-registration" action="<?php echo JRoute::_('index.php?option=com_users&task=registration.register&s='.$this->_sid.'&u='.$this->_uid); ?>" method="post" class="form-validate">
<?php foreach ($this->form->getFieldsets() as $fieldset): // Iterate through the form fieldsets and display each one.?>
	<?php $fields = $this->form->getFieldset($fieldset->name);?>
	<?php if (count($fields)):?>
		<div style="width:260px; margin-left:350px;padding-top:300px;">
			<ul>
		<?php foreach($fields as $field):// Iterate through the fields in the set and display them.?>
			<?php if ($field->hidden):// If the field is hidden, just display the input.?>
				<?php echo $field->input;?>
			<?php else:?>
					
				<li style="list-style-type:none;"><?php echo ($field->type!='Spacer') ? $field->input : "&#160;"; ?></li>
			<?php endif;?>
		<?php endforeach;?>
			</ul>
		</div>
	<?php endif;?>
<?php endforeach;?>
		<div style="margin-left:380px;">
			<button type="submit" style="width:157px;height:59px;background:url(/templates/ja_mitius/images/tg_btn.gif); border:0;"></button>
			<input type="hidden" name="option" value="com_users" />
			<input type="hidden" name="task" value="registration.register" />
			<?php echo JHtml::_('form.token');?>
		</div>
	</form>
</div>
