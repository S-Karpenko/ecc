<?php
	$document = JFactory::getDocument();
	JHTML::_('behavior.mootools');
	$mainframe = JFactory::getApplication();
	$uri = JFactory::getURI();
	$document->addScript($uri->root().'administrator/components/com_chronoforms/form_widgets/multi_upload/multi_upload.js');
	$clean_fldname = str_replace('[]', '', $params['input_name']);
?>
<div class="multi-upload-attachments-wrapper" style="float:left;">
	<div id="error-message-<?php echo $clean_fldname; ?>"></div>
	<?php	
		$fields_data_list = array();
		if(!empty($form->files[$clean_fldname])){
			$fields_data_list = $form->files[$clean_fldname];
			$file_name_key = 'name';
			$file_orig_name_key = 'original_name';
			$file_id_key = 'file_id';
		}else{
			if(!empty($params['data_path']) && !is_null($form->get_array_value($form->data, explode('.', trim($params['data_path']))))){
				$fields_data_list = $form->get_array_value($form->data, explode('.', trim($params['data_path'])));
				$file_name_key = !empty($params['data_path_name']) ? $params['data_path_name'] : '';
				$file_orig_name_key = !empty($params['data_path_original_name']) ? $params['data_path_original_name'] : '';
				$file_id_key = !empty($params['data_path_id']) ? $params['data_path_id'] : '';
			}
		}
		//print_r2($form->data);
		foreach($fields_data_list as $k => $field_data):
			?>
			<div class="multi-upload-list-file radios_over">
				<input type="checkbox" name="<?php echo $clean_fldname.'['.$k.']'; ?>" value="<?php echo (!empty($file_name_key) ? $field_data[$file_name_key] : $field_data); ?>" checked="checked" onClick="MultiUpload.fixLimit(this, 'multi_upload_limit_<?php echo $clean_fldname; ?>');" />
				<?php if(!empty($file_orig_name_key) && isset($field_data[$file_orig_name_key])): ?>
					<input type="hidden" name="cf_file_orig_name_<?php echo $clean_fldname.'['.$k.']'; ?>" value="<?php echo $field_data[$file_orig_name_key]; ?>" />
					<label style="width:auto;"><?php echo $field_data[$file_orig_name_key]; ?></label>
				<?php else: ?>
					<label style="width:auto;"><?php echo (!empty($file_name_key) ? $field_data[$file_name_key] : $field_data); ?></label>
				<?php endif; ?>
				<?php if(!empty($file_id_key) && isset($field_data[$file_id_key])): ?>
					<input type="hidden" name="cf_file_id_<?php echo $clean_fldname.'['.$k.']'; ?>" value="<?php echo $field_data[$file_id_key]; ?>" />
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
</div>
<div class="clear"></div>
<div class="multi-upload-add-attachment-link" style="padding-left:150px;">
	<a href="javascript:void(0)" class="multi-upload-add-attachment command" onclick="MultiUpload.addAttachment(this, '<?php echo $params['input_name']; ?>', <?php echo $params['limit']; ?>, '<?php echo $uri->root().'administrator/components/com_chronoforms/form_widgets/multi_upload/'; ?>')">Attach file</a>
	<input type="hidden" alt="ghost" name="multi_upload_limit_<?php echo $clean_fldname; ?>" class="multi-upload-limit" id="multi_upload_limit_<?php echo $clean_fldname; ?>" value="<?php echo count($fields_data_list); ?>" />
</div>