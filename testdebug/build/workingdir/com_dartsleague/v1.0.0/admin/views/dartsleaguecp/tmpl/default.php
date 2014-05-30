<?php defined('_JEXEC') or die('Restricted access');?>

<form action="index.php" method="post" name="adminForm">
	<div class="adminform">
		<div class="cpanel-left">
			<div id="cpanel">
		<?php
		$link = 'index.php?option=com_dartsleague&view=players';
		// echo ()
		echo ($link, JText::_('COM_DARTSLEAGUE_PLAYERS'));
		
		// echo ($link JText::_('COM_DARTSLEAGUE_PLAYERS'));
		
 		// echo PhocaGalleryRenderAdmin::quickIconButton( $link, 'icon-48-pg-image.png', JText::_('COM_DARTSLEAGUE_PLAYERS) ); 
		 

/*  		$link = 'index.php?option=com_dartsleague&view=phocagalleryin';
		echo PhocaGalleryRenderAdmin::quickIconButton( $link, 'icon-48-pg-info.png', JText::_( 'COM_DARTSLEAGUE_INFO' ) );
	 */	
		
	?>
				
		<div style="clear:both">&nbsp;</div>
		<p>&nbsp;</p>
		<div style="text-align:center;padding:0;margin:0;border:0">
			<iframe style="padding:0;margin:0;border:0" src="http://www.phoca.cz/adv/phocagallery" noresize="noresize" frameborder="0" border="0" cellspacing="0" scrolling="no" width="500" marginwidth="0" marginheight="0" height="125">
			<a href="http://www.phoca.cz/adv/phocagallery" target="_blank">Phoca Gallery</a>
			</iframe> 
		</div>
	</div>
</div>
		
<div class="cpanel-right">
	<div style="border:1px solid #ccc;background:#fff;margin:15px;padding:15px">
		<div style="float:right;margin:10px;">
			<?php echo JHTML::_('image', 'administrator/components/com_dartsleague/assets/images/logo-phoca.png', 'Phoca.cz' );?>
		</div>
			
		<?php
		echo '<h3>'.  JText::_('COM_DARTSLEAGUE_VERSION').'</h3>'
		.'<p>'.  $this->tmpl['version'] .'</p>';

		echo '<h3>'.  JText::_('COM_DARTSLEAGUE_COPYRIGHT').'</h3>'
		.'<p>© 2007 - '.  date("Y"). ' Jan Pavelka</p>'
		.'<p><a href="http://www.phoca.cz/" target="_blank">www.phoca.cz</a></p>';

		echo '<h3>'.  JText::_('COM_DARTSLEAGUE_LICENCE').'</h3>'
		.'<p><a href="http://www.gnu.org/licenses/gpl-2.0.html" target="_blank">GPLv2</a></p>';
		
		echo '<h3>'.  JText::_('COM_DARTSLEAGUE_TRANSLATION').': '. JText::_('COM_DARTSLEAGUE_TRANSLATION_LANGUAGE_TAG').'</h3>'
        .'<p>© 2007 - '.  date("Y"). ' '. JText::_('COM_DARTSLEAGUE_TRANSLATER'). '</p>'
        .'<p>'.JText::_('COM_DARTSLEAGUE_TRANSLATION_SUPPORT_URL').'</p>';
		
		?>
		<p>&nbsp;</p>
		<p><strong><?php echo JText::_('COM_DARTSLEAGUE_SHADOWBOX_LICENCE_HEAD');?></strong></p>
		<p class="license"><?php echo JText::_('COM_DARTSLEAGUE_SHADOWBOX_LICENCE');?></p>
		<p><a href="http://www.shadowbox-js.com/" target="_blank">Shadowbox.js</a> by <a target="_blank" href="http://www.shadowbox-js.com/">Michael J. I. Jackson</a><br />
		<a target="_blank" href="http://creativecommons.org/licenses/by-nc-sa/3.0/">Creative Commons Attribution-Noncommercial-Share Alike</a></p>
		
		<p><strong><?php echo JText::_('COM_DARTSLEAGUE_HIGHSLIDE_LICENCE_HEAD');?></strong></p>
		<p class="license"><?php echo JText::_('COM_DARTSLEAGUE_HIGHSLIDE_LICENCE');?></p>
		<p><a href="http://highslide.com/" target="_blank">Highslide JS</a> by <a target="_blank" href="http://highslide.com/">Torstein Hønsi</a><br />
		<a target="_blank" href="http://creativecommons.org/licenses/by-nc/2.5/">Creative Commons Attribution-NonCommercial 2.5  License</a></p>
		
		<p><strong><?php echo JText::_('COM_DARTSLEAGUE_BOXPLUS_LICENCE_HEAD');?></strong></p>
		<p class="license"><?php echo JText::_('COM_DARTSLEAGUE_BOXPLUS_LICENCE');?></p>
		<p><a href="http://hunyadi.info.hu/en/projects/boxplus" target="_blank">boxplus</a> by <a target="_blank" href="http://hunyadi.info.hu/">Levente Hunyadi</a><br />
		<a target="_blank" href="http://www.gnu.org/licenses/gpl.html">GPL</a></p>
		
		<p>Google™, Google Maps™, Google Picasa™ and YouTube Broadcast Yourself™ are registered trademarks of Google Inc.</p>
		
		<?php
		echo '<div style="border-top:1px solid #c2c2c2"></div>'
.'<div id="pg-update"><a href="http://www.phoca.cz/version/index.php?phocagallery='.  $this->tmpl['version'] .'" target="_blank">'.  JText::_('COM_DARTSLEAGUE_CHECK_FOR_UPDATE') .'</a></div>'
		
		
		;
		?>
		
	</div>
		</div>

	</div>

	<input type="hidden" name="option" value="com_dartsleague" /> <input
		type="hidden" name="view" value="phocagallerycp" /> <input
		type="hidden" name="<?php echo JUtility::getToken(); ?>" value="1" />
</form>