<div class="dragable" id="cfaction_widget_multi_upload">Multi Upload</div>
<!--start_element_code-->
<div class="element_code" id="cfaction_widget_multi_upload_element">
	<label class="action_label">Multi Upload</label>
	<div id="cfactionevent_widget_multi_upload_{n}_success" class="form_event good_event">
		<label class="form_event_label">OnSuccess</label>
	</div>
	<div id="cfactionevent_widget_multi_upload_{n}_fail" class="form_event bad_event">
		<label class="form_event_label">OnFail</label>
	</div>
	<input type="hidden" name="chronoaction[{n}][action_widget_multi_upload_{n}_fields]" id="action_widget_multi_upload_{n}_fields" value="<?php echo $action_params['fields']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_widget_multi_upload_{n}_limit]" id="action_widget_multi_upload_{n}_limit" value="<?php echo $action_params['limit']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_widget_multi_upload_{n}_max_size]" id="action_widget_multi_upload_{n}_max_size" value="<?php echo $action_params['max_size']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_widget_multi_upload_{n}_upload_path]" id="action_widget_multi_upload_{n}_upload_path" value="<?php echo $action_params['upload_path']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_widget_multi_upload_{n}_target_path]" id="action_widget_multi_upload_{n}_target_path" value="<?php echo $action_params['target_path']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_widget_multi_upload_{n}_target_path_name]" id="action_widget_multi_upload_{n}_target_path_name" value="<?php echo $action_params['target_path_name']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_widget_multi_upload_{n}_target_path_original_name]" id="action_widget_multi_upload_{n}_target_path_original_name" value="<?php echo $action_params['target_path_original_name']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_widget_multi_upload_{n}_target_path_id]" id="action_widget_multi_upload_{n}_target_path_id" value="<?php echo $action_params['target_path_id']; ?>" />
	
	
	<input type="hidden" id="chronoaction_id_{n}" name="chronoaction_id[{n}]" value="{n}" />
	<input type="hidden" name="chronoaction[{n}][type]" value="widget_multi_upload" />
</div>
<!--end_element_code-->
<div class="element_config" id="cfaction_widget_multi_upload_element_config">
	<?php echo $PluginTabsHelper->Header(array('settings' => 'Settings', 'help' => 'Help'), 'widget_multi_upload_config_{n}'); ?>
	<?php echo $PluginTabsHelper->tabStart('settings'); ?>
		<?php echo $HtmlHelper->input('action_widget_multi_upload_{n}_fields_config', array('type' => 'text', 'label' => "Fields Configuration", 'class' => 'medium_input', 'smalldesc' => 'The fields configuration in this format:field_name:extensions,e.g:field1:zip-pdf')); ?>
		<?php echo $HtmlHelper->input('action_widget_multi_upload_{n}_limit_config', array('type' => 'text', 'label' => "Maximum Limit", 'smalldesc' => 'Maximum number of files accepted through the widget.')); ?>
		<?php echo $HtmlHelper->input('action_widget_multi_upload_{n}_max_size_config', array('type' => 'text', 'label' => "Max Size", 'smalldesc' => 'Maximum accepted file size in KB.')); ?>
		<?php echo $HtmlHelper->input('action_widget_multi_upload_{n}_upload_path_config', array('type' => 'text', 'label' => "Upload Path", 'class' => 'big_input', 'smalldesc' => 'Absolute path for files uploaded, leave empty to use the default uploads folder.')); ?>
		<?php echo $HtmlHelper->input('action_widget_multi_upload_{n}_target_path_config', array('type' => 'text', 'label' => "Target Data Path", 'smalldesc' => 'The data array key under which the files data will be stored after submission.')); ?>
		<?php echo $HtmlHelper->input('action_widget_multi_upload_{n}_target_path_name_config', array('type' => 'text', 'label' => "File Name", 'smalldesc' => 'The data array key under which the file name will be stored after submission.')); ?>
		<?php echo $HtmlHelper->input('action_widget_multi_upload_{n}_target_path_original_name_config', array('type' => 'text', 'label' => "Original File Name", 'smalldesc' => 'The data array key under which the file original name will be stored after submission.')); ?>
		<?php echo $HtmlHelper->input('action_widget_multi_upload_{n}_target_path_id_config', array('type' => 'text', 'label' => "File ID", 'smalldesc' => 'The data array key under which the file id will be stored after submission.')); ?>
		
	<?php echo $PluginTabsHelper->tabEnd(); ?>
	
	<?php echo $PluginTabsHelper->tabStart('help'); ?>
		<p>
			<ul>
				<li>Don't forget to set your form method to "File" under the form "Edit" screen.</li>
			</ul>
		</p>
	<?php echo $PluginTabsHelper->tabEnd(); ?>
</div>