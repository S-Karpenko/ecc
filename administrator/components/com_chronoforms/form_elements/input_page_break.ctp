<div class="dragable" id="input_page_break">Page Break</div>
<div class="element_code" id="input_page_break_element">
	<label id="input_page_break_{n}_label" class="page_break_label updatable_label" style="width: 200px !important;">Page Break - <?php echo $element_params['page_label']; ?></label>
	<?php echo $HtmlHelper->input('chronofield[{n}][input_page_break_{n}_page_label]', array('type' => 'hidden', 'id' => 'input_page_break_{n}_page_label', 'value' => $element_params['page_label'])); ?>
	
	<input type="hidden" id="chronofield_id_{n}" name="chronofield_id" value="{n}" />
    <input type="hidden" name="chronofield[{n}][tag]" value="input" />
    <input type="hidden" name="chronofield[{n}][type]" value="page_break" />
	<input type="hidden" id="container_id_{n}" name="chronofield[{n}][container_id]" value="<?php echo $element_params['container_id']; ?>" />
</div>
<div class="element_config" id="input_page_break_element_config">
	<?php echo $PluginTabsHelper->Header(array('settings' => 'Settings', 'help' => 'Help'), 'input_page_break_element_config_{n}'); ?>
	<?php echo $PluginTabsHelper->tabStart('settings'); ?>
		<?php echo $HtmlHelper->input('input_page_break_{n}_page_label_config', array('type' => 'text', 'label' => 'Page Label', 'class' => 'small_input', 'value' => '')); ?>
	<?php echo $PluginTabsHelper->tabEnd(); ?>
	<?php echo $PluginTabsHelper->tabStart('help'); ?>
		This element will start a new form page, you should configure the page shown under the "Show HTML" action settings, each form page can be inside its own Event.
		<br />
		<br />
		If you want to do the same inside a custom code then please use this formula: <?php echo htmlspecialchars('<!--_CHRONOFORMS_PAGE_BREAK_-->'); ?>
	<?php echo $PluginTabsHelper->tabEnd(); ?>
</div>