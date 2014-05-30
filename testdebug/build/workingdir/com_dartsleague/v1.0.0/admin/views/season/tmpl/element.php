<?php
/**
 * @version		$Id: element.php 549 2010-08-30 15:39:45Z SilverPC Consultants. $
 * @package		dl season element
 * @author		SilverPC Consultants.
 * @copyright	Copyright (c) 2009 - 2011 SilverPC Consultants. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

?>
<form action="index.php" method="post" name="adminForm">

	<h1><?php echo JText::_('Select Seasons'); ?></h1>

  <table class="dlAdminTableFilters">
    <tr>
      <td class="dlAdminTableFiltersSearch">
				<?php echo JText::_('Filter:'); ?>
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search'] ?>" class="text_area" title="<?php echo JText::_('Filter by title'); ?>"/>
				<button onclick="this.form.submit();"><?php echo JText::_('Go'); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_category').value='0';this.form.getElementById('filter_trash').value='0';this.form.getElementById('filter_author').value='0';this.form.getElementById('filter_state').value='-1';this.form.getElementById('filter_featured').value='-1';this.form.submit();"><?php echo JText::_('Reset'); ?></button>
        </td>
      <td class="dlAdminTableFiltersSelects">
	      <?php echo $this->lists['trash']; ?>
	      <?php echo $this->lists['featured']; ?>
	      <?php echo $this->lists['categories']; ?>
	      <?php echo $this->lists['authors']; ?>
	      <?php echo $this->lists['state']; ?>
      </td>
    </tr>
  </table>
  <table class="adminlist">
    <thead>
      <tr>
        <th><?php echo JText::_( 'Num' ); ?></th>
        <th><?php echo JHTML::_('grid.sort', 'Name', 'i.name', @$this->lists['order_Dir'], @$this->lists['order']); ?></th>
        <th><?php echo JHTML::_('grid.sort', 'Description', 'description', @$this->lists['order_Dir'], @$this->lists['order']); ?></th>
        <th><?php echo JHTML::_('grid.sort', 'Year', 'year', @$this->lists['order_Dir'], @$this->lists['order']); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php $k = 0; $i = 0;	$n = count( $this->rows );
			foreach ($this->rows as $row):
			?>
      <tr class="<?php echo "row$k"; ?>">
        <td><?php echo $i+1; ?></td>
        <td><a class="dlListItemDisabled" title="<?php echo JText::_('Click to add this Season'); ?>" onclick="window.parent.jSelectItem('<?php echo $row->id; ?>', '<?php echo str_replace(array("'", "\""), array("\\'", ""),$row->title); ?>', 'id');"><?php echo $row->title; ?></a></td>
        <td><?php echo $row->name; ?></td>
        <td><?php echo $row->description; ?></td>
        <td class="dlCenter"><?php echo $row->year;?></td>
<!--        <td class="dlCenter"><?php echo $row->email; ?></td>
        <td class="dlCenter"><?php echo $row->mobile; ?></td>    -->
        <td><?php echo $row->id; ?></td>
      </tr>
      <?php $k = 1 - $k; $i++; endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="7"><?php echo $this->page->getListFooter(); ?></td>
      </tr>
    </tfoot>
  </table>
  <input type="hidden" name="option" value="<?php echo $option;?>" />
  <input type="hidden" name="view" value="season" />
  <input type="hidden" name="task" value="element" />
  <input type="hidden" name="tmpl" value="component" />
  <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
  <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>
