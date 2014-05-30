<?php
/**
 * @version		$Id: scoresheets.php 478 2010-06-16 16:11:42Z SilverPC Consultants. $
 * @package		dartsleague
 * @author		SilverPC Consultants.
 * @copyright	Copyright (c) 2009 - 2011 SilverPC Consultants. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class JElementscoresheets extends JElement
{

	var	$_name = 'scoresheets';

	function fetchElement($name, $value, &$node, $control_name){
		$document = & JFactory::getDocument();
		$js = "		
		function jSelectItem(id, title, object) {
			var exists = false;
			$$('#scoresheetsList input').each(function(element){
					if(element.value==id){
						alert('".JText::_('Scoresheet exists already in the list')."');
						exists = true;			
					}
			});
			if(!exists){
				var container = new Element('div').injectInside($('scoresheetsList'));
				var img = new Element('img',{'class':'remove', 'src':'images/publish_x.png'}).injectInside(container);
				var span = new Element('span',{'class':'handle'}).setHTML(title).injectInside(container);
				var input = new Element('input',{'value':id, 'type':'hidden', 'name':'".$control_name."[".$name."][]'}).injectInside(container);
				var div = new Element('div',{'style':'clear:both;'}).injectInside(container);
				fireEvent('sortingready');
				alert('".JText::_('Item added in the list')."');			
			}
		}
		
		window.addEvent('domready', function(){			
			fireEvent('sortingready');
		});
		
		window.addEvent('sortingready', function(){
			new Sortables($('scoresheetsList'), {
			 	handles:$$('.handle')
			
			});
			$$('#scoresheetsList .remove').addEvent('click', function(){
				$(this).getParent().remove();
			});
		});
		";

		$document->addScriptDeclaration($js);
		
		$css = "
		#scoresheetsList {
			height:100px;
			overflow:hidden;
		}
		#scoresheetsList span {
			display:inline-block;
			height:16px;
			line-height:16px;
		}
		#scoresheetsList span.handle {
			cursor:move;
		}
		#scoresheetsList img.remove {
			width:16px;
			height:16px;
			margin-right:4px;
			cursor:pointer;
			float:left;
		}
		";
		$document->addStyleDeclaration($css);
		$current = array();
		if(is_string($value) && !empty($value))
			$current[]=$value;
		if(is_array($value))
			$current=$value;
			
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_dl'.DS.'tables');
		$output = '<div id="scoresheetsList">';
		foreach($current as $id){
			$row = &JTable::getInstance('dartsleagueScoresheet', 'Table');
			$row->load($id);
			$output .= '
			<div>
				<img class="remove" src="images/publish_x.png"/>
				<span class="handle">'.$row->title.'</span>
				<input type="hidden" value="'.$row->id.'" name="'.$control_name.'['.$name.'][]"/>
				<div style="clear:both;"></div>
			</div>
			';
		}
		$output .= '</div>';
		return $output;
	}
}
