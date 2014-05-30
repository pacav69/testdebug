<?php
/**
 * @version		$Id: player.php 478 2010-06-16 16:11:42Z SilverPC Consultants. $
 * @package		player
 * @author		SilverPC Consultants.
 * @copyright	Copyright (c) 2009 - 2011 SilverPC Consultants. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class playerControllerplayer extends JController
{

	function display() {
		JRequest::setVar('view', 'player');
		parent::display();
	}

	function save() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('player');
		$model->save();
	}

	function apply() {
		$this->save();
	}

	function cancel() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('player');
		$model->cancel();
	}

	function tag() {
		$model = & $this->getModel('tag');
		$model->addTag();
	}

	function download(){
		$model = & $this->getModel('player');
		$model->download();
	}

	function resetHits(){
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('player');
		$model->resetHits();

	}

	function resetRating(){
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('player');
		$model->resetRating();

	}

	function filebrowser(){
		$view = & $this->getView('player', 'html');
		$view->setLayout('filebrowser');
		$view->filebrowser();

	}

}
