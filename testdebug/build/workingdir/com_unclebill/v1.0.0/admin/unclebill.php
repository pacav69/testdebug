<?php
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// Access check.
if (! JFactory::getUser ()->authorise ( 'core.manage', 'com_unclebill' )) {
	return JError::raiseWarning ( 404, JText::_ ( 'JERROR_ALERTNOAUTHOR' ) );
}

// require helper file
JLoader::register ( 'UncleBillHelper', dirname ( __FILE__ ) . DS . 'helpers' . DS . 'unclebill.php' );

// import joomla controller library
jimport ( 'joomla.application.component.controller' );

// Get an instance of the controller prefixed by UncleBill
$controller = JController::getInstance ( 'UncleBill' );

// Perform the Request task
$controller->execute ( JRequest::getCmd ( 'task' ) );

// Redirect if set by the controller
$controller->redirect ();
