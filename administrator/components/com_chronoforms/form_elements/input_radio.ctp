<div class="dragable" id="input_radio">Radio Box</div>
<div class="element_code" id="input_radio_element">
	<label id="input_radio_{n}_label" class="text_label updatable_label"><?php echo $element_params['label_text']; ?></label>
	<?php //echo $form->radio('Chronoforms.'.$element_params['input_name'], $element_params['options'], array('value' => $element_params['input_value'], 'legend' => $element_params['legend'])); ?>
	<span class="temp_div_container options_refelctor">
		<?php
		$temp_options = explode("\n", $element_params['options']);
		$element_params['options'] = array();
		foreach($temp_options as $temp_option){
			$temp_option_details = explode('=', $temp_option);
			$element_params['options'][strval($temp_option_details[0])] = trim($temp_option_details[1]);
		}
		?>
		<?php foreach($element_params['options'] as $radio_option_value => $radio_option_text): ?>
		<input type="radio" name="radio_{n}_input_name" id="radio_{n}_input_id" />
		<label for="<?php echo $element_params['input_id'].$radio_option_value; ?>"><?php echo $radio_option_text; ?></label>
		<?php endforeach; ?>
	</span>
	<?php 
	$options = '';
	foreach($element_params['options'] as $radio_option_value => $radio_option_text){
		if(!empty($options)){
			$options .= "\n";
		}
		$options .= $radio_option_value.'='.$radio_option_text;
	}
	?>
	<?php echo $HtmlHelper->input('chronofield[{n}][input_radio_{n}_input_id]', array('type' => 'hidden', 'id' => 'input_radio_{n}_input_id', 'value' => $element_params['input_id'])); ?>
	<?php echo $HtmlHelper->input('chronofield[{n}][input_radio_{n}_label_text]', array('type' => 'hidden', 'id' => 'input_radio_{n}_label_text', 'value' => $element_params['label_text'])); ?>
	<?php echo $HtmlHelper->input('chronofield[{n}][input_radio_{n}_input_name]', array('type' => 'hidden', 'id' => 'input_radio_{n}_input_name', 'value' => $element_params['input_name'])); ?>
	<?php echo $HtmlHelper->input('chronofield[{n}][input_radio_{n}_input_value]', array('type' => 'hidden', 'id' => 'input_radio_{n}_input_value', 'value' => $element_params['input_value'])); ?>
	<?php echo $HtmlHelper->input('chronofield[{n}][input_radio_{n}_radios_over]', array('type' => 'hidden', 'id' => 'input_radio_{n}_radios_over', 'value' => $element_params['radios_over'])); ?>
	<?php echo $HtmlHelper->input('chronofield[{n}][input_radio_{n}_legend]', array('type' => 'hidden', 'id' => 'input_radio_{n}_legend', 'value' => $element_params['legend'])); ?>
	<?php echo $HtmlHelper->input('chronofield[{n}][input_radio_{n}_ghost]', array('type' => 'hidden', 'id' => 'input_radio_{n}_ghost', 'value' => $element_params['ghost'])); ?>
	<?php echo $HtmlHelper->input('chronofield[{n}][input_radio_{n}_ghost_value]', array('type' => 'hidden', 'id' => 'input_radio_{n}_ghost_value', 'value' => $element_params['ghost_value'])); ?>
	<?php echo $HtmlHelper->input('chronofield[{n}][input_radio_{n}_input_title]', array('type' => 'hidden', 'id' => 'input_radio_{n}_input_title', 'value' => $element_params['input_title'])); ?>
	<?php echo $HtmlHelper->input('chronofield[{n}][input_radio_{n}_label_over]', array('type' => 'hidden', 'id' => 'input_radio_{n}_label_over', 'value' => $element_params['label_over'])); ?>
	<?php echo $HtmlHelper->input('chronofield[{n}][input_radio_{n}_hide_label]', array('type' => 'hidden', 'id' => 'input_radio_{n}_hide_label', 'value' => $element_params['hide_label'])); ?>
	<?php echo $HtmlHelper->input('chronofield[{n}][input_radio_{n}_multiline_start]', array('type' => 'hidden', 'id' => 'input_radio_{n}_multiline_start', 'value' => $element_params['multiline_start'])); ?>
	<?php echo $HtmlHelper->input('chronofield[{n}][input_radio_{n}_multiline_add]', array('type' => 'hidden', 'id' => 'input_radio_{n}_multiline_add', 'value' => $element_params['multiline_add'])); ?>
	<?php echo $HtmlHelper->input('chronofield[{n}][input_radio_{n}_enable_dynamic_data]', array('type' => 'hidden', 'id' => 'input_radio_{n}_enable_dynamic_data', 'value' => $element_params['enable_dynamic_data'])); ?>
	<?php echo $HtmlHelper->input('chronofield[{n}][input_radio_{n}_data_path]', array('type' => 'hidden', 'id' => 'input_radio_{n}_data_path', 'value' => $element_params['data_path'])); ?>
	<?php echo $HtmlHelper->input('chronofield[{n}][input_radio_{n}_value_key]', array('type' => 'hidden', 'id' => 'input_radio_{n}_value_key', 'value' => $element_params['value_key'])); ?>
	<?php echo $HtmlHelper->input('chronofield[{n}][input_radio_{n}_text_key]', array('type' => 'hidden', 'id' => 'input_radio_{n}_text_key', 'value' => $element_params['text_key'])); ?>
	
	
	<textarea name="chronofield[{n}][input_radio_{n}_options]" id="input_radio_{n}_options" style="display:none"><?php echo $options; ?></textarea>
    <textarea name="chronofield[{n}][input_radio_{n}_validations]" id="input_radio_{n}_validations" style="display:none"><?php echo $element_params['validations']; ?></textarea>
    <textarea name="chronofield[{n}][input_radio_{n}_instructions]" id="input_radio_{n}_instructions" style="display:none"><?php echo $element_params['instructions']; ?></textarea>
    <textarea name="chronofield[{n}][input_radio_{n}_tooltip]" id="input_radio_{n}_tooltip" style="display:none"><?php echo $element_params['tooltip']; ?></textarea>
    <input type="hidden" id="chronofield_id_{n}" name="chronofield_id" value="{n}" />
    <input type="hidden" name="chronofield[{n}][tag]" value="input" />
    <input type="hidden" name="chronofield[{n}][type]" value="radio" />
	<input type="hidden" id="container_id_{n}" name="chronofield[{n}][container_id]" value="<?php echo $element_params['container_id']; ?>" />
</div>
<div class="element_config" id="input_radio_element_config">
	<?php echo $PluginTabsHelper->Header(array('general' => 'General', 'other' => 'Other', 'validation' => 'Validation', 'ghost' => 'Ghost', 'dynamic_data' => 'Dynamic Data'), 'input_radio_element_config_{n}'); ?>
	<?php echo $PluginTabsHelper->tabStart('general'); ?>
		<?php echo $HtmlHelper->input('input_radio_{n}_label_text_config', array('type' => 'text', 'label' => 'Label Text', 'class' => 'small_input', 'value' => '')); ?>
		<?php echo $HtmlHelper->input('input_radio_{n}_input_name_config', array('type' => 'text', 'label' => 'Field Name', 'class' => 'small_input', 'value' => '', 'smalldesc' => 'No spaces or any special characters, but underscores _ and square brackets [] are allowed.')); ?>
		<?php echo $HtmlHelper->input('input_radio_{n}_input_title_config', array('type' => 'text', 'label' => 'Field title', 'class' => 'small_input', 'value' => '', 'smalldesc' => 'Change this to override the default JS validation error message.')); ?>
		<?php echo $HtmlHelper->input('input_radio_{n}_input_value_config', array('type' => 'text', 'label' => 'Field Default Value', 'class' => 'small_input', 'value' => '')); ?>
		<?php echo $HtmlHelper->input('input_radio_{n}_options_config', array('type' => 'textarea', 'label' => 'Options', 'rows' => 5, 'cols' => 50, 'operation' => "multi_option", 'operation_fieldtype' => "radio", 'smalldesc' => 'in value=text multi line format.')); ?>
		<?php echo $HtmlHelper->input('input_radio_{n}_input_id_config', array('type' => 'text', 'label' => 'Field ID', 'class' => 'small_input', 'value' => '')); ?>
		
	<?php echo $PluginTabsHelper->tabEnd(); ?>
	<?php echo $PluginTabsHelper->tabStart('other'); ?>
		<?php echo $HtmlHelper->input('input_radio_{n}_label_over_config', array('type' => 'checkbox', 'label' => 'Label Over', 'value' => '1', 'rule' => "bool")); ?>
		<?php echo $HtmlHelper->input('input_radio_{n}_hide_label_config', array('type' => 'checkbox', 'label' => 'Hide Label', 'value' => '1', 'rule' => "bool")); ?>
		<?php echo $HtmlHelper->input('input_radio_{n}_radios_over_config', array('type' => 'checkbox', 'label' => 'Radios Vertical', 'value' => '1', 'rule' => "bool")); ?>
		<?php echo $HtmlHelper->input('input_radio_{n}_instructions_config', array('type' => 'textarea', 'label' => 'Instructions for users', 'rows' => 5, 'cols' => 50)); ?>
		<?php echo $HtmlHelper->input('input_radio_{n}_tooltip_config', array('type' => 'textarea', 'label' => 'Tooltip', 'rows' => 5, 'cols' => 50)); ?>
		<?php echo $HtmlHelper->input('input_radio_{n}_multiline_start_config', array('type' => 'checkbox', 'label' => 'Start a Multi field row', 'value' => '1', 'rule' => "bool")); ?>
		<?php echo $HtmlHelper->input('input_radio_{n}_multiline_add_config', array('type' => 'checkbox', 'label' => 'Add to a Multi field row', 'value' => '1', 'rule' => "bool")); ?>
		
	<?php echo $PluginTabsHelper->tabEnd(); ?>
	<?php echo $PluginTabsHelper->tabStart('validation'); ?>
		<?php echo $HtmlHelper->input('input_radio_{n}_validations_config', array('type' => 'checkbox', 'label' => 'Required', 'class' => 'small_input', 'value' => 'required', 'rule' => "split", 'splitter' => ",")); ?>
	<?php echo $PluginTabsHelper->tabEnd(); ?>
	<?php echo $PluginTabsHelper->tabStart('ghost'); ?>
		<?php echo $HtmlHelper->input('input_radio_{n}_ghost_config', array('type' => 'checkbox', 'label' => 'Enable Ghost', 'class' => 'small_input', 'value' => '1', 'rule' => "bool", 'smalldesc' => 'The ghost is a hidden field with the same name to assure that a key with this field name always exists in the POST array.')); ?>
		<?php echo $HtmlHelper->input('input_radio_{n}_ghost_value_config', array('type' => 'text', 'label' => 'Ghost Value', 'value' => '', 'class' => 'medium_input', 'smalldesc' => 'Value here will appear if no choice has been made.')); ?>
	<?php echo $PluginTabsHelper->tabEnd(); ?>
	<?php echo $PluginTabsHelper->tabStart('dynamic_data'); ?>
		<?php echo $HtmlHelper->input('input_radio_{n}_enable_dynamic_data_config', array('type' => 'select', 'label' => 'Enable', 'options' => array(0 => 'No', 1 => 'Yes'), 'default' => '1', 'smalldesc' => 'Enable Dynamic options loading, the options should exist in the $form->data array.')); ?>
		<?php echo $HtmlHelper->input('input_radio_{n}_data_path_config', array('type' => 'text', 'label' => 'Data Path', 'value' => '', 'smalldesc' => 'The data path in the $form->data array, can be a MODEL ID, or a path using dots: MODEL1.MODEL2')); ?>
		<?php echo $HtmlHelper->input('input_radio_{n}_value_key_config', array('type' => 'text', 'label' => 'Value Key', 'smalldesc' => 'The key name under which each option value will be found, so for example, if you have a multi list of users data loaded, then you may enter here "id"')); ?>
		<?php echo $HtmlHelper->input('input_radio_{n}_text_key_config', array('type' => 'text', 'label' => 'Text Key', 'smalldesc' => 'The key name under which each option text will be found, so for example, if you have a multi list of users data loaded, then you may enter here "username"')); ?>
		
	<?php echo $PluginTabsHelper->tabEnd(); ?>
</div>