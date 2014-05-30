<?php
/**
 * @version		$Id: players.php 528 2010-08-03 15:36:23Z SilverPC Consultants. $
 * @package		dartsleagueplayer_s
 * @author		SilverPC Consultants.
 * @copyright	Copyright (c) 2009 - 2011 SilverPC Consultants. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');

class dartsleagueModelplayers extends JModel {

	function getData() {

		$mainframe = &JFactory::getApplication();
		$params = &JComponentHelper::getParams('com_dartsleague');
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd('view');
		$db = &JFactory::getDBO();
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($option.$view.'.limitstart', 'limitstart', 0, 'int');
		$filter_order = $mainframe->getUserStateFromRequest($option.$view.'filter_order', 'filter_order', 'i.id', 'cmd');
		$filter_order_Dir = $mainframe->getUserStateFromRequest($option.$view.'filter_order_Dir', 'filter_order_Dir', 'DESC', 'word');
		$filter_trash = $mainframe->getUserStateFromRequest($option.$view.'filter_trash', 'filter_trash', 0, 'int');
		$filter_featured = $mainframe->getUserStateFromRequest($option.$view.'filter_featured', 'filter_featured', -1, 'int');
		$filter_player = $mainframe->getUserStateFromRequest($option.$view.'filter_player', 'filter_player', 0, 'int');
		$filter_author = $mainframe->getUserStateFromRequest($option.$view.'filter_author', 'filter_author', 0, 'int');
		$filter_state = $mainframe->getUserStateFromRequest($option.$view.'filter_state', 'filter_state', -1, 'int');
		$search = $mainframe->getUserStateFromRequest($option.$view.'search', 'search', '', 'string');
		$search = JString::strtolower($search);

		//$query = "SELECT i.*, g.name AS groupname, c.name AS team, v.name AS author, w.name as moderator, u.name AS editor FROM #__dartsleague_player as i";
        //$query = "SELECT i.*, c.teamid AS team FROM #__dartsleague_players as i";
        //$query = "SELECT i.*, v.name AS venuename FROM #__dartsleague_teams as i";
        //$query .= " LEFT JOIN #__dartsleague_venues AS v ON v.id = i.catid";
        $query = "SELECT i.*, t.name AS teamname FROM #__dartsleague_players as i";
        $query .= " LEFT JOIN #__dartsleague_teams AS t ON t.id = i.teamid";
        // $query = "SELECT i.* FROM #__dartsleague_players as i";
		// $query .= " LEFT JOIN #__dartsleague_teams AS c ON c.id = i.teamid";
        // ." LEFT JOIN #__groups AS g ON g.id = i.access"." LEFT JOIN #__users AS u ON u.id = i.checked_out"." LEFT JOIN #__users AS v ON v.id = i.created_by"." LEFT JOIN #__users AS w ON w.id = i.modified_by";

		$query .= " WHERE i.trash={$filter_trash}";

		if ($search) {

			$search = JString::str_ireplace('*', '', $search);
			$words = explode(' ', $search);
			for($i=0; $i<count($words); $i++){
				$words[$i]= '+'.$words[$i];
				$words[$i].= '*';
			}
			$search = implode(' ', $words);
			$search = $db->Quote($db->getEscaped($search, true), false);

			if($params->get('adminSearch')=='full')
			// $query .= " AND MATCH(i.title, i.email, i.introtext, i.`fulltext`, i.extra_fields_search, i.image_caption,i.image_credits,i.video_caption,i.video_credits,i.metadesc,i.metakey)";
			$query .= " AND MATCH(i.firstname)";
            else
			$query .= " AND MATCH( i.firstname )";

			$query.= " AGAINST ({$search} IN BOOLEAN MODE)";
		}

		/**if ($filter_state > - 1) {
			$query .= " AND i.published={$filter_state}";
		}  */

		/**if ($filter_featured > - 1) {
			$query .= " AND i.featured={$filter_featured}";
		}  */

		if ($filter_player > 0) {
			if ($params->get('showChildCatplayers')) {
				require_once (JPATH_SITE.DS.'components'.DS.'com_dartsleague'.DS.'models'.DS.'playerslist.php');
				$players = dartsleagueModelplayerlist::getplayerChilds($filter_player);
				$players[] = $filter_player;
				JArrayHelper::toInteger($players);
				$categories = @array_unique($players);
				$sql = @implode(',', $players);
				$query .= " AND i.teamid IN ({$sql})";
			} else {
				$query .= " AND i.teamid={$filter_team}";
			}

		}

/*		if ($filter_author > 0) {
			$query .= " AND i.created_by={$filter_author}";
		} */

		if ($filter_order == 'i.ordering') {
			$query .= " ORDER BY i.teamid, i.ordering {$filter_order_Dir}";
		} else {
			$query .= " ORDER BY {$filter_order} {$filter_order_Dir} ";
		}

		$db->setQuery($query, $limitstart, $limit);
		$rows = $db->loadObjectList();
		return $rows;

	}

	function getTotal() {

		$mainframe = &JFactory::getApplication();
		$params = &JComponentHelper::getParams('com_dartsleague');
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd('view');
		$db = &JFactory::getDBO();
		$filter_trash = $mainframe->getUserStateFromRequest($option.$view.'filter_trash', 'filter_trash', 0, 'int');
		$filter_featured = $mainframe->getUserStateFromRequest($option.$view.'filter_featured', 'filter_featured', -1, 'int');
		$filter_player = $mainframe->getUserStateFromRequest($option.$view.'filter_player', 'filter_player', 0, 'int');
		$filter_author = $mainframe->getUserStateFromRequest($option.$view.'filter_author', 'filter_author', 0, 'int');
		$filter_state = $mainframe->getUserStateFromRequest($option.$view.'filter_state', 'filter_state', -1, 'int');
		$search = $mainframe->getUserStateFromRequest($option.$view.'search', 'search', '', 'string');
		$search = JString::strtolower($search);

		$query = "SELECT COUNT(*) FROM #__dartsleague_players"; 
        // WHERE trash={$filter_trash}"; 

		if ($search) {

			$search = JString::str_ireplace('*', '', $search);
			$words = explode(' ', $search);
			for($i=0; $i<count($words); $i++){
				$words[$i]= '+'.$words[$i];
				$words[$i].= '*';
			}
			$search = implode(' ', $words);
			$search = $db->Quote($db->getEscaped($search, true), false);

			if($params->get('adminSearch')=='full')
			// $query .= " AND MATCH(title, introtext, email, `fulltext`, extra_fields_search, image_caption, image_credits, video_caption, video_credits, metadesc, metakey)";
            $query .= " AND MATCH(firstname)";
			else
			$query .= " AND MATCH( firstname )";

			$query.= " AGAINST ({$search} IN BOOLEAN MODE)";
		}

		if ($filter_state > - 1) {
			$query .= " AND published={$filter_state}";
		}

		if ($filter_featured > - 1) {
			$query .= " AND featured={$filter_featured}";
		}

		if (filter_player > 0) {
			if ($params->get('showChildCatplayers')) {
				require_once (JPATH_SITE.DS.'components'.DS.'com_dartsleague'.DS.'models'.DS.'playerlist.php');
				$categories = dartsleagueModelplayerlist::getteamChilds($filter_team);
				$categories[] = filter_player;
				JArrayHelper::toInteger($categories);
				$categories = @array_unique($categories);
				$sql = @implode(',', $categories);
				$query .= " AND teamid IN ({$sql})";
			} else {
				$query .= " AND teamid={$filter_team}";
			}

		}

		if ($filter_author > 0) {
			$query .= " AND created_by={$filter_author}";
		}

		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;

	}

	function publish() {

		$mainframe = &JFactory::getApplication();
		$cid = JRequest::getVar('cid');
		$row = &JTable::getInstance('dartsleagueplayer', 'Table');
		foreach ($cid as $id) {
			$row->load($id);
			$row->publish($id, 1);
		}
		$cache = &JFactory::getCache('com_dartsleague');
		$cache->clean();
		$mainframe->redirect('index.php?option=com_dartsleague&view=players');
	}

	function unpublish() {

		$mainframe = &JFactory::getApplication();
		$cid = JRequest::getVar('cid');
		$row = &JTable::getInstance('dartsleagueplayer', 'Table');
		foreach ($cid as $id) {
			$row->load($id);
			$row->publish($id, 0);
		}
		$cache = &JFactory::getCache('com_dartsleague');
		$cache->clean();
		$mainframe->redirect('index.php?option=com_dartsleague&view=players');
	}

	function saveorder() {

		$mainframe = &JFactory::getApplication();
		$db = &JFactory::getDBO();
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		$total = count($cid);
		$order = JRequest::getVar('order', array(0), 'post', 'array');
		JArrayHelper::toInteger($order, array(0));
		$row = &JTable::getInstance('dartsleagueplayer', 'Table');
		$groupings = array();
		for ($i = 0; $i < $total; $i++) {
			$row->load((int) $cid[$i]);
			$groupings[] = $row->teamid;
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
				$row->reorder('teamid = '.(int) $group.' AND trash=0');
			}
		}
		$cache = &JFactory::getCache('com_dartsleague');
		$cache->clean();
		$msg = JText::_('New ordering saved');
		$mainframe->redirect('index.php?option=com_dartsleague&view=players', $msg);
	}

	function orderup() {

		$mainframe = &JFactory::getApplication();
		$cid = JRequest::getVar('cid');
		$row = &JTable::getInstance('dartsleagueplayer', 'Table');
		$row->load($cid[0]);
		$row->move(-1, 'teamid = '.(int) $row->teamid.' AND trash=0');
		$params = &JComponentHelper::getParams('com_dartsleague');
		if(!$params->get('disableCompactOrdering'))
		$row->reorder('teamid = '.(int) $row->teamid.' AND trash=0');
		$cache = &JFactory::getCache('com_dartsleague');
		$cache->clean();
		$msg = JText::_('New ordering saved');
		$mainframe->redirect('index.php?option=com_dartsleague&view=players', $msg);
	}

	function orderdown() {

		$mainframe = &JFactory::getApplication();
		$cid = JRequest::getVar('cid');
		$row = &JTable::getInstance('dartsleagueplayer', 'Table');
		$row->load($cid[0]);
		$row->move(1, 'teamid = '.(int) $row->teamid.' AND trash=0');
		$params = &JComponentHelper::getParams('com_dartsleague');
		if(!$params->get('disableCompactOrdering'))
		$row->reorder('teamid = '.(int) $row->teamid.' AND trash=0');
		$cache = &JFactory::getCache('com_dartsleague');
		$cache->clean();
		$msg = JText::_('New ordering saved');
		$mainframe->redirect('index.php?option=com_dartsleague&view=players', $msg);
	}

	function savefeaturedorder() {

		$mainframe = &JFactory::getApplication();
		$db = &JFactory::getDBO();
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		$total = count($cid);
		$order = JRequest::getVar('order', array(0), 'post', 'array');
		JArrayHelper::toInteger($order, array(0));
		$row = &JTable::getInstance('dartsleagueplayer', 'Table');
		$groupings = array();
		for ($i = 0; $i < $total; $i++) {
			$row->load((int) $cid[$i]);
			$groupings[] = $row->teamid;
			if ($row->featured_ordering != $order[$i]) {
				$row->featured_ordering = $order[$i];
				if (!$row->store()) {
					JError::raiseError(500, $db->getErrorMsg());
				}
			}
		}
		$params = &JComponentHelper::getParams('com_dartsleague');
		if(!$params->get('disableCompactOrdering')){
			$groupings = array_unique($groupings);
			foreach ($groupings as $group) {
				$row->reorder('featured = 1 AND trash=0', 'featured_ordering');
			}
		}
		$cache = &JFactory::getCache('com_dartsleague');
		$cache->clean();
		$msg = JText::_('New featured ordering saved');
		$mainframe->redirect('index.php?option=com_dartsleague&view=players', $msg);
	}

	function featuredorderup() {

		$mainframe = &JFactory::getApplication();
		$cid = JRequest::getVar('cid');
		$row = &JTable::getInstance('dartsleagueplayer', 'Table');
		$row->load($cid[0]);
		$row->move(-1, 'featured=1 AND trash=0', 'featured_ordering');
		$params = &JComponentHelper::getParams('com_dartsleague');
		if(!$params->get('disableCompactOrdering'))
		$row->reorder('featured=1 AND trash=0', 'featured_ordering');
		$cache = &JFactory::getCache('com_dartsleague');
		$cache->clean();
		$msg = JText::_('New ordering saved');
		$mainframe->redirect('index.php?option=com_dartsleague&view=players', $msg);
	}

	function featuredorderdown() {

		$mainframe = &JFactory::getApplication();
		$cid = JRequest::getVar('cid');
		$row = &JTable::getInstance('dartsleagueplayer', 'Table');
		$row->load($cid[0]);
		$row->move(1, 'featured=1 AND trash=0', 'featured_ordering');
		$params = &JComponentHelper::getParams('com_dartsleague');
		if(!$params->get('disableCompactOrdering'))
		$row->reorder('featured=1 AND trash=0', 'featured_ordering');
		$cache = &JFactory::getCache('com_dartsleague');
		$cache->clean();
		$msg = JText::_('New ordering saved');
		$mainframe->redirect('index.php?option=com_dartsleague&view=players', $msg);
	}

	function accessregistered() {

		$mainframe = &JFactory::getApplication();
		$db = &JFactory::getDBO();
		$row = &JTable::getInstance('dartsleagueplayer', 'Table');
		$cid = JRequest::getVar('cid');
		$row->load($cid[0]);
		$row->access = 1;
		if (!$row->check()) {
			return $row->getError();
		}
		if (!$row->store()) {
			return $row->getError();
		}
		$cache = &JFactory::getCache('com_dartsleague');
		$cache->clean();
		$msg = JText::_('New access setting saved');
		$mainframe->redirect('index.php?option=com_dartsleague&view=players', $msg);
	}

	function accessspecial() {

		$mainframe = &JFactory::getApplication();
		$db = &JFactory::getDBO();
		$row = &JTable::getInstance('dartsleagueplayer', 'Table');
		$cid = JRequest::getVar('cid');
		$row->load($cid[0]);
		$row->access = 2;
		if (!$row->check()) {
			return $row->getError();
		}
		if (!$row->store()) {
			return $row->getError();
		}
		$cache = &JFactory::getCache('com_dartsleague');
		$cache->clean();
		$msg = JText::_('New access setting saved');
		$mainframe->redirect('index.php?option=com_dartsleague&view=players', $msg);
	}

	function accesspublic() {

		$mainframe = &JFactory::getApplication();
		$db = &JFactory::getDBO();
		$row = &JTable::getInstance('dartsleagueplayer', 'Table');
		$cid = JRequest::getVar('cid');
		$row->load($cid[0]);
		$row->access = 0;
		if (!$row->check()) {
			return $row->getError();
		}
		if (!$row->store()) {
			return $row->getError();
		}
		$cache = &JFactory::getCache('com_dartsleague');
		$cache->clean();
		$msg = JText::_('New access setting saved');
		$mainframe->redirect('index.php?option=com_dartsleague&view=players', $msg);
	}

	function copy() {

		$mainframe = &JFactory::getApplication();
		jimport('joomla.filesystem.file');
		require_once (JPATH_COMPONENT.DS.'models'.DS.'player.php');
		$params = &JComponentHelper::getParams('com_dartsleague');
		$playerModel = new dartsleagueModelplayer;
		$db = &JFactory::getDBO();
		$cid = JRequest::getVar('cid');
		JArrayHelper::toInteger($cid);
		$row = &JTable::getInstance('dartsleagueplayer', 'Table');

		$nullDate = $db->getNullDate();

		foreach ($cid as $id) {

			//Load source player
			$player = &JTable::getInstance('dartsleagueplayer', 'Table');
			$player->load($id);
			$player->id = (int) $player->id;

			//Source images
			$sourceImage = JPATH_ROOT.DS.'media'.DS.'dartsleague'.DS.'players'.DS.'src'.DS.md5("Image".$player->id).'.jpg';
			$sourceImageXS = JPATH_ROOT.DS.'media'.DS.'dartsleague'.DS.'players'.DS.'cache'.DS.md5("Image".$player->id).'_XS.jpg';
			$sourceImageS = JPATH_ROOT.DS.'media'.DS.'dartsleague'.DS.'players'.DS.'cache'.DS.md5("Image".$player->id).'_S.jpg';
			$sourceImageM = JPATH_ROOT.DS.'media'.DS.'dartsleague'.DS.'players'.DS.'cache'.DS.md5("Image".$player->id).'_M.jpg';
			$sourceImageL = JPATH_ROOT.DS.'media'.DS.'dartsleague'.DS.'players'.DS.'cache'.DS.md5("Image".$player->id).'_L.jpg';
			$sourceImageXL = JPATH_ROOT.DS.'media'.DS.'dartsleague'.DS.'players'.DS.'cache'.DS.md5("Image".$player->id).'_XL.jpg';
			$sourceImageGeneric = JPATH_ROOT.DS.'media'.DS.'dartsleague'.DS.'players'.DS.'cache'.DS.md5("Image".$player->id).'_Generic.jpg';

			//Source gallery
			$sourceGallery = JPATH_ROOT.DS.'media'.DS.'dartsleague'.DS.'galleries'.DS.$player->id;
			$sourceGalleryTag = $player->gallery;

			//Source video
			preg_match_all("#^{(.*?)}(.*?){#", $player->video, $matches, PREG_PATTERN_ORDER);
			$videotype = $matches[1][0];
			$videofile = $matches[2][0];

			if ($videotype == 'flv' || $videotype == 'swf' || $videotype == 'wmv' || $videotype == 'mov' || $videotype == 'mp4' || $videotype == '3gp' || $videotype == 'divx') {
				if (JFile::exists(JPATH_ROOT.DS.'media'.DS.'dartsleague'.DS.'videos'.DS.$videofile.'.'.$videotype)) {
					$sourceVideo = $videofile.'.'.$videotype;
					//$row->video='{'.$videotype.'}'.$row->id.'{/'.$videotype.'}';
				}
			}


			$row->store();
		}

		$mainframe->redirect('index.php?option=com_dartsleague&view=players', JText::_('Copy completed'));
	}

	function featured() {

		$mainframe = &JFactory::getApplication();
		$db = &JFactory::getDBO();
		$cid = JRequest::getVar('cid');
		$row = &JTable::getInstance('dartsleagueplayer', 'Table');
		foreach ($cid as $id) {
			$row->load($id);
			if ($row->featured == 1)
			$row->featured = 0;
			else {
				$row->featured = 1;
				$row->featured_ordering = 1;
			}
			$row->store();
		}
		$cache = &JFactory::getCache('com_dartsleague');
		$cache->clean();
		$mainframe->redirect('index.php?option=com_dartsleague&view=players', JText::_('players changed'));
	}

	function trash() {

		$mainframe = &JFactory::getApplication();
		$db = &JFactory::getDBO();
		$cid = JRequest::getVar('cid');
		JArrayHelper::toInteger($cid);
		$row = &JTable::getInstance('dartsleagueplayer', 'Table');
		foreach ($cid as $id) {
			$row->load($id);
			$row->trash = 1;
			$row->store();
		}
		$cache = &JFactory::getCache('com_dartsleague');
		$cache->clean();
		$mainframe->redirect('index.php?option=com_dartsleague&view=players', JText::_('players moved to trash'));

	}

	function restore() {

		$mainframe = &JFactory::getApplication();
		$db = &JFactory::getDBO();
		$cid = JRequest::getVar('cid');
		$row = &JTable::getInstance('dartsleagueplayer', 'Table');
		$warning = false;
		foreach ($cid as $id) {
			$row->load($id);
			$query = "SELECT COUNT(*) FROM #__dartsleague_teams WHERE id=".(int)$row->teamid." AND trash = 0";
			$db->setQuery($query);
			$result = $db->loadResult();
			if ($result) {
				$row->trash = 0;
				$row->store();
			} else {
				$warning = true;
			}

		}
		$cache = &JFactory::getCache('com_dartsleague');
		$cache->clean();
		if ($warning)
		$mainframe->enqueueMessage(JText::_('Some of the players have not been restored because they belong to a team which is in trash.'), 'notice');
		$mainframe->redirect('index.php?option=com_dartsleague&view=players', JText::_('players restored'));

	}

	function remove() {

		$mainframe = &JFactory::getApplication();
		jimport('joomla.filesystem.file');
		$params = &JComponentHelper::getParams('com_dartsleague');
		require_once (JPATH_COMPONENT.DS.'models'.DS.'player.php');
		$playerModel = new dartsleagueModelplayer;
		$db = &JFactory::getDBO();
		$cid = JRequest::getVar('cid');
		$row = &JTable::getInstance('dartsleagueplayer', 'Table');
		foreach ($cid as $id) {
			$row->load($id);
			$row->id = (int) $row->id;
			//Delete images
			if (JFile::exists(JPATH_ROOT.DS.'media'.DS.'dartsleague'.DS.'players'.DS.'src'.DS.md5("Image".$row->id).'.jpg')) {
				JFile::delete(JPATH_ROOT.DS.'media'.DS.'dartsleague'.DS.'players'.DS.'src'.DS.md5("Image".$row->id).'.jpg');
			}
			if (JFile::exists(JPATH_ROOT.DS.'media'.DS.'dartsleague'.DS.'players'.DS.'cache'.DS.md5("Image".$row->id).'_XS.jpg')) {
				JFile::delete(JPATH_ROOT.DS.'media'.DS.'dartsleague'.DS.'players'.DS.'cache'.DS.md5("Image".$row->id).'_XS.jpg');
			}
			if (JFile::exists(JPATH_ROOT.DS.'media'.DS.'dartsleague'.DS.'players'.DS.'cache'.DS.md5("Image".$row->id).'_S.jpg')) {
				JFile::delete(JPATH_ROOT.DS.'media'.DS.'dartsleague'.DS.'players'.DS.'cache'.DS.md5("Image".$row->id).'_S.jpg');
			}
			if (JFile::exists(JPATH_ROOT.DS.'media'.DS.'dartsleague'.DS.'players'.DS.'cache'.DS.md5("Image".$row->id).'_M.jpg')) {
				JFile::delete(JPATH_ROOT.DS.'media'.DS.'dartsleague'.DS.'players'.DS.'cache'.DS.md5("Image".$row->id).'_M.jpg');
			}
			if (JFile::exists(JPATH_ROOT.DS.'media'.DS.'dartsleague'.DS.'players'.DS.'cache'.DS.md5("Image".$row->id).'_L.jpg')) {
				JFile::delete(JPATH_ROOT.DS.'media'.DS.'dartsleague'.DS.'players'.DS.'cache'.DS.md5("Image".$row->id).'_L.jpg');
			}
			if (JFile::exists(JPATH_ROOT.DS.'media'.DS.'dartsleague'.DS.'players'.DS.'cache'.DS.md5("Image".$row->id).'_XL.jpg')) {
				JFile::delete(JPATH_ROOT.DS.'media'.DS.'dartsleague'.DS.'players'.DS.'cache'.DS.md5("Image".$row->id).'_XL.jpg');
			}
			if (JFile::exists(JPATH_ROOT.DS.'media'.DS.'dartsleague'.DS.'players'.DS.'cache'.DS.md5("Image".$row->id).'_Generic.jpg')) {
				JFile::delete(JPATH_ROOT.DS.'media'.DS.'dartsleague'.DS.'players'.DS.'cache'.DS.md5("Image".$row->id).'_Generic.jpg');
			}

			$row->delete($id);
		}
		$cache = &JFactory::getCache('com_dartsleague');
		$cache->clean();
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
/*        if (!isset($row->parent)) {
            $row->parent = 0;
        }*/
        $query = "SELECT id as value, name as text FROM #__dartsleague_players WHERE id > 0 {$idCheck}";

        if ($hideUnpublished){
            $query.=" AND published=1 ";
        }

        if ($hideTrashed){
            $query.=" AND trash=0 ";
        }

        $query.=" ORDER BY ordering";
        // $query.=" ORDER BY parent, ordering";
        $db->setQuery($query);
        $mplayers = $db->loadObjectList();
/*        $children = array ();
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
        foreach ($list as $player) {
            if($player->trash) $player->treename.=' [**'.JText::_('Trashed player').'**]';
            if(!$player->published) $player->treename.=' [**'.JText::_('Unpublished player').'**]';
            $mplayers[] = JHTML::_('select.option', $player->id, '&nbsp;&nbsp;&nbsp;'.$player->treename);
        } */ 
        return $mplayers;
    }
    
	function import() {

		$mainframe = &JFactory::getApplication();
		jimport('joomla.filesystem.file');
		$db = &JFactory::getDBO();
		$query = "SELECT * FROM #__sections";
		$db->setQuery($query);
		$sections = $db->loadObjectList();

		$query = "SELECT COUNT(*) FROM #__dartsleague_players";
		$db->setQuery($query);
		$result = $db->loadResult();
		if($result)
		$preserveplayerIDs = false;
		else
		$preserveplayerIDs = true;

		$xml = new JSimpleXML;
		$xml->loadFile(JPATH_COMPONENT.DS.'models'.DS.'team.xml');
		$teamParams = new JParameter('');

		foreach ($xml->document->params as $paramGroup) {
			foreach ($paramGroup->param as $param) {
				if ($param->attributes('type') != 'spacer') {
					$teamParams->set($param->attributes('name'), $param->attributes('default'));
				}
			}
		}
		$teamParams = $teamParams->toString();

		$xml = new JSimpleXML;
		$xml->loadFile(JPATH_COMPONENT.DS.'models'.DS.'player.xml');
		$playerParams = new JParameter('');

		foreach ($xml->document->params as $paramGroup) {
			foreach ($paramGroup->param as $param) {
				if ($param->attributes('type') != 'spacer') {
					$playerParams->set($param->attributes('name'), $param->attributes('default'));
				}
			}
		}
		$playerParams = $playerParams->toString();

		$query = "SELECT id, name FROM #__dartsleague_tags";
		$db->setQuery($query);
		$tags = $db->loadObjectList();

		if(is_null($tags))
		$tags = array();

		foreach ($sections as $section) {
			$dartsleagueteam = &JTable::getInstance('dartsleagueteam', 'Table');
			$dartsleagueteam->name = $section->title;
			$dartsleagueteam->email = $section->email;
            $dartsleagueteam->birthdate = $section->birthdate; 
			//$dartsleagueteam->alias = $section->title;
			$dartsleagueteam->description = $section->description;
			$dartsleagueteam->parent = 0;
			$dartsleagueteam->published = $section->published;
			$dartsleagueteam->access = $section->access;
			$dartsleagueteam->ordering = $section->ordering;
			$dartsleagueteam->image = $section->image;
			$dartsleagueteam->trash = 0;
			$dartsleagueteam->params = $teamParams;
			$dartsleagueteam->check();
			$dartsleagueteam->store();
			if (JFile::exists(JPATH_SITE.DS.'images'.DS.'stories'.DS.$section->image)) {
				JFile::copy(JPATH_SITE.DS.'images'.DS.'stories'.DS.$section->image, JPATH_SITE.DS.'media'.DS.'dartsleague'.DS.'categories'.DS.$dartsleagueteam->image);
			}
			$query = "SELECT * FROM #__teams WHERE section = ".(int)$section->id;
			$db->setQuery($query);
			$categories = $db->loadObjectList();

			foreach ($categories as $team) {
				$dartsleagueSubteam = &JTable::getInstance('dartsleagueteam', 'Table');
				$dartsleagueSubteam->name = $team->title;
				$dartsleagueSubteam->email = $team->email;
				//$dartsleagueSubteam->alias = $team->title;
				$dartsleagueSubteam->description = $team->description;
				$dartsleagueSubteam->parent = $dartsleagueteam->id;
				$dartsleagueSubteam->published = $team->published;
				$dartsleagueSubteam->access = $team->access;
				$dartsleagueSubteam->ordering = $team->ordering;
				$dartsleagueSubteam->image = $team->image;
				$dartsleagueSubteam->trash = 0;
				$dartsleagueSubteam->params = $teamParams;
				$dartsleagueSubteam->check();
				$dartsleagueSubteam->store();
				if (JFile::exists(JPATH_SITE.DS.'images'.DS.'stories'.DS.$team->image)) {
					JFile::copy(JPATH_SITE.DS.'images'.DS.'stories'.DS.$team->image, JPATH_SITE.DS.'media'.DS.'dartsleague'.DS.'categories'.DS.$dartsleagueSubteam->image);
				}

				$query = "SELECT * FROM #__content WHERE teamid = ".(int)$team->id;
				$db->setQuery($query);
				$players = $db->loadObjectList();

				foreach ($players as $player) {

					$dartsleagueplayer = &JTable::getInstance('dartsleagueplayer', 'Table');
					$dartsleagueplayer->firstname = $player->firstname;
					$dartsleagueplayer->lastname = $player->lastname;
					$dartsleagueplayer->plalias = $player->plalias;
                    $dartsleagueplayer->gender = $player->gender; 
                    $dartsleagueplayer->nationality = $player->nationality; 
                    $dartsleagueplayer->birthdate = $player->birthdate; 
                    $dartsleagueplayer->email = $player->email;
                    $dartsleagueplayer->mobile = $player->mobile;
                    $dartsleagueplayer->teamid = $player->teamid;   
                    $dartsleagueplayer->gamesplayed = $player->gamesplayed;
                    $dartsleagueplayer->gameswon = $player->gameswon;
                    $dartsleagueplayer->gameslost = $player->gameslost;
                    $dartsleagueplayer->teamposition = $player->teamposition;
                    $dartsleagueplayer->tons = $player->tons;
                    $dartsleagueplayer->ton140s = $player->ton140s;
                    $dartsleagueplayer->hipoints = $player->hipoints;
                    $dartsleagueplayer->season = $player->season;
                    $dartsleagueplayer->weekno = $player->weekno;
                    $dartsleagueplayer->published = $player->published;
                    $dartsleagueplayer->image = $player->image;
					
					$dartsleagueplayer->check();
					if($preserveplayerIDs){
						$dartsleagueplayer->id = $player->id;
						$db->insertObject('#__dartsleague_players', $dartsleagueplayer);
					}
					else {
						$dartsleagueplayer->store();
					}  
   				}

			}

		}

		//Handartsleaguee Uncategorized players
		$query = "SELECT * FROM #__content WHERE sectionid = 0";
		$db->setQuery($query);
		$players = $db->loadObjectList();

		if($players){
			$dartsleagueUncategorised = &JTable::getInstance('dartsleagueteam', 'Table');
			$dartsleagueUncategorised->name = 'Uncategorized';
			//$dartsleagueUncategorised->alias = 'Uncategorized';
			//$dartsleagueUncategorised->parent = 0;
			//$dartsleagueUncategorised->published = 1;
			//$dartsleagueUncategorised->access = 0;
			//$dartsleagueUncategorised->ordering = 0;
			//$dartsleagueUncategorised->trash = 0;
			//$dartsleagueUncategorised->params = $teamParams;
			//$dartsleagueUncategorised->check();
			//$dartsleagueUncategorised->store();

			foreach ($players as $player) {

				$dartsleagueplayer = &JTable::getInstance('dartsleagueplayer', 'Table');
                    $dartsleagueplayer->firstname = $player->firstname;
                    $dartsleagueplayer->lastname = $player->lastname;
                    $dartsleagueplayer->plalias = $player->plalias;
                    $dartsleagueplayer->sex = $player->sex;
                    $dartsleagueplayer->nationality = $player->nationality; 
                    $dartsleagueplayer->birthdate = $player->birthdate; 
                    $dartsleagueplayer->email = $player->email; 
                    $dartsleagueplayer->mobile = $player->mobile;
                    $dartsleagueplayer->teamid = $player->teamid; 
                    $dartsleagueplayer->gamesplayed = $player->gamesplayed; 
                    $dartsleagueplayer->gameswon = $player->gameswon; 
                    $dartsleagueplayer->gameslost = $player->gameslost; 
                    $dartsleagueplayer->teamposition = $player->teamposition; 
                    $dartsleagueplayer->tons = $player->tons; 
                    $dartsleagueplayer->ton140s = $player->ton140s; 
                    $dartsleagueplayer->ton180s = $player->ton180s; 
                    $dartsleagueplayer->hipoints = $player->hipoints; 
                    $dartsleagueplayer->season = $player->season;
                    $dartsleagueplayer->weekno = $player->weekno;
                    /**
				$dartsleagueplayer->title = $player->title;
				$dartsleagueplayer->email = $player->email;
				$dartsleagueplayer->alias = $player->title;
				$dartsleagueplayer->teamid = $dartsleagueUncategorised->id;
				if ($player->state < 0) {
					$dartsleagueplayer->trash = 1;
				} else {
					$dartsleagueplayer->trash = 0;
					$dartsleagueplayer->published = $player->state;
				}
				$dartsleagueplayer->introtext = $player->introtext;
				$dartsleagueplayer->fulltext = $player->fulltext;
				$dartsleagueplayer->created = $player->created;
				$dartsleagueplayer->created_by = $player->created_by;
				$dartsleagueplayer->created_by_alias = $player->created_by_alias;
				$dartsleagueplayer->modified = $player->modified;
				$dartsleagueplayer->modified_by = $player->modified_by;
				$dartsleagueplayer->publish_up = $player->publish_up;
				$dartsleagueplayer->publish_down = $player->publish_down;
				$dartsleagueplayer->access = $player->access;
				$dartsleagueplayer->ordering = $player->ordering;
				$dartsleagueplayer->hits = $player->hits;
				$dartsleagueplayer->metadesc = $player->metadesc;
				$dartsleagueplayer->metadata = $player->metadata;
				$dartsleagueplayer->metakey = $player->metakey;
				$dartsleagueplayer->params = $playerParams; */
				$dartsleagueplayer->check();
				if($preserveplayerIDs){
					$dartsleagueplayer->id = $player->id;
					$db->insertObject('#__dartsleague_players', $dartsleagueplayer);
				}
				else {
					$dartsleagueplayer->store();
				}

// 				if(!empty($player->metakey)){
// 					$playerTags = explode(',', $player->metakey);
// 					foreach($playerTags as $playerTag){
// 						$playerTag = JString::trim($playerTag);
// 						if(in_array($playerTag ,JArrayHelper::getColumn($tags, 'name'))){

// 							$query = "SELECT id FROM #__dartsleague_tags WHERE name=".$db->Quote($playerTag);
// 							$db->setQuery($query);
// 							$id = $db->loadResult();
// 							$query = "INSERT INTO #__dartsleague_tags_xref (`id`, `tagID`, `playerID`) VALUES (NULL, {$id}, {$dartsleagueplayer->id})";
// 							$db->setQuery($query);
// 							$db->query();
// 						}
// 						else {
// 							$dartsleagueTag = &JTable::getInstance('dartsleagueTag', 'Table');
// 							$dartsleagueTag->name = $playerTag;
// 							$dartsleagueTag->published = 1;
// 							$dartsleagueTag->store();
// 							$tags[]=$dartsleagueTag;
// 							$query = "INSERT INTO #__dartsleague_tags_xref (`id`, `tagID`, `playerID`) VALUES (NULL, {$dartsleagueTag->id}, {$dartsleagueplayer->id})";
// 							$db->setQuery($query);
// 							$db->query();
// 						} 
// 					}
// 				}
			}
		}
		$mainframe->redirect('index.php?option=com_dartsleague&view=players', JText::_('Import Completed'));
	}

	function move() {

		$mainframe = &JFactory::getApplication();
		$cid = JRequest::getVar('cid');
		$teamid = JRequest::getInt('team');
		$row = &JTable::getInstance('dartsleagueplayer', 'Table');
		foreach ($cid as $id) {
			$row->load($id);
			$row->teamid = $teamid;
			$row->ordering = $row->getNextOrder('teamid = '.$row->teamid.' AND published = 1');
			$row->store();
		}
		$cache = &JFactory::getCache('com_dartsleague');
		$cache->clean();
		$mainframe->redirect('index.php?option=com_dartsleague&view=players', JText::_('Move completed'));

	}

}
