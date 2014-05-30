<?php
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );
// import the Joomla modellist library
jimport ( 'joomla.application.component.modellist' );
/**
 * DartsLeagueList Model
 */
class DartsLeagueModelDartsLeagues extends JModelList {
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return string SQL query
	 */
	protected function getListQuery() {
		// Create a new query object.
		$db = JFactory::getDBO ();
		$query = $db->getQuery ( true );
		// Select some fields
		$query->select ( 'id,firstname' );
		// From the dartsleague_players table
		$query->from ( '#__dartsleague_players' );
		return $query;
	}
}
