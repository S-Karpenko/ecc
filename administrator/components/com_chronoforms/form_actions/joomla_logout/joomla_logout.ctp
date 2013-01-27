<div class="dragable" id="cfaction_joomla_logout">Joomla Logout</div>
<!--start_element_code-->
<div class="element_code" id="cfaction_joomla_logout_element">
	<label class="action_label" style="display: block; float:none!important;">Joomla Logout</label>
	<div id="cfactionevent_joomla_logout_{n}_success" class="form_event good_event">
		<label class="form_event_label">OnSuccess</label>
	</div>
	<div id="cfactionevent_joomla_logout_{n}_fail" class="form_event bad_event">
		<label class="form_event_label">OnFail</label>
	</div>
	<input type="hidden" name="chronoaction[{n}][action_joomla_logout_{n}_user_id]" id="action_joomla_logout_{n}_user_id" value="<?php echo $action_params['user_id']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_joomla_logout_{n}_redirect_url]" id="action_joomla_logout_{n}_redirect_url" value="<?php echo $action_params['redirect_url']; ?>" />
	
	<input type="hidden" id="chronoaction_id_{n}" name="chronoaction_id[{n}]" value="{n}" />
	<input type="hidden" name="chronoaction[{n}][type]" value="joomla_logout" />
</div>
<!--end_element_code-->
<div class="element_config" id="cfaction_joomla_logout_element_config">
	<?php echo $PluginTabsHelper->Header(array('settings' => 'Settings', 'help' => 'Help'), 'joomla_logout_config_{n}'); ?>
	<?php echo $PluginTabsHelper->tabStart('settings'); ?>
		<?php echo $HtmlHelper->input('action_joomla_logout_{n}_redirect_url_config', array('type' => 'text', 'label' => 'Redirect URL', 'class' => 'big_input', 'smalldesc' => 'The URL to redirect to after logout.')); ?>
		<?php echo $HtmlHelper->input('action_joomla_logout_{n}_user_id_config', array('type' => 'text', 'label' => 'User ID', 'class' => 'medium_input', 'smalldesc' => 'The id of the user to logout, leave empty and it will logout the logged in user.')); ?>
	<?php echo $PluginTabsHelper->tabEnd(); ?>
	<?php echo $PluginTabsHelper->tabStart('help'); ?>
		<p>
			<ul>
				<li>Configure the settings under the "Settings" tab.</li>
			</ul>
		</p>
	<?php echo $PluginTabsHelper->tabEnd(); ?>
</div>