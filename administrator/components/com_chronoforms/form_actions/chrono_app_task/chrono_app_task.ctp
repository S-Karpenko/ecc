<div class="dragable" id="cfaction_chrono_app_task">Chrono App Task</div>
<!--start_element_code-->
<div class="element_code" id="cfaction_chrono_app_task_element">
	<label class="action_label" style="display: block; float:none!important;">Chrono App Task - <?php echo $action_params['action_label']; ?></label>
	<!--
	<textarea name="chronoaction[{n}][action_chrono_app_task_{n}_content1]" id="action_chrono_app_task_{n}_content1" style="display:none"><?php echo htmlspecialchars($action_params['content1']); ?></textarea>
    <input type="hidden" name="chronoaction[{n}][action_chrono_app_task_{n}_mode]" id="action_chrono_app_task_{n}_mode" value="<?php echo $action_params['mode']; ?>" />
	-->
	<input type="hidden" name="chronoaction[{n}][action_chrono_app_task_{n}_action_label]" id="action_chrono_app_task_{n}_action_label" value="<?php echo $action_params['action_label']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_chrono_app_task_{n}_option]" id="action_chrono_app_task_{n}_option" value="<?php echo $action_params['option']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_chrono_app_task_{n}_task]" id="action_chrono_app_task_{n}_task" value="<?php echo $action_params['task']; ?>" />
	
	<input type="hidden" id="chronoaction_id_{n}" name="chronoaction_id[{n}]" value="{n}" />
	<input type="hidden" name="chronoaction[{n}][type]" value="chrono_app_task" />
</div>
<!--end_element_code-->
<div class="element_config" id="cfaction_chrono_app_task_element_config">
	<?php echo $PluginTabsHelper->Header(array('settings' => 'Settings', 'help' => 'Help'), 'chrono_app_task_config_{n}'); ?>
	<?php echo $PluginTabsHelper->tabStart('settings'); ?>
		<?php //echo $HtmlHelper->input('action_chrono_app_task_{n}_mode_config', array('type' => 'select', 'label' => 'Mode', 'options' => array('controller' => 'Controller', 'view' => 'View'), 'smalldesc' => 'When should this code run ? during the controller code processing (early) or later when the ouput is viewed.')); ?>
		<?php echo $HtmlHelper->input('action_chrono_app_task_{n}_action_label_config', array('type' => 'text', 'label' => "Action Label", 'class' => 'medium_input', 'smalldesc' => 'Label for your action in the wizard.')); ?>
		<?php echo $HtmlHelper->input('action_chrono_app_task_{n}_option_config', array('type' => 'text', 'label' => "Option", 'class' => 'medium_input', 'smalldesc' => 'The Chrono option you need to run without the com_, leave empty and this will be auto configured by your Chrono App.')); ?>
		<?php echo $HtmlHelper->input('action_chrono_app_task_{n}_task_config', array('type' => 'text', 'label' => "Task", 'class' => 'medium_input', 'smalldesc' => 'The Chrono task you need to run, leave empty and this will be auto configured by your Chrono App.')); ?>
		<?php //echo $HtmlHelper->input('action_chrono_app_task_{n}_content1_config', array('type' => 'textarea', 'label' => "Code", 'rows' => 20, 'cols' => 70, 'smalldesc' => 'any code can be placed here, any PHP code should include the PHP tags.')); ?>
	<?php echo $PluginTabsHelper->tabEnd(); ?>
	<?php echo $PluginTabsHelper->tabStart('help'); ?>
		<p>
			<ul>
				<li>This action will execute a Chrono App task.</li>
			</ul>
		</p>
	<?php echo $PluginTabsHelper->tabEnd(); ?>
</div>