<?php
/**
 * Kunena Component
 * @package Kunena.Administrator
 * @subpackage Views
 *
 * @copyright (C) 2008 - 2012 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/
defined ( '_JEXEC' ) or die ();

/**
 * Templates view for Kunena backend
 */
class KunenaAdminViewTemplates extends KunenaView {
	function displayDefault() {
		$this->setToolBarDefault();
		$this->templates = $this->get('templates');
		$this->navigation = $this->get ( 'AdminNavigation' );
		$this->display();
	}

	function displayAdd () {
		$this->setToolBarAdd();
		$this->display ();
	}

	function displayEdit () {
		$this->setToolBarEdit();
		$this->params = $this->get('editparams');
		$this->details = $this->get('templatedetails');
		$this->templatename = $this->app->getUserState ( 'kunena.edit.template');
		$template = KunenaTemplate::getInstance($this->templatename);
		$template->initializeBackend();

		$this->templatefile = KPATH_SITE.'/template/'.$this->templatename.'/params.ini';

		if ( !JFile::exists($this->templatefile))  {
			$ourFileHandle = @fopen($this->templatefile, 'w');
			if ($ourFileHandle) fclose($ourFileHandle);
		}

		$this->display();
	}

	function displayChoosecss() {
		$this->setToolBarChoosecss();
		$this->templatename = $this->app->getUserState ( 'kunena.edit.template');
		$this->dir = KPATH_SITE.'/template/'.$this->templatename.'/css';
		jimport('joomla.filesystem.folder');
		$this->files = JFolder::files($this->dir, '\.css$', false, false);
		$this->display();
	}

	function displayEditcss() {
		$this->setToolBarEditcss();
		$this->templatename = $this->app->getUserState ( 'kunena.editcss.tmpl');
		$this->filename = $this->app->getUserState ( 'kunena.editcss.filename');
		$this->content = $this->get ( 'FileContentParsed');
		$this->css_path = KPATH_SITE.'/template/'.$this->templatename.'/css/'.$this->filename;
		$this->ftp = $this->get('FTPcredentials');
		$this->display();
	}

	protected function setToolBarDefault() {
		JToolBarHelper::title ( JText::_('COM_KUNENA'), 'kunena.png' );
		JToolBarHelper::spacer();
		JToolBarHelper::custom('publish', 'default.png', 'default_f2.png', 'COM_KUNENA_A_TEMPLATE_MANAGER_DEFAULT');
		JToolBarHelper::spacer();
		JToolBarHelper::addNew('add', 'COM_KUNENA_A_TEMPLATE_MANAGER_ADD');
		JToolBarHelper::spacer();
		JToolBarHelper::custom('edit', 'edit.png', 'edit_f2.png', 'COM_KUNENA_EDIT');
		JToolBarHelper::spacer();
		JToolBarHelper::custom('uninstall', 'delete.png','delete_f2.png', 'COM_KUNENA_A_TEMPLATE_MANAGER_UNINSTALL');
		JToolBarHelper::spacer();
	}

	protected function setToolBarAdd() {
		JToolBarHelper::title ( JText::_('COM_KUNENA'), 'kunena.png' );
		JToolBarHelper::spacer();
	}

	protected function setToolBarEdit() {
		JToolBarHelper::title ( JText::_('COM_KUNENA'), 'kunena.png' );
		JToolBarHelper::spacer();
		JToolBarHelper::apply('apply');
		JToolBarHelper::spacer();
		JToolBarHelper::save('save');
		JToolBarHelper::spacer();
		JToolBarHelper::custom('choosecss', 'css.png','css_f2.png', 'COM_KUNENA_A_TEMPLATE_MANAGER_EDITCSS', false, false );
		JToolBarHelper::spacer();
		JToolBarHelper::cancel('templates');
		JToolBarHelper::spacer();
	}

	protected function setToolBarChoosecss() {
		JToolBarHelper::title ( JText::_('COM_KUNENA'), 'kunena.png' );
		JToolBarHelper::spacer();
		JToolBarHelper::custom('editcss', 'css.png', 'css_f2.png', 'COM_KUNENA_A_TEMPLATE_MANAGER_EDITCSS');
		JToolBarHelper::spacer();
		JToolBarHelper::spacer();
		JToolBarHelper::cancel('templates');
		JToolBarHelper::spacer();
	}

	protected function setToolBarEditcss() {
		JToolBarHelper::title ( JText::_('COM_KUNENA'), 'kunena.png' );
		JToolBarHelper::spacer();
		JToolBarHelper::save('savecss');
		JToolBarHelper::spacer();
		JToolBarHelper::spacer();
		JToolBarHelper::cancel('templates');
		JToolBarHelper::spacer();
	}
}
