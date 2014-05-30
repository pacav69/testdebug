<?php
/**
 * @version		$Id: playerslist.php 478 2010-06-16 16:11:42Z SilverPC Consultants. $
 * @package		dartsleague
 * @author		SilverPC Consultants.
 * @copyright	Copyright (c) 2009 - 2011 SilverPC Consultants. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');

class dartsleagueModelplayerslist extends JModel
{

	function getData() {

		$mainframe = &JFactory::getApplication();
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd('view');
		$db = & JFactory::getDBO();
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($option.$view.'.limitstart', 'limitstart', 0, 'int');
		$search = $mainframe->getUserStateFromRequest($option.$view.'search', 'search', '', 'string');
		$search = JString::strtolower($search);
		$filter_order = $mainframe->getUserStateFromRequest($option.$view.'filter_order', 'filter_order', 'c.ordering', 'cmd');
		$filter_order_Dir = $mainframe->getUserStateFromRequest($option.$view.'filter_order_Dir', 'filter_order_Dir', '', 'word');
		$filter_trash = $mainframe->getUserStateFromRequest($option.$view.'filter_trash', 'filter_trash', 0, 'int');
		$filter_state = $mainframe->getUserStateFromRequest($option.$view.'filter_state', 'filter_state', -1, 'int');

		// $query = "SELECT c.*, g.name AS groupname, exfg.name as extra_fields_group FROM #__dartsleague_teams as c LEFT JOIN #__groups AS g ON g.id = c.access LEFT JOIN #__dartsleague_extra_fields_groups AS exfg ON exfg.id = c.extraFieldsGroup WHERE c.id>0";
        $query = "SELECT c.*, v.name AS teamname FROM #__dartsleague_players as c"; 
        $query .= " LEFT JOIN #__dartsleague_teams AS v ON v.id = c.teamid";
        $query .= " WHERE c.teamid>0";
		if (!$filter_trash){
			$query .= " AND c.trash=0";
		}

		if ($search) {
			$query .= " AND LOWER( c.name ) LIKE ".$db->Quote('%'.$db->getEscaped($search, true).'%', false);
		}

		if ($filter_state > -1) {
			$query .= " AND c.published={$filter_state}";
		}


		$query .= " ORDER BY {$filter_order} {$filter_order_Dir}";


		$db->setQuery($query);
		$rows = $db->loadObjectList();

		$categories = array();

		if ($search) {
			foreach ($rows as $row) {
				$row->treename = $row->name;
				$players[]=$row;
			}

		}
		else {
			$players = $this->indentRows($rows);
		}
		if (isset($players))
			$total = count($players);
		else $total = 0;
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total, $limitstart, $limit);
		$players = @array_slice($players, $pageNav->limitstart, $pageNav->limit);
		return $players;
	}

	function getTotal() {

		$mainframe = &JFactory::getApplication();
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd('view');
		$db = & JFactory::getDBO();
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($option.'.limitstart', 'limitstart', 0, 'int');
		$search = $mainframe->getUserStateFromRequest($option.$view.'search', 'search', '', 'string');
		$search = JString::strtolower($search);
		$filter_trash = $mainframe->getUserStateFromRequest($option.$view.'filter_trash', 'filter_trash', 0, 'int');
		$filter_state = $mainframe->getUserStateFromRequest($option.$view.'filter_state', 'filter_state', 1, 'int');

		$query = "SELECT COUNT(*) FROM #__dartsleague_players WHERE id>0";

		if (!$filter_trash){
			$query .= " AND trash=0";
		}

		if ($search) {
			$query .= " AND LOWER( name ) LIKE ".$db->Quote('%'.$db->getEscaped($search, true).'%', false);
		}

		if ($filter_state > -1) {
			$query .= " AND published={$filter_state}";
		}

		$db->setQuery($query);
		$total = $db->loadResult();
		return $total;

	}


	function indentRows( & $rows) {

		$children = array ();
		if(count($rows)){
			foreach ($rows as $v) {
				$pt = $v->parent;
				$list = @$children[$pt]?$children[$pt]: array ();
				array_push($list, $v);
				$children[$pt] = $list;
			}
		}
		$players = JHTML::_('menu.treerecurse', 0, '', array (), $children);
		return $players;
	}

	function publish() {

		$mainframe = &JFactory::getApplication();
		$cid = JRequest::getVar('cid');
		$row = & JTable::getInstance('dartsleagueplayers', 'Table');
		foreach ($cid as $id) {
			$row->load($id);
			$row->publish($id, 1);
		}
		$cache = & JFactory::getCache('com_dartsleague');
		$cache->clean();
		$mainframe->redirect('index.php?option=com_dartsleague&view=players');
	}

	function unpublish() {

		$mainframe = &JFactory::getApplication();
		$cid = JRequest::getVar('cid');
		$row = & JTable::getInstance('dartsleagueplayers', 'Table');
		foreach ($cid as $id) {
			$row->load($id);
			$row->publish($id, 0);
		}
		$cache = & JFactory::getCache('com_dartsleague');
		$cache->clean();
		$mainframe->redirect('index.php?option=com_dartsleague&view=players');
	}

	function saveorder() {

		$mainframe = &JFactory::getApplication();
		$db = & JFactory::getDBO();
		$cid = JRequest::getVar('cid', array (0), 'post', 'array');
		$total = count($cid);
		$order = JRequest::getVar('order', array (0), 'post', 'array');
		JArrayHelper::toInteger($order, array (0));
		$row = & JTable::getInstance('dartsleagueplayers', 'Table');
		$groupings = array ();
		for ($i = 0; $i < $total; $i++) {
			$row->load(( int )$cid[$i]);
			$groupings[] = $row->parent;
			if ($row->ordering != $order[$i]) {
				$row->ordering = $order[$i];
				if (!$row->store()) {
					JError::raiseError(500, $db->getErrorMsg());
				}
			}
		}
		$params = &JComponentHelper::getParams('com_dartsleague');
		if(!$params->get('disableCompactOrdering')){
			$groupings = array_unique($groupings);
			foreach ($groupings as $group) {
				$row->reorder('parent = '.( int )$group.' AND trash=0');
			}
		}
		$cache = & JFactory::getCache('com_dartsleague');
		$cache->clean();
		$msg = JText::_('New ordering saved');
		$mainframe->redirect('index.php?option=com_dartsleague&view=players', $msg);
	}

	function orderup() {

		$mainframe = &JFactory::getApplication();
		$cid = JRequest::getVar('cid');
		$row = & JTable::getInstance('dartsleagueplayers', 'Table');
		$row->load($cid[0]);
		$row->move(-1, 'parent = '.$row->parent.' AND trash=0');
		$params = &JComponentHelper::getParams('com_dartsleague');
		if(!$params->get('disableCompactOrdering'))
			$row->reorder('parent = '.$row->parent.' AND trash=0');
		$cache = & JFactory::getCache('com_dartsleague');
		$cache->clean();
		$msg = JText::_('New ordering saved');
		$mainframe->redirect('index.php?option=com_dartsleague&view=players', $msg);
	}

	function orderdown() {

		$mainframe = &JFactory::getApplication();
		$cid = JRequest::getVar('cid');
		$row = & JTable::getInstance('dartsleagueplayers', 'Table');
		$row->load($cid[0]);
		$row->move(1, 'parent = '.$row->parent.' AND trash=0');
		$params = &JComponentHelper::getParams('com_dartsleague');
		if(!$params->get('disableCompactOrdering'))
			$row->reorder('parent = '.$row->parent.' AND trash=0');
		$cache = & JFactory::getCache('com_dartsleague');
		$cache->clean();
		$msg = JText::_('New ordering saved');
		$mainframe->redirect('index.php?option=com_dartsleague&view=players', $msg);
	}

	function accessregistered() {

		$mainframe = &JFactory::getApplication();
		$db = & JFactory::getDBO();
		$row = & JTable::getInstance('dartsleagueplayers', 'Table');
		$cid = JRequest::getVar('cid');
		$row->load($cid[0]);
		$row->access = 1;
		if (!$row->check()) {
			return $row->getError();
		}
		if (!$row->store()) {
			return $row->getError();
		}
		$cache = & JFactory::getCache('com_dartsleague');
		$cache->clean();
		$msg = JText::_('New access setting saved');
		$mainframe->redirect('index.php?option=com_dartsleague&view=players', $msg);
	}

	function accessspecial() {

		$mainframe = &JFactory::getApplication();
		$db = & JFactory::getDBO();
		$row = & JTable::getInstance('dplayers', 'Table');
		$cid = JRequest::getVar('cid');
		$row->load($cid[0]);
		$row->access = 2;
		if (!$row->check()) {
			return $row->getError();
		}
		if (!$row->store()) {
			return $row->getError();
		}
		$cache = & JFactory::getCache('com_dartsleague');
		$cache->clean();
		$msg = JText::_('New access setting saved');
		$mainframe->redirect('index.php?option=com_dartsleague&view=players', $msg);
	}

	function accesspublic() {

		$mainframe = &JFactory::getApplication();
		$db = & JFactory::getDBO();
		$row = & JTable::getInstance('dartsleagueplayers', 'Table');
		$cid = JRequest::getVar('cid');
		$row->load($cid[0]);
		$row->access = 0;
		if (!$row->check()) {
			return $row->getError();
		}
		if (!$row->store()) {
			return $row->getError();
		}
		$cache = & JFactory::getCache('com_dartsleague');
		$cache->clean();
		$msg = JText::_('New access setting saved');
		$mainframe->redirect('index.php?option=com_dartsleague&view=players', $msg);
	}

	function trash() {

		$mainframe = &JFactory::getApplication();
		$db = & JFactory::getDBO();
		$cid = JRequest::getVar('cid');
		$row = & JTable::getInstance('dartsleagueplayers', 'Table');

		JArrayHelper::toInteger($cid);

		require_once(JPATH_SITE.DS.'components'.DS.'com_dartsleague'.DS.'models'.DS.'playerslist.php');
		$model = new dartsleagueModelItemlist;
		$players = $cid;
		foreach ($cid as $id) {
			$players = @array_merge($players, dartsleagueModelItemlist::getCategoryChilds($id));
		}
		$players = @array_unique($players);
		JArrayHelper::toInteger($players);
		$sql = @implode(',',$players);
		$db = & JFactory::getDBO();
		$query = "UPDATE #__dartsleague_players SET trash=1  WHERE id IN ({$sql})";
		$db->setQuery($query);
		$db->query();
		$query = "UPDATE #__dartsleague_players SET trash=1  WHERE catid IN ({$sql})";
		$db->setQuery($query);
		$db->query();

		$cache = & JFactory::getCache('com_dartsleague');
		$cache->clean();
		$mainframe->redirect('index.php?option=com_dartsleague&view=players', JText::_('players moved to trash'));

	}

	function restore() {

		$mainframe = &JFactory::getApplication();
		$db = & JFactory::getDBO();
		$cid = JRequest::getVar('cid');
		$row = & JTable::getInstance('dartsleagueCategory', 'Table');
		$warning = false;
		foreach ($cid as $id) {
			$row->load($id);
			if ((int)$row->parent==0){
				$row->trash = 0;
				$row->store();
			}
			else {
				$query = "SELECT COUNT(*) FROM #__dartsleague_players WHERE id={$row->parent} AND trash = 0";
				$db->setQuery($query);
				$result=$db->loadResult();
				if ($result){
					$row->trash = 0;
					$row->store();
				}
				else {
					$warning=true;
				}

			}


		}
		$cache = & JFactory::getCache('com_dartsleague');
		$cache->clean();
		if($warning)
			$mainframe->enqueueMessage(JText::_('Some of the players have not been restored because their parent players is in trash.'), 'notice');
		$mainframe->redirect('index.php?option=com_dartsleague&view=players', JText::_('Categories restored'));

	}

	function remove() {

		$mainframe = &JFactory::getApplication();
		jimport('joomla.filesystem.file');
		$db = & JFactory::getDBO();
		$cid = JRequest::getVar('cid');
		JArrayHelper::toInteger($cid);
		$row = & JTable::getInstance('dartsleagueplayers', 'Table');

		$warningItems = false;
		$warningChilds = false;
		$cid = array_reverse($cid);
		for ($i = 0; $i < sizeof($cid); $i++) {
			$row->load($cid[$i]);

			$query = "SELECT COUNT(*) FROM #__dartsleague_players WHERE catid={$cid[$i]}";
			$db->setQuery($query);
			$num = $db->loadResult();

			if ($num > 0 ){
				$warningItems = true;
			}

			$query = "SELECT COUNT(*) FROM #__dartsleague_players WHERE parent={$cid[$i]}";
			$db->setQuery($query);
			$childs = $db->loadResult();

			if ($childs > 0) {
				$warningChilds = true;
			}

			if ($childs==0 && $num==0){

				if ($row->image) {
					JFile::delete(JPATH_ROOT.DS.'media'.DS.'dartsleague'.DS.'players'.DS.$row->image);
				}
				$row->delete($cid[$i]);

			}
		}
		$cache = & JFactory::getCache('com_dartsleague');
		$cache->clean();

		if ($warningItems){
			$mainframe->enqueueMessage(JText::_('Some of the players have not been deleted because they have players.'), 'notice');
		}
		if ($warningChilds){
			$mainframe->enqueueMessage(JText::_('Some of the players have not been deleted because they have child players.'), 'notice');
		}

		$mainframe->redirect('index.php?option=com_dartsleague&view=players', JText::_('Delete Completed'));
	}

	function playersTree(  $row = NULL, $hideTrashed=false, $hideUnpublished=true) {

		$db = & JFactory::getDBO();
		if (isset($row->id)) {
			$idCheck = ' AND id != '.( int )$row->id;
		}
		else {
			$idCheck = null;
		}
		if (!isset($row->parent)) {
			$row->parent = 0;
		}
		$query = "SELECT m.* FROM #__dartsleague_players m WHERE id > 0 {$idCheck}";

		if ($hideUnpublished){
			$query.=" AND published=1 ";
		}

		if ($hideTrashed){
			$query.=" AND trash=0 ";
		}

		$query.=" ORDER BY ordering";
        // $query.=" ORDER BY parent, ordering";   
		$db->setQuery($query);
		$mitems = $db->loadObjectList();
		$children = array ();
		if ($mplayers) {
			foreach ($mplayers as $v) {
				$pt = $v->parent;
				$list = @$children[$pt]?$children[$pt]: array ();
				array_push($list, $v);
				$children[$pt] = $list;
			}
		}
		$list = JHTML::_('menu.treerecurse', 0, '', array (), $children, 9999, 0, 0);
		$mplayers = array ();
		foreach ($list as $item) {
			if($players->trash) $item->treename.=' [**'.JText::_('Trashed players').'**]';
			if(!$players->published) $item->treename.=' [**'.JText::_('Unpublished players').'**]';
			$mplayers[] = JHTML::_('select.option', $item->id, '&nbsp;&nbsp;&nbsp;'.$players->treename);
		}
		return $mplayers;
	}

	function move() {

		$mainframe = &JFactory::getApplication();
		$cid = JRequest::getVar('cid');
		$catid = JRequest::getInt('players');
		$row = &JTable::getInstance('dartsleagueplayers', 'Table');
		foreach ($cid as $id) {
			$row->load($id);
			$row->parent = $catid;
			$row->ordering = $row->getNextOrder('parent = '.$row->parent.' AND published = 1');
			$row->store();
		}
		$cache = &JFactory::getCache('com_dartsleague');
		$cache->clean();
		$mainframe->redirect('index.php?option=com_dartsleague&view=players', JText::_('Move completed'));

	}

}
