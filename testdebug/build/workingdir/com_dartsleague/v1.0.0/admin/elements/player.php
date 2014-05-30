<?php
/**
 * @version		$Id: player.php 478 2010-06-16 16:11:42Z SilverPC Consultants. $
 * @package		dartsleague elements player
 * @author		SilverPC Consultants.
 * @copyright	Copyright (c) 2009 - 2011 SilverPC Consultants. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class JElementplayer extends JElement
{

	var $_name = 'player';

	function fetchElement($firstname, $value, & $node, $control_name)
	{
	
		$mainframe = &JFactory::getApplication();
	
		$db = & JFactory::getDBO();
		$doc = & JFactory::getDocument();
		$fieldName = $control_name.'['.$name.']';
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_dartsleague'.DS.'tables');
		$player = & JTable::getInstance('dartsleagueplayer', 'Table');
	
		if ($value) {
			$player->load($value);
		}
		else {
			$player->title = JText::_('Select an player...');
		}
	
		$js = "
		function jSelectplayer(id, name) {
			document.getElementById(object + '_id').value = id;
			document.getElementById(object + '_name').value = firstname;
			document.getElementById('sbox-window').close();
		}
		";
		
		$doc->addScriptDeclaration($js);
	
		$link = 'index.php?option=com_dartsleague&amp;view=player&amp;task=element&amp;tmpl=component&amp;object='.$name;
	
		JHTML::_('behavior.modal', 'a.modal');
	
		$html = '
		<div style="float:left;">
			<input style="background:#fff;margin:3px 0;" type="text" id="'.$name.'_name" value="'.htmlspecialchars($player->weekno, ENT_QUOTES, 'UTF-8').'" disabled="disabled" />
		</div>
		<div class="button2-left">
			<div class="blank">
				<a class="modal" title="'.JText::_('Select an player').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 700, y: 450}}">'.JText::_('Select').'</a>
			</div>
		</div>
		<input type="hidden" id="'.$name.'_id" name="'.$fieldName.'" value="'.( int )$value.'" />
		';
	
		return $html;
	}

}

