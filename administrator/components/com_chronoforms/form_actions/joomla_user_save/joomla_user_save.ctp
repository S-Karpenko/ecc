<div class="dragable" id="cfaction_joomla_user_save">Joomla User Create/Update</div>
<!--start_element_code-->
<div class="element_code" id="cfaction_joomla_user_save_element">
	<label class="action_label" style="display: block; float:none!important;">Joomla User Create/Update</label>
	<div id="cfactionevent_joomla_user_save_{n}_success" class="form_event good_event">
		<label class="form_event_label">OnSuccess</label>
	</div>
	<div id="cfactionevent_joomla_user_save_{n}_fail" class="form_event bad_event">
		<label class="form_event_label">OnFail</label>
	</div>
	<input type="hidden" name="chronoaction[{n}][action_joomla_user_save_{n}_name]" id="action_joomla_user_save_{n}_name" value="<?php echo $action_params['name']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_joomla_user_save_{n}_username]" id="action_joomla_user_save_{n}_username" value="<?php echo $action_params['username']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_joomla_user_save_{n}_email]" id="action_joomla_user_save_{n}_email" value="<?php echo $action_params['email']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_joomla_user_save_{n}_password]" id="action_joomla_user_save_{n}_password" value="<?php echo $action_params['password']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_joomla_user_save_{n}_password2]" id="action_joomla_user_save_{n}_password2" value="<?php echo $action_params['password2']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_joomla_user_save_{n}_old_password]" id="action_joomla_user_save_{n}_old_password" value="<?php echo $action_params['old_password']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_joomla_user_save_{n}_user_id]" id="action_joomla_user_save_{n}_user_id" value="<?php echo $action_params['user_id']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_joomla_user_save_{n}_new_usertype]" id="action_joomla_user_save_{n}_new_usertype" value="<?php echo $action_params['new_usertype']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_joomla_user_save_{n}_new_usertype_field]" id="action_joomla_user_save_{n}_new_usertype_field" value="<?php echo $action_params['new_usertype_field']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_joomla_user_save_{n}_function]" id="action_joomla_user_save_{n}_function" value="<?php echo $action_params['function']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_joomla_user_save_{n}_enable_old_password]" id="action_joomla_user_save_{n}_enable_old_password" value="<?php echo $action_params['enable_old_password']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_joomla_user_save_{n}_user_data_path]" id="action_joomla_user_save_{n}_user_data_path" value="<?php echo $action_params['user_data_path']; ?>" />
	
	<input type="hidden" id="chronoaction_id_{n}" name="chronoaction_id[{n}]" value="{n}" />
	<input type="hidden" name="chronoaction[{n}][type]" value="joomla_user_save" />
</div>
<!--end_element_code-->
<div class="element_config" id="cfaction_joomla_user_save_element_config">
	<?php echo $PluginTabsHelper->Header(array('fields' => 'Fields', 'settings' => 'Settings', 'help' => 'Help'), 'joomla_user_save_config_{n}'); ?>
	<?php echo $PluginTabsHelper->tabStart('fields'); ?>
		<?php echo $HtmlHelper->input('action_joomla_user_save_{n}_name_config', array('type' => 'text', 'label' => 'Name field name', 'class' => 'medium_input', 'smalldesc' => 'The name of the field which is going to hold the Name data')); ?>
		<?php echo $HtmlHelper->input('action_joomla_user_save_{n}_username_config', array('type' => 'text', 'label' => 'Username field name', 'class' => 'medium_input', 'smalldesc' => 'The name of the field which is going to hold the Username data')); ?>
		<?php echo $HtmlHelper->input('action_joomla_user_save_{n}_email_config', array('type' => 'text', 'label' => 'Email field name', 'class' => 'medium_input', 'smalldesc' => 'The name of the field which is going to hold the Email data')); ?>
		<?php echo $HtmlHelper->input('action_joomla_user_save_{n}_password_config', array('type' => 'text', 'label' => 'Password field name', 'class' => 'medium_input', 'smalldesc' => 'The name of the field which is going to hold the Password data')); ?>
		<?php echo $HtmlHelper->input('action_joomla_user_save_{n}_password2_config', array('type' => 'text', 'label' => 'Verify Password field name', 'class' => 'medium_input', 'smalldesc' => 'The name of the field which is going to hold the Verify Password data')); ?>
		<?php echo $HtmlHelper->input('action_joomla_user_save_{n}_old_password_config', array('type' => 'text', 'label' => 'Old Password field name', 'class' => 'medium_input', 'smalldesc' => 'The name of the field which is going to hold the Old Password data')); ?>
		<?php echo $HtmlHelper->input('action_joomla_user_save_{n}_user_id_config', array('type' => 'text', 'label' => 'User ID field name', 'class' => 'medium_input', 'smalldesc' => 'The name of the field which is going to hold the user id, useful when updating users data by an admin or a different user.')); ?>
		
	<?php echo $PluginTabsHelper->tabEnd(); ?>
	<?php echo $PluginTabsHelper->tabStart('settings'); ?>
		<?php //echo $HtmlHelper->input('action_joomla_user_save_{n}_override_allow_user_registration_config', array('type' => 'select', 'label' => 'Override the Joomla Allow user registration', 'label_over' => true, 'options' => array(0 => 'No', 1 => 'Yes'), 'smalldesc' => 'Its advised that you disable the Joomla allow user registration setting and enable this one so that users are forced to register here.')); ?>
		<?php
			$database = JFactory::getDBO();
			$query = "SELECT * FROM `#__usergroups`";
			$database->setQuery($query);
			$options = array();
			$groups = $database->loadObjectList();
			foreach($groups as $group){
				$options[$group->id] = $group->title;
			}
		?>
		<?php echo $HtmlHelper->input('action_joomla_user_save_{n}_new_usertype_config',  array('type' => 'select', 'label' => 'Usertype', 'options' => $options, 'size' => 6, 'multiple' => 'multiple', 'rule' => "split", 'splitter' => ",", 'smalldesc' => 'The new user type/group.')); ?>
		<?php echo $HtmlHelper->input('action_joomla_user_save_{n}_new_usertype_field_config', array('type' => 'text', 'label' => 'Usertype field name', 'class' => 'medium_input', 'smalldesc' => 'The name of the field which is going to hold the group id of the new created users, the User Type field should be empty.')); ?>
		<?php echo $HtmlHelper->input('action_joomla_user_save_{n}_function_config', array('type' => 'select', 'label' => 'Function', 'options' => array(0 => 'Create/Update', 1 => 'Update Self'), 'smalldesc' => '"Create/Update will create or update any loaded user record, the "id" field should be present in the data array, "Update Self" will update the details of the logged ins user.')); ?>
		<?php echo $HtmlHelper->input('action_joomla_user_save_{n}_enable_old_password_config', array('type' => 'select', 'label' => 'Enable Old Password', 'options' => array(0 => 'No', 1 => 'Yes'), 'smalldesc' => 'Enable Old password data check, useful for "Update Self" function only.')); ?>
		<?php echo $HtmlHelper->input('action_joomla_user_save_{n}_user_data_path_config', array('type' => 'text', 'label' => 'User Data Path', 'class' => 'medium_input', 'smalldesc' => 'The data path under which the saved user data will be present in the form->data array.')); ?>
		
	<?php echo $PluginTabsHelper->tabEnd(); ?>
	<?php echo $PluginTabsHelper->tabStart('help'); ?>
		<p>
			<ul>
				<li>Assign your form field's names to the required fields names under the "Fields" tab.</li>
				<li>Configure the settings under the "Settings" tab.</li>
				<li>This action can be used by Admins to create/update users OR by users to update their own accounts.</li>
				<li>In case of updating existing users (either by admins or end users), passing empty password fields will not overwrite the saved password.</li>
			</ul>
		</p>
	<?php echo $PluginTabsHelper->tabEnd(); ?>
</div>