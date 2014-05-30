<?php
/**
 * @version		$Id: users.php 478 2010-06-16 16:11:42Z SilverPC Consultants. $
 * @package		dartsleague
 * @author		SilverPC Consultants.
 * @copyright	Copyright (c) 2009 - 2011 SilverPC Consultants. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class JElementUsers extends JElement
{

	var	$_name = 'users';

	function fetchElement($name, $value, &$node, $control_name){
		$document = & JFactory::getDocument();
		$js = "		
		function jSelectUser(id, title, object) {
			var exists = false;
			$$('#usersList input').each(function(element){
					if(element.value==id){
						alert('".JText::_('User exists already in the list')."');
						exists = true;			
					}
			});
			if(!exists){
				var container = new Element('div').injectInside($('usersList'));
				var img = new Element('img',{'class':'remove', 'src':'images/publish_x.png'}).injectInside(container);
				var span = new Element('span',{'class':'handle'}).setHTML(title).injectInside(container);
				var input = new Element('input',{'value':id, 'type':'hidden', 'name':'".$control_name."[".$name."][]'}).injectInside(container);
				var div = new Element('div',{'style':'clear:both;'}).injectInside(container);
				fireEvent('sortingready');
				alert('".JText::_('User added in the list')."');			
			}
		}
		
		window.addEvent('domready', function(){			
			fireEvent('sortingready');
		});
		window.addEvent('sortingready', function(){
			new Sortables($('usersList'), {
			 	handles:$$('.handle')
			
			});
		
			
			$$('#usersList .remove').addEvent('click', function(){
				$(this).getParent().remove();
			})
			
		});
		";

		$document->addScriptDeclaration($js);
		
		$css = "#usersList {
					height:100px;
					overflow:hidden;
				}
				#usersList span {
					display:inline-block;
					height:16px;
					line-height:16px;
				}
				#usersList span.handle {
					cursor:move;
				}
				#usersList img.remove {
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

		$output = '<div id="usersList">';
		foreach($current as $id){
			$row=&JFactory::getUser($id);
			$output.='
			<div>
				<img class="remove" src="images/publish_x.png"/>
				<span class="handle">'.$row->name.'</span>
				<input type="hidden" value="'.$row->id.'" name="'.$control_name.'['.$name.'][]"/>
				<div style="clear:both"></div>
			</div>';
		}
		$output.='</div>';
		return $output;
	}
}
