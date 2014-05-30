<?php
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// import Joomla view library
jimport ( 'joomla.application.component.view' );

/**
 * UncleBill View
 */
class UncleBillViewUncleBill extends JView {
	/**
	 * display method of Hello view
	 *
	 * @return void
	 */
	public function display($tpl = null) {
		// get the Data
		$form = $this->get ( 'Form' );
		$item = $this->get ( 'Item' );
		$script = $this->get ( 'Script' );
		
		// Check for errors.
		if (count ( $errors = $this->get ( 'Errors' ) )) {
			JError::raiseError ( 500, implode ( '<br />', $errors ) );
			return false;
		}
		// Assign the Data
		$this->form = $form;
		$this->item = $item;
		$this->script = $script;
		
		// Set the toolbar
		$this->addToolBar ();
		
		// Display the template
		parent::display ( $tpl );
		
		// Set the document
		$this->setDocument ();
	}
	
	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() {
		JRequest::setVar ( 'hidemainmenu', true );
		$user = JFactory::getUser ();
		$userId = $user->id;
		$isNew = $this->item->id == 0;
		$canDo = UncleBillHelper::getActions ( $this->item->id );
		JToolBarHelper::title ( $isNew ? JText::_ ( 'COM_UNCLEBILL_MANAGER_UNCLEBILL_NEW' ) : JText::_ ( 'COM_UNCLEBILL_MANAGER_UNCLEBILL_EDIT' ), 'unclebill' );
		// Built the actions for new and existing records.
		if ($isNew) {
			// For new records, check the create permission.
			if ($canDo->get ( 'core.create' )) {
				JToolBarHelper::apply ( 'unclebill.apply', 'JTOOLBAR_APPLY' );
				JToolBarHelper::save ( 'unclebill.save', 'JTOOLBAR_SAVE' );
				JToolBarHelper::custom ( 'unclebill.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false );
			}
			JToolBarHelper::cancel ( 'unclebill.cancel', 'JTOOLBAR_CANCEL' );
		} else {
			if ($canDo->get ( 'core.edit' )) {
				// We can save the new record
				JToolBarHelper::apply ( 'unclebill.apply', 'JTOOLBAR_APPLY' );
				JToolBarHelper::save ( 'unclebill.save', 'JTOOLBAR_SAVE' );
				
				// We can save this record, but check the create permission to see if we can return to make a new one.
				if ($canDo->get ( 'core.create' )) {
					JToolBarHelper::custom ( 'unclebill.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false );
				}
			}
			if ($canDo->get ( 'core.create' )) {
				JToolBarHelper::custom ( 'unclebill.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false );
			}
			JToolBarHelper::cancel ( 'unclebill.cancel', 'JTOOLBAR_CLOSE' );
		}
	}
	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument() {
		$isNew = $this->item->id == 0;
		$document = JFactory::getDocument ();
		$document->setTitle ( $isNew ? JText::_ ( 'COM_UNCLEBILL_UNCLEBILL_CREATING' ) : JText::_ ( 'COM_UNCLEBILL_UNCLEBILL_EDITING' ) );
		$document->addScript ( JURI::root () . $this->script );
		$document->addScript ( JURI::root () . "/administrator/components/com_unclebill/views/unclebill/submitbutton.js" );
		JText::script ( 'COM_UNCLEBILL_UNCLEBILL_ERROR_UNACCEPTABLE' );
	}
}
