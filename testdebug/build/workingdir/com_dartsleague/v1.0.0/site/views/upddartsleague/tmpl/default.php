<?php
// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

JHtml::_ ( 'behavior.keepalive' );
JHtml::_ ( 'behavior.formvalidation' );
JHtml::_ ( 'behavior.tooltip' );

// get the menu parameters for use
$menuparams = $this->state->get ( "menuparams" );
$headingtxtcolor = $menuparams->get ( "headingtxtcolor" );
$headingbgcolor = $menuparams->get ( "headingbgcolor" );

?>
<h2 style="color:<?php echo $headingtxtcolor; ?>; background-color:<?php echo $headingbgcolor; ?>;">Update
	the DartsLeague greeting</h2>

<form class="form-validate"
	action="<?php echo JRoute::_('index.php'); ?>" method="post"
	id="upddartsleague" name="upddartsleague">
	<fieldset>
		<dartsleague>
			<dt><?php echo $this->form->getLabel('id'); ?></dt>
			<dd><?php echo $this->form->getInput('id'); ?></dd>
			<dt></dt>
			<dd></dd>
			<dt><?php echo $this->form->getLabel('greeting'); ?></dt>
			<dd><?php echo $this->form->getInput('greeting'); ?></dd>
			<dt></dt>
			<dd></dd>
			<dt></dt>
			<dd>
				<input type="hidden" name="option" value="com_dartsleague" /> <input
					type="hidden" name="task" value="upddartsleague.submit" />
			</dd>
			<dt></dt>
			<dd>
				<button type="submit" class="button"><?php echo JText::_('Submit'); ?></button>
			                <?php echo JHtml::_('form.token'); ?>
                </dd>
		</dartsleague>
		<fieldset>

</form>
<div class="clr"></div>
