<?php
/**
 * @version		$Id: default.php 479 2010-06-16 16:49:53Z SilverPC Consultants. $
 * @package		dartsleague
 * @author		SilverPC Consultants.
 * @copyright	Copyright (c) 2009 - 2011 SilverPC Consultants. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<!-- To move into dartsleague.mootools.js -->
<script type="text/javascript">
	//<![CDATA[
	function submitbutton(pressbutton) {
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
		if (trim( document.adminForm.name.value ) == "") {
			alert( '<?php echo JText::_('A category must at least have a title!', true);?>' );
		} else {
			submitform( pressbutton );
		}
	}
	//]]>
</script>

<form action="index.php" enctype="multipart/form-data" method="post" name="adminForm" id="adminForm">

  <table cellspacing="0" cellpadding="0" border="0" class="adminFormdartsleagueContainer">
    <tbody>
      <tr>
        <td>
        
					<fieldset class="adminform">
						<legend><?php echo JText::_('Details');?></legend>
						<table class="admintable">
							<tr>
								<td class="key"><?php echo JText::_('Title'); ?></td>
								<td><input class="text_area dartsleagueTitleBox" type="text" name="name" id="name" value="<?php echo $this->row->name; ?>" maxlength="250" /></td>
							</tr>
							<tr>
								<td class="key"><?php echo JText::_('Title alias'); ?></td>
								<td><input class="text_area dartsleagueTitleBox" type="text" name="alias" value="<?php echo $this->row->alias; ?>" maxlength="250" /></td>
							</tr>
							<tr>
								<td class="key"><?php	echo JText::_('Parent category'); ?></td>
								<td><?php echo $this->lists['parent']; ?></td>
							</tr>
   							<tr>
								<td class="key"><?php	echo JText::_('Associated "Extra Fields Group"');	?></td>
								<td><?php echo $this->lists['extraFieldsGroup']; ?></td>
							</tr>
							<tr>
								<td class="key"><?php	echo JText::_('Published');	?></td>
								<td><?php echo $this->lists['published']; ?></td>
							</tr>						
							<tr>
								<td class="key"><?php echo JText::_('Access level'); ?></td>
								<td><?php echo $this->lists['access']; ?></td>
							</tr>
						</table>
					</fieldset>

				  <!-- Tabs start here -->
				  <div class="simpleTabs">
				  
				    <ul class="simpleTabsNavigation">
				      <li id="tabContent"><a href="#"><?php echo JText::_('Description'); ?></a></li>
				      <li id="tabImage"><a href="#"><?php echo JText::_('Image'); ?></a></li>
				    </ul>

				    <!-- Tab content -->
				    <div class="simpleTabsContent">
							<div class="dartsleagueItemFormEditor">
								<span class="dartsleagueItemFormEditorTitle">
									<?php echo JText::_('Category description'); ?>
								</span>
								<?php echo $this->editor; ?>
								<div class="dummyHeight"></div>
								<div class="clr"></div>
							</div>
							<div class="clr"></div>
				    </div>
				    
				    <!-- Tab image -->
				    <div class="simpleTabsContent">
							<table class="admintable">
							  <tr>
							    <td align="right" class="key"><?php echo JText::_('Category image'); ?></td>
							    <td>
							    	<input type="file" name="image" class="fileUpload" /> 
										<?php if (!empty($this->row->image)): ?>
										<img src="<?php echo JURI::root();?>media/dartsleague/categories/<?php echo $this->row->image; ?>" alt="<?php echo $this->row->name;?>" class="dartsleagueAdminImage" /> 
							      <input type="checkbox" name="del_image" id="del_image" />
							      <label for="del_image"><?php echo JText::_('Check this box to delete current image or just upload a new image to replace the existing one'); ?></label>
							      <?php endif; ?>
							    </td>
							  </tr>
							</table>
				    </div>

					</div>
					<!-- Tabs end here -->

					<!-- dartsleague Category Plugins -->
					<?php if (count($this->dartsleaguePlugins)):?>
					<div class="itemPlugins">
						<?php foreach ($this->dartsleaguePlugins as $dartsleaguePlugin) : ?>
						<?php if(!is_null($dartsleaguePlugin)):?>
						<fieldset class="adminform">
							<legend><?php echo $dartsleaguePlugin->name;?></legend>
							<?php echo $dartsleaguePlugin->fields;?>
						</fieldset>
						<?php endif; ?>
						<?php endforeach; ?>
					</div>
					<?php endif;?>
					
					<div class="clr"></div>
				</td>
				<td id="adminFormdartsleagueSidebar">
					<fieldset class="adminform">
						<legend><?php echo JText::_('Parameters');?></legend>
						<?php
						jimport('joomla.html.pane');
						$pane = & JPane::getInstance('sliders', array('allowAllClose' => true));
						echo $pane->startPane( 'content-pane' );
						
						echo $pane->startPanel( JText::_( 'Category item layout' ), 'category-item-layout' );
						echo $this->form->render('params','category-item-layout');
						echo $pane->endPanel();
						
						echo $pane->startPanel( JText::_( 'Category view options' ), 'category-view-options' );
						echo $this->form->render('params','category-view-options');
						echo $pane->endPanel();
						
						echo $pane->startPanel( JText::_( 'Item image options' ), 'image-options' );
						echo $this->form->render('params','item-image-options');
						echo $pane->endPanel();
						
						echo $pane->startPanel( JText::_( 'Item view options in category listings' ), 'item-view-options-listings' );
						echo $this->form->render('params','item-view-options-listings');
						echo $pane->endPanel();
						
						echo $pane->startPanel( JText::_( 'Item view options' ), 'item-view-options' );
						echo $this->form->render('params','item-view-options');
						echo $pane->endPanel();
						
						echo $pane->endPane();
						?>
					</fieldset>
				</td>
			</tr>
		</tbody>
	</table>
	
	<input type="hidden" name="id" value="<?php echo $this->row->id;?>" />
	<input type="hidden" name="option" value="<?php echo $option;?>" />
	<input type="hidden" name="view" value="category" />
	<input type="hidden" name="task" value="<?php echo JRequest::getVar('task'); ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>
