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

class DLViewTeam extends JView
{

	function display($tpl = null)
	{
	
		JRequest::setVar('hidemainmenu', 1);
		$model = & $this->getModel();
		$team = $model->getData();
		JFilterOutput::objectHTMLSafe( $team );
		if(!$team->id)
			$team->published=1;
		$this->assignRef('row', $team);
		$wysiwyg = & JFactory::getEditor();
		$editor = $wysiwyg->display('description', $team->description, '100%', '250', '40', '5', array('pagebreak', 'readmore'));
		$this->assignRef('editor', $editor);
		$lists = array ();
		$lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $team->published);
		$lists['access'] = JHTML::_('list.accesslevel', $team);
		$query = 'SELECT ordering AS value, name AS text FROM #__dl_teams ORDER BY ordering';
		$lists['ordering'] = JHTML::_('list.specificordering', $team, $team->id, $query);
/*        
        require_once (JPATH_COMPONENT.DS.'models'.DS.'extrafields.php');
        $extraFieldsModel = new dlModelExtraFields;
        $groups = $extraFieldsModel->getGroups();
        $group [] = JHTML::_ ( 'select.option', '0', JText::_ ( '-- None --' ), 'id', 'name' );
        $group = array_merge ( $group, $groups );
        $lists['extraFieldsGroup'] = JHTML::_ ( 'select.genericlist', $group, 'extraFieldsGroup', 'class="inputbox" size="1" ', 'id', 'name', $category->extraFieldsGroup );
*/
        require_once(JPATH_COMPONENT.DS.'models'.DS.'venues.php');
        $venuesModel= new dlModelvenues();
        $venues_option[]=JHTML::_('select.option', 0, JText::_('- Select Venue -'));
        $venues = $venuesModel->venuesTree(NUll, true, false);
        $venues_options=@array_merge($venues_option, $venues);
        // dump($venues_options, '$venues_options');
        // $lists['venueid'] <--  the third part must match  _JHTML::_ ... _'venueid'_ otherwise the selection will not be saved - this allows the selection list to appear in the default view using $lists->venueid  --> 'class="inputbox" size="1"', 'value', 'text', $team->catid);
        $lists['venueid'] = JHTML::_('select.genericlist', $venues_options, 'venueid', 'class="inputbox" size="1"', 'value', 'text', $team->catid);
//
//        $query = "SELECT id AS value, name AS venuename FROM #__dl_venues WHERE id={$team->catid}";
//        $lists['venueslist'] = JHTML::_('list.specificordering', $team, $team->id, $query);

        //$query = 'SELECT id AS value, name AS text FROM #__dl_teams ';
        //$query .= " LEFT JOIN "
        //$lists['ordering'] = JHTML::_('list.specificordering', $team, $team->id, $query);
        
//		$teams[] = JHTML::_('select.option', '0', JText::_('-- None --'));
/*       // if ($front) {
        if (!$row->id){
            $row->catid = $venue->get('catid');
        } else {
            $row->catid = $row->catid ? $row->catid : $venue->get('catid');
        }*/
        
/*        
        require_once (JPATH_COMPONENT.DS.'models'.DS.'teams.php');
        $teamsModel = new dlModelteams;
        $tree=$teamsModel->teamsTree($team);
        $teams = array_merge($teams,$tree);
        $lists['venuetypes'] = JHTML::_('select.genericlist', $teams, 'venuetypes', 'class="inputbox"', 'value', 'text', $team->parent);
  */  
		require_once (JPATH_COMPONENT.DS.'models'.DS.'extrafields.php');
		$extraFieldsModel = new dlModelExtraFields;
		$groups = $extraFieldsModel->getGroups();
		$group [] = JHTML::_ ( 'select.option', '0', JText::_ ( '-- None --' ), 'id', 'name' );
		$group = array_merge ( $group, $groups );
		$lists['extraFieldsGroup'] = JHTML::_ ( 'select.genericlist', $group, 'extraFieldsGroup', 'class="inputbox" size="1" ', 'id', 'name', $team->extraFieldsGroup );
	
	
		JPluginHelper::importPlugin ( 'dl' );
		$dispatcher = &JDispatcher::getInstance ();
		$dlPlugins=$dispatcher->trigger('onRenderAdminForm', array (&$team, 'team' ) );
		$this->assignRef('dlPlugins', $dlPlugins);
	
	
		$params = & JComponentHelper::getParams('com_dl');
		$this->assignRef('params', $params);
		
		$form = new JParameter('', JPATH_COMPONENT.DS.'models'.DS.'team.xml');
		$form->loadINI($team->params);
		$this->assignRef('form', $form);
		
		$teams[0] = JHTML::_('select.option', '0', JText::_('-- None --'));
		$lists['inheritFrom'] = JHTML::_('select.genericlist', $teams, 'params[inheritFrom]', 'class="inputbox"', 'value', 'text', $form->get('inheritFrom'));
		
		$this->assignRef('lists', $lists);
		(JRequest::getInt('cid'))? $title = JText::_('Edit Team') : $title = JText::_('Add Team');
		JToolBarHelper::title($title, 'dl.png');
		JToolBarHelper::save();
		JToolBarHelper::custom('saveAndNew','save.png','save_f2.png','Save &amp; New', false);
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
                        // new toolbar
        JToolBarHelper::help( 'screen.team',true ); 
	
		parent::display($tpl);
	}

}
