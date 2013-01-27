<div class="dragable" id="cfaction_joomla_user_activation">Joomla User Activation</div>
<!--start_element_code-->
<div class="element_code" id="cfaction_joomla_user_activation_element">
	<label class="action_label" style="display: block; float:none!important;">Joomla User Activation</label>
	<div id="cfactionevent_joomla_user_activation_{n}_success" class="form_event good_event">
		<label class="form_event_label">OnSuccess</label>
	</div>
	<div id="cfactionevent_joomla_user_activation_{n}_fail" class="form_event bad_event">
		<label class="form_event_label">OnFail</label>
	</div>
	
	<input type="hidden" name="chronoaction[{n}][action_joomla_user_activation_{n}_override_allow_user_registration]" id="action_joomla_user_activation_{n}_override_allow_user_registration" value="<?php echo $action_params['override_allow_user_registration']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_joomla_user_activation_{n}_allow_redirects]" id="action_joomla_user_activation_{n}_allow_redirects" value="<?php echo $action_params['allow_redirects']; ?>" />
	
	
	<input type="hidden" id="chronoaction_id_{n}" name="chronoaction_id[{n}]" value="{n}" />
	<input type="hidden" name="chronoaction[{n}][type]" value="joomla_user_activation" />
</div>
<!--end_element_code-->
<div class="element_config" id="cfaction_joomla_user_activation_element_config">
	<?php echo $PluginTabsHelper->Header(array('settings' => 'Settings', 'help' => 'Help'), 'joomla_user_activation_config_{n}'); ?>
	<?php echo $PluginTabsHelper->tabStart('settings'); ?>
		<?php echo $HtmlHelper->input('action_joomla_user_activation_{n}_override_allow_user_registration_config', array('type' => 'select', 'label' => 'Override the Joomla Allow user registration', 'label_over' => true, 'options' => array(0 => 'No', 1 => 'Yes'), 'smalldesc' => 'this should be enabled, its the only reason this action has been made!!')); ?>
		<?php echo $HtmlHelper->input('action_joomla_user_activation_{n}_allow_redirects_config', array('type' => 'select', 'label' => 'Allow default Redirects', 'options' => array(0 => 'No', 1 => 'Yes'), 'smalldesc' => 'By default, Joomla redirects the user some where depending on the success or the failure of the activation, should we redirect the user ?')); ?>
	<?php echo $PluginTabsHelper->tabEnd(); ?>
	<?php echo $PluginTabsHelper->tabStart('help'); ?>
		<p>
			<ul>
				<li>This action should be placed in a new form event, you should configure your Joomla registration action to use the link to this new event in the activation links.</li>
			</ul>
		</p>
	<?php echo $PluginTabsHelper->tabEnd(); ?>
</div>