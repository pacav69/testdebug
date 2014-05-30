<?php
/**
 * @version		$Id: dartsleagueplugin.php 478 2010-06-16 16:11:42Z SilverPC Consultants. $
 * @package		dartsleague
 * @author		SilverPC Consultants.
 * @copyright	Copyright (c) 2009 - 2011 SilverPC Consultants. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');
JLoader::register('dartsleagueParameter', JPATH_ADMINISTRATOR.DS.'components'.DS.'com_dartsleague'.DS.'lib'.DS.'dartsleagueparameter.php');

class dartsleaguePlugin extends JPlugin {

	/**
	 * Below we list all available BACKEND events, to trigger dartsleague plugins and generate additional fields in the item, category and user forms.
	 */

	/* ------------ Functions to render plugin parameters in the backend - no need to change anything ------------ */
	function onRenderAdminForm( & $item, $type, $tab='') {
	
		$mainframe = &JFactory::getApplication();
		$form = new dartsleagueParameter($item->plugins, JPATH_SITE.DS.'plugins'.DS.'dartsleague'.DS.$this->pluginName.'.xml', $this->pluginName);
		if ( !empty ($tab)) {
			$path = $type.'-'.$tab;
		}
		else {
			$path = $type;
		}
		$fields = $form->render('plugins', $path);
		if ($fields){
			$plugin = new JObject;
			$plugin->set('name', $this->pluginNameHumanReadable);
			$plugin->set('fields', $fields);
			return $plugin;	
		}

	}

}

