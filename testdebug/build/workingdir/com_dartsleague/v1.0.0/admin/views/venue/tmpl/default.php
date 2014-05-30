<?php
/**
 * @version		$Id: default.php 503 2010-06-24 21:11:53Z SilverPC Consultants. $
 * @package		dartsleague views venue tmpl default
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
		if (trim( document.adminForm.name.value ) == "") {
			alert( '<?php echo JText::_('Venue must have a name', true); ?>' );
		} 
        /** else if (trim( document.adminForm.catid.value ) == "0") {
			alert( '<?php echo JText::_('Please select a category', true); ?>' );
		}   */
        else {
			<?php if(!$this->params->get('taggingSystem')): ?>
			var getSelectedTags = document.getElementById("selectedTags");
			for(i=0; i<getSelectedTags.options.length; i++) getSelectedTags.options[i].selected = true;
			<?php endif; ?>
			submitform( pressbutton );
		}
	}

	function addAttachment(){
		var div = new Element('div',{'style':'border-top: 1px dotted #ccc; margin: 4px; padding: 10px;'}).injectInside($('venueAttachments'));
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
		initExtraFieldsEditor();
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

		<?php if(!$this->params->get('taggingSystem')): ?>

		<?php if( !$this->params->get('lockTags') || $this->user->gid>23): ?>
		$('newTagButton').addEvent('click', function(){
			var log = $('tagsLog');
			log.empty().addClass('tagsLoading');
			var tag=$('tag').getProperty('value');
			var url = 'index.php?option=com_dartsleague&view=venue&task=tag&tag='+tag;
			new Ajax(url, {
				method: 'get',
				onComplete: function(res){
					var jsonResponse=Json.evaluate(res);
					if (jsonResponse.status=="success"){
						var option = new Element('option',{'value':jsonResponse.id}).setHTML(jsonResponse.name).injectInside($('tags'));
					}
					(function(){
						log.setHTML(jsonResponse.msg);
						log.removeClass('tagsLoading');
					}).delay(1000);
				}
			}).request();
		});
		<?php endif; ?>

		$('addTagButton').addEvent('click', function(){
			$$('#tags option').each(function(el) {
				if (el.selected){
					el.injectInside($('selectedTags'));
				}
			});
		});

		$('removeTagButton').addEvent('click', function(){
			$$('#selectedTags option').each(function(el) {
				if (el.selected){
					el.injectInside($('tags'));
				}
			});
		});
		<?php endif; ?>



		$('browseSrv').addEvent('click', function(e){
			e = new Event(e).stop();
			SqueezeBox.initialize();
			SqueezeBox.fromElement(this, {
				handler: 'iframe',
				url: '<?php echo JURI::base()?>index.php?option=com_dartsleague&view=venue&task=filebrowser&type=image&tmpl=component',
				size: {x: 590, y: 400}
			});
		})

		$$('ul.tags').addEvent('click', function(){
			$('search-field').focus();
		})
		var completer = new Autocompleter.Ajax.Json($('search-field'), '<?php echo JURI::root()?>index.php?option=com_dartsleague&view=venue&task=tags',
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
							<td><label for="Name"><?php echo JText::_('Name'); ?></label><?php echo JHTML::_('tooltip', JText::_( 'TIPNAMEUSEDTOIDENTIFYVENUENAME' )); ?></td>
							<td><input class="text_area dartsleagueTitleBox" type="text" name="name" id="name" maxlength="250" value="<?php echo $this->row->name; ?>" /></td>
							<td><label><?php echo JText::_('Published'); ?></label></td>
							<td><?php echo $this->lists['published']; ?></td>
						</tr>
						<tr>
							<td><label for="address"><?php echo JText::_('Address'); ?></label><?php echo JHTML::_('tooltip', JText::_( 'TIPNAMEUSEDTOIDENTIFYVENUEADDRESS' )); ?></td>
							<td><input class="text_area dartsleagueTitleBox" type="text" name="address" id="address" maxlength="250" value="<?php echo $this->row->address; ?>" /></td>
						</tr>
						<tr>
							<td><label for="contact"><?php echo JText::_('Contact Number'); ?></label><?php echo JHTML::_('tooltip', JText::_( 'TIPNAMEUSEDTOIDENTIFYVENUECONTACTNO' )); ?></td>
                            <td><input class="text_area dartsleagueTitleBox" type="text" name="contact" id="contact" maxlength="250" value="<?php echo $this->row->contact; ?>" /></td>
  						</tr>
                        <tr>
                            <td><label for="boardnum"><?php echo JText::_('Board Numbers'); ?>
                           <!-- </label><?php echo JHTML::_('tooltip', JText::_( 'TIPNAMEUSEDTOIDENTIFYVENUEBOARDNUM' )); ?> -->
                            </td>
                            <td><input class="text_area dartsleagueTitleBox" type="text" name="boardnum" id="boardnum" maxlength="2" value="<?php echo $this->row->boardnum; ?>" /></td>
                        </tr>
<!--                        <tr>
                            <td><label for="boardnum"><?php echo JText::_('Board Numbers'); ?></label><?php echo JHTML::_('tooltip', JText::_( 'TIPNAMEUSEDTOIDENTIFYVENUEBOARDNUM' )); ?></td>
                            <td><input class="text_area dartsleagueTitleBox" type="text" name="boardnum" id="boardnum" maxlength="250" value="<?php echo $this->row->boardnum; ?>" /></td>
                        </tr>  -->        
                        <tr>
                            <td><label for="mapinfo"><?php echo JText::_('Mapinfo'); ?></label><?php echo JHTML::_('tooltip', JText::_( 'TIPNAMEUSEDTOIDENTIFYVENUEMAP' )); ?></td>
                            <td><input class="text_area dartsleagueTitleBox" type="text" name="mapinfo" id="mapinfo" maxlength="250" value="<?php echo $this->row->mapinfo; ?>" /></td>
                        </tr>
                        <tr>
                            <td><label for="vip"><?php echo JText::_('VIP Card Accepted'); ?></label><?php echo JHTML::_('tooltip', JText::_( 'TIPNAMEUSEDTOIDENTIFYVENUEVIP' )); ?></td>
                            <!-- The booleanlist of vip is created at Viewvenue -->
                            <td><?php echo $this->lists['vip']; ?></td> 
                        </tr>
<!--                        <tr>
							<td><label><?php echo JText::_('Tags'); ?></label></td>
							<td>  
					      <?php if($this->params->get('taggingSystem')): ?> -->
					      <!-- Free tagging -->
<!--								<ul class="tags">
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
								<?php else: ?> -->
								<!-- Selection based tagging -->
								<?php if( !$this->params->get('lockTags') || $this->user->gid>23): ?>
<!--								<div style="float:left;">
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
 						</tr> -->
					</table>

				  <!-- Tabs start here -->
				  <div class="simpleTabs">
				    <ul class="simpleTabsNavigation">
				      <li id="tabContent"><a href="#"><?php echo JText::_('Content'); ?></a><?php echo JHTML::_('tooltip', JText::_( 'TIPNAMEUSEDTOIDENTIFYVENUECONTENT' )); ?></li>
				      <li id="tabImage"><a href="#"><?php echo JText::_('Image'); ?></a></li>
				      <li id="tabImageGallery"><a href="#"><?php echo JText::_('Image gallery'); ?></a></li>
				      <!--<li id="tabVideo"><a href="#"><?php echo JText::_('Video'); ?></a></li> -->   
				      <!-- <li id="tabExtraFields"><a href="#"><?php echo JText::_('Extra fields'); ?></a></li>  -->   
				     <!-- <li id="tabAttachments"><a href="#"><?php echo JText::_('Attachments'); ?></a></li> -->
                      <!-- TODO: find out items plugin can it be used for venue -->
				      <?php if(count(array_filter($this->dartsleaguePluginsvenueOther))): ?>
				      <li id="tabPlugins"><a href="#"><?php echo JText::_('Plugins'); ?></a></li>
				      <?php endif; ?>
				    </ul>

				    <!-- Tab content -->
				    <div class="simpleTabsContent">
							<?php if($this->params->get('mergeEditors')): ?>
							<div class="dartsleaguevenueFormEditor">
								<?php echo $this->text; ?>
								<div class="dummyHeight"></div>
								<div class="clr"></div>
							</div>
							<?php else: ?>
							<div class="dartsleaguevenueFormEditor">
								<span class="dartsleaguevenueFormEditorTitle">
									<?php echo JText::_('Introtext (teaser content/excerpt)'); ?>
								</span>
								<?php echo $this->introtext; ?>
								<div class="dummyHeight"></div>
								<div class="clr"></div>
							</div>
							<div class="dartsleaguevenueFormEditor">
								<span class="dartsleaguevenueFormEditorTitle">
									<?php echo JText::_('Fulltext (main content)'); ?>
								</span>
								<?php echo $this->fulltext; ?>
								<div class="dummyHeight"></div>
								<div class="clr"></div>
							</div>
							<?php endif; ?>

							<?php if (count($this->dartsleaguePluginsvenueContent)): ?>
							<div class="venuePlugins">
								<?php foreach ($this->dartsleaguePluginsvenueContent as $dartsleaguePlugin) : ?>
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
							    <td align="right" class="key"><?php echo JText::_('venue image'); ?></td>
							    <td>
							    	<input type="file" name="image" class="fileUpload" />
							    	<br /><br />
							    	<input type="text" name="existingImage" id="existingImageValue" class="text_area" readonly="readonly"> <input type="button" value="<?php echo JText::_('Browse server...'); ?>" id="browseSrv"  />
							    </td>
							  </tr>
							  <tr>
							    <td align="right" class="key"><?php echo JText::_('venue image caption'); ?></td>
							    <td><input type="text" name="image_caption" size="30" class="text_area" value="<?php echo $this->row->image_caption; ?>" /></td>
							  </tr>
							  <tr>
							    <td align="right" class="key"><?php echo JText::_('venue image credits'); ?></td>
							    <td><input type="text" name="image_credits" size="30" class="text_area" value="<?php echo $this->row->image_credits; ?>" /></td>
							  </tr>
							  <?php if (!empty($this->row->image)): ?>
							  <tr>
							    <td align="right" class="key"><?php echo JText::_('venue image preview'); ?></td>
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

							<?php if (count($this->dartsleaguePluginsvenueImage)): ?>
							<div class="venuePlugins">
							  <?php foreach ($this->dartsleaguePluginsvenueImage as $dartsleaguePlugin) : ?>
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
							<table class="admintable" id="venue_gallery_content">
							  <tr>
							    <td align="right" valign="top" class="key"><?php echo JText::_('Upload a zip file with images'); ?></td>
							    <td valign="top">
							    	<input type="file" name="gallery" class="fileUpload" />
							    	<?php if (!empty($this->row->gallery)): ?>
							      <div id="venueGallery">
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

							<?php if (count($this->dartsleaguePluginsvenueGallery)): ?>
							<div class="venuePlugins">
							  <?php foreach ($this->dartsleaguePluginsvenueGallery as $dartsleaguePlugin) : ?>
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

				    <!-- Tab video -->
				    <div class="simpleTabsContent">
							<?php if ($this->lists['checkAllVideos']): ?>
							<table class="admintable" id="venue_video_content">
							  <tr>
							    <td align="right" class="key"><?php echo JText::_('Video source'); ?></td>
							    <td>
							    	<?php $pane = & JPane::getInstance('Tabs',$this->options); echo $pane->startPane('myPane'); ?>
							      <?php echo $pane->startPanel(JText::_('Upload'), 'vidtab1'); ?>
							      <div class="panel" id="Upload_video">
							        <input type="file" name="video" class="fileUpload" />
							      </div>
							      <?php echo $pane->endPanel(); ?>
							      <?php echo $pane->startPanel(JText::_('Browse server/use remote video'), 'vidtab2');	?>
							      <div class="panel" id="Remote_video">
							      	<a class="modal" rel="{handartsleagueer: 'iframe', size: {x: 590, y: 400}}" href="index.php?option=com_dartsleague&view=venue&task=filebrowser&type=video&tmpl=component"><?php echo JText::_('Browse videos on server')?></a> <?php echo JText::_('or'); ?> <?php echo JText::_('paste remote video URL'); ?> <input type="text" size="30" name="remoteVideo" value="<?php echo $this->lists['remoteVideo'] ?>" />
							      </div>
							      <?php echo $pane->endPanel(); ?>
							      <?php echo $pane->startPanel(JText::_('Video provider'), 'vidtab3'); ?>
							      <div class="panel" id="Video_from_provider">
							      	<?php echo JText::_('Select video provider'); ?> <?php echo $this->lists['providers']; ?> <?php echo JText::_('and enter video ID'); ?> <input type="text" name="videoID" value="<?php echo $this->lists['providerVideo'] ?>" />
							      	<br /><br />
							      	<a class="modal" rel="{handartsleagueer: 'iframe', size: {x: 990, y: 600}}" href="http://pacav69@gmail.com/allvideos-documentation"><?php echo JText::_('Read the AllVideos documentation for more...'); ?></a>
							      </div>
							      <?php echo $pane->endPanel(); ?>
							      <?php echo $pane->startPanel(JText::_('Embed'), 'vidtab4'); ?>
							      <div class="panel" id="embedVideo">
							      	<?php echo JText::_('Paste video HTML embed code below'); ?>:
							      	<br />
							      	<textarea name="embedVideo" rows="5" cols="50" class="textarea"><?php echo $this->lists['embedVideo']; ?></textarea>
							      </div>
							      <?php echo $pane->endPanel(); ?>
							      <?php echo $pane->endPane(); ?>
							  	</td>
							  </tr>
							  <tr>
							    <td align="right" class="key"><?php echo JText::_('Video caption'); ?></td>
							    <td><input type="text" name="video_caption" size="50" class="text_area" value="<?php echo $this->row->video_caption; ?>" /></td>
							  </tr>
							  <tr>
							    <td align="right" class="key"><?php echo JText::_('Video credits'); ?></td>
							    <td><input type="text" name="video_credits" size="50" class="text_area" value="<?php echo $this->row->video_credits; ?>" /></td>
							  </tr>
							  <?php if($this->row->video): ?>
							  <tr>
							    <td align="right" class="key"><?php echo JText::_('Video preview'); ?></td>
							    <td>
							      <?php echo $this->row->video; ?>
							      <br />
							      <input type="checkbox" name="del_video" id="del_video" />
							      <label for="del_video"><?php echo JText::_('Check this box to delete current video or use the form above to replace the existing one'); ?></label>
							    </td>
							  </tr>
							  <?php endif; ?>
							</table>
							<?php else: ?>
							<dartsleague id="system-message">
							  <dt class="notice"><?php echo JText::_('Notice'); ?></dt>
							  <dd class="notice message fade">
							    <ul>
							      <li><?php echo JText::_('Notice: Please install SilverPC Consultants. AllVideos plugin if you want to use the video features of dartsleague!'); ?></li>
							    </ul>
							  </dd>
							</dartsleague>
							<table class="admintable" id="venue_video_content">
							  <tr>
							    <td align="right" class="key"><?php echo JText::_('Video source'); ?></td>
							    <td>
							    	<?php $pane = & JPane::getInstance('Tabs'); echo $pane->startPane('myPane'); ?>
							      <?php echo $pane->startPanel(JText::_('Use video embed code'), 'vidtab4'); ?>
							      <div class="panel" id="embedVideo">
							      	<?php echo JText::_('Paste video embed code here'); ?>
							      	<br />
							      	<textarea name="embedVideo" rows="5" cols="50" class="textarea"><?php echo $this->lists['embedVideo']; ?></textarea>
							      </div>
							      <?php echo $pane->endPanel(); ?>
							      <?php echo $pane->endPane(); ?>
							  	</td>
							  </tr>
							  <tr>
							    <td align="right" class="key"><?php echo JText::_('Video caption'); ?></td>
							    <td><input type="text" name="video_caption" size="50" class="text_area" value="<?php echo $this->row->video_caption; ?>" /></td>
							  </tr>
							  <tr>
							    <td align="right" class="key"><?php echo JText::_('Video credits'); ?></td>
							    <td><input type="text" name="video_credits" size="50" class="text_area" value="<?php echo $this->row->video_credits; ?>" /></td>
							  </tr>
							  <?php if($this->row->video): ?>
							  <tr>
							    <td align="right" class="key"><?php echo JText::_('Video preview'); ?></td>
							    <td>
							      <?php echo $this->row->video; ?>
							      <br />
							      <input type="checkbox" name="del_video" id="del_video" />
							      <label for="del_video"><?php echo JText::_('Use the form above to replace the existing video or check this box to delete current video'); ?></label>
							    </td>
							  </tr>
							  <?php endif; ?>
							</table>
							<?php endif; ?>

							<?php if (count($this->dartsleaguePluginsvenueVideo)): ?>
							<div class="venuePlugins">
							  <?php foreach ($this->dartsleaguePluginsvenueVideo as $dartsleaguePlugin) : ?>
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

				    <!-- Tab extra fields -->
				    <div class="simpleTabsContent">
					    <div id="extraFieldsContainer">
					    	<?php if (count($this->extraFields)): ?>
					      <table class="admintable" id="extraFields">
					        <?php foreach ($this->extraFields as $extraField): ?>
					        <tr>
					          <td align="right" class="key"><?php echo $extraField->name; ?></td>
					          <td><?php echo $extraField->element; ?></td>
					        </tr>
					        <?php endforeach; ?>
					      </table>
					      <?php else: ?>
					      <dartsleague id="system-message">
					        <dt class="notice"><?php echo JText::_('Notice'); ?></dt>
					        <dd class="notice message fade">
					          <ul>
					            <li><?php echo JText::_('Please select a category first to retrieve its related "Extra Fields"...'); ?></li>
					          </ul>
					        </dd>
					      </dartsleague>
					      <?php endif; ?>

							</div>

					    <?php if (count($this->dartsleaguePluginsvenueExtraFields)): ?>
					    <div class="venuePlugins">
					      <?php foreach ($this->dartsleaguePluginsvenueExtraFields as $dartsleaguePlugin) : ?>
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

				    <!-- Tab attachements -->
				    <div class="simpleTabsContent">
							<div class="venueAttachments">
							  <?php if (count($this->row->attachments)): ?>
							  <table class="adminlist">
							    <tr>
							      <th><?php echo JText::_('Filename'); ?></th>
							      <th><?php echo JText::_('Title'); ?></th>
							      <th><?php echo JText::_('Title attribute'); ?></th>
							      <th><?php echo JText::_('Downloads'); ?></th>
							      <th><?php echo JText::_('Operations'); ?></th>
							    </tr>
							    <?php foreach ($this->row->attachments as $attachment) : ?>
							    <tr>
							      <td class="attachment_entry"><?php echo $attachment->filename; ?></td>
							      <td><?php echo $attachment->title; ?></td>
							      <td><?php echo $attachment->titleAttribute; ?></td>
							      <td><?php echo $attachment->hits; ?></td>
							      <td><a href="index.php?option=com_dartsleague&amp;view=venue&amp;task=download&amp;id=<?php echo $attachment->id ?>"><?php echo JText::_('Download'); ?></a> <a class="deleteAttachmentButton" href="index.php?option=com_dartsleague&amp;view=venue&amp;task=deleteAttachment&amp;id=<?php echo $attachment->id?>&amp;cid=<?php echo $this->row->id; ?>"><?php echo JText::_('Delete'); ?></a></td>
							    </tr>
							    <?php endforeach; ?>
							  </table>
							  <?php endif; ?>
							</div>

							<div style="padding:0 16px;">
								<input type="button" value="<?php echo JText::_('Add attachment field'); ?>" onclick="addAttachment();" />
							</div>

							<div id="venueAttachments"></div>

							<?php if (count($this->dartsleaguePluginsvenueAttachments)): ?>
							<div class="venuePlugins">
							  <?php foreach ($this->dartsleaguePluginsvenueAttachments as $dartsleaguePlugin) : ?>
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

				    <?php if(count(array_filter($this->dartsleaguePluginsvenueOther))): ?>
				    <!-- Tab other plugins -->
				    <div class="simpleTabsContent">
					    <div class="venuePlugins">
					      <?php foreach ($this->dartsleaguePluginsvenueOther as $dartsleaguePlugin) : ?>
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
          <input type="hidden" name="view" value="venue" />
          <input type="hidden" name="task" value="<?php echo JRequest::getVar('task'); ?>" />
          <?php echo JHTML::_('form.token'); ?>
        </td>

        <td id="adminFormdartsleagueSidebar"<?php if(!$this->params->get('sideBarDisplay')): ?> style="display:none;"<?php endif; ?>>

        	<table class="sidebarDetails">
            <?php if($this->row->id): ?>
            <tr>
              <td><strong><?php echo JText::_('venue ID'); ?></strong></td>
              <td><?php echo $this->row->id; ?></td>
            </tr>
            <tr>
              <td><strong><?php echo JText::_('State'); ?></strong></td>
              <td><?php echo ($this->row->published > 0) ? JText::_('Published') : JText::_('Unpublished'); ?></td>
            </tr>
<!--            <tr>
              <td><strong><?php echo JText::_('Featured state'); ?></strong></td>
              <td><?php echo ($this->row->featured > 0) ? JText::_('Featured'):	JText::_('Not featured'); ?></td>
            </tr> -->
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
              <td class="dartsleaguevenueFormDateField"><?php echo JHTML::_( 'calendar',$this->row->created, 'created', 'created', '%Y-%m-%d %H:%M:%S'); ?></td>
            </tr>
            <tr>
              <td align="right" class="key"><?php echo JText::_('Start publishing'); ?></td>
              <td class="dartsleaguevenueFormDateField"><?php echo JHTML::_( 'calendar',$this->row->publish_up, 'publish_up', 'publish_up', '%Y-%m-%d %H:%M:%S'); ?></td>
            </tr>
            <tr>
              <td align="right" class="key"><?php echo JText::_('Finish publishing'); ?></td>
              <td class="dartsleaguevenueFormDateField"><?php echo JHTML::_( 'calendar',$this->row->publish_down, 'publish_down', 'publish_down', '%Y-%m-%d %H:%M:%S'); ?></td>
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
          <?php echo $pane->startPanel(JText::_('venue view options in category listings'), "venue-view-options-listings"); ?>
          <?php echo $this->form->render('params','venue-view-options-listings'); ?>
          <?php echo $pane->endPanel(); ?>
          <?php echo $pane->startPanel(JText::_('venue view options'), "venue-view-options"); ?>
          <?php echo $this->form->render('params','venue-view-options'); ?>
          <?php echo $pane->endPanel(); ?>
          <?php echo $pane->endPane(); ?>
         </td>
      </tr>
    </tbody>
  </table>
  <div class="clr"></div>
</form>
