<?php
/**
 * @version		$Id: dartsleagueuser.php 478 2010-06-16 16:11:42Z SilverPC Consultants. $
 * @package		dartsleague
 * @author		SilverPC Consultants.
 * @copyright	Copyright (c) 2009 - 2011 SilverPC Consultants. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class TabledartsleagueUser extends JTable
{

	var $id = null;
	var $userID = null;
	var $userName = null;
	var $gender = null;
	var $description = null;
	var $image = null;
	var $url = null;
	var $group = null;
	var $plugins = null;

	function __construct( & $db) {
	
		parent::__construct('#__dartsleague_users', 'id', $db);
	}
	
	function check() {
	
		if (trim($this->url) != '' && substr($this->url, 0, 7) != 'http://')
		$this->url = 'http://'.$this->url;
		return true;
	}
	
	function bind($array, $ignore = '')	{
	
		if (key_exists('plugins', $array) && is_array($array['plugins'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['plugins']);
			$array['plugins'] = $registry->toString();
		}
		
		return parent::bind($array, $ignore);
	}
	
}
