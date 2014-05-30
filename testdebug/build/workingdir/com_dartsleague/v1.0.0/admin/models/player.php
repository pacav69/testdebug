<?php
/**
 * @version		$Id: player.php 567 2010-09-23 11:50:09Z SilverPC Consultants. $
 * @package		dartsleagueplayer
 * @author		SilverPC Consultants.
 * @copyright	Copyright (c) 2009 - 2011 SilverPC Consultants. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.application.component.model' );

JTable::addIncludePath ( JPATH_COMPONENT . DS . 'tables' );
class dartsleagueModelplayer extends JModel {
	function getData() {
		// cid is needed for the display of data in the edit mode
		$id = JRequest::getVar ( 'cid' );
		$row = &JTable::getInstance ( 'dartsleagueplayer', 'Table' );
		$row->load ( $id );
		return $row;
	}
	function save($front = false) {
		$mainframe = &JFactory::getApplication ();
		jimport ( 'joomla.filesystem.file' );
		jimport ( 'joomla.filesystem.archive' );
		require_once (JPATH_COMPONENT_ADMINISTRATOR . DS . 'lib' . DS . 'class.upload.php');
		$db = &JFactory::getDBO ();
		$user = &JFactory::getUser ();
		$row = &JTable::getInstance ( 'dartsleagueplayer', 'Table' );
		$params = &JComponentHelper::getParams ( 'com_dartsleague' );
		$nullDate = $db->getNullDate ();
		// player_s_ not to be confused with player
		if (! $row->bind ( JRequest::get ( 'post' ) )) {
			$mainframe->redirect ( 'index.php?option=com_dartsleague&view=players', $row->getError (), 'error' );
		}
		
		if ($front && $row->id == NULL) {
			if (! $user->authorize ( 'com_dartsleague', 'add', 'team', $row->teamid ) && ! $user->authorize ( 'com_dartsleague', 'add', 'team', 'all' )) {
				$mainframe->redirect ( 'index.php?option=com_dartsleague&view=player&task=add&tmpl=component', JText::_ ( 'You are not allowed to post to this team. Save failed.' ), 'error' );
			}
		}
		
		($row->id) ? $isNew = false : $isNew = true;
		
		if ($params->get ( 'mergeEditors' )) {
			$text = JRequest::getVar ( 'text', '', 'post', 'string', 2 );
			if ($params->get ( 'xssFiltering' )) {
				$filter = new JFilterInput ( array (), array (), 1, 1, 0 );
				$text = $filter->clean ( $text );
			}
			$pattern = '#<hr\s+id=("|\')system-readmore("|\')\s*\/*>#i';
			$tagPos = preg_match ( $pattern, $text );
			if ($tagPos == 0) {
				$row->introtext = $text;
				$row->fulltext = '';
			} else
				list ( $row->introtext, $row->fulltext ) = preg_split ( $pattern, $text, 2 );
		} else {
			$row->introtext = JRequest::getVar ( 'introtext', '', 'post', 'string', 2 );
			$row->fulltext = JRequest::getVar ( 'fulltext', '', 'post', 'string', 2 );
			if ($params->get ( 'xssFiltering' )) {
				$filter = new JFilterInput ( array (), array (), 1, 1, 0 );
				$row->introtext = $filter->clean ( $row->introtext );
				$row->fulltext = $filter->clean ( $row->fulltext );
			}
		}
		
		/**
		 * @todo: check code
		 *
		 * if ($row->id) {
		 * $datenow = &JFactory::getDate();
		 * $row->modified = $datenow->toMySQL();
		 * $row->modified_by = $user->get('id');
		 * } else {
		 * $row->ordering = $row->getNextOrder("teamid = {$row->teamid} AND trash = 0");
		 * if (JRequest::getInt('featured'))
		 * $row->featured_ordering = $row->getNextOrder("featured = 1 AND trash = 0", 'featured_ordering');
		 * }
		 */
		
		/**
		 * if ($front) {
		 * if (!$row->id)
		 * $row->created_by = $user->get('id');
		 * } else {
		 * $row->created_by = $row->created_by ? $row->created_by : $user->get('id');
		 * }
		 *
		 * if ($row->created && strlen(trim($row->created)) <= 10) {
		 * $row->created .= ' 00:00:00';
		 * }
		 *
		 * $config = &JFactory::getConfig();
		 * $tzoffset = $config->getValue('config.offset');
		 * $date = &JFactory::getDate($row->created, $tzoffset);
		 * $row->created = $date->toMySQL();
		 *
		 * if (strlen(trim($row->publish_up)) <= 10) {
		 * $row->publish_up .= ' 00:00:00';
		 * }
		 *
		 * $date = &JFactory::getDate($row->publish_up, $tzoffset);
		 * $row->publish_up = $date->toMySQL();
		 *
		 * if (trim($row->publish_down) == JText::_('Never') || trim($row->publish_down) == '') {
		 * $row->publish_down = $nullDate;
		 * } else {
		 * if (strlen(trim($row->publish_down)) <= 10) {
		 * $row->publish_down .= ' 00:00:00';
		 * }
		 * $date = &JFactory::getDate($row->publish_down, $tzoffset);
		 * $row->publish_down = $date->toMySQL();
		 * }
		 *
		 * $metadata = JRequest::getVar('meta', null, 'post', 'array');
		 * if (is_array($metadata)) {
		 * $txt = array();
		 * foreach ($metadata as $k=>$v) {
		 * if ($k == 'description') {
		 * $row->metadesc = $v;
		 * } elseif ($k == 'keywords') {
		 * $row->metakey = $v;
		 * } else {
		 * $txt[] = "$k=$v";
		 * }
		 * }
		 * $row->metadata = implode("\n", $txt);
		 * }
		 */
		// $row->featured = JRequest::getInt('featured');
		// player not to be confused with player_s_
		if (! $row->check ()) {
			$mainframe->redirect ( 'index.php?option=com_dartsleague&view=player&cid=' . $row->id, $row->getError (), 'error' );
		}
		
		$dispatcher = &JDispatcher::getInstance ();
		// JPluginHelper::importPlugin('dartsleague');
		$result = $dispatcher->trigger ( 'onBeforedartsleagueSave', array (
				&$row,
				$isNew 
		) );
		if (in_array ( false, $result, true )) {
			JError::raiseError ( 500, $row->getError () );
			return false;
		}
		
		if (version_compare ( phpversion (), '5.0' ) < 0) {
			$tmpRow = $row;
		} else {
			$tmpRow = clone ($row);
		}
		// player_s_ not to be confused with player
		if (! $row->store ()) {
			$mainframe->redirect ( 'index.php?option=com_dartsleague&view=players', $row->getError (), 'error' );
		}
		$playerID = $row->id;
		$row = $tmpRow;
		$row->id = $playerID;
		
		if (! $params->get ( 'disableCompactOrdering' ))
			$row->reorder ( "teamid = {$row->teamid} AND trash = 0" );
		
		if (JRequest::getInt ( 'featured' ) && ! $params->get ( 'disableCompactOrdering' ))
			$row->reorder ( "featured = 1 AND trash = 0", 'featured_ordering' );
		
		$files = JRequest::get ( 'files' );
		
		$mainframe->redirect ( 'index.php?option=com_dartsleague&view=player', $handle->error, 'error' );
	}
}

if ($front) {
	if (! dartsleagueHelperPermissions::canPublishplayer ( $row->teamid ) && $row->published == 1) {
		$row->published = 0;
		$mainframe->enqueueMessage ( JText::_ ( "You don't have the permission to publish players." ), 'notice' );
	}
}
// player_s_ not to be confused with player
if (! $row->store ()) {
	$mainframe->redirect ( 'index.php?option=com_dartsleague&view=players', $row->getError (), 'error' );
}

$row->checkin ();

$cache = &JFactory::getCache ( 'com_dartsleague' );
$cache->clean ();

$dispatcher->trigger ( 'onAfterdartsleagueSave', array (
		&$row,
		$isNew 
) );

switch (JRequest::getCmd ( 'task' )) {
	case 'apply' :
		$msg = JText::_ ( 'Changes to player saved' );
		// player not to be confused with player_s_
		$link = 'index.php?option=com_dartsleague&view=player&cid=' . $row->id;
		break;
	case 'saveAndNew' :
		$msg = JText::_ ( 'player saved' );
		// player not to be confused with player_s_
		$link = 'index.php?option=com_dartsleague&view=player';
		break;
	case 'save' :
	default :
		$msg = JText::_ ( 'player Saved' );
		if ($front)
			// player not to be confused with player_s_
			$link = 'index.php?option=com_dartsleague&view=player&task=edit&cid=' . $row->id . '&tmpl=component';
		else
			// player_s_ not to be confused with player
			$link = 'index.php?option=com_dartsleague&view=players';
		break;
}
$mainframe->redirect ( $link, $msg );
function cancel() {
	$mainframe = &JFactory::getApplication ();
	$cid = JRequest::getInt ( 'id' );
	$row = &JTable::getInstance ( 'dartsleagueplayer', 'Table' );
	$row->load ( $cid );
	$row->checkin ();
	$msg = JText::_ ( 'NO_CHANGES_MADE' );
	// player_s_ not to be confused with player
	$mainframe->redirect ( 'index.php?option=com_dartsleague&view=players', $msg );
}
function resetHits() {
	$mainframe = &JFactory::getApplication ();
	$cid = JRequest::getInt ( 'id' );
	$db = &JFactory::getDBO ();
	$query = "UPDATE #__dartsleague_players SET hits=0 WHERE id={$id}";
	$db->setQuery ( $query );
	$db->query ();
	if ($mainframe->isAdmin ())
		// player not to be confused with player_s_
		$url = 'index.php?option=com_dartsleague&view=player&cid=' . $id;
	else
		// player not to be confused with player_s_
		$url = 'index.php?option=com_dartsleague&view=player&task=edit&cid=' . $id . '&tmpl=component';
	$mainframe->redirect ( $url, JText::_ ( 'Successfully reset player hits' ) );
}
function resetRating() {
	$mainframe = &JFactory::getApplication ();
	$id = JRequest::getInt ( 'id' );
	$db = &JFactory::getDBO ();
	$query = "DELETE FROM #__dartsleague_rating WHERE playerID={$id}";
	$db->setQuery ( $query );
	$db->query ();
	if ($mainframe->isAdmin ())
		// player not to be confused with player_s_
		$url = 'index.php?option=com_dartsleague&view=player&cid=' . $id;
	else
		// player not to be confused with player_s_
		$url = 'index.php?option=com_dartsleague&view=player&task=edit&cid=' . $id . '&tmpl=component';
	$mainframe->redirect ( $url, JText::_ ( 'Successfully reset player rating' ) );
}
function getRating() {
	$id = JRequest::getInt ( 'cid' );
	$db = &JFactory::getDBO ();
	$query = "SELECT * FROM #__dartsleague_rating WHERE playerID={$id}";
	$db->setQuery ( $query, 0, 1 );
	$row = $db->loadObject ();
	return $row;
}
function checkSIG() {
	$mainframe = &JFactory::getApplication ();
	if (JFile::exists ( JPATH_PLUGINS . DS . 'content' . DS . 'jw_sigpro.php' )) {
		return true;
	} else {
		return false;
	}
}
