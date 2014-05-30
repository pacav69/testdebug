<?php
/**
 * @version		$Id: scoresheet.php 478 2010-06-16 16:11:42Z SilverPC Consultants. $
 * @package		dartsleague
 * @author		SilverPC Consultants.
 * @copyright	Copyright (c) 2009 - 2011 SilverPC Consultants. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class JElementscoresheet extends JElement
{

	var $_name = 'scoresheet';

	function fetchElement($name, $value, & $node, $control_name)
	{
	
		$mainframe = &JFactory::getApplication();
	
		$db = & JFactory::getDBO();
		$doc = & JFactory::getDocument();
		$fieldName = $control_name.'['.$name.']';
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_dartsleague'.DS.'tables');
		$scoresheet = & JTable::getInstance('dartsleaguescoresheet', 'Table');
	
		if ($value) {
			$scoresheet->load($value);
		}
		else {
			$scoresheet->title = JText::_('Select an scoresheet...');
		}
	
		$js = "
		function jSelectscoresheet(id, weekno, season) {
			document.getElementById(object + '_id').value = id;
			document.getElementById(object + '_name').value = weekno;
			document.getElementById('sbox-window').close();
		}
		";
		
		$doc->addScriptDeclaration($js);
	
		$link = 'index.php?option=com_dartsleague&amp;view=scoresheet&amp;task=element&amp;tmpl=component&amp;object='.$name;
	
		JHTML::_('behavior.modal', 'a.modal');
	
		$html = '
		<div style="float:left;">
			<input style="background:#fff;margin:3px 0;" type="text" id="'.$name.'_name" value="'.htmlspecialchars($scoresheet->weekno, ENT_QUOTES, 'UTF-8').'" disabled="disabled" />
		</div>
		<div class="button2-left">
			<div class="blank">
				<a class="modal" title="'.JText::_('Select an scoresheet').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 700, y: 450}}">'.JText::_('Select').'</a>
			</div>
		</div>
		<input type="hidden" id="'.$name.'_id" name="'.$fieldName.'" value="'.( int )$value.'" />
		';
	
		return $html;
	}

}

