<div class="dragable" id="cfaction_custom_datepicker_moo">Custom Mootools Datepicker</div>
<!--start_element_code-->
<div class="element_code" id="cfaction_custom_datepicker_moo_element">
	<label class="action_label" style="display: block; float:none!important;">Custom Mootools Datepicker</label>
	<textarea name="chronoaction[{n}][action_custom_datepicker_moo_{n}_content1]" id="action_custom_datepicker_moo_{n}_content1" style="display:none"><?php echo $action_params['content1']; ?></textarea>
    <input type="hidden" name="chronoaction[{n}][action_custom_datepicker_moo_{n}_field_class]" id="action_custom_datepicker_moo_{n}_field_class" value="<?php echo $action_params['field_class']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_custom_datepicker_moo_{n}_pickerClass]" id="action_custom_datepicker_moo_{n}_pickerClass" value="<?php echo $action_params['pickerClass']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_custom_datepicker_moo_{n}_format]" id="action_custom_datepicker_moo_{n}_format" value="<?php echo $action_params['format']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_custom_datepicker_moo_{n}_allowEmpty]" id="action_custom_datepicker_moo_{n}_allowEmpty" value="<?php echo $action_params['allowEmpty']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_custom_datepicker_moo_{n}_timePicker]" id="action_custom_datepicker_moo_{n}_timePicker" value="<?php echo $action_params['timePicker']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_custom_datepicker_moo_{n}_pickOnly]" id="action_custom_datepicker_moo_{n}_pickOnly" value="<?php echo $action_params['pickOnly']; ?>" />
	
	<input type="hidden" id="chronoaction_id_{n}" name="chronoaction_id[{n}]" value="{n}" />
	<input type="hidden" name="chronoaction[{n}][type]" value="custom_datepicker_moo" />
</div>
<!--end_element_code-->
<div class="element_config" id="cfaction_custom_datepicker_moo_element_config">
	<?php echo $PluginTabsHelper->Header(array('settings' => 'Settings', 'help' => 'Help'), 'custom_datepicker_moo_config_{n}'); ?>
	<?php echo $PluginTabsHelper->tabStart('settings'); ?>
		<?php echo $HtmlHelper->input('action_custom_datepicker_moo_{n}_field_class_config', array('type' => 'text', 'label' => "Field Class", 'class' => 'medium_input', 'smalldesc' => "The class name assigned to the field(s) which will be used as date field.")); ?>
		<?php echo $HtmlHelper->input('action_custom_datepicker_moo_{n}_pickerClass_config', array('type' => 'select', 'label' => "Picker Style", 'options' => array('datepicker_dashboard' => 'DashBoard', 'datepicker_jqui' => 'JQUI', 'datepicker_vista' => 'Vista'), 'smalldesc' => "The class for the picker itself, will control how the calendar looks like.")); ?>
		<?php echo $HtmlHelper->input('action_custom_datepicker_moo_{n}_format_config', array('type' => 'text', 'label' => "Date format shown", 'class' => 'medium_input', 'smalldesc' => "The format shown inside the visible field for the user in the form.")); ?>
		<?php echo $HtmlHelper->input('action_custom_datepicker_moo_{n}_allowEmpty_config', array('type' => 'select', 'label' => 'Allow Empty ?', 'options' => array(0 => 'No', 1 => 'Yes'), 'smalldesc' => 'Allow the field to be empty, will load the field with empty value.')); ?>
		<?php echo $HtmlHelper->input('action_custom_datepicker_moo_{n}_timePicker_config', array('type' => 'select', 'label' => 'Load Time picker ?', 'options' => array(0 => 'No', 1 => 'Yes'), 'smalldesc' => 'Load the time picker after selecting a date ?')); ?>
		<?php echo $HtmlHelper->input('action_custom_datepicker_moo_{n}_pickOnly_config', array('type' => 'select', 'label' => 'Pick Only', 'options' => array(0 => 'Disabled', 'time' => 'Time', 'days' => 'Days', 'months' => 'Months', 'years' => 'Years'), 'smalldesc' => 'Should this date field pick some specific data only ?')); ?>
		
		<?php echo $HtmlHelper->input('action_custom_datepicker_moo_{n}_content1_config', array('type' => 'textarea', 'label' => "Extra options extension", 'rows' => 10, 'cols' => 50, 'smalldesc' => "Add extra picker options here, e.g:<br />days: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'], startView: 'decades'")); ?>
	<?php echo $PluginTabsHelper->tabEnd(); ?>
	<?php echo $PluginTabsHelper->tabStart('help'); ?>
		<p>
			<ul>
				<li>Add your fields class then start configuring your picker.</li>
				<li>All picker options and config are available here: <br />http://mootools.net/forge/p/mootools_datepicker</li>
			</ul>
		</p>
	<?php echo $PluginTabsHelper->tabEnd(); ?>
</div>