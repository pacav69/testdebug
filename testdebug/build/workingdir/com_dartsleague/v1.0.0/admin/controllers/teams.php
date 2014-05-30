<?php
/**
 * @version		$Id: teams.php 478 2010-06-16 16:11:42Z SilverPC Consultants. $
 * @package		dartsleague teams
 * @author		SilverPC Consultants.
 * @copyright	Copyright (c) 2009 - 2011 SilverPC Consultants. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class dartsleagueControllerTeams extends JController
{

	function display() {
		JRequest::setVar('view', 'Teams');
		parent::display();
	}

	function publish() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('teams');
		$model->publish();
	}

	function unpublish() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('teams');
		$model->unpublish();
	}

	function saveorder() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('teams');
		$model->saveorder();
	}

	function orderup() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('teams');
		$model->orderup();
	}

	function orderdown() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('teams');
		$model->orderdown();
	}

	function savefeaturedorder() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('teams');
		$model->savefeaturedorder();
	}

	function featuredorderup() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('teams');
		$model->featuredorderup();
	}

	function featuredorderdown() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('teams');
		$model->featuredorderdown();
	}

	function accessregistered() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('teams');
		$model->accessregistered();
	}

	function accessspecial() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('teams');
		$model->accessspecial();
	}

	function accesspublic() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('teams');
		$model->accesspublic();
	}

	function featured() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('teams');
		$model->featured();
	}

	function trash() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('teams');
		$model->trash();
	}

	function restore() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('teams');
		$model->restore();
	}

	function remove() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('teams');
		$model->remove();
	}
    // team not to be confused with team_s_
	function add() {
		$mainframe = &JFactory::getApplication();
		$mainframe->redirect('index.php?option=com_dartsleague&view=team');
	}
      // team not to be confused with team_s_   
	function edit() {
		$mainframe = &JFactory::getApplication();
		$cid = JRequest::getVar('cid');
		$mainframe->redirect('index.php?option=com_dartsleague&view=team&cid='.$cid[0]);
	}

	function copy() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('teams');
		$model->copy();
	}

	function element() {
		JRequest::setVar('view', 'teams');
		JRequest::setVar('layout', 'element');
		parent::display();
	}
	
	function import(){
		$model = & $this->getModel('teams');
		$model->import();
	}
	
	function move(){
		$view = & $this->getView('teams', 'html');
		$view->setLayout('move');
		$view->move();
	}
	
	function saveMove(){
		$model = & $this->getModel('teams');
		$model->move();
	}

}
