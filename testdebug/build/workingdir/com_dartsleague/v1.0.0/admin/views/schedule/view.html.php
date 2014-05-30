<?php
/**
 * @version		$Id: view.html.php 549 2010-08-30 15:39:45Z SilverPC Consultants. $
 * @package		dartsleague
 * @author		SilverPC Consultants.
 * @copyright	Copyright (c) 2009 - 2011 SilverPC Consultants. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class dartsleagueViewCategory extends JView
{

	function display($tpl = null)
	{
	
		JRequest::setVar('hidemainmenu', 1);
		$model = & $this->getModel();
		$category = $model->getData();
		JFilterOutput::objectHTMLSafe( $category );
		if(!$category->id)
			$category->published=1;
		$this->assignRef('row', $category);
		$wysiwyg = & JFactory::getEditor();
		$editor = $wysiwyg->display('description', $category->description, '100%', '250', '40', '5', array('pagebreak', 'readmore'));
		$this->assignRef('editor', $editor);
		$lists = array ();
		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $category->published);
		$lists['access'] = JHTML::_('list.accesslevel', $category);
		$query = 'SELECT ordering AS value, name AS text FROM #__dartsleague_categories ORDER BY ordering';
		$lists['ordering'] = JHTML::_('list.specificordering', $category, $category->id, $query);
		$categories[] = JHTML::_('select.option', '0', JText::_('-- None --'));
		
		require_once (JPATH_COMPONENT.DS.'models'.DS.'categories.php');
		$categoriesModel = new dartsleagueModelCategories;
		$tree=$categoriesModel->categoriesTree($category);
		$categories = array_merge($categories,$tree);
		$lists['parent'] = JHTML::_('select.genericlist', $categories, 'parent', 'class="inputbox"', 'value', 'text', $category->parent);
		
		require_once (JPATH_COMPONENT.DS.'models'.DS.'extrafields.php');
		$extraFieldsModel = new dartsleagueModelExtraFields;
		$groups = $extraFieldsModel->getGroups();
		$group [] = JHTML::_ ( 'select.option', '0', JText::_ ( '-- None --' ), 'id', 'name' );
		$group = array_merge ( $group, $groups );
		$lists['extraFieldsGroup'] = JHTML::_ ( 'select.genericlist', $group, 'extraFieldsGroup', 'class="inputbox" size="1" ', 'id', 'name', $category->extraFieldsGroup );
	
	
		JPluginHelper::importPlugin ( 'dartsleague' );
		$dispatcher = &JDispatcher::getInstance ();
		$dartsleaguePlugins=$dispatcher->trigger('onRenderAdminForm', array (&$category, 'category' ) );
		$this->assignRef('dartsleaguePlugins', $dartsleaguePlugins);
	
	
		$params = & JComponentHelper::getParams('com_dartsleague');
		$this->assignRef('params', $params);
		
		$form = new JParameter('', JPATH_COMPONENT.DS.'models'.DS.'category.xml');
		$form->loadINI($category->params);
		$this->assignRef('form', $form);
		
		$categories[0] = JHTML::_('select.option', '0', JText::_('-- None --'));
		$lists['inheritFrom'] = JHTML::_('select.genericlist', $categories, 'params[inheritFrom]', 'class="inputbox"', 'value', 'text', $form->get('inheritFrom'));
		
		$this->assignRef('lists', $lists);
		(JRequest::getInt('cid'))? $title = JText::_('Edit Category') : $title = JText::_('Add Category');
		JToolBarHelper::title($title, 'dartsleague.png');
		JToolBarHelper::save();
		JToolBarHelper::custom('saveAndNew','save.png','save_f2.png','Save &amp; New', false);
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
                        // new toolbar
        JToolBarHelper::help( 'screen.schedule',true ); 
	
		parent::display($tpl);
	}

}
