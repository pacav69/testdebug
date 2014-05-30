<?php
/**
 * @version		$Id: categories.php 478 2010-06-16 16:11:42Z SilverPC Consultants. $
 * @package		dartsleague elements categories
 * @author		SilverPC Consultants.
 * @copyright	Copyright (c) 2009 - 2011 SilverPC Consultants. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class JElementCategories extends JElement
{

	var	$_name = 'categories';

	function fetchElement($name, $value, &$node, $control_name) {
		$db = &JFactory::getDBO();

		$query = 'SELECT m.* FROM #__dartsleague_categories m WHERE published = 1 ORDER BY parent, ordering';
		$db->setQuery( $query );
		$mitems = $db->loadObjectList();
		$children = array();
		if ( $mitems )
		{
			foreach ( $mitems as $v )
			{
				$pt 	= $v->parent;
				$list = @$children[$pt] ? $children[$pt] : array();
				array_push( $list, $v );
				$children[$pt] = $list;
			}
		}
		$list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0 );
		$mitems = array();
		$mitems [] = JHTML::_ ( 'select.option', '0', '- ' . JText::_ ( 'None' ) . ' -' );

		foreach ( $list as $item ) {
			$mitems[] = JHTML::_('select.option',  $item->id, '&nbsp;&nbsp;&nbsp;'.$item->treename );
		}

		$attributes = 'class="inputbox"';
		if($node->attributes('multiple')){
			$attributes.=' multiple="multiple"';
		}

		return JHTML::_('select.genericlist',  $mitems, ''.$control_name.'['.$name.'][]', $attributes, 'value', 'text', $value );
	}

}
