<?php
/**
 * @version		$Id: dartsleagueusergroup.php 478 2010-06-16 16:11:42Z SilverPC Consultants. $
 * @package		dartsleague
 * @author		SilverPC Consultants.
 * @copyright	Copyright (c) 2009 - 2011 SilverPC Consultants. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class TabledartsleagueUserGroup extends JTable {

	var $id = null;
	var $name = null;
	var $permissions = null;

	function __construct( & $db) {
	
		parent::__construct('#__dartsleague_user_groups', 'id', $db);
	}

	function check() {
	
		if (trim($this->name) == '') {
			$this->setError(JText::_('Group cannot be empty'));
			return false;
		}
		return true;
	}

	function bind($array, $ignore = '') {
		
		if (key_exists('params', $array) && is_array($array['params'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			if(JRequest::getVar('categories')=='all' || JRequest::getVar('categories')=='none')
			$registry->setValue('categories',JRequest::getVar('categories'));
			$array['permissions'] = $registry->toString();
		}
		return parent::bind($array, $ignore);
	}

}
