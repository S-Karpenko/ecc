<div class="dragable" id="cfaction_iframe_request">iFrame Request</div>
<!--start_element_code-->
<div class="element_code" id="cfaction_iframe_request_element">
	<label class="action_label" style="display: block; float:none!important;">iFrame Request</label>
	<input type="hidden" name="chronoaction[{n}][action_iframe_request_{n}_enabled]" id="action_iframe_request_{n}_enabled" value="<?php echo $action_params['enabled']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_iframe_request_{n}_response_element_id]" id="action_iframe_request_{n}_response_element_id" value="<?php echo $action_params['response_element_id']; ?>" />
	
	<input type="hidden" name="chronoaction[{n}][action_iframe_request_{n}_onrequest_fn]" id="action_iframe_request_{n}_onrequest_fn" value="<?php echo $action_params['onrequest_fn']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_iframe_request_{n}_onsuccess_fn]" id="action_iframe_request_{n}_onsuccess_fn" value="<?php echo $action_params['onsuccess_fn']; ?>" />
		
	<input type="hidden" id="chronoaction_id_{n}" name="chronoaction_id[{n}]" value="{n}" />
	<input type="hidden" name="chronoaction[{n}][type]" value="iframe_request" />
</div>
<!--end_element_code-->
<div class="element_config" id="cfaction_iframe_request_element_config">
	<?php echo $PluginTabsHelper->Header(array('settings' => 'Settings', 'advanced' => 'Advanced', 'help' => 'Help'), 'iframe_request_config_{n}'); ?>
	<?php echo $PluginTabsHelper->tabStart('settings'); ?>
		<?php echo $HtmlHelper->input('action_iframe_request_{n}_enabled_config', array('type' => 'select', 'label' => 'Enabel iFrame Request', 'options' => array(0 => 'No', 1 => 'Yes'), 'smalldesc' => 'Enable the form to be submitted using a hidden iFrame.')); ?>
		<?php echo $HtmlHelper->input('action_iframe_request_{n}_response_element_id_config', array('type' => 'text', 'label' => "Response Element ID", 'class' => 'medium_input', 'smalldesc' => "The id of the element which will be loaded with the response string when the request is completed with success.")); ?>
		
	<?php echo $PluginTabsHelper->tabEnd(); ?>
	
	<?php echo $PluginTabsHelper->tabStart('advanced'); ?>
		<?php echo $HtmlHelper->input('action_iframe_request_{n}_onrequest_fn_config', array('type' => 'text', 'label' => "On Request Function name", 'class' => 'medium_input', 'smalldesc' => "A JS function name to run when the request is sent, please add the 2 brackets at the end, e.g: myfn()")); ?>
		<?php echo $HtmlHelper->input('action_iframe_request_{n}_onsuccess_fn_config', array('type' => 'text', 'label' => "On Success Function name", 'class' => 'medium_input', 'smalldesc' => "A JS function name to run when the request is completed successfully, please add the 2 brackets at the end, e.g: myfn()")); ?>
		
	<?php echo $PluginTabsHelper->tabEnd(); ?>
	<?php echo $PluginTabsHelper->tabStart('help'); ?>
		<p>
			<ul>
				<li>If you want the user to submit the form without leaving the current page then this action should do what you need.</li>
				<li>You may wrap the form into a div and use the div's id as the "Response Element ID" to display the submission result.</li>
			</ul>
		</p>
	<?php echo $PluginTabsHelper->tabEnd(); ?>
</div>