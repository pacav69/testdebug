<?php
/**
 * @version		$Id: players.php 478 2010-06-16 16:11:42Z SilverPC Consultants. $
 * @package		players
 * @author		SilverPC Consultants.
 * @copyright	Copyright (c) 2009 - 2011 SilverPC Consultants. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class playersControllerplayers extends JController
{

	function display() {
		JRequest::setVar('view', 'players');
		parent::display();
	}

	function publish() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('players');
		$model->publish();
	}

	function unpublish() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('players');
		$model->unpublish();
	}

	function saveorder() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('players');
		$model->saveorder();
	}

	function orderup() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('players');
		$model->orderup();
	}

	function orderdown() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('players');
		$model->orderdown();
	}

	function savefeaturedorder() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('players');
		$model->savefeaturedorder();
	}

	function featuredorderup() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('players');
		$model->featuredorderup();
	}

	function featuredorderdown() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('players');
		$model->featuredorderdown();
	}

	function accessregistered() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('players');
		$model->accessregistered();
	}

	function accessspecial() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('players');
		$model->accessspecial();
	}

	function accesspublic() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('players');
		$model->accesspublic();
	}

/* 	function featured() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('players');
		$model->featured();
	} */

	function trash() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('players');
		$model->trash();
	}

	function restore() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('players');
		$model->restore();
	}

	function remove() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('players');
		$model->remove();
	}
    // player not to be confused with player_s_
	function add() {
		$mainframe = &JFactory::getApplication();
		$mainframe->redirect('index.php?option=com_dartsleague&view=player');
	}
      // player not to be confused with player_s_   
	function edit() {
		$mainframe = &JFactory::getApplication();
		$cid = JRequest::getVar('cid');
		$mainframe->redirect('index.php?option=com_dartsleague&view=player&cid='.$cid[0]);
	}

	function copy() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('players');
		$model->copy();
	}

	function element() {
		JRequest::setVar('view', 'players');
		JRequest::setVar('layout', 'element');
		parent::display();
	}
	
	function import(){
		$model = & $this->getModel('players');
		$model->import();
	}
	
	function move(){
		$view = & $this->getView('players', 'html');
		$view->setLayout('move');
		$view->move();
	}
	
	function saveMove(){
		$model = & $this->getModel('players');
		$model->move();
	}

}
