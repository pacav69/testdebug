<?php
/**
 * @version		$Id: default.php 503 2010-06-24 21:11:53Z SilverPC Consultants. $
 * @package		dl views team
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
			alert( '<?php echo JText::_('Team must have a Name', true); ?>' );
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
		var div = new Element('div',{'style':'border-top: 1px dotted #ccc; margin: 4px; padding: 10px;'}).injectInside($('teamAttachments'));
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
			var url = 'index.php?option=com_dl&view=team&task=tag&tag='+tag;
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

		$('catid').addEvent('change', function(){
			var selectedValue = this.value;
			var url = 'index.php?option=com_dl&view=team&task=extraFields&cid='+selectedValue;
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
				url: '<?php echo JURI::base()?>index.php?option=com_dl&view=team&task=filebrowser&type=image&tmpl=component',
				size: {x: 590, y: 400}
			});
		})

		$$('ul.tags').addEvent('click', function(){
			$('search-field').focus();
		})
		var completer = new Autocompleter.Ajax.Json($('search-field'), '<?php echo JURI::root()?>index.php?option=com_dl&view=team&task=tags',
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

<div id="dlToggleSidebarContainer">
	<a href="#" id="dlToggleSidebar"><?php echo JText::_('Toggle sidebar'); ?></a>
</div>

<form action="index.php" enctype="multipart/form-data" method="post" name="adminForm">

  <table cellspacing="0" cellpadding="0" border="0" class="adminFormdlContainer">
    <tbody>
      <tr>
        <td>

					<table class="adminform adminFormdl">
						<tr>
							<td><label for="name"><?php echo JText::_('Team Name'); ?></label><?php echo JHTML::_('tooltip', JText::_( 'TIPNAMEUSEDTOIDENTIFYNAME' )); ?></td>
							<td><input class="text_area dlTitleBox" type="text" name="name" id="name" maxlength="250" value="<?php echo $this->row->name; ?>" /></td>
							<td><label><?php echo JText::_('Published'); ?></label></td>
							<td><?php echo $this->lists['published']; ?></td>
						</tr>
						<tr>
<!--                            <td><label><?php echo JText::_('Category'); ?></label></td>
                            <td><?php echo $this->lists['categories']; ?></td> -->
							<td>
                                <label for="team"><?php echo JText::_('Venue'); ?></label>
                                <?php echo JHTML::_('tooltip', JText::_( 'TIPNAMEUSEDTOIDENTIFYVENUE' )); ?>
                        </td>
                        <td>
                          <?php if($user->gid>23):?>
                             <?php echo $row->venuename; ?>  
                              <!--<a href="<?php echo JRoute::_('index.php?option=com_dl&task=edit&cid[]='.$row->catid);?>"><?php echo $row->teamname; ?></a>-->
                          <?php else:?> 
                          <!--<a href="<?php echo JRoute::_('index.php?option=com_dl&task=edit&cid[]='.$row->venuename);?>"><?php echo $row->venuename; ?></a>-->
                          <!--<?php echo $this->lists['venuetypes']; ?> -->

                          <?php endif; ?>
                        </td>
                
<!--                     <td>
      <?php echo $this->lists['venuetypes']; ?>  -->
                           </td>
                            <!-- <td><input class="text_area dlTitleBox" type="text" name="venue" id="team" maxlength="250" value="<?php echo $this->row->catid; ?>" /></td> -->
						</tr>
					</table>
          <input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
          <input type="hidden" name="option" value="<?php echo $option; ?>" />
          <input type="hidden" name="view" value="teams" />
          <input type="hidden" name="task" value="<?php echo JRequest::getVar('task'); ?>" />
          <?php echo JHTML::_('form.token'); ?>
        </td>

        <td id="adminFormdlSidebar"<?php if(!$this->params->get('sideBarDisplay')): ?> style="display:none;"<?php endif; ?>>

        	<table class="sidebarDetails">
            <?php if($this->row->id): ?>
            <tr>
              <td><strong><?php echo JText::_('Team ID'); ?></strong></td>
              <td><?php echo $this->row->id; ?></td>
            </tr>
            <tr>
              <td><strong><?php echo JText::_('State'); ?></strong></td>
              <td><?php echo ($this->row->published > 0) ? JText::_('Published') : JText::_('Unpublished'); ?></td>
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
              <td class="dlteamFormDateField"><?php echo JHTML::_( 'calendar',$this->row->created, 'created', 'created', '%Y-%m-%d %H:%M:%S'); ?></td>
            </tr>
            <tr>
              <td align="right" class="key"><?php echo JText::_('Start publishing'); ?></td>
              <td class="dlteamFormDateField"><?php echo JHTML::_( 'calendar',$this->row->publish_up, 'publish_up', 'publish_up', '%Y-%m-%d %H:%M:%S'); ?></td>
            </tr>
            <tr>
              <td align="right" class="key"><?php echo JText::_('Finish publishing'); ?></td>
              <td class="dlteamFormDateField"><?php echo JHTML::_( 'calendar',$this->row->publish_down, 'publish_down', 'publish_down', '%Y-%m-%d %H:%M:%S'); ?></td>
            </tr>
          </table>
          <?php	echo $pane->endPanel(); ?>
         </td>
      </tr>
    </tbody>
  </table>
  <div class="clr"></div>
</form>
