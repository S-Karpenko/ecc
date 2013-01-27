<div class="dragable" id="input_multi_upload">Multi Upload</div>
<div class="element_code" id="input_multi_upload_element">
	<label class="text_label updatable_label" style="width:80%">Multi Upload - <?php echo $element_params['label_text']; ?></label>
	<!--<img src="" />-->
	<?php echo $HtmlHelper->input('chronofield[{n}][input_multi_upload_{n}_label_text]', array('type' => 'hidden', 'id' => 'input_multi_upload_{n}_label_text', 'value' => $element_params['label_text'])); ?>
	<?php echo $HtmlHelper->input('chronofield[{n}][input_multi_upload_{n}_input_name]', array('type' => 'hidden', 'id' => 'input_multi_upload_{n}_input_name', 'value' => $element_params['input_name'])); ?>
	<?php echo $HtmlHelper->input('chronofield[{n}][input_multi_upload_{n}_limit]', array('type' => 'hidden', 'id' => 'input_multi_upload_{n}_limit', 'value' => $element_params['limit'])); ?>
	
	<?php echo $HtmlHelper->input('chronofield[{n}][input_multi_upload_{n}_label_over]', array('type' => 'hidden', 'id' => 'input_multi_upload_{n}_label_over', 'value' => $element_params['label_over'])); ?>
	<?php echo $HtmlHelper->input('chronofield[{n}][input_multi_upload_{n}_hide_label]', array('type' => 'hidden', 'id' => 'input_multi_upload_{n}_hide_label', 'value' => $element_params['hide_label'])); ?>
	
	<?php echo $HtmlHelper->input('chronofield[{n}][input_multi_upload_{n}_data_path]', array('type' => 'hidden', 'id' => 'input_multi_upload_{n}_data_path', 'value' => $element_params['data_path'])); ?>
	<?php echo $HtmlHelper->input('chronofield[{n}][input_multi_upload_{n}_data_path_name]', array('type' => 'hidden', 'id' => 'input_multi_upload_{n}_data_path_name', 'value' => $element_params['data_path_name'])); ?>
	<?php echo $HtmlHelper->input('chronofield[{n}][input_multi_upload_{n}_data_path_original_name]', array('type' => 'hidden', 'id' => 'input_multi_upload_{n}_data_path_original_name', 'value' => $element_params['data_path_original_name'])); ?>
	<?php echo $HtmlHelper->input('chronofield[{n}][input_multi_upload_{n}_data_path_id]', array('type' => 'hidden', 'id' => 'input_multi_upload_{n}_data_path_id', 'value' => $element_params['data_path_id'])); ?>
	
	<textarea name="chronofield[{n}][input_multi_upload_{n}_validations]" id="input_multi_upload_{n}_validations" style="display:none"><?php echo $element_params['validations']; ?></textarea>
    <textarea name="chronofield[{n}][input_multi_upload_{n}_instructions]" id="input_multi_upload_{n}_instructions" style="display:none"><?php echo $element_params['instructions']; ?></textarea>
    <textarea name="chronofield[{n}][input_multi_upload_{n}_tooltip]" id="input_multi_upload_{n}_tooltip" style="display:none"><?php echo $element_params['tooltip']; ?></textarea>
    <input type="hidden" id="chronofield_id_{n}" name="chronofield_id" value="{n}" />
    <input type="hidden" name="chronofield[{n}][tag]" value="input" />
    <input type="hidden" name="chronofield[{n}][type]" value="widget" />
	<input type="hidden" name="chronofield[{n}][widget]" value="multi_upload" />
	<input type="hidden" id="container_id_{n}" name="chronofield[{n}][container_id]" value="<?php echo $element_params['container_id']; ?>" />
</div>
<div class="element_config" id="input_multi_upload_element_config">
	<?php echo $PluginTabsHelper->Header(array('general' => 'General', 'republish' => 'Data Republish', 'other' => 'Other', 'validation' => 'Validation'), 'input_multi_upload_element_config_{n}'); ?>
	<?php echo $PluginTabsHelper->tabStart('general'); ?>
		<?php echo $HtmlHelper->input('input_multi_upload_{n}_label_text_config', array('type' => 'text', 'label' => 'Label Text', 'class' => 'small_input', 'value' => '')); ?>
		<?php echo $HtmlHelper->input('input_multi_upload_{n}_input_name_config', array('type' => 'text', 'label' => 'Field Name', 'class' => 'small_input', 'value' => '', 'smalldesc' => "Don't forget to add 2 square brackets [] after the field name to post array of files, e.g: attachments[]")); ?>
		<?php echo $HtmlHelper->input('input_multi_upload_{n}_limit_config', array('type' => 'text', 'label' => 'Limit', 'class' => 'small_input', 'value' => '')); ?>
		
	<?php echo $PluginTabsHelper->tabEnd(); ?>
	<?php echo $PluginTabsHelper->tabStart('republish'); ?>
		<?php echo $HtmlHelper->input('input_multi_upload_{n}_data_path_config', array('type' => 'text', 'label' => "Files Data Path", 'class' => 'medium_input', 'smalldesc' => 'The data array key under which the files data will be available in the form->data array.')); ?>
		<?php echo $HtmlHelper->input('input_multi_upload_{n}_data_path_name_config', array('type' => 'text', 'label' => "File Name", 'smalldesc' => 'The array key under which the file name is stored, leave empty if the names are under the data path directly.')); ?>
		<?php echo $HtmlHelper->input('input_multi_upload_{n}_data_path_original_name_config', array('type' => 'text', 'label' => "File Original Name", 'smalldesc' => 'The array key under which the file original name is stored.')); ?>
		<?php echo $HtmlHelper->input('input_multi_upload_{n}_data_path_id_config', array('type' => 'text', 'label' => "File ID", 'smalldesc' => 'The array key under which the file id is stored.')); ?>
		<?php //echo $HtmlHelper->input('input_multi_upload_{n}_target_path_config', array('type' => 'text', 'label' => "Target Data Path", 'smalldesc' => 'The data array key under which the files data will be stored after submission.')); ?>
		
	<?php echo $PluginTabsHelper->tabEnd(); ?>
	<?php echo $PluginTabsHelper->tabStart('other'); ?>
		<?php echo $HtmlHelper->input('input_multi_upload_{n}_label_over_config', array('type' => 'checkbox', 'label' => 'Label Over', 'value' => '1', 'rule' => "bool")); ?>
		<?php echo $HtmlHelper->input('input_multi_upload_{n}_hide_label_config', array('type' => 'checkbox', 'label' => 'Hide Label', 'value' => '1', 'rule' => "bool")); ?>
		<?php echo $HtmlHelper->input('input_multi_upload_{n}_instructions_config', array('type' => 'textarea', 'label' => 'Instructions for users', 'rows' => 5, 'cols' => 50)); ?>
		<?php echo $HtmlHelper->input('input_multi_upload_{n}_tooltip_config', array('type' => 'textarea', 'label' => 'Tooltip', 'rows' => 5, 'cols' => 50)); ?>
	<?php echo $PluginTabsHelper->tabEnd(); ?>
	<?php echo $PluginTabsHelper->tabStart('validation'); ?>
		<?php echo $HtmlHelper->input('input_multi_upload_{n}_validations_config', array('type' => 'checkbox', 'label' => 'Required', 'class' => 'small_input', 'value' => 'required', 'rule' => "split", 'splitter' => ",")); ?>
	<?php echo $PluginTabsHelper->tabEnd(); ?>
</div>