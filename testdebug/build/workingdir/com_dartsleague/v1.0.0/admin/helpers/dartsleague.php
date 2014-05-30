<?php
// No direct access to this file
defined ( '_JEXEC' ) or die ();

/**
 * DartsLeague component helper.
 */
abstract class DartsLeagueHelper {
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($submenu) {
		JSubMenuHelper::addEntry ( JText::_ ( 'COM_DARTSLEAGUE_SUBMENU_PLAYERS' ), 'index.php?option=com_dartsleague', $submenu == 'players' );
		JSubMenuHelper::addEntry ( JText::_ ( 'COM_DARTSLEAGUE_SUBMENU_VENUES' ), 'index.php?option=com_dartsleague&view=venues&extension=com_dartsleague', $submenu == 'venues' );
		JSubMenuHelper::addEntry ( JText::_ ( 'COM_DARTSLEAGUE_SUBMENU_TEAMS' ), 'index.php?option=com_dartsleague&view=teams&extension=com_dartsleague', $submenu == 'teams' );
		
		// set some global property
		$document = JFactory::getDocument ();
		$document->addStyleDeclaration ( '.icon-48-dartsleague {background-image: url(../media/com_dartsleague/images/silverpclogo48x48.png});}' );
		if ($submenu == 'categories') {
			$document->setTitle ( JText::_ ( 'COM_DARTSLEAGUE_ADMINISTRATION_CATEGORIES' ) );
		}
	}
	/**
	 * Get the actions
	 */
	public static function getActions($messageId = 0) {
		$user = JFactory::getUser ();
		$result = new JObject ();
		
		if (empty ( $messageId )) {
			$assetName = 'com_dartsleague';
		} else {
			$assetName = 'com_dartsleague.message.' . ( int ) $messageId;
		}
		
		$actions = array (
				'core.admin',
				'core.manage',
				'core.create',
				'core.edit',
				'core.delete' 
		);
		
		foreach ( $actions as $action ) {
			$result->set ( $action, $user->authorise ( $action, $assetName ) );
		}
		
		return $result;
	}
}
