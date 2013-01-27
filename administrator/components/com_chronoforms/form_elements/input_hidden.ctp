<div class="dragable" id="input_hidden">Hidden Box</div>
<div class="element_code" id="input_hidden_element">
	<label for="<?php echo $element_params['input_id']; ?>" id="input_hidden_{n}_label" class="hidden_label updatable_label" style="width: 200px !important;"><?php echo 'Hidden field - '.$element_params['label_text']; ?></label>
	<input type="hidden" name="hidden_{n}_input_name" id="hidden_{n}_input_id" value="<?php echo $element_params['input_value']; ?>" />
	<?php echo $HtmlHelper->input('chronofield[{n}][input_hidden_{n}_input_id]', array('type' => 'hidden', 'id' => 'input_hidden_{n}_input_id', 'value' => $element_params['input_id'])); ?>
	<?php echo $HtmlHelper->input('chronofield[{n}][input_hidden_{n}_input_name]', array('type' => 'hidden', 'id' => 'input_hidden_{n}_input_name', 'value' => $element_params['input_name'])); ?>
	<?php echo $HtmlHelper->input('chronofield[{n}][input_hidden_{n}_input_value]', array('type' => 'hidden', 'id' => 'input_hidden_{n}_input_value', 'value' => $element_params['input_value'])); ?>
	<?php echo $HtmlHelper->input('chronofield[{n}][input_hidden_{n}_label_text]', array('type' => 'hidden', 'id' => 'input_hidden_{n}_label_text', 'value' => $element_params['label_text'])); ?>
	
	<input type="hidden" id="chronofield_id_{n}" name="chronofield_id" value="{n}" />
    <input type="hidden" name="chronofield[{n}][tag]" value="input" />
    <input type="hidden" name="chronofield[{n}][type]" value="hidden" />
	<input type="hidden" id="container_id_{n}" name="chronofield[{n}][container_id]" value="<?php echo $element_params['container_id']; ?>" />
</div>
<div class="element_config" id="input_hidden_element_config">
	<?php echo $HtmlHelper->input('input_hidden_{n}_label_text_config', array('type' => 'text', 'label' => 'Label Text', 'class' => 'medium_input', 'smalldesc' => 'Some text to identify your field in the wizard.')); ?>
	<?php echo $HtmlHelper->input('input_hidden_{n}_input_name_config', array('type' => 'text', 'label' => 'Field Name', 'class' => 'small_input', 'value' => '', 'smalldesc' => 'No spaces or any special characters, but underscores _ and square brackets [] are allowed.')); ?>
	<?php echo $HtmlHelper->input('input_hidden_{n}_input_value_config', array('type' => 'text', 'label' => 'Field default value', 'class' => 'small_input', 'value' => '')); ?>
	<?php echo $HtmlHelper->input('input_hidden_{n}_input_id_config', array('type' => 'text', 'label' => 'Field id', 'class' => 'small_input', 'value' => '')); ?>
	
</div>