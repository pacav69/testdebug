<?php
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// import Joomla controlleradmin library
jimport ( 'joomla.application.component.controlleradmin' );

/**
 * DartsLeagues Controller
 */
class DartsLeagueControllerDartsLeagues extends JControllerAdmin {
	/**
	 * Proxy for getModel.
	 *
	 * @since 1.6
	 */
	public function getModel($name = 'DartsLeague', $prefix = 'DartsLeagueModel') {
		$model = parent::getModel ( $name, $prefix, array (
				'ignore_request' => true 
		) );
		return $model;
	}
}
