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

class dlViewUserGroups extends JView
{

	function display($tpl = null) {

		$mainframe = &JFactory::getApplication();
		$user = & JFactory::getUser();
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd('view');
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($option.$view.'.limitstart', 'limitstart', 0, 'int');
		$filter_order = $mainframe->getUserStateFromRequest($option.$view.'filter_order', 'filter_order', '', 'cmd');
		$filter_order_Dir = $mainframe->getUserStateFromRequest($option.$view.'filter_order_Dir', 'filter_order_Dir', '', 'word');

		$model = & $this->getModel();
		$userGroups = $model->getData();

		$this->assignRef('rows', $userGroups);
		$total = $model->getTotal();

		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total, $limitstart, $limit);
		$this->assignRef('page', $pageNav);

		$lists = array ();

		$lists['order_Dir'] = $filter_order_Dir;
		$lists['order'] = $filter_order;

		$this->assignRef('lists', $lists);

		JToolBarHelper::title(JText::_('User Groups'), 'dl.png');

		JToolBarHelper::deleteList('Are you sure you want to delete selected groups?', 'remove', 'Delete');
		JToolBarHelper::editList();
		JToolBarHelper::addNew();
		JToolBarHelper::preferences('com_dl', '500', '600');

		JSubMenuHelper::addEntry(JText::_('Dashboard'), 'index.php?option=com_dl');
		JSubMenuHelper::addEntry(JText::_('Items'), 'index.php?option=com_dl&view=items');
						// new menu 
		JSubMenuHelper::addEntry(JText::_('Scoresheets'), 'index.php?option=com_dl&view=scoresheets');
                                                // new menu 
        JSubMenuHelper::addEntry(JText::_('Venues'), 'index.php?option=com_dl&view=venues');
                                                        // new menu 
        JSubMenuHelper::addEntry(JText::_('Players'), 'index.php?option=com_dl&view=players');
                                                                        // new menu 
        JSubMenuHelper::addEntry(JText::_('Seasons'), 'index.php?option=com_dl&view=seasons');
                                                                        // new menu 
        JSubMenuHelper::addEntry(JText::_('Teams'), 'index.php?option=com_dl&view=teams');
		JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_dl&view=categories');
		JSubMenuHelper::addEntry(JText::_('Tags'), 'index.php?option=com_dl&view=tags');
		JSubMenuHelper::addEntry(JText::_('Comments'), 'index.php?option=com_dl&view=comments');
		JSubMenuHelper::addEntry(JText::_('Users'), 'index.php?option=com_dl&view=users');
		JSubMenuHelper::addEntry(JText::_('User Groups'), 'index.php?option=com_dl&view=userGroups', true);

		if ($user->gid > 23) {
			JSubMenuHelper::addEntry(JText::_('Extra Fields'), 'index.php?option=com_dl&view=extraFields');
			JSubMenuHelper::addEntry(JText::_('Extra Field Groups'), 'index.php?option=com_dl&view=extraFieldsGroups');
		}

		JSubMenuHelper::addEntry(JText::_('Information'), 'index.php?option=com_dl&view=info');

		parent::display($tpl);
	}

}
