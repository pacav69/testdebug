<?php
/**
 * @version		$Id: move.php 478 2010-06-16 16:11:42Z SilverPC Consultants. $
 * @package		dartsleague
 * @author		SilverPC Consultants.
 * @copyright	Copyright (c) 2009 - 2011 SilverPC Consultants. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

?>

<script type="text/javascript">
	//<![CDATA[
	function submitbutton(pressbutton) {
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
		if (trim( document.adminForm.category.value ) == "") {
			alert( '<?php echo JText::_('You must select a target category', true);?>' );
		} else {
			submitform( pressbutton );
		}
	}
	//]]>
</script>

<form action="index.php" method="post" name="adminForm">
	<fieldset>
		<legend><?php echo JText::_('Target category');?></legend>
		<?php echo $this->lists['categories'];?>
	</fieldset>
	<fieldset>
		<legend><?php echo JText::_('Items being moved');?></legend>
		<ol>
			<?php foreach ($this->rows as $row):?>
			<li><?php echo $row->title;?><input type="hidden" name="cid[]" value="<?php echo $row->id;?>" /></li>
			<?php endforeach;?>
		</ol>
	</fieldset>
	<input type="hidden" name="option" value="<?php echo $option;?>" />
	<input type="hidden" name="view" value="<?php echo JRequest::getVar('view'); ?>" />
	<input type="hidden" name="task" value="<?php echo JRequest::getVar('task'); ?>" />
</form>
