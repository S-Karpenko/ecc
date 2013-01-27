<div class="dragable" id="cfaction_xls_export">XLS Export</div>
<!--start_element_code-->
<div class="element_code" id="cfaction_xls_export_element">
	<label class="action_label">XLS Export</label>
	<input type="hidden" name="chronoaction[{n}][action_xls_export_{n}_data_path]" id="action_xls_export_{n}_data_path" value="<?php echo $action_params['data_path']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_xls_export_{n}_list_fields]" id="action_xls_export_{n}_list_fields" value="<?php echo $action_params['list_fields']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_xls_export_{n}_list_headers]" id="action_xls_export_{n}_list_headers" value="<?php echo $action_params['list_headers']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_xls_export_{n}_add_bom]" id="action_xls_export_{n}_add_bom" value="<?php echo $action_params['add_bom']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_xls_export_{n}_file_name]" id="action_xls_export_{n}_file_name" value="<?php echo $action_params['file_name']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_xls_export_{n}_save_file]" id="action_xls_export_{n}_save_file" value="<?php echo $action_params['save_file']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_xls_export_{n}_post_file_name]" id="action_xls_export_{n}_post_file_name" value="<?php echo $action_params['post_file_name']; ?>" />
	
	<input type="hidden" id="chronoaction_id_{n}" name="chronoaction_id[{n}]" value="{n}" />
	<input type="hidden" name="chronoaction[{n}][type]" value="xls_export" />
</div>
<!--end_element_code-->
<div class="element_config" id="cfaction_xls_export_element_config">
	<?php echo $PluginTabsHelper->Header(array('settings' => 'Settings', 'help' => 'Help'), 'xls_export_config_{n}'); ?>
	<?php echo $PluginTabsHelper->tabStart('settings'); ?>
		<?php echo $HtmlHelper->input('action_xls_export_{n}_data_path_config', array('type' => 'text', 'label' => 'Data Path', 'class' => 'medium_input', 'smalldesc' => 'The path to the Data list in the $form->data array, e.g: MODEL_ID')); ?>
		<?php echo $HtmlHelper->input('action_xls_export_{n}_list_fields_config', array('type' => 'text', 'label' => 'Fields list', 'class' => 'big_input', 'smalldesc' => 'Comma separated list of fields to be included, no spaces, leave empty and all fields will be added.')); ?>
		<?php echo $HtmlHelper->input('action_xls_export_{n}_list_headers_config', array('type' => 'text', 'label' => 'Headers list', 'class' => 'big_input', 'smalldesc' => 'Comma separated list of headers labels to be included, no spaces, leave empty and data list keys will be used.')); ?>
		<?php echo $HtmlHelper->input('action_xls_export_{n}_add_bom_config', array('type' => 'select', 'label' => 'Add BOM', 'options' => array(0 => 'No', 1 => 'Yes'), 'smalldesc' => 'Add the UTF-8 BOM characters to the output ? this helps MS Excel detect the file as UTF-8 if you have any special characters inside.')); ?>
		<?php echo $HtmlHelper->input('action_xls_export_{n}_file_name_config', array('type' => 'text', 'label' => 'File Name', 'class' => 'medium_input', 'smalldesc' => 'The export file name.')); ?>
		<?php echo $HtmlHelper->input('action_xls_export_{n}_save_file_config', array('type' => 'select', 'label' => 'Save File', 'options' => array(0 => 'No', 1 => 'Yes'), 'smalldesc' => 'Should we save the file instead of sending it for download ?')); ?>
		<?php echo $HtmlHelper->input('action_xls_export_{n}_post_file_name_config', array('type' => 'text', 'label' => 'Post field Name', 'class' => 'medium_input', 'smalldesc' => 'The saved file field name which can be used in the email attachments.')); ?>
		
	<?php echo $PluginTabsHelper->tabEnd(); ?>
	<?php echo $PluginTabsHelper->tabStart('help'); ?>
		<p>
			<ul>
				<li>The action will generate XLS file contains HTML table which is importable by MS Excel (tested on Excel 2003,2007 and 2010).</li>
				<li>The records list array should be in your $form->data array, using a DB Multi record loader for example.</li>
			</ul>
		</p>
	<?php echo $PluginTabsHelper->tabEnd(); ?>
</div>