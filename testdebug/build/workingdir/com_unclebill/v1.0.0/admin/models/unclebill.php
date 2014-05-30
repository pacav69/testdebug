<?php
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// import Joomla modelform library
jimport ( 'joomla.application.component.modeladmin' );

/**
 * UncleBill Model
 */
class UncleBillModelUncleBill extends JModelAdmin {
	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param array $data
	 *        	of input data.
	 * @param string $key
	 *        	of the key for the primary key.
	 *        	
	 * @return boolean
	 * @since 1.6
	 */
	protected function allowEdit($data = array(), $key = 'id') {
		// Check specific edit permission then general edit permission.
		return JFactory::getUser ()->authorise ( 'core.edit', 'com_unclebill.message.' . (( int ) isset ( $data [$key] ) ? $data [$key] : 0) ) or parent::allowEdit ( $data, $key );
	}
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param
	 *        	type	The table type to instantiate
	 * @param
	 *        	string	A prefix for the table class name. Optional.
	 * @param
	 *        	array	Configuration array for model. Optional.
	 * @return JTable database object
	 * @since 1.6
	 */
	public function getTable($type = 'UncleBill', $prefix = 'UncleBillTable', $config = array()) {
		return JTable::getInstance ( $type, $prefix, $config );
	}
	/**
	 * Method to get the record form.
	 *
	 * @param array $data
	 *        	the form.
	 * @param boolean $loadData
	 *        	the form is to load its own data (default case), false if not.
	 * @return mixed JForm object on success, false on failure
	 * @since 1.6
	 */
	public function getForm($data = array(), $loadData = true) {
		// Get the form.
		$form = $this->loadForm ( 'com_unclebill.unclebill', 'unclebill', array (
				'control' => 'jform',
				'load_data' => $loadData 
		) );
		if (empty ( $form )) {
			return false;
		}
		return $form;
	}
	/**
	 * Method to get the script that have to be included on the form
	 *
	 * @return string files
	 */
	public function getScript() {
		return 'administrator/components/com_unclebill/models/forms/unclebill.js';
	}
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return mixed data for the form.
	 * @since 1.6
	 */
	protected function loadFormData() {
		// Check the session for previously entered form data.
		$data = JFactory::getApplication ()->getUserState ( 'com_unclebill.edit.unclebill.data', array () );
		if (empty ( $data )) {
			$data = $this->getItem ();
		}
		return $data;
	}
}
