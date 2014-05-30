<?php
/**
 * @version		$Id: default.php 503 2010-06-24 21:11:53Z SilverPC Consultants. $
 * @package		dartsleague
 * @author		SilverPC Consultants.
 * @copyright	Copyright (c) 2009 - 2011 SilverPC Consultants. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$db = &JFactory::getDBO();
$nullDate = $db->getNullDate();

?>

<script type="text/javascript">
	//<![CDATA[
	function submitbutton(pressbutton) {

		syncExtraFieldsEditor();

		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
		if (trim( document.adminForm.title.value ) == "") {
			alert( '<?php echo JText::_('scoresheet must have a title', true); ?>' );
		} else if (trim( document.adminForm.catid.value ) == "0") {
			alert( '<?php echo JText::_('Please select a category', true); ?>' );
		} else {
			<?php if(!$this->params->get('taggingSystem')): ?>
			var getSelectedTags = document.getElementById("selectedTags");
			for(i=0; i<getSelectedTags.options.length; i++) getSelectedTags.options[i].selected = true;
			<?php endif; ?>
			submitform( pressbutton );
		}
	}

	function addAttachment(){
		var div = new Element('div',{'style':'border-top: 1px dotted #ccc; margin: 4px; padding: 10px;'}).injectInside($('scoresheetAttachments'));
		var input = new Element('input',{'name':'attachment_file[]','type':'file'}).injectInside(div);
		var input = new Element('input',{'value':'<?php echo JText::_('Remove',true); ?>','type':'button',events:{ click: function(){this.getParent().remove();} } }).injectInside(div);
		var br = new Element('br').injectInside(div);
		var label = new Element('label').setHTML('<?php echo JText::_('Link title (optional)', true); ?>').injectInside(div);
		var input = new Element('input',{'name':'attachment_title[]','type':'text', 'class':'linkTitle'}).injectInside(div);
		var br = new Element('br').injectInside(div);
		var label = new Element('label').setHTML('<?php echo JText::_('Link title attribute (optional)', true); ?>').injectInside(div);
		var textarea = new Element('textarea',{'name':'attachment_title_attribute[]','cols':'30', 'rows':'3'}).injectInside(div);
	}

	window.addEvent('domready', function(){
// 		initExtraFieldsEditor();
		$$('.deleteAttachmentButton').addEvent('click', function(e){
			e = new Event(e).stop();
			if (confirm('<?php echo JText::_('Are you sure?', true); ?>')) {
				var element = this.getParent().getParent();
				var deleteAnimation = new Fx.Style(element, 'opacity', {duration:500});
				var url = this.getProperty('href');
				new Ajax(url, {
					method: 'get',
				 	onComplete: function(){
						deleteAnimation.start(100, 0).chain(function(){element.remove();});;
				 	}
				}).request();
			}
		});

		<?php endif; ?>

		$('catid').addEvent('change', function(){
			var selectedValue = this.value;
			var url = 'index.php?option=com_dartsleague&view=scoresheet&task=extraFields&cid='+selectedValue;
			<?php if ($this->row->id): ?>
			url+='&id=<?php echo $this->row->id?>';
			<?php endif; ?>
			new Fx.Style($('extraFieldsContainer'), 'opacity', {
				duration: 700
			}).start(0).chain(function(){
				new Ajax(url, {
					method: 'get',
					update: $('extraFieldsContainer'),
				 	onComplete: function(){
						initExtraFieldsEditor();
						new Fx.Style($('extraFieldsContainer'), 'opacity', {
			            	duration: 700
						}).start(1);
					}
				}).request();
			})
		});

		$('browseSrv').addEvent('click', function(e){
			e = new Event(e).stop();
			SqueezeBox.initialize();
			SqueezeBox.fromElement(this, {
				handler: 'iframe',
				url: '<?php echo JURI::base()?>index.php?option=com_dartsleague&view=scoresheet&task=filebrowser&type=image&tmpl=component',
				size: {x: 590, y: 400}
			});
		})

		$$('ul.tags').addEvent('click', function(){
			$('search-field').focus();
		})
		var completer = new Autocompleter.Ajax.Json($('search-field'), '<?php echo JURI::root()?>index.php?option=com_dartsleague&view=scoresheet&task=tags',
			{
		    'postVar': 'q',
		    'postData': {tags:($$('ul.tags input[type=hidden]').getProperty('value')).join(",")},
		    'onRequest': function(el) {
		        $('search-field').addClass('tagsLoading');
		    },
		    'onComplete': function(el) {
		    	$('search-field').removeClass('tagsLoading');
		    }
		});


	});

	//]]>
</script>

<div id="dartsleagueToggleSidebarContainer">
	<a href="#" id="dartsleagueToggleSidebar"><?php echo JText::_('Toggle sidebar'); ?></a>
</div>

<form action="index.php" enctype="multipart/form-data" method="post" name="adminForm">

  <table cellspacing="0" cellpadding="0" border="0" class="adminFormdartsleagueContainer">
    <tbody>
      <tr>
        <td>

					<table class="adminform adminFormdartsleague">
						<tr>
							<td><label for="title"><?php echo JText::_('Title'); ?></label></td>
							<td><input class="text_area dartsleagueTitleBox" type="text" name="title" id="title" maxlength="250" value="<?php echo $this->row->title; ?>" /></td>
							<td><label><?php echo JText::_('Published'); ?></label></td>
							<td><?php echo $this->lists['published']; ?></td>
						</tr>
						<tr>
							<td><label for="email"><?php echo JText::_('Email'); ?></label></td>
							<td><input class="text_area dartsleagueTitleBox" type="text" name="email" id="title" maxlength="250" value="<?php echo $this->row->email; ?>" /></td>
						</tr>
						<tr>
							<td><label for="alias"><?php echo JText::_('Title alias'); ?></label></td>
							<td><input class="text_area dartsleagueTitleBox" type="text" name="alias" id="alias" maxlength="250" value="<?php echo $this->row->alias; ?>" /></td>
							<td><label for="featured"><?php echo JText::_('Is it featured?'); ?></label></td>
							<td><input type="checkbox" name="featured" id="featured" <?php echo $this->row->featured?'checked="checked"':''; ?> value="1" /></td>
						</tr>
						<tr>
							<td><label><?php echo JText::_('Tags'); ?></label></td>
							<td>
					      <?php if($this->params->get('taggingSystem')): ?>
					      <!-- Free tagging -->
								<ul class="tags">
									<?php if(isset($this->row->tags) && count($this->row->tags)): ?>
									<?php foreach($this->row->tags as $tag): ?>
									<li class="addedTag">
										<?php echo $tag->name; ?>
										<span class="tagRemove" onclick="this.getParent().remove();">x</span>
										<input type="hidden" name="tags[]" value="<?php echo $tag->name; ?>" />
									</li>
									<?php endforeach; ?>
									<?php endif; ?>
									<li class="tagAdd"><input type="text" id="search-field" /></li>
									<li class="clr"></li>
								</ul>
								<span class="dartsleagueNote">
									<?php echo JText::_('Write a tag and press "return" or "comma" to add it.'); ?>
								</span>
								<?php else: ?>
								<!-- Selection based tagging -->
								<?php if( !$this->params->get('lockTags') || $this->user->gid>23): ?>
								<div style="float:left;">
									<input type="text" name="tag" id="tag" />
									<input type="button" id="newTagButton" value="<?php echo JText::_('Add'); ?>" />
								</div>
								<div id="tagsLog"></div>
								<div class="clr"></div>

								<span class="dartsleagueNote">
									<?php echo JText::_('Write a tag and press "Add" to insert it to the available tags list.<br />New tags are appended at the bottom of the available tags list (left).'); ?>
								</span>
								<?php endif; ?>
								<table cellspacing="0" cellpadding="0" border="0" id="tagLists">
									<tr>
										<td id="tagListsLeft">
											<span><?php echo JText::_('Available tags'); ?></span>
											<?php echo $this->lists['tags'];	?>
										</td>
										<td id="tagListsButtons">
											<input type="button" id="addTagButton" value="<?php echo JText::_('Add'); ?> &raquo;" />
											<br /><br />
											<input type="button" id="removeTagButton" value="&laquo; <?php echo JText::_('Remove'); ?>" />
										</td>
										<td id="tagListsRight">
											<span><?php echo JText::_('Selected tags'); ?></span>
											<?php echo $this->lists['selectedTags']; ?>
										</td>
									</tr>
								</table>
								<?php endif; ?>
							</td>
							<td><label><?php echo JText::_('Category'); ?></label></td>
							<td><?php echo $this->lists['categories']; ?></td>
						</tr>
					</table>

				  <!-- Tabs start here -->
				  <div class="simpleTabs">
				    <ul class="simpleTabsNavigation">
				      <li id="tabContent"><a href="#"><?php echo JText::_('Content'); ?></a></li>
				      <li id="tabImage"><a href="#"><?php echo JText::_('Image'); ?></a></li>
				      <li id="tabImageGallery"><a href="#"><?php echo JText::_('Image gallery'); ?></a></li>
				      <li id="tabVideo"><a href="#"><?php echo JText::_('Video'); ?></a></li>
				      <li id="tabExtraFields"><a href="#"><?php echo JText::_('Extra fields'); ?></a></li>
				      <li id="tabAttachments"><a href="#"><?php echo JText::_('Attachments'); ?></a></li>
                      <!-- TODO: find out items plugin can it be used for scoresheet -->
				      <?php if(count(array_filter($this->dartsleaguePluginsscoresheetOther))): ?>
				      <li id="tabPlugins"><a href="#"><?php echo JText::_('Plugins'); ?></a></li>
				      <?php endif; ?>
				    </ul>

				    <!-- Tab content -->
				    <div class="simpleTabsContent">
							<?php if($this->params->get('mergeEditors')): ?>
							<div class="dartsleaguescoresheetFormEditor">
								<?php echo $this->text; ?>
								<div class="dummyHeight"></div>
								<div class="clr"></div>
							</div>
							<?php else: ?>
							<div class="dartsleaguescoresheetFormEditor">
								<span class="dartsleaguescoresheetFormEditorTitle">
									<?php echo JText::_('Introtext (teaser content/excerpt)'); ?>
								</span>
								<?php echo $this->introtext; ?>
								<div class="dummyHeight"></div>
								<div class="clr"></div>
							</div>
							<div class="dartsleaguescoresheetFormEditor">
								<span class="dartsleaguescoresheetFormEditorTitle">
									<?php echo JText::_('Fulltext (main content)'); ?>
								</span>
								<?php echo $this->fulltext; ?>
								<div class="dummyHeight"></div>
								<div class="clr"></div>
							</div>
							<?php endif; ?>

							<?php if (count($this->dartsleaguePluginsscoresheetContent)): ?>
							<div class="scoresheetPlugins">
								<?php foreach ($this->dartsleaguePluginsscoresheetContent as $dartsleaguePlugin) : ?>
								<?php if(!is_null($dartsleaguePlugin)): ?>
								<fieldset>
									<legend><?php echo $dartsleaguePlugin->name; ?></legend>
									<?php echo $dartsleaguePlugin->fields; ?>
								</fieldset>
								<?php endif; ?>
								<?php endforeach; ?>
							</div>
							<?php endif; ?>
							<div class="clr"></div>
				    </div>

				    <!-- Tab image -->
				    <div class="simpleTabsContent">
							<table class="admintable">
							  <tr>
							    <td align="right" class="key"><?php echo JText::_('scoresheet image'); ?></td>
							    <td>
							    	<input type="file" name="image" class="fileUpload" />
							    	<br /><br />
							    	<input type="text" name="existingImage" id="existingImageValue" class="text_area" readonly="readonly"> <input type="button" value="<?php echo JText::_('Browse server...'); ?>" id="browseSrv"  />
							    </td>
							  </tr>
							  <tr>
							    <td align="right" class="key"><?php echo JText::_('scoresheet image caption'); ?></td>
							    <td><input type="text" name="image_caption" size="30" class="text_area" value="<?php echo $this->row->image_caption; ?>" /></td>
							  </tr>
							  <tr>
							    <td align="right" class="key"><?php echo JText::_('scoresheet image credits'); ?></td>
							    <td><input type="text" name="image_credits" size="30" class="text_area" value="<?php echo $this->row->image_credits; ?>" /></td>
							  </tr>
							  <?php if (!empty($this->row->image)): ?>
							  <tr>
							    <td align="right" class="key"><?php echo JText::_('scoresheet image preview'); ?></td>
							    <td>
							      <a class="modal" href="<?php echo $this->row->image; ?>" title="<?php echo JText::_('Click on image to preview in original size'); ?>">
							      	<img alt="<?php echo $this->row->title; ?>" src="<?php echo $this->row->thumb; ?>" class="dartsleagueAdminImage"/>
							      </a>
							      <input type="checkbox" name="del_image" id="del_image" />
							      <label for="del_image"><?php echo JText::_('Check this box to delete current image or just upload a new image to replace the existing one'); ?></label>
							    </td>
							  </tr>
							  <?php endif; ?>
							</table>

							<?php if (count($this->dartsleaguePluginsscoresheetImage)): ?>
							<div class="scoresheetPlugins">
							  <?php foreach ($this->dartsleaguePluginsscoresheetImage as $dartsleaguePlugin) : ?>
							  <?php if(!is_null($dartsleaguePlugin)): ?>
							  <fieldset>
							    <legend><?php echo $dartsleaguePlugin->name; ?></legend>
							    <?php echo $dartsleaguePlugin->fields; ?>
							  </fieldset>
							  <?php endif; ?>
							  <?php endforeach; ?>
							</div>
							<?php endif; ?>
				    </div>

				    <!-- Tab image gallery -->
				    <div class="simpleTabsContent">
							<?php if ($this->lists['checkSIG']): ?>
							<table class="admintable" id="scoresheet_gallery_content">
							  <tr>
							    <td align="right" valign="top" class="key"><?php echo JText::_('Upload a zip file with images'); ?></td>
							    <td valign="top">
							    	<input type="file" name="gallery" class="fileUpload" />
							    	<?php if (!empty($this->row->gallery)): ?>
							      <div id="scoresheetGallery">
							        <?php echo $this->row->gallery; ?>
							        <input type="checkbox" name="del_gallery" id="del_gallery"/>
							        <label for="del_gallery"><?php echo JText::_('Check this box to delete current image gallery or just upload a new image gallery to replace the existing one'); ?></label>
							      </div>
							      <?php endif; ?>
							  	</td>
							  </tr>
							</table>
							<?php else: ?>
							<dartsleague id="system-message">
							  <dt class="notice"><?php echo JText::_('Notice'); ?></dt>
							  <dd class="notice message fade">
							    <ul>
							      <li><?php echo JText::_('Notice: Please install SilverPC Consultants. Simple Image Gallery Pro plugin if you want to use the image gallery features of dartsleague!'); ?></li>
							    </ul>
							  </dd>
							</dartsleague>
							<?php endif; ?>

							<?php if (count($this->dartsleaguePluginsscoresheetGallery)): ?>
							<div class="scoresheetPlugins">
							  <?php foreach ($this->dartsleaguePluginsscoresheetGallery as $dartsleaguePlugin) : ?>
							  <?php if(!is_null($dartsleaguePlugin)): ?>
							  <fieldset>
							    <legend><?php echo $dartsleaguePlugin->name; ?></legend>
							    <?php echo $dartsleaguePlugin->fields; ?>
							  </fieldset>
							  <?php endif; ?>
							  <?php endforeach; ?>
							</div>
							<?php endif; ?>
				    </div>

				    <?php if(count(array_filter($this->dartsleaguePluginsscoresheetOther))): ?>
				    <!-- Tab other plugins -->
				    <div class="simpleTabsContent">
					    <div class="scoresheetPlugins">
					      <?php foreach ($this->dartsleaguePluginsscoresheetOther as $dartsleaguePlugin) : ?>
					      <?php if(!is_null($dartsleaguePlugin)): ?>
					      <fieldset>
					        <legend><?php echo $dartsleaguePlugin->name; ?></legend>
					        <?php echo $dartsleaguePlugin->fields; ?>
					      </fieldset>
					      <?php endif; ?>
					      <?php endforeach; ?>
					    </div>
				    </div>
				    <?php endif; ?>
				  </div>
					<!-- Tabs end here -->

          <input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
          <input type="hidden" name="option" value="<?php echo $option; ?>" />
          <input type="hidden" name="view" value="scoresheet" />
          <input type="hidden" name="task" value="<?php echo JRequest::getVar('task'); ?>" />
          <?php echo JHTML::_('form.token'); ?>
        </td>

        <td id="adminFormdartsleagueSidebar"<?php if(!$this->params->get('sideBarDisplay')): ?> style="display:none;"<?php endif; ?>>

        	<table class="sidebarDetails">
            <?php if($this->row->id): ?>
            <tr>
              <td><strong><?php echo JText::_('scoresheet ID'); ?></strong></td>
              <td><?php echo $this->row->id; ?></td>
            </tr>
            <tr>
              <td><strong><?php echo JText::_('State'); ?></strong></td>
              <td><?php echo ($this->row->published > 0) ? JText::_('Published') : JText::_('Unpublished'); ?></td>
            </tr>
            <tr>
              <td><strong><?php echo JText::_('Featured state'); ?></strong></td>
              <td><?php echo ($this->row->featured > 0) ? JText::_('Featured'):	JText::_('Not featured'); ?></td>
            </tr>
            <tr>
              <td><strong><?php echo JText::_('Created date'); ?></strong></td>
              <td><?php if ($this->row->created == $nullDate) echo JText::_('New document'); else echo JHTML::_('date', $this->row->created, JText::_('DATE_FORMAT_LC2')); ?></td>
            </tr>
            <tr>
              <td><strong><?php echo JText::_('Created by'); ?></strong></td>
              <td><?php echo $this->row->author; ?></td>
            </tr>
            <tr>
              <td><strong><?php echo JText::_('Modified date'); ?></strong></td>
              <td><?php if ( $this->row->modified == $nullDate ) {	echo JText::_('Never');}
			else { echo JHTML::_('date', $this->row->modified, JText::_('DATE_FORMAT_LC2'));} ?></td>
            </tr>
            <tr>
              <td><strong><?php echo JText::_('Modified by'); ?></strong></td>
              <td><?php echo $this->row->moderator; ?></td>
            </tr>
            <tr>
              <td><strong><?php echo JText::_('Hits'); ?></strong></td>
              <td>
              	<?php echo $this->row->hits; ?>
              	<?php if($this->row->hits): ?>
              	<input type="button" onclick="submitbutton('resetHits');" value="<?php echo JText::_('Reset'); ?>" class="button" name="resetHits" />
              	<?php endif; ?>
              </td>
            </tr>
            <?php endif; ?>
            <?php if($this->row->id): ?>
            <tr>
            	<td><strong><?php echo JText::_('Rating'); ?></strong></td>
	          	<td>
	          		<?php echo $this->row->ratingCount; ?> <?php echo JText::_('votes'); ?>
	            	<?php if($this->row->ratingCount): ?>
	            	<br />
	            	(<?php echo JText::_('average rating'); ?>: <?php echo number_format(($this->row->ratingSum/$this->row->ratingCount),2); ?>/5.00)
	            	<?php endif; ?>
	            	<input type="button" onclick="submitbutton('resetRating');" value="<?php echo JText::_('Reset'); ?>" class="button" name="resetRating" />
	         		</td>
            </tr>
            <?php endif; ?>
            <tr>
              <td><strong><?php echo JText::_('Max upload size'); ?></strong></td>
              <td><?php echo ini_get('upload_max_filesize'); ?></td>
            </tr>
          </table>

          <?php $pane = & JPane::getInstance('sliders', array('allowAllClose' => true)); echo $pane->startPane('myPane2'); ?>
          <?php echo $pane->startPanel(JText::_('Author &amp; Publishing Status'), 'details'); ?>
          <table class="admintable">
            <tr>
              <td align="right" class="key"><?php echo JText::_('Author'); ?></td>
              <td><?php echo $this->lists['authors']; ?></td>
            </tr>
            <tr>
              <td align="right" class="key"><?php echo JText::_('Author alias'); ?></td>
              <td><input class="text_area" type="text" name="created_by_alias" maxlength="250" value="<?php echo $this->row->created_by_alias; ?>" /></td>
            </tr>
            <tr>
              <td align="right" class="key"><?php echo JText::_('Access level'); ?></td>
              <td><?php echo $this->lists['access']; ?></td>
            </tr>
            <tr>
              <td align="right" class="key"><?php echo JText::_('Creation date'); ?></td>
              <td class="dartsleaguescoresheetFormDateField"><?php echo JHTML::_( 'calendar',$this->row->created, 'created', 'created', '%Y-%m-%d %H:%M:%S'); ?></td>
            </tr>
            <tr>
              <td align="right" class="key"><?php echo JText::_('Start publishing'); ?></td>
              <td class="dartsleaguescoresheetFormDateField"><?php echo JHTML::_( 'calendar',$this->row->publish_up, 'publish_up', 'publish_up', '%Y-%m-%d %H:%M:%S'); ?></td>
            </tr>
            <tr>
              <td align="right" class="key"><?php echo JText::_('Finish publishing'); ?></td>
              <td class="dartsleaguescoresheetFormDateField"><?php echo JHTML::_( 'calendar',$this->row->publish_down, 'publish_down', 'publish_down', '%Y-%m-%d %H:%M:%S'); ?></td>
            </tr>
          </table>
          <?php	echo $pane->endPanel(); ?>

          <?php echo $pane->startPanel(JText::_('Metadata Information'), "metadata-page"); ?>
          <table class="admintable">
            <tr>
              <td align="right" class="key"><?php echo JText::_('Description'); ?></td>
              <td><textarea rows="5" name="metadesc" cols="30"><?php echo $this->row->metadesc; ?></textarea></td>
            </tr>
            <tr>
              <td align="right" class="key"><?php echo JText::_('Keywords'); ?></td>
              <td><textarea rows="5" name="metakey" cols="30"><?php echo $this->row->metakey; ?></textarea></td>
            </tr>
            <tr>
              <td align="right" class="key"><?php echo JText::_('Robots'); ?></td>
              <td><input type="text" name="meta[robots]" size="30" value="<?php echo $this->lists['metadata']->get('robots'); ?>" /></td>
            </tr>
            <tr>
              <td align="right" class="key"><?php echo JText::_('Author'); ?></td>
              <td><input type="text" name="meta[author]" size="30" value="<?php echo $this->lists['metadata']->get('author'); ?>" /></td>
            </tr>
          </table>
          <?php	echo $pane->endPanel(); ?>
          <?php echo $pane->startPanel(JText::_('scoresheet view options in category listings'), "scoresheet-view-options-listings"); ?>
          <?php echo $this->form->render('params','scoresheet-view-options-listings'); ?>
          <?php echo $pane->endPanel(); ?>
          <?php echo $pane->startPanel(JText::_('scoresheet view options'), "scoresheet-view-options"); ?>
          <?php echo $this->form->render('params','scoresheet-view-options'); ?>
          <?php echo $pane->endPanel(); ?>
          <?php echo $pane->endPane(); ?>
         </td>
      </tr>
    </tbody>
  </table>
  <div class="clr"></div>
</form>
