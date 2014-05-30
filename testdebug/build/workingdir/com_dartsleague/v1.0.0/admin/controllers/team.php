<?php
/**
 * @version		$Id: team.php 478 2010-06-16 16:11:42Z SilverPC Consultants. $
 * @package		dartsleague team
 * @author		SilverPC Consultants.
 * @copyright	Copyright (c) 2009 - 2011 SilverPC Consultants. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class dartsleagueControllerTeam extends JController
{

	function display() {
		JRequest::setVar('view', 'team');
		parent::display();
	}

	function save() {
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('team');
		$model->save();
	}

	function apply() {
		$this->save();
	}
    
/*    function cancel() {
        $mainframe = &JFactory::getApplication();
        $mainframe->redirect('index.php?option=com_dartsleague&view=teams');
    }  */
	function cancel() {
        $mainframe = &JFactory::getApplication();
        $msg = JText::_( 'NO_CHANGES_MADE' ); 
        $mainframe->redirect('index.php?option=com_dartsleague&view=teams', $msg);
       //         $msg = JText::_( 'GEANNULEERD' );
       //  $this->setRedirect( 'index.php?option=com_dartscores&view=instellingen', $msg );
	//	JRequest::checkToken() or jexit('Invalid Token');
	//	$model = & $this->getModel('team');
	//	$model->cancel();
	}

	function deleteAttachment() {
		$model = & $this->getModel('team');
		$model->deleteAttachment();
	}

	function tag() {
		$model = & $this->getModel('tag');
		$model->addTag();
	}

	function download(){
		$model = & $this->getModel('team');
		$model->download();
	}

	function extraFields(){
		$mainframe = &JFactory::getApplication();
		$teamID=JRequest::getInt('id',NULL);
		$categoryModel = & $this->getModel('category');
		$category=$categoryModel->getData();
		$extraFieldModel = & $this->getModel('extraField');
		$extraFields = $extraFieldModel->getExtraFieldsByGroup($category->extraFieldsGroup);

		$output='<table class="admintable" id="extraFields">';
		$counter=0;
		if (count($extraFields)){
			foreach ($extraFields as $extraField){
				$output.='<tr><td align="right" class="key">'.$extraField->name.'</td>';
				$output.='<td>'.$extraFieldModel->renderExtraField($extraField,$teamID).'</td></tr>';
				$counter++;
			}
		}
		$output.='</table>';

		if ($counter==0) $output=JText::_("This category doesn't have assigned extra fields");

		echo $output;

		$mainframe->close();
	}

	function resetHits(){
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('team');
		$model->resetHits();

	}

	function resetRating(){
		JRequest::checkToken() or jexit('Invalid Token');
		$model = & $this->getModel('team');
		$model->resetRating();

	}

	function filebrowser(){
		$view = & $this->getView('team', 'html');
		$view->setLayout('filebrowser');
		$view->filebrowser();

	}

}
