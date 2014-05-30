<?php
/**
 * @version		$Id: view.html.php 549 2010-08-30 15:39:45Z SilverPC Consultants. $
 * @package		dl
 * @author		SilverPC Consultants.
 * @copyright	Copyright (c) 2009 - 2011 SilverPC Consultants. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class dlViewUser extends JView
{

	function display($tpl = null) {
	
		JRequest::setVar('hidemainmenu', 1);
		$model = & $this->getModel();
		$user = $model->getData();
		JFilterOutput::objectHTMLSafe( $user );
		if (JRequest::getInt('userID')) {
			$joomlaUser = & JUser::getInstance(JRequest::getInt('userID'));
		}
		else {
			$joomlaUser = & JUser::getInstance($user->userID);
		}
	
		$user->name = $joomlaUser->name;
		$user->userID = $joomlaUser->id;
		$this->assignRef('row', $user);
	
		$wysiwyg = & JFactory::getEditor();
		$editor = $wysiwyg->display('description', $user->description, '100%', '250', '40', '5', false);
		$this->assignRef('editor', $editor);
	
		$lists = array ();
		$genderOptions[] = JHTML::_('select.option', 'm', JText::_('Male'));
		$genderOptions[] = JHTML::_('select.option', 'f', JText::_('Female'));
		$lists['gender'] = JHTML::_('select.radiolist', $genderOptions, 'gender','','value','text',$user->gender);
		
		$userGroupOptions=$model->getUserGroups();
		$lists['userGroup']=JHTML::_('select.genericlist', $userGroupOptions, 'group', 'class="inputbox"', 'id', 'name', $user->group);
		
		$this->assignRef('lists', $lists);
	
		$params = & JComponentHelper::getParams('com_dl');
		$this->assignRef('params', $params);
		
		JPluginHelper::importPlugin ( 'dl' );
		$dispatcher = &JDispatcher::getInstance ();
		$dlPlugins=$dispatcher->trigger('onRenderAdminForm', array (&$user, 'user' ) );
		$this->assignRef('dlPlugins', $dlPlugins);
	
		JToolBarHelper::title(JText::_('User'), 'dl.png');
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
                                // new toolbar
        JToolBarHelper::help( 'screen.user',true ); 
		$toolbar=JToolBar::getInstance('toolbar');
		$toolbar->prependButton('Link', 'edit', 'Edit Joomla User', JURI::base().'index.php?option=com_users&view=user&task=edit&cid[]='.$user->userID);
	
		parent::display($tpl);
	}

}
