<?php
/**
 * @version		$Id: dartsleaguevenue.php 478 2010-06-16 16:11:42Z SilverPC Consultants. $
 * @package		dartsleague tables dartsleaguevenue
 * @author		SilverPC Consultants.
 * @copyright	Copyright (c) 2009 - 2011 SilverPC Consultants. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class Tabledartsleaguevenue extends JTable {

	var $id = null;
    var $name = null;
    var $address = null; 
    var $contact = null;
    var $boardnum = null;
    var $mapinfo = null; 
    var $vip = null;    
    var $introtext = null;
    var $fulltext = null;
    var $published = null;
    var $extra_fields = null;  
    var $trash = null;
    var $catid = null;
    var $ordering = null; 
    var $checked_out = null;
    var $checked_out_time = null; 
    var $hits = null;
    var $params = null;
    
    
    /**
  var $email = null;
	var $alias = null;
	var $catid = null;
	var $published = null;
	var $introtext = null;
	var $fulltext = null;
	var $image_caption = null;
	var $image_credits = null;
	var $video = null;
	var $video_caption = null;
	var $video_credits = null;
	var $gallery = null;
	var $extra_fields = null;
	var $extra_fields_search = null;
	var $created = null;
	var $created_by = null;
	var $created_by_alias = null;
	var $modified = null;
	var $modified_by = null;
	var $publish_up = null;
	var $publish_down = null;
	var $checked_out = null;
	var $checked_out_time = null;
	var $trash = null;
	var $access = null;
	var $ordering = null;
	var $featured = null;
	var $featured_ordering = null;
	var $hits = null;
	var $metadata = null;
	var $metadesc = null;
	var $metakey = null;
	var $params = null;
	var $plugins = null;
    */
	function __construct(&$db) {

		parent::__construct('#__dartsleague_venues', 'id', $db);
	}

	function check() {

		if (trim($this->name) == '') {
			$this->setError(JText::_('Venue must have a name'));
			return false;
		}
  /**

		if(JPluginHelper::isEnabled('system', 'unicodeslug'))
			$this->alias = JFilterOutput::stringURLSafe($this->alias);
		else {
			mb_internal_encoding("UTF-8");
			mb_regex_encoding("UTF-8");
			$this->alias = trim(mb_strtolower($this->alias));
			$this->alias = str_replace('-', ' ', $this->alias);
			$this->alias = mb_ereg_replace('[[:space:]]+', ' ', $this->alias);
			$this->alias = trim(str_replace(' ', '-', $this->alias));
			$this->alias = str_replace('.', '', $this->alias);
			$this->alias = str_replace('"', '', $this->alias);
			$this->alias = str_replace("'", '', $this->alias);

			$stripthese = ',|~|!|@|%|^|(|)|<|>|:|;|{|}|[|]|&|`|â€ž|â€¹|â€™|â€˜|â€œ|â€�|â€¢|â€º|Â«|Â´|Â»|Â°|«|»|…';
			$strips = explode('|', $stripthese);
			foreach ($strips as $strip) {
				$this->alias = str_replace($strip, '', $this->alias);
			}


			$params = &JComponentHelper::getParams('com_dartsleague');
			$SEFReplacements = array();
			$items = explode(',', $params->get('SEFReplacements'));
			foreach ($items as $item) {
				if (! empty($item)) {
					@list($src, $dst) = explode('|', trim($item));
					$SEFReplacements[trim($src)] = trim($dst);
				}
			}


			foreach ($SEFReplacements as $key=>$value) {
				$this->alias = str_replace($key, $value, $this->alias);
			}

			$this->alias = trim($this->alias, '-.');

			if (trim(str_replace('-', '', $this->alias)) == '') {
				$datenow = &JFactory::getDate();
				$this->alias = $datenow->toFormat("%Y-%m-%d-%H-%M-%S");
			}
			 
			 
		}     */


		return true;

	}

	function bind($array, $ignore = '') {

		if (key_exists('params', $array) && is_array($array['params'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString();
		}

		if (key_exists('plugins', $array) && is_array($array['plugins'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['plugins']);
			$array['plugins'] = $registry->toString();
		}

		return parent::bind($array, $ignore);
	}

	function getNextOrder($where = '', $column = 'ordering') {

		$query = "SELECT MAX({$column}) FROM #__dartsleague_venues";
		$query .= ($where ? " WHERE ".$where : "");
		$this->_db->setQuery($query);
		$maxord = $this->_db->loadResult();
		if ($this->_db->getErrorNum()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return $maxord + 1;
	}

	function reorder($where = '', $column = 'ordering') {

		$k = $this->_tbl_key;
		$query = "SELECT {$this->_tbl_key}, {$column} FROM #__dartsleague_venues WHERE {$column}>0";
		$query .= ($where ? " AND ".$where : "");
		$query .= " ORDER BY {$column}";

		$this->_db->setQuery($query);
		if (!($orders = $this->_db->loadObjectList())) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}


		for ($i = 0, $n = count($orders); $i < $n; $i++) {
			if ($orders[$i]->$column >= 0) {
				if ($orders[$i]->$column != $i + 1) {
					$orders[$i]->$column = $i + 1;
					$query = "UPDATE #__dartsleague_venues SET {$column}=".(int) $orders[$i]->$column;
					$query .= ' WHERE '.$k.' = '.$this->_db->Quote($orders[$i]->$k);
					$this->_db->setQuery($query);
					$this->_db->query();
				}
			}
		}

		return true;
	}

	function move($dirn, $where = '', $column='ordering') {
		 
		$k = $this->_tbl_key;

		$sql = "SELECT $this->_tbl_key, {$column} FROM $this->_tbl";

		if ($dirn < 0) {
			$sql .= ' WHERE '.$column.' < '.(int) $this->$column;
			$sql .= ($where ? ' AND '.$where : '');
			$sql .= ' ORDER BY '.$column.' DESC';
		} else if ($dirn > 0) {
			$sql .= ' WHERE '.$column.' > '.(int) $this->$column;
			$sql .= ($where ? ' AND '.$where : '');
			$sql .= ' ORDER BY '.$column;
		} else {
			$sql .= ' WHERE '.$column.' = '.(int) $this->$column;
			$sql .= ($where ? ' AND '.$where : '');
			$sql .= ' ORDER BY '.$column;
		}

		$this->_db->setQuery($sql, 0, 1);


		$row = null;
		$row = $this->_db->loadObject();

		if (isset($row)) {
			$query = 'UPDATE '.$this->_tbl.' SET '.$column.' = '.(int) $row->$column.' WHERE '.$this->_tbl_key.' = '.$this->_db->Quote($this->$k);
			$this->_db->setQuery($query);

			if (!$this->_db->query()) {
				$err = $this->_db->getErrorMsg();
				JError::raiseError(500, $err);
			}

			$query = 'UPDATE '.$this->_tbl.' SET '.$column.' = '.(int) $this->$column.' WHERE '.$this->_tbl_key.' = '.$this->_db->Quote($row->$k);
			$this->_db->setQuery($query);

			if (!$this->_db->query()) {
				$err = $this->_db->getErrorMsg();
				JError::raiseError(500, $err);
			}
			$this->$column = $row->$column;
		} else {
			$query = 'UPDATE '.$this->_tbl.' SET '.$column.' = '.(int) $this->$column.' WHERE '.$this->_tbl_key.' = '.$this->_db->Quote($this->$k);
			$this->_db->setQuery($query);

			if (!$this->_db->query()) {
				$err = $this->_db->getErrorMsg();
				JError::raiseError(500, $err);
			}
		}
		return true;
	}

}
