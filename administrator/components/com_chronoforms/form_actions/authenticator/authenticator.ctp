<div class="dragable" id="cfaction_authenticator">Authenticator</div>
<!--start_element_code-->
<div class="element_code" id="cfaction_authenticator_element">
	<label class="action_label" style="display: block; float:none!important;">Authenticator</label>
	<div id="cfactionevent_authenticator_{n}_allowed" class="form_event good_event">
		<label class="form_event_label">Allowed</label>
	</div>
	<div id="cfactionevent_authenticator_{n}_denied" class="form_event bad_event">
		<label class="form_event_label">Denied</label>
	</div>
	
	<input type="hidden" name="chronoaction[{n}][action_authenticator_{n}_groups]" id="action_authenticator_{n}_groups" value="<?php echo $action_params['groups']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_authenticator_{n}_guests]" id="action_authenticator_{n}_guests" value="<?php echo $action_params['guests']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_authenticator_{n}_inheritable]" id="action_authenticator_{n}_inheritable" value="<?php echo $action_params['inheritable']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_authenticator_{n}_denied_groups]" id="action_authenticator_{n}_denied_groups" value="<?php echo $action_params['denied_groups']; ?>" />
	
	<input type="hidden" id="chronoaction_id_{n}" name="chronoaction_id[{n}]" value="{n}" />
	<input type="hidden" name="chronoaction[{n}][type]" value="authenticator" />
</div>
<!--end_element_code-->
<div class="element_config" id="cfaction_authenticator_element_config">
	<?php echo $PluginTabsHelper->Header(array('settings' => 'Settings', 'help' => 'Help'), 'authenticator_config_{n}'); ?>
	<?php echo $PluginTabsHelper->tabStart('settings'); ?>
		
		<?php
			$jversion = new JVersion();
			if($jversion->RELEASE > 1.5){
				$options = $action_params['groups_list'];
			}else{
				$options = $action_params['groups_list'];
				//$options = array(18 => 'Registered users', 19 => 'Authors', 20 => 'Editors', 21 => 'Publishers', 23 => 'Managers', 24 => 'Administrators', 25 => 'Super Administrators');
			}
		?>
		<?php echo $HtmlHelper->input('action_authenticator_{n}_groups_config',  array('type' => 'select', 'label' => 'Allowed groups', 'options' => $options, 'size' => 11, 'class' => 'medium_input', 'multiple' => 'multiple', 'rule' => "split", 'splitter' => ",", 'smalldesc' => 'Select the groups authorized.')); ?>
		
		<?php echo $HtmlHelper->input('action_authenticator_{n}_guests_config', array('type' => 'select', 'label' => 'Allow guests', 'options' => array(0 => 'No', 1 => 'Yes'), 'class' => 'medium_input', 'smalldesc' => "Guests are non logged in users, choose wheather you want to allow them access or not.")); ?>
		<?php echo $HtmlHelper->input('action_authenticator_{n}_inheritable_config', array('type' => 'select', 'label' => 'Inheritable Permissions', 'options' => array(0 => 'No', 1 => 'Yes'), 'class' => 'medium_input', 'smalldesc' => "Enable this if you want sub groups to inherit the access permissions of their parents.")); ?>
		<?php echo $HtmlHelper->input('action_authenticator_{n}_denied_groups_config',  array('type' => 'select', 'label' => 'Denied groups', 'options' => $options, 'size' => 11, 'class' => 'medium_input', 'multiple' => 'multiple', 'rule' => "split", 'splitter' => ",", 'smalldesc' => 'Select the groups denied, this is useful only if you have the "Inheritable" option set to "Yes".')); ?>
		<?php //echo $HtmlHelper->input('action_authenticator_{n}_content1_config', array('type' => 'textarea', 'label' => "Code", 'rows' => 20, 'cols' => 70, 'smalldesc' => 'any code can be placed here, any PHP code should include the PHP tags.')); ?>
	<?php echo $PluginTabsHelper->tabEnd(); ?>
	<?php echo $PluginTabsHelper->tabStart('help'); ?>
		<p>
			<ul>
				<li>Select which user groups should be allowed access.</li>
				<li>Permissions are not inheritable by default, this means that if you need to allow both the "Public" group and the "Administrators" group then you need to select BOTH of them.</li>
				<li>If the user's group is Allowed AND Denied then the authentication will fail leading to a denied.</li>
				<li>Insert next form events in the "Allowed" event or insert "Show stopper" in the "Denied" event to halt the form.</li>
			</ul>
		</p>
	<?php echo $PluginTabsHelper->tabEnd(); ?>
</div>