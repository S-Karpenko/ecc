<div class="dragable" id="cfaction_load_fields_hash">Load Fields Hash</div>
<!--start_element_code-->
<div class="element_code" id="cfaction_load_fields_hash_element">
	<label class="action_label">Load Fields Hash</label>
	<input type="hidden" name="chronoaction[{n}][action_load_fields_hash_{n}_fields]" id="action_load_fields_hash_{n}_fields" value="<?php echo $action_params['fields']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_load_fields_hash_{n}_hash_field_name]" id="action_load_fields_hash_{n}_hash_field_name" value="<?php echo $action_params['hash_field_name']; ?>" />
	
	<input type="hidden" id="chronoaction_id_{n}" name="chronoaction_id[{n}]" value="{n}" />
	<input type="hidden" name="chronoaction[{n}][type]" value="load_fields_hash" />
</div>
<!--end_element_code-->
<div class="element_config" id="cfaction_load_fields_hash_element_config">
	<?php echo $PluginTabsHelper->Header(array('settings' => 'Settings', 'help' => 'Help'), 'load_fields_hash_config_{n}'); ?>
	<?php echo $PluginTabsHelper->tabStart('settings'); ?>
		<?php echo $HtmlHelper->input('action_load_fields_hash_{n}_fields_config', array('type' => 'text', 'label' => 'Fields list', 'class' => 'big_input', 'smalldesc' => 'Comma separated list of fields, no spaces, you can use dots to get sub arrays values.')); ?>
		<?php echo $HtmlHelper->input('action_load_fields_hash_{n}_hash_field_name_config', array('type' => 'text', 'label' => 'Hash field name', 'smalldesc' => '')); ?>
		
	<?php echo $PluginTabsHelper->tabEnd(); ?>
	<?php echo $PluginTabsHelper->tabStart('help'); ?>
		<p>
			<ul>
				<li>This action will generate a security hash of some fields values, you can check the hash back in the "on submit" event using the "check fields hash" action.</li>
				<li>The goal here is to make sure that these fields values have not been changed by the end user after they have been sent by the server.</li>
				<li>Insert a list of fields names to be hashed.</li>
				<li>The order of this action in the load routine, should only be when the fields data exist in the $form->data array.</li>
				<li>Only fields values in the $form->data array will be used, default fields values will NOT be checked and WILL generate a fail error if checked.</li>
			</ul>
		</p>
	<?php echo $PluginTabsHelper->tabEnd(); ?>
</div>