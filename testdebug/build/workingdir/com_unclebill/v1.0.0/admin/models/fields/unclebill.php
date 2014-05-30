<?php
// No direct access to this file
defined ( '_JEXEC' ) or die ();

// import the list field type
jimport ( 'joomla.form.helper' );
JFormHelper::loadFieldClass ( 'list' );

/**
 * UncleBill Form Field class for the UncleBill component
 */
class JFormFieldUncleBill extends JFormFieldList {
	/**
	 * The field type.
	 *
	 * @var string
	 */
	protected $type = 'UncleBill';
	
	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return array array of JHtml options.
	 */
	protected function getOptions() {
		$db = JFactory::getDBO ();
		$query = $db->getQuery ( true );
		$query->select ( '#__unclebill.id as id,greeting,#__categories.title as category,catid' );
		$query->from ( '#__unclebill' );
		$query->leftJoin ( '#__categories on catid=#__categories.id' );
		$db->setQuery ( ( string ) $query );
		$messages = $db->loadObjectList ();
		$options = array ();
		if ($messages) {
			foreach ( $messages as $message ) {
				$options [] = JHtml::_ ( 'select.option', $message->id, $message->greeting . ($message->catid ? ' (' . $message->category . ')' : '') );
			}
		}
		$options = array_merge ( parent::getOptions (), $options );
		return $options;
	}
}
