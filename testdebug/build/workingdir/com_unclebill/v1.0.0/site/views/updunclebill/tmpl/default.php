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
	the UncleBill greeting</h2>

<form class="form-validate"
	action="<?php echo JRoute::_('index.php'); ?>" method="post"
	id="updunclebill" name="updunclebill">
	<fieldset>
		<dl>
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
				<input type="hidden" name="option" value="com_unclebill" /> <input
					type="hidden" name="task" value="updunclebill.submit" />
			</dd>
			<dt></dt>
			<dd>
				<button type="submit" class="button"><?php echo JText::_('Submit'); ?></button>
			                <?php echo JHtml::_('form.token'); ?>
                </dd>
		</dl>
		<fieldset>

</form>
<div class="clr"></div>
