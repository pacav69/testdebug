<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component dartsleague Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined( '_JEXEC' ) or die();
jimport( 'joomla.application.component.view' );
dartsleagueimport( 'dartsleague.render.renderinfo' );

class dartsleagueCpViewdartsleaguecp extends JView
{
	public function display($tpl = null) {
		
		$tmpl = array();
		JHtml::stylesheet( 'administrator/components/com_dartsleague/assets/dartsleague.css' );
		//JHTML::_('behavior.tooltip');
		$tmpl['version'] = dartsleagueRenderInfo::getdartsleagueVersion();
		
		$this->assignRef('tmpl',	$tmpl);
		$this->addToolbar();
		parent::display($tpl);
	}
	
	protected function addToolbar() {
		require_once JPATH_COMPONENT.DS.'helpers'.DS.'com_dartsleague.php';

		$state	= $this->get('State');
		$canDo	= PhocaGalleryCpHelper::getActions();
		JToolBarHelper::title( JText::_( 'COM_DARTSLEAGUE_DL_CONTROL_PANEL' ), 'dartsleague.png' );
		
		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_dartsleague');
			JToolBarHelper::divider();
		}
		
		JToolBarHelper::help( 'screen.dartsleague', true );
	}
}
?>