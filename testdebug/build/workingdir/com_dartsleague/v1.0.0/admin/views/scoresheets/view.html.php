<?php
/**
 * @version		$Id: view.html.php 549 2010-08-30 15:39:45Z SilverPC Consultants. $
 * @package		dl  scoresheets <**
 * @author		SilverPC Consultants.
 * @copyright	Copyright (c) 2009 - 2011 SilverPC Consultants. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view' );

class dlViewscoresheets extends JView
{
	function display($tpl = null) {

		jimport('joomla.filesystem.file');
		$mainframe = &JFactory::getApplication();
		$user = & JFactory::getUser();
		$option=JRequest::getCmd('option');
		$view=JRequest::getCmd('view');
		$limit = $mainframe->getUserStateFromRequest ( 'global.list.limit', 'limit', $mainframe->getCfg ( 'list_limit' ), 'int' );
		$limitstart = $mainframe->getUserStateFromRequest ( $option.$view.'.limitstart', 'limitstart', 0, 'int' );
		$filter_order = $mainframe->getUserStateFromRequest($option.$view.'filter_order','filter_order','i.id','cmd' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest($option.$view.'filter_order_Dir','filter_order_Dir','DESC','word' );
		$filter_trash = $mainframe->getUserStateFromRequest($option.$view.'filter_trash', 'filter_trash', 0, 'int');
		$filter_featured = $mainframe->getUserStateFromRequest($option.$view.'filter_featured', 'filter_featured', -1, 'int');
		$filter_category = $mainframe->getUserStateFromRequest($option.$view.'filter_category', 'filter_category',0, 'int');
		$filter_author = $mainframe->getUserStateFromRequest($option.$view.'filter_author', 'filter_author',0, 'int');
		$filter_state=$mainframe->getUserStateFromRequest( $option.$view.'filter_state','filter_state',-1,'int' );
		$search = $mainframe->getUserStateFromRequest($option.$view.'search','search','','string');
		$search = JString::strtolower ( $search );
		$params = &JComponentHelper::getParams('com_dl');

		$model = &$this->getModel();
		$scoresheet = $model->getData();
		$this->assignRef('rows',$scoresheet);

		$lists = array ();
		$lists ['search'] = $search;

		if (!$filter_order) {
			$filter_order = 'category';
		}
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;

		$filter_trash_options[] = JHTML::_('select.option', 0, JText::_('Current'));
		$filter_trash_options[] = JHTML::_('select.option', 1, JText::_('Trashed'));
		$lists['trash'] = JHTML::_('select.genericlist', $filter_trash_options, 'filter_trash', 'onchange="this.form.submit();"', 'value', 'text', $filter_trash);

		require_once(JPATH_COMPONENT.DS.'models'.DS.'categories.php');
		$categoriesModel= new dlModelCategories;
		$categories_option[]=JHTML::_('select.option', 0, JText::_('- Select category -'));
		$categories = $categoriesModel->categoriesTree(NULL, false, false);
		$categories_options=@array_merge($categories_option, $categories);
		$lists['categories'] = JHTML::_('select.genericlist', $categories_options, 'filter_category', 'onchange="this.form.submit();"', 'value', 'text', $filter_category);

		$lists['authors'] = JHTML::_('list.users', 'filter_author', $filter_author, true, 'onchange="this.form.submit();"' );

		$filter_state_options[] = JHTML::_('select.option', -1, JText::_('- Select publishing state -'));
		$filter_state_options[] = JHTML::_('select.option', 1, JText::_('Published'));
		$filter_state_options[] = JHTML::_('select.option', 0, JText::_('Unpublished'));
		$lists['state'] = JHTML::_('select.genericlist', $filter_state_options, 'filter_state', 'onchange="this.form.submit();"', 'value', 'text', $filter_state);

		$filter_featured_options[] = JHTML::_('select.option', -1, JText::_('- Select featured state -'));
		$filter_featured_options[] = JHTML::_('select.option', 1, JText::_('Featured'));
		$filter_featured_options[] = JHTML::_('select.option', 0, JText::_('Not featured'));
		$lists['featured'] = JHTML::_('select.genericlist', $filter_featured_options, 'filter_featured', 'onchange="this.form.submit();"', 'value', 'text', $filter_featured);

		$this->assignRef('lists',$lists);

		$total=$model->getTotal();
		jimport ( 'joomla.html.pagination' );

		$pageNav = new JPagination ( $total, $limitstart, $limit );
		$this->assignRef('page',$pageNav);

		JToolBarHelper::title( JText::_('Scoresheets'), 'dl.png' );
		if ($filter_trash == 1) {
			JToolBarHelper::custom('restore','restore.png','restore_f2.png','Restore', true);
			JToolBarHelper::deleteList('Are you sure you want to delete selected scoresheet?', 'remove', 'Delete');
		}
		else{
             // TODO: adjust menu options
			// JToolBarHelper::customX( 'featured', 'default.png', 'default_f2.png', 'Toggle featured state' );
			JToolBarHelper::publishList();
			JToolBarHelper::unpublishList();
			JToolBarHelper::customX( 'move', 'move.png', 'move_f2.png', 'Move');
			JToolBarHelper::customX( 'copy', 'copy.png', 'copy_f2.png', 'Copy' );
			JToolBarHelper::editList();
			JToolBarHelper::addNew();
			JToolBarHelper::trash('trash');

		}


		JToolBarHelper::preferences('com_dl', '500', '600');
                // new toolbar 
        JToolBarHelper::help( 'screen.scoresheets',true );  

		JSubMenuHelper::addEntry(JText::_('Dashboard'), 'index.php?option=com_dl');
		//JSubMenuHelper::addEntry(JText::_('Items'), 'index.php?option=com_dl&view=items');
						// new menu 
		JSubMenuHelper::addEntry(JText::_('Scoresheets'), 'index.php?option=com_dl&view=scoresheets', true);
                                                // new menu 
        JSubMenuHelper::addEntry(JText::_('Venues'), 'index.php?option=com_dl&view=venues');
                                                        // new menu 
        JSubMenuHelper::addEntry(JText::_('Players'), 'index.php?option=com_dl&view=players');
                                                                        // new menu 
        JSubMenuHelper::addEntry(JText::_('Seasons'), 'index.php?option=com_dl&view=seasons');
                                                                                // new menu 
        JSubMenuHelper::addEntry(JText::_('Teams'), 'index.php?option=com_dl&view=teams');
                                                                              // new menu 
        JSubMenuHelper::addEntry(JText::_('Schedules'), 'index.php?option=com_dl&view=schedules');
                                                                                // new menu 
        JSubMenuHelper::addEntry(JText::_('Trophies'), 'index.php?option=com_dl&view=trophies');
		//JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_dl&view=categories');
		//if( !$params->get('lockTags') || $user->gid>23)
		//	JSubMenuHelper::addEntry(JText::_('Tags'), 'index.php?option=com_dl&view=tags');
		//JSubMenuHelper::addEntry(JText::_('Comments'), 'index.php?option=com_dl&view=comments');

		if ($user->gid > 23) {
			//JSubMenuHelper::addEntry(JText::_('Users'), 'index.php?option=com_dl&view=users');
			//JSubMenuHelper::addEntry(JText::_('User Groups'), 'index.php?option=com_dl&view=userGroups');
			//JSubMenuHelper::addEntry(JText::_('Extra Fields'), 'index.php?option=com_dl&view=extraFields');
			//JSubMenuHelper::addEntry(JText::_('Extra Field Groups'), 'index.php?option=com_dl&view=extraFieldsGroups');
			JSubMenuHelper::addEntry(JText::_('Information'), 'index.php?option=com_dl&view=info');
		}

		$this->assignRef('filter_featured',$filter_featured);
		$this->assignRef('filter_trash',$filter_trash);
		parent::display($tpl);
	}

	function move(){

		$mainframe = &JFactory::getApplication();
		JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');
		$cid = JRequest::getVar('cid');

		foreach ($cid as $id) {
			$row = & JTable::getInstance('dlItem', 'Table');
			$row->load($id);
			$rows[]=$row;
		}

		require_once(JPATH_COMPONENT.DS.'models'.DS.'categories.php');
		$categoriesModel= new dlModelCategories;
		$categories = $categoriesModel->categoriesTree();
		$lists['categories'] = JHTML::_('select.genericlist', $categories, 'category', 'class="inputbox" size="8"', 'value', 'text');

		$this->assignRef('rows',$rows);
		$this->assignRef('lists',$lists);

		JToolBarHelper::title( JText::_('Move scoresheet'), 'dl.png' );

		JToolBarHelper::custom('saveMove','save.png','save_f2.png','Save', false);
		JToolBarHelper::cancel();
                                        // new toolbar
        JToolBarHelper::help( 'screen.scoresheets',true ); 

		parent::display();
	}

}
