<?php
/**
 * @version		$Id: default.php 503 2010-06-24 21:11:53Z SilverPC Consultants. $
 * @package		dartsleague player
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

// 		syncExtraFieldsEditor();

		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
		if (trim( document.adminForm.firstname.value ) == "") {
			alert( '<?php echo JText::_('Player must have a First Name', true); ?>' );
		} 
        /** else if (trim( document.adminForm.catid.value ) == "0") {
			alert( '<?php echo JText::_('Please select a category', true); ?>' );
		}   */
        else {
			
			submitform( pressbutton );
		}
	}

	

		$('cid').addEvent('change', function(){
			var selectedValue = this.value;
			var url = 'index.php?option=com_dartsleague&view=player&task=extraFields&cid='+selectedValue;
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
				url: '<?php echo JURI::base()?>index.php?option=com_dartsleague&view=player&task=filebrowser&type=image&tmpl=component',
				size: {x: 590, y: 400}
			});
		})

		$$('ul.tags').addEvent('click', function(){
			$('search-field').focus();
		})
		var completer = new Autocompleter.Ajax.Json($('search-field'), '<?php echo JURI::root()?>index.php?option=com_dartsleague&view=player&task=tags',
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
							<td><label for="firstname"><?php echo JText::_('First Name'); ?></label></td>
							<td><input class="text_area dartsleagueTitleBox" type="text" name="firstname" id="firstname" maxlength="250" value="<?php echo $this->row->firstname; ?>" /></td>
							<td><label><?php echo JText::_('Published'); ?></label></td>
							<td><?php echo $this->lists['published']; ?></td>
						</tr>
						<tr>
							<td><label for="lastname"><?php echo JText::_('Last Name'); ?></label></td>
							<td><input class="text_area dartsleagueTitleBox" type="text" name="lastname" id="lastname" maxlength="250" value="<?php echo $this->row->lastname; ?>" /></td>
						</tr>
                        <tr>
                            <td><label for="plalias"><?php echo JText::_('Alias'); ?></label></td>
                            <td><input class="text_area dartsleagueTitleBox" type="text" name="plalias" id="plalias" maxlength="250" value="<?php echo $this->row->plalias; ?>" /></td>
                        </tr>
                        <tr>
                            <td class="key"><label for="gender"><?php    echo JText::_('Gender'); ?></label></td>
                            <td><?php echo $this->lists['gender']; ?></td>
                        </tr>
                        <tr>
                            <td><label for="nationality"><?php echo JText::_('Nationality'); ?></label></td>
                            <td><input class="text_area dartsleagueTitleBox" type="text" name="nationality" id="nationality" maxlength="250" value="<?php echo $this->row->nationality; ?>" /></td>
                        </tr>
                        <tr>
                            <td><label for="birthdate"><?php echo JText::_('Birthdate'); ?></label></td>
                            <td><input class="text_area dartsleagueTitleBox" type="text" name="birthdate" id="birthdate" maxlength="250" value="<?php echo JHTML::Date ($this->row->birthdate); ?>" /></td>
                        </tr>
                        <tr>
                            <td><label for="email"><?php echo JText::_('Email'); ?></label></td>
                            <td><input class="text_area dartsleagueTitleBox" type="text" name="email" id="email" maxlength="250" value="<?php echo $this->row->email; ?>" /></td>
                        </tr>
                        <tr>
                            <td><label for="mobile"><?php echo JText::_('Mobile'); ?></label></td>
                            <td><input class="text_area dartsleagueTitleBox" type="text" name="mobile" id="mobile" maxlength="250" value="<?php echo $this->row->mobile; ?>" /></td>
                        </tr>
                        <tr>
                            <td>
                                <label for="team"><?php echo JText::_('Team'); ?></label>
                                
                            </td>
                            <td>
                                <?php echo $this->lists['teamid']; ?>
                            </td> 
                        </tr>    
<!--                        <tr>
                            <td><label for="team"><?php echo JText::_('team'); ?></label></td>
                            <td><input class="text_area dartsleagueTitleBox" type="text" name="team" id="team" maxlength="250" value="<?php echo $this->row->team; ?>" /></td>
                        </tr>  -->
<!--                        <tr>
                            <td><label for="gamesplayed"><?php echo JText::_('Games played'); ?></label></td>
                            <td><input class="text_area dartsleagueTitleBox" type="text" name="gamesplayed" id="gamesplayed" maxlength="250" value="<?php echo $this->row->gamesplayed; ?>" /></td>
                        </tr>
                        <tr>
                            <td><label for="gameswon"><?php echo JText::_('Games Won'); ?></label></td>
                            <td><input class="text_area dartsleagueTitleBox" type="text" name="gameswon" id="gameswon" maxlength="250" value="<?php echo $this->row->gameswon; ?>" /></td>
                        </tr>
                        <tr>
                            <td><label for="gameslost"><?php echo JText::_('Games Lost'); ?></label></td>
                            <td><input class="text_area dartsleagueTitleBox" type="text" name="gameslost" id="gameslost" maxlength="250" value="<?php echo $this->row->gameslost; ?>" /></td>
                        </tr>
                        <tr>
                            <td><label for="teamposition"><?php echo JText::_('team Position'); ?></label></td>
                            <td><input class="text_area dartsleagueTitleBox" type="text" name="teamposition" id="teamposition" maxlength="250" value="<?php echo $this->row->teamposition; ?>" /></td>
                        </tr>
                        <tr>
                            <td><label for="tons"><?php echo JText::_('Tons'); ?></label></td>
                            <td><input class="text_area dartsleagueTitleBox" type="text" name="tons" id="tons" maxlength="250" value="<?php echo $this->row->tons; ?>" /></td>
                        </tr>
                        <tr>
                            <td><label for="ton140s"><?php echo JText::_('Ton 140s'); ?></label></td>
                            <td><input class="text_area dartsleagueTitleBox" type="text" name="ton140s" id="ton140s" maxlength="250" value="<?php echo $this->row->ton140s; ?>" /></td>
                        </tr>
                        <tr>
                            <td><label for="ton180s"><?php echo JText::_('Ton 180s'); ?></label></td>
                            <td><input class="text_area dartsleagueTitleBox" type="text" name="ton180s" id="ton180s" maxlength="250" value="<?php echo $this->row->ton180s; ?>" /></td>
                        </tr>
                        <tr>
                            <td><label for="hipoints"><?php echo JText::_('Hi Points'); ?></label></td>
                            <td><input class="text_area dartsleagueTitleBox" type="text" name="hipoints" id="hipoints" maxlength="250" value="<?php echo $this->row->hipoints; ?>" /></td>
                        </tr>
                        <tr>
                            <td><label for="season"><?php echo JText::_('Season'); ?></label></td>
                            <td><input class="text_area dartsleagueTitleBox" type="text" name="season" id="season" maxlength="250" value="<?php echo $this->row->season; ?>" /></td>
                        </tr>
                        <tr>
                            <td><label for="weekno"><?php echo JText::_('Week No'); ?></label></td>
                            <td><input class="text_area dartsleagueTitleBox" type="text" name="weekno" id="weekno" maxlength="250" value="<?php echo $this->row->weekno; ?>" /></td>
                        </tr>
                        <tr>  
-->

						
					</table>

				  <!-- Tabs start here -->
				  <div class="simpleTabs">
				    <ul class="simpleTabsNavigation">
				      <li id="tabContent"><a href="#"><?php echo JText::_('Content'); ?></a></li>
				      <li id="tabImage"><a href="#"><?php echo JText::_('Image'); ?></a></li>
				      <li id="tabImageGallery"><a href="#"><?php echo JText::_('Image gallery'); ?></a></li>
				      <!--<li id="tabVideo"><a href="#"><?php echo JText::_('Video'); ?></a></li> -->   
				      <!-- <li id="tabExtraFields"><a href="#"><?php echo JText::_('Extra fields'); ?></a></li>  -->   
				     <!-- <li id="tabAttachments"><a href="#"><?php echo JText::_('Attachments'); ?></a></li> -->
                      <!-- TODO: find out items plugin can it be used for player -->
				      <?php if(count(array_filter($this->dartsleaguePluginsplayerOther))): ?>
				      <li id="tabPlugins"><a href="#"><?php echo JText::_('Plugins'); ?></a></li>
				      <?php endif; ?>
				    </ul>

				    <!-- Tab content -->
				    <div class="simpleTabsContent">
							<?php if($this->params->get('mergeEditors')): ?>
							<div class="dartsleagueplayerFormEditor">
								<?php echo $this->text; ?>
								<div class="dummyHeight"></div>
								<div class="clr"></div>
							</div>
							<?php else: ?>
							<div class="dartsleagueplayerFormEditor">
								<span class="dartsleagueplayerFormEditorTitle">
									<?php echo JText::_('Introtext (teaser content/excerpt)'); ?>
								</span>
								<?php echo $this->introtext; ?>
								<div class="dummyHeight"></div>
								<div class="clr"></div>
							</div>
							<div class="dartsleagueplayerFormEditor">
								<span class="dartsleagueplayerFormEditorTitle">
									<?php echo JText::_('Fulltext (main content)'); ?>
								</span>
								<?php echo $this->fulltext; ?>
								<div class="dummyHeight"></div>
								<div class="clr"></div>
							</div>
							<?php endif; ?>

							<?php if (count($this->dartsleaguePluginsplayerContent)): ?>
							<div class="playerPlugins">
								<?php foreach ($this->dartsleaguePluginsplayerContent as $dartsleaguePlugin) : ?>
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
							    <td align="right" class="key"><?php echo JText::_('player image'); ?></td>
							    <td>
							    	<input type="file" name="image" class="fileUpload" />
							    	<br /><br />
							    	<input type="text" name="existingImage" id="existingImageValue" class="text_area" readonly="readonly"> <input type="button" value="<?php echo JText::_('Browse server...'); ?>" id="browseSrv"  />
							    </td>
							  </tr>
							  <tr>
							    <td align="right" class="key"><?php echo JText::_('player image caption'); ?></td>
							    <td><input type="text" name="image_caption" size="30" class="text_area" value="<?php echo $this->row->image_caption; ?>" /></td>
							  </tr>
							  <tr>
							    <td align="right" class="key"><?php echo JText::_('player image credits'); ?></td>
							    <td><input type="text" name="image_credits" size="30" class="text_area" value="<?php echo $this->row->image_credits; ?>" /></td>
							  </tr>
							  <?php if (!empty($this->row->image)): ?>
							  <tr>
							    <td align="right" class="key"><?php echo JText::_('player image preview'); ?></td>
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

							<?php if (count($this->dartsleaguePluginsplayerImage)): ?>
							<div class="playerPlugins">
							  <?php foreach ($this->dartsleaguePluginsplayerImage as $dartsleaguePlugin) : ?>
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
							<table class="admintable" id="player_gallery_content">
							  <tr>
							    <td align="right" valign="top" class="key"><?php echo JText::_('Upload a zip file with images'); ?></td>
							    <td valign="top">
							    	<input type="file" name="gallery" class="fileUpload" />
							    	<?php if (!empty($this->row->gallery)): ?>
							      <div id="playerGallery">
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

							<?php if (count($this->dartsleaguePluginsplayerGallery)): ?>
							<div class="playerPlugins">
							  <?php foreach ($this->dartsleaguePluginsplayerGallery as $dartsleaguePlugin) : ?>
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
							<table class="admintable" id="player_video_content">
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
							      	<a class="modal" rel="{handartsleagueer: 'iframe', size: {x: 590, y: 400}}" href="index.php?option=com_dartsleague&view=player&task=filebrowser&type=video&tmpl=component"><?php echo JText::_('Browse videos on server')?></a> <?php echo JText::_('or'); ?> <?php echo JText::_('paste remote video URL'); ?> <input type="text" size="30" name="remoteVideo" value="<?php echo $this->lists['remoteVideo'] ?>" />
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
							<table class="admintable" id="player_video_content">
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

							<?php if (count($this->dartsleaguePluginsplayerVideo)): ?>
							<div class="playerPlugins">
							  <?php foreach ($this->dartsleaguePluginsplayerVideo as $dartsleaguePlugin) : ?>
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
				    

					<!-- Tabs end here -->

          <input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
          <input type="hidden" name="option" value="<?php echo $option; ?>" />
          <input type="hidden" name="view" value="player" />
          <input type="hidden" name="task" value="<?php echo JRequest::getVar('task'); ?>" />
          <?php echo JHTML::_('form.token'); ?>
        </td>

        <td id="adminFormdartsleagueSidebar"<?php if(!$this->params->get('sideBarDisplay')): ?> style="display:none;"<?php endif; ?>>

        	<table class="sidebarDetails">
            <?php if($this->row->id): ?>
            <tr>
              <td><strong><?php echo JText::_('player ID'); ?></strong></td>
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
<!--            <?php if($this->row->id): ?>
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
            <?php endif; ?> -->
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
              <td class="dartsleagueplayerFormDateField"><?php echo JHTML::_( 'calendar',$this->row->created, 'created', 'created', '%Y-%m-%d %H:%M:%S'); ?></td>
            </tr>
            <tr>
              <td align="right" class="key"><?php echo JText::_('Start publishing'); ?></td>
              <td class="dartsleagueplayerFormDateField"><?php echo JHTML::_( 'calendar',$this->row->publish_up, 'publish_up', 'publish_up', '%Y-%m-%d %H:%M:%S'); ?></td>
            </tr>
            <tr>
              <td align="right" class="key"><?php echo JText::_('Finish publishing'); ?></td>
              <td class="dartsleagueplayerFormDateField"><?php echo JHTML::_( 'calendar',$this->row->publish_down, 'publish_down', 'publish_down', '%Y-%m-%d %H:%M:%S'); ?></td>
            </tr>
          </table>
          <?php	echo $pane->endPanel(); ?>

<!--          <?php echo $pane->startPanel(JText::_('Metadata Information'), "metadata-page"); ?>
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
          <?php	echo $pane->endPanel(); ?>  -->
<!--          <?php echo $pane->startPanel(JText::_('player view options in category listings'), "player-view-options-listings"); ?>
          <?php echo $this->form->render('params','player-view-options-listings'); ?>
          <?php echo $pane->endPanel(); ?>
          <?php echo $pane->startPanel(JText::_('player view options'), "player-view-options"); ?>
          <?php echo $this->form->render('params','player-view-options'); ?>
          <?php echo $pane->endPanel(); ?>
          <?php echo $pane->endPane(); ?>  -->
         </td>
      </tr>
    </tbody>
  </table>
  <div class="clr"></div>
</form>
