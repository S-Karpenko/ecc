<div class="dragable" id="cfaction_style_form">Style Form</div>
<!--start_element_code-->
<div class="element_code" id="cfaction_style_form_element">
	<label class="action_label" style="display: block; float:none!important;">Style Form</label>
	<!--
	<textarea name="chronoaction[{n}][action_style_form_{n}_content1]" id="action_style_form_{n}_content1" style="display:none"><?php echo $action_params['content1']; ?></textarea>
    -->
	<input type="hidden" name="chronoaction[{n}][action_style_form_{n}_form_width]" id="action_style_form_{n}_form_width" value="<?php echo $action_params['form_width']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_style_form_{n}_background_color]" id="action_style_form_{n}_background_color" value="<?php echo $action_params['background_color']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_style_form_{n}_label_width]" id="action_style_form_{n}_label_width" value="<?php echo $action_params['label_width']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_style_form_{n}_label_font_size]" id="action_style_form_{n}_label_font_size" value="<?php echo $action_params['label_font_size']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_style_form_{n}_label_font_weight]" id="action_style_form_{n}_label_font_weight" value="<?php echo $action_params['label_font_weight']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_style_form_{n}_label_font_family]" id="action_style_form_{n}_label_font_family" value="<?php echo $action_params['label_font_family']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_style_form_{n}_sub_label_width]" id="action_style_form_{n}_sub_label_width" value="<?php echo $action_params['sub_label_width']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_style_form_{n}_sub_label_font_size]" id="action_style_form_{n}_sub_label_font_size" value="<?php echo $action_params['sub_label_font_size']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_style_form_{n}_sub_label_font_weight]" id="action_style_form_{n}_sub_label_font_weight" value="<?php echo $action_params['sub_label_font_weight']; ?>" />
	<input type="hidden" name="chronoaction[{n}][action_style_form_{n}_sub_label_font_family]" id="action_style_form_{n}_sub_label_font_family" value="<?php echo $action_params['sub_label_font_family']; ?>" />
	
	<input type="hidden" id="chronoaction_id_{n}" name="chronoaction_id[{n}]" value="{n}" />
	<input type="hidden" name="chronoaction[{n}][type]" value="style_form" />
</div>
<!--end_element_code-->
<div class="element_config" id="cfaction_style_form_element_config">
	<?php echo $PluginTabsHelper->Header(array('general' => 'General', 'subs' => 'Sub Elements', 'help' => 'Help'), 'style_form_config_{n}'); ?>
	<?php echo $PluginTabsHelper->tabStart('general'); ?>
		<?php echo $HtmlHelper->input('action_style_form_{n}_form_width_config', array('type' => 'text', 'label' => "Form Width", 'smalldesc' => "The desired form width in px or % or auto.")); ?>
		<?php echo $HtmlHelper->input('action_style_form_{n}_background_color_config', array('type' => 'text', 'label' => "Background Color", 'smalldesc' => "The desired form background color.")); ?>
		<?php echo $HtmlHelper->input('action_style_form_{n}_label_width_config', array('type' => 'text', 'label' => "Label Width", 'smalldesc' => "The desired main elements labels width.")); ?>
		<?php echo $HtmlHelper->input('action_style_form_{n}_label_font_size_config', array('type' => 'text', 'label' => "Label Font Size", 'smalldesc' => "The labels font size.")); ?>
		<?php echo $HtmlHelper->input('action_style_form_{n}_label_font_weight_config', array('type' => 'text', 'label' => "Label Font Weight", 'smalldesc' => "The labels font weight.")); ?>
		<?php echo $HtmlHelper->input('action_style_form_{n}_label_font_family_config', array('type' => 'text', 'label' => "Label Font Family", 'class' => 'medium_input', 'smalldesc' => "The labels font family.")); ?>
		
	<?php echo $PluginTabsHelper->tabEnd(); ?>
	<?php echo $PluginTabsHelper->tabStart('subs'); ?>
		<?php echo $HtmlHelper->input('action_style_form_{n}_sub_label_width_config', array('type' => 'text', 'label' => "Label Width", 'smalldesc' => "The label width of sub elements, e.g: radios, checkboxes...")); ?>
		<?php echo $HtmlHelper->input('action_style_form_{n}_sub_label_font_size_config', array('type' => 'text', 'label' => "Label Font Size", 'smalldesc' => "The label font size of sub elements, e.g: radios, checkboxes...")); ?>
		<?php echo $HtmlHelper->input('action_style_form_{n}_sub_label_font_weight_config', array('type' => 'text', 'label' => "Label Font Weight", 'smalldesc' => "The label font weight of sub elements, e.g: radios, checkboxes...")); ?>
		<?php echo $HtmlHelper->input('action_style_form_{n}_sub_label_font_family_config', array('type' => 'text', 'label' => "Label Font Family", 'class' => 'medium_input', 'smalldesc' => "The label font family of sub elements, e.g: radios, checkboxes...")); ?>
		
	<?php echo $PluginTabsHelper->tabEnd(); ?>
	<?php echo $PluginTabsHelper->tabStart('help'); ?>
		<p>
			<ul>
				<li>The action will convert the configuration to CSS code and use it to style your form.</li>
			</ul>
		</p>
	<?php echo $PluginTabsHelper->tabEnd(); ?>
</div>