<div class="dragable" id="input_container">Container</div>
<div class="element_code" id="input_container_element" style="min-height: 10px; background-color: #ddf; border: 2px dotted #777; padding-top:25px;">
	<label id="input_container_{n}_label" class="container_label updatable_label" style="width: 200px !important; position: absolute; left:1px; top:1px; margin-top: 0px !important;"><?php echo $element_params['area_label']; ?></label>
	<a href="#" onClick="collapseContainer({n}); return false;" id="input_container_collapse_toggler_{n}" style="font-size:12px; font-weight:bold; position:absolute; top:0px; width:50%; text-align:right;">Collapse</a>
	<?php echo $HtmlHelper->input('chronofield[{n}][input_container_{n}_area_label]', array('type' => 'hidden', 'id' => 'input_container_{n}_area_label', 'value' => $element_params['area_label'])); ?>
	<?php echo $HtmlHelper->input('chronofield[{n}][input_container_{n}_collapsed]', array('type' => 'hidden', 'id' => 'input_container_{n}_collapsed', 'value' => $element_params['collapsed'])); ?>
	<?php echo $HtmlHelper->input('chronofield[{n}][input_container_{n}_container_type]', array('type' => 'hidden', 'id' => 'input_container_{n}_container_type', 'value' => $element_params['container_type'])); ?>
	<?php echo $HtmlHelper->input('chronofield[{n}][input_container_{n}_container_class]', array('type' => 'hidden', 'id' => 'input_container_{n}_container_class', 'value' => $element_params['container_class'])); ?>
	<textarea name="chronofield[{n}][input_container_{n}_start_code]" id="input_container_{n}_start_code" style="display:none"><?php echo htmlspecialchars($element_params['start_code']); ?></textarea>
    <textarea name="chronofield[{n}][input_container_{n}_end_code]" id="input_container_{n}_end_code" style="display:none"><?php echo htmlspecialchars($element_params['end_code']); ?></textarea>
    
	<input type="hidden" id="chronofield_id_{n}" name="chronofield_id" value="{n}" />
    <input type="hidden" name="chronofield[{n}][tag]" value="input" />
    <input type="hidden" name="chronofield[{n}][type]" value="container" />
	<input type="hidden" id="container_id_{n}" name="chronofield[{n}][container_id]" value="<?php echo $element_params['container_id']; ?>" />
	
	<!--CONTAINER_ELEMENTS-{n}-->
</div>
<div class="element_config" id="input_container_element_config">
	<?php echo $PluginTabsHelper->Header(array('settings' => 'Settings', 'help' => 'Help'), 'input_container_element_config_{n}'); ?>
	<?php echo $PluginTabsHelper->tabStart('settings'); ?>
		<?php echo $HtmlHelper->input('input_container_{n}_area_label_config', array('type' => 'text', 'label' => 'Area Label', 'class' => 'small_input', 'value' => '')); ?>
		<?php echo $HtmlHelper->input('input_container_{n}_container_type_config', array('type' => 'select', 'label' => 'Container Type', 'options' => array('' => 'Virtual (none)', 'div' => 'Div', 'fieldset' => 'Field Set', 'tabs_area' => 'Tabs Area', 'tab' => 'Tab', 'sliders_area' => 'Sliders Area', 'slider' => 'Slider', 'custom' => 'Custom'), 'smalldesc' => 'Virtual = no output, Custom mode output can be inserted below.')); ?>
		<?php echo $HtmlHelper->input('input_container_{n}_container_class_config', array('type' => 'text', 'label' => 'Container Class', 'class' => 'small_input', 'value' => '')); ?>
		<?php echo $HtmlHelper->input('input_container_{n}_start_code_config', array('type' => 'textarea', 'label' => 'Start Code', 'rows' => 5, 'cols' => 50, 'style' => 'width:380px !important;', 'smalldesc' => 'The code to be inserted at the start of the container when in "custom" mode.')); ?>
		<?php echo $HtmlHelper->input('input_container_{n}_end_code_config', array('type' => 'textarea', 'label' => 'End Code', 'rows' => 5, 'cols' => 50, 'style' => 'width:380px !important;', 'smalldesc' => 'The code to be inserted at the end of the container when in "custom" mode.')); ?>
		<?php echo $HtmlHelper->input('input_container_{n}_collapsed_config', array('type' => 'hidden', 'value' => '')); ?>
	<?php echo $PluginTabsHelper->tabEnd(); ?>
	<?php echo $PluginTabsHelper->tabStart('help'); ?>
		Select the container type and drag other elements inside, the "Virtual" type will not have any output in the form code, use it if you want to save space or oragnize your wizard view.
		<br /><br />
		If you need a "Tabs" box then you will have to have multiple containers configured to "Tab" inside a parent container configured to "Tabs Area", same applies to "Sliders".
	<?php echo $PluginTabsHelper->tabEnd(); ?>
</div>