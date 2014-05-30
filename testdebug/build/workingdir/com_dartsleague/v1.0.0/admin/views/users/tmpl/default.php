<?php
/**
 * @version		$Id: default.php 549 2010-08-30 15:39:45Z SilverPC Consultants. $
 * @package		dl
 * @author		SilverPC Consultants.
 * @copyright	Copyright (c) 2009 - 2011 SilverPC Consultants. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<script type="text/javascript">
	//<![CDATA[
	window.addEvent('domready', function(){

		// For the Joomla! checkbox toggle button
		$$('#jToggler').addEvent('click', function(){
			checkAll(<?php echo count( $this->rows ); ?>);
		});

		// Toolbar controls
		$$('#toolbar-Link a').addEvent('click', function(e){
			var answer = confirm('<?php echo JText::_('WARNING: You are about to import Joomla! users to dl generating corresponding dl user groups! If you have executed this operation before, duplicate content may be produced!', true); ?>')
			if (!answer){
				new Event(e).stop();
			}
		});

	});
	//]]>
</script>

<form action="index.php" method="post" name="adminForm">

  <table class="dlAdminTableFilters">
    <tr>
      <td class="dlAdminTableFiltersSearch">
				<?php echo JText::_('Filter:'); ?>
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search'] ?>" class="text_area" title="<?php echo JText::_('Filter by name'); ?>"/>
				<button onclick="this.form.submit();"><?php echo JText::_('Go'); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_state').value='-1';this.form.getElementById('filter_group').value='0';this.form.getElementById('filter_group_dl').value='0';this.form.submit();"><?php echo JText::_('Reset'); ?></button>
      </td>
      <td class="dlAdminTableFiltersSelects">
      	<?php echo $this->lists['filter_group_dl']; ?>
      	<?php echo $this->lists['filter_group']; ?>
      	<?php echo $this->lists['status']; ?>
      </td>
    </tr>
  </table>

  <table class="adminlist">
    <thead>
      <tr>
        <th>#</th>
        <th><input id="jToggler" type="checkbox" name="toggle" value="" /></th>
        <th><?php echo JHTML::_('grid.sort', 'Name', 'juser.name', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
        <th><?php echo JHTML::_('grid.sort', 'Username', 'juser.username', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
        <th><?php echo JText::_('Logged in'); ?></th>
        <th><?php echo JHTML::_('grid.sort', 'Enabled', 'juser.block', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
        <th><?php echo JHTML::_('grid.sort', 'Joomla! Group', 'juser.usertype', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
        <th><?php echo JHTML::_('grid.sort', 'dl Group', 'groupname', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
        <th><?php echo JHTML::_('grid.sort', 'E-mail', 'juser.email', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
        <th><?php echo JHTML::_('grid.sort', 'Last Visit', 'juser.lastvisitDate', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
        <th><?php echo JHTML::_('grid.sort', 'ID', 'juser.id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php
      $k = 0; $i = 0;
			foreach ($this->rows as $row) :
				$row->checked_out=0;
				$checked 	= JHTML::_('grid.id', $i, $row->profileID );
			?>
      <tr class="<?php echo "row$k"; ?>">
        <td><?php echo $i+1; ?></td>
        <td class="dlCenter"><?php if ($row->profileID) echo $checked; ?></td>
        <td><a href="<?php echo $row->link; ?>"><?php echo $row->name;?></a></td>
        <td><?php echo $row->username;?></td>
        <td class="dlCenter"><?php echo $row->loggedin ? '<img src="images/tick.png" width="16" height="16" border="0" alt="" />': ''; ?></td>
        <td class="dlCenter"><?php echo $row->block ? '<img src="images/publish_x.png" width="16" height="16" border="0" alt="" />': '<img src="images/tick.png" width="16" height="16" border="0" alt="" />'; ?></td>
        <td><?php echo $row->usertype;?></td>
        <td><?php echo $row->groupname;?></td>
        <td><?php echo $row->email;?></td>
        <td class="dlDate"><?php echo JHTML::_('date', $row->lvisit , JText::_('DL_DATE_FORMAT')); ?></td>
        <td><?php echo $row->id; ?></td>
      </tr>
      <?php $k = 1 - $k; $i++; endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="11"><?php echo $this->page->getListFooter(); ?></td>
      </tr>
    </tfoot>
  </table>

  <input type="hidden" name="option" value="<?php echo $option;?>" />
  <input type="hidden" name="view" value="<?php echo JRequest::getVar('view'); ?>" />
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
  <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
  <input type="hidden" name="boxchecked" value="0" />
  <?php echo JHTML::_( 'form.token' );?>
</form>
