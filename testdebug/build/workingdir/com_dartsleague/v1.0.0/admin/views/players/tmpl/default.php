<?php
/**
 * @version		$Id: default.php 549 2010-08-30 15:39:45Z SilverPC Consultants. $
 * @package		dartsleague   players template
 * @author		SilverPC Consultants.
 * @copyright	Copyright (c) 2009 - 2011 SilverPC Consultants. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
$db = &JFactory::getDBO(); $nullDate = $db->getNullDate();
$ordering = ( ($this->lists['order'] == 'i.ordering' || $this->lists['order'] == 'category' || $this->filter_featured) && (!$this->filter_trash));
?>

<!-- To move into dartsleague.mootools.js -->
<script type="text/javascript">
	window.addEvent('domready', function(){

		// For the Joomla! checkbox toggle button
		$$('#jToggler').addEvent('click', function(){
			checkAll(<?php echo count( $this->rows ); ?>);
		});

	});
</script>

<form action="index.php" method="post" name="adminForm">
  <table class="dartsleagueAdminTableFilters">
    <tr>
      <td class="dartsleagueAdminTableFiltersSearch">
				<?php echo JText::_('Filter:'); ?>
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search'] ?>" class="text_area" title="<?php echo JText::_('Filter by title'); ?>"/>
				<button onclick="this.form.submit();"><?php echo JText::_('Go'); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_team').value='0';this.form.getElementById('filter_trash').value='0';this.form.getElementById('filter_author').value='0';this.form.getElementById('filter_state').value='-1';this.form.getElementById('filter_featured').value='-1';this.form.submit();"><?php echo JText::_('Reset'); ?></button>
        </td>
      <td class="dartsleagueAdminTableFiltersSelects">
	      <?php echo $this->lists['trash']; ?>
	      <?php echo $this->lists['featured']; ?>
	      &nbsp;|
	      <?php echo $this->lists['categories']; ?>
	      <?php echo $this->lists['authors']; ?>
	      <?php echo $this->lists['state']; ?>
      </td>
    </tr>
  </table>

  <table class="adminlist">
    <thead>
      <tr>
        <th width="20" align="left">#</th>
        <th width="20"><input id="jToggler" type="checkbox" name="toggle" value="" /></th>
        <th class="title"><?php echo JHTML::_('grid.sort', 'First Name', 'i.firstname', @$this->lists['order_Dir'], @$this->lists['order']); ?></th>

        <th nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'Last Name', 'lastname', @$this->lists['order_Dir'], @$this->lists['order']); ?></th>
        <th width="10%" nowrap="nowrap">
          <?php echo JHTML::_('grid.sort', 'Alias', 'plalias', @$this->lists['order_Dir'], @$this->lists['order']); ?>
        </th>
        <th width="10%" nowrap="nowrap">
          <?php echo JHTML::_('grid.sort', 'Email', 'email', @$this->lists['order_Dir'], @$this->lists['order']); ?> 
        </th>
        <th width="10%" nowrap="nowrap">   
          <?php echo JHTML::_('grid.sort', 'Mobile ', 'mobile ', @$this->lists['order_Dir'], @$this->lists['order']); ?>
        </th>
        <th width="10%" nowrap="nowrap">   
          <?php echo JHTML::_('grid.sort', 'Team ', 'teamname ', @$this->lists['order_Dir'], @$this->lists['order']); ?>
        </th>
        <th width="5%" nowrap="nowrap">
          <?php echo JHTML::_('grid.sort', 'Published', 'i.published', @$this->lists['order_Dir'], @$this->lists['order']); ?>
        </th>     
<!--        <th><?php echo JText::_('Image'); ?></th> -->
        <th width="1%" nowrap="nowrap">
          <?php echo JHTML::_('grid.sort', 'ID', 'i.id', @$this->lists['order_Dir'], @$this->lists['order']); ?>
        </th>
      </tr>
    </thead>
    <tbody>
      <?php
      $k = 0; $i = 0;	$n = count( $this->rows );
			$user=& JFactory::getUser();
			foreach ($this->rows as $row) :
				$checked 	= JHTML::_('grid.checkedout', $row, $i );
				$published = JHTML::_('grid.published', $row, $i );
				$access = JHTML::_('grid.access', $row, $i );
				$link = JRoute::_('index.php?option=com_dartsleague&view=player&cid='.$row->id);
			?>
      <tr class="<?php echo "row$k"; ?>">
				<td><?php echo $i+1; ?></td>
				<td class="dartsleagueCenter"><?php echo $checked; ?></td>
				<td class="dartsleagueCenter">
					<?php if (JTable::isCheckedOut($user->get('id'), $row->checked_out )):?>
					    <?php echo $row->firstname;?>
					<?php else: ?>
					        <?php if(!$this->filter_trash):?>
					            <a href="<?php echo $link; ?>">
					        <?php endif; ?>
					        <?php echo $row->firstname;?>
					        <?php if(!$this->filter_trash):?>
					        </a>
					        <?php endif; ?>
					<?php endif;?>        
				</td>
                <td class="dartsleagueCenter">
                  <?php if(!$this->filter_trash):?>
                           <a href="<?php echo $link; ?>">
                  <?php endif; ?>
                     <?php echo $row->lastname;?>
                      <?php if(!$this->filter_trash):?>
                            </a>
                      <?php endif; ?>
                </td>
				<td class="dartsleagueCenter">
					<?php if(!$this->filter_trash):?>
                        <a href="<?php echo $link; ?>"> 
					<?php endif; ?>
					<?php echo $row->plalias;?> 
                    <?php if(!$this->filter_trash):?>
					</a>
					<?php endif; ?>
				</td>                            
<!--				<td class="dartsleagueCenter">
                    <?php echo $row->alias;?>
                </td>-->
                <td class="dartsleagueCenter">
                    <?php echo $row->email;?>
                </td>
                <td class="dartsleagueCenter">
                    <?php echo $row->mobile;?>
                </td>
                <td class="dartsleagueCenter">
                   <?php echo $row->teamname;?>  
<!--                   <?php echo $row->teamid;?> -->
<!--                     <?php echo $this->lists['teamid'];?>  --> 
                </td>
                <td class="dartsleagueCenter">
                  <?php echo ($this->filter_trash) ? strip_tags($published,'<img>') : $published; ?>
                </td> 
<!--				<td class="dartsleagueOrder">
					<?php if ($this->filter_featured=='1'):?>
					<span><?php echo $this->page->orderUpIcon($i, true, 'featuredorderup', 'Move Up', $ordering); ?></span>
					<span><?php echo $this->page->orderDownIcon($i, $n, true, 'featuredorderdown', 'Move Down', $ordering); ?></span>
					<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
					<input type="text" name="order[]" size="5" value="<?php echo $row->featured_ordering; ?>" <?php echo $disabled ?>	class="text_area dartsleagueOrderBox" />
					<?php else: ?>
					<span><?php echo $this->page->orderUpIcon($i, ($row->catid == @$this->rows[$i-1]->catid), 'orderup', 'Move Up', $ordering); ?></span>
					<span><?php echo $this->page->orderDownIcon($i, $n, ($row->catid == @$this->rows[$i+1]->catid), 'orderdown', 'Move Down', $ordering); ?></span>
					<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
					<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" <?php echo $disabled ?>	class="text_area dartsleagueOrderBox" />
					<?php endif; ?>
				</td> -->  
<!--                    <td>
                    <a href="<?php echo JRoute::_('index.php?option=com_dartsleague&view=team&id='.$row->cid);?>"><?php echo $row->team; ?></a>
                    </td>--> 
<!--				<td>
					<?php if($user->gid>23):?>
					<a href="<?php echo JRoute::_('index.php?option=com_users&task=edit&cid[]='.$row->created_by);?>"><?php echo $row->author; ?></a>
					<?php else:?>
					<?php echo $row->author; ?>
					<?php endif; ?>
				</td>
                -->
<!--				<td>
					<?php if($user->gid>23):?>
					<a href="<?php echo JRoute::_('index.php?option=com_users&task=edit&cid[]='.$row->modified_by);?>"><?php echo $row->moderator; ?></a>
					<?php else:?>
					<?php echo $row->moderator; ?>
					<?php endif; ?>
				</td>
                -->
<!--				<td class="dartsleagueCenter"><?php echo ($this->filter_trash)?strip_tags($access):$access;?></td>  -->
<!--                    <td class="dartsleagueDate"><?php echo JHTML::_('date', $row->created , JText::_('DARTSLEAGUE_DATE_FORMAT')); ?>
                    </td>   -->
<!--                <td class="dartsleagueDate"><?php echo ($row->modified == $nullDate) ? JText::_('Never') : JHTML::_('date', $row->modified , JText::_('DARTSLEAGUE_DATE_FORMAT')); ?></td>-->
<!--                <td><?php echo $row->hits ?></td>-->
<!--				<td class="dartsleagueCenter">
					<?php if (JFile::exists(JPATH_SITE.DS.'media'.DS.'dartsleague'.DS.'players'.DS.'cache'.DS.md5("Image".$row->id).'_XL.jpg')): ?>
					<a href="<?php echo JURI::root().'media/dartsleague/players/cache/'.md5("Image".$row->id).'_XL.jpg';?>" class="modal"><img src="templates/khepri/images/menu/icon-16-media.png" alt="<?php echo JText::_('Preview image');?>" /></a>
					<?php endif; ?>
				</td>  
                -->
				<td><?php echo $row->id; ?></td>
      </tr>
      <?php $k = 1 - $k; $i++; endforeach; ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="15"><?php echo $this->page->getListFooter(); ?></td>
      </tr>
    </tfoot>
  </table>

  <input type="hidden" name="option" value="<?php echo $option;?>" />
  <input type="hidden" name="view" value="<?php echo JRequest::getVar('view'); ?>" />
  <input type="hidden" name="task" value="<?php echo JRequest::getVar('task'); ?>" />
  <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
  <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
  <input type="hidden" name="boxchecked" value="0" />
  <?php	echo JHTML::_('form.token'); ?>
</form>
