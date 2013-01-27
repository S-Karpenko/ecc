<div class="dragable" id="cfaction_chrono_connectivity_task">Chrono Connectivity Task</div>
<!--start_element_code-->
<div class="element_code" id="cfaction_chrono_connectivity_task_element">
	<label class="action_label" style="display: block; float:none!important;">Chrono Connectivity Task</label>
	<div id="cfactionevent_chrono_connectivity_task_{n}_success" class="form_event good_event">
		<label class="form_event_label">OnSuccess</label>
	</div>
	<div id="cfactionevent_chrono_connectivity_task_{n}_fail" class="form_event bad_event">
		<label class="form_event_label">OnFail</label>
	</div>
	<input type="hidden" name="chronoaction[{n}][action_chrono_connectivity_task_{n}_connection_name]" id="action_chrono_connectivity_task_{n}_connection_name" value="<?php echo $action_params['connection_name']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_chrono_connectivity_task_{n}_task]" id="action_chrono_connectivity_task_{n}_task" value="<?php echo $action_params['task']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_chrono_connectivity_task_{n}_field_name]" id="action_chrono_connectivity_task_{n}_field_name" value="<?php echo $action_params['field_name']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_chrono_connectivity_task_{n}_data]" id="action_chrono_connectivity_task_{n}_data" value="<?php echo $action_params['data']; ?>" />
	
	<input type="hidden" name="chronoaction[{n}][action_chrono_connectivity_task_{n}_error_message]" id="action_chrono_connectivity_task_{n}_error_message" value="<?php echo $action_params['error_message']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_chrono_connectivity_task_{n}_show_returned_errors]" id="action_chrono_connectivity_task_{n}_show_returned_errors" value="<?php echo $action_params['show_returned_errors']; ?>" />
	<!--
	<input type="hidden" name="chronoaction[{n}][action_chrono_connectivity_task_{n}_purge_old_data]" id="action_chrono_connectivity_task_{n}_purge_old_data" value="<?php echo $action_params['purge_old_data']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_chrono_connectivity_task_{n}_purge_data_lifetime]" id="action_chrono_connectivity_task_{n}_purge_data_lifetime" value="<?php echo $action_params['purge_data_lifetime']; ?>" />
	-->
	<input type="hidden" id="chronoaction_id_{n}" name="chronoaction_id[{n}]" value="{n}" />
	<input type="hidden" name="chronoaction[{n}][type]" value="chrono_connectivity_task" />
</div>
<!--end_element_code-->
<div class="element_config" id="cfaction_chrono_connectivity_task_element_config">
	<?php echo $PluginTabsHelper->Header(array('settings' => 'Settings', 'help' => 'Help'), 'chrono_connectivity_task_config_{n}'); ?>
	<?php echo $PluginTabsHelper->tabStart('settings'); ?>
		<?php echo $HtmlHelper->input('action_chrono_connectivity_task_{n}_connection_name_config', array('type' => 'text', 'label' => "Connection Name", 'class' => 'medium_input', 'smalldesc' => 'Leave empty for auto integration, if no connection name is passed for this action then this will be used.')); ?>
		<?php echo $HtmlHelper->input('action_chrono_connectivity_task_{n}_task_config', array('type' => 'select', 'label' => "Task", 'options' => array('list_data' => 'List Data', 'edit_data' => 'Edit/Read Data', 'delete_data' => 'Delete Data', 'save_data' => 'Save Data', 'binary_data' => 'Binary Data'), 'smalldesc' => 'Leave empty for auto integration, if no task is passed for this action then this will be used.')); ?>
		<?php echo $HtmlHelper->input('action_chrono_connectivity_task_{n}_data_config', array('type' => 'text', 'label' => "Data field name", 'class' => 'medium_input', 'smalldesc' => 'Leave empty for auto integration, if no data is passed for this action then the value of this field will be used.')); ?>
		<?php echo $HtmlHelper->input('action_chrono_connectivity_task_{n}_field_name_config', array('type' => 'text', 'label' => "Field Name", 'class' => 'medium_input', 'smalldesc' => 'Leave empty for auto integration, if no field name is passed for this action then this will be used, useful for "binary" tasks only.')); ?>
		<?php echo $HtmlHelper->input('action_chrono_connectivity_task_{n}_error_message_config', array('type' => 'text', 'label' => "Error Message", 'class' => 'medium_input', 'smalldesc' => 'The error message which will be added to the errors/debug (see below) array in case some problem occurred.')); ?>
		<?php echo $HtmlHelper->input('action_chrono_connectivity_task_{n}_show_returned_errors_config', array('type' => 'select', 'label' => "Show errors", 'options' => array(0 => 'No (Add to debug only!)', 1 => 'Yes'), 'smalldesc' => 'Should the errors be added to the Errors array (visible to user) or to the Debug messages array ?')); ?>
		<?php //echo $HtmlHelper->input('action_chrono_connectivity_task_{n}_purge_old_data_config', array('type' => 'select', 'label' => "Purge Old Data", 'options' => array(0 => 'No', 1 => 'Yes'), 'smalldesc' => 'Should the action check and remove any old session data ?')); ?>
		<?php //echo $HtmlHelper->input('action_chrono_connectivity_task_{n}_purge_data_lifetime_config', array('type' => 'text', 'label' => "Session Data Lifetime", 'class' => 'medium_input', 'smalldesc' => 'The session data life time which will be used to decide if the data is old enough or not.')); ?>
		
	<?php echo $PluginTabsHelper->tabEnd(); ?>
	<?php echo $PluginTabsHelper->tabStart('help'); ?>
		<p>
			<ul>
				<li>This action will execute a Chrono Connectivity task, e.g: list data, edit, save, delete...etc, the task executed depends on your connection configuration.</li>
			</ul>
		</p>
	<?php echo $PluginTabsHelper->tabEnd(); ?>
</div>