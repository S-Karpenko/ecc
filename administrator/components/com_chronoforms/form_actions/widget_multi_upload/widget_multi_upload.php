<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
class CfactionWidgetMultiUpload{
	var $formname;
	var $formid;
	var $group = array('id' => 'widgets_processors', 'title' => 'Widgets Processors');
	var $events = array('success' => 0, 'fail' => 0);
	var $details = array('title' => 'Multi Upload', 'tooltip' => 'Processes the files sent using the Multi Upload Widget.');
	
	function run($form, $actiondata){
		$params = new JParameter($actiondata->params);
		if(trim($params->get('fields', ''))){
			jimport('joomla.utilities.error');
			jimport('joomla.filesystem.file');
			//get upload path
			$upload_path = $params->get('upload_path');
			if(!empty($upload_path)){
				$upload_path = str_replace(array("/", "\\"), DS, $upload_path);
				if(substr($upload_path, -1) == DS){
					$upload_path = substr_replace($upload_path, '', -1);
				}
				$upload_path = str_replace("JOOMLA_PATH", JPATH_SITE, $upload_path).DS;
				$params->set('upload_path', $upload_path);
			}else{
				$upload_path = JPATH_SITE.DS.'components'.DS.'com_chronoforms'.DS.'uploads'.DS.$form->form_details->name.DS;
			}
			
			$fields = explode(',', trim($params->get('fields', '')));
			$array_fields = $fields;
			foreach($array_fields as $k => $v){
				$first = explode(':', $v);
				$array_fields[$k] = $first[0];
			}
			//Try to generate an auto file link
			$site_link = JURI::Base();
			if(substr($site_link, -1) == "/"){
				$site_link = substr_replace($site_link, '', -1);
			}
			//check if there are some checkboxes old fields
			foreach($array_fields as $k => $field){
				if(!empty($form->data[$field]) && is_array($form->data[$field])){
					//cut any extra files over the limit
					if(strlen(trim($params->get('limit', ''))) > 0 && is_numeric(trim($params->get('limit', '')))){
						$limit = (int)trim($params->get('limit', ''));
						if(count($form->data[$field]) > $limit){
							$this->events['fail'] = 1;
							$form->validation_errors[$field] = "You have exceeded the maximum number of allowed files.";
							return false;
						}
					}
					foreach($form->data[$field] as $kk => $file_real_name){
						if(file_exists($upload_path.DS.$file_real_name)){
							//$file_info = pathinfo($upload_path.DS.$file_real_name);
							$form->files[$field][$kk]['size'] = filesize($upload_path.DS.$file_real_name);
							$form->files[$field][$kk]['path'] = $upload_path.$file_real_name;
							if(!empty($form->data['cf_file_orig_name_'.$field][$kk])){
								$form->files[$field][$kk]['original_name'] = $form->data['cf_file_orig_name_'.$field][$kk];
							}
							if(!empty($form->data['cf_file_id_'.$field][$kk])){
								$form->files[$field][$kk]['file_id'] = $form->data['cf_file_id_'.$field][$kk];
							}
							$form->files[$field][$kk]['name'] = $file_real_name;
							$form->files[$field][$kk]['link'] = str_replace(array(JPATH_SITE, DS), array($site_link, "/"), $upload_path.$file_real_name);
							//set extra paths
							if(strlen(trim($params->get('target_path_name', ''))) > 0){
								$form->files[$field][$kk][trim($params->get('target_path_name', ''))] = $form->files[$field][$kk]['name'];
							}
							if((strlen(trim($params->get('target_path_original_name', ''))) > 0) && !empty($form->files[$field][$kk]['original_name'])){
								$form->files[$field][$kk][trim($params->get('target_path_original_name', ''))] = $form->files[$field][$kk]['original_name'];
							}
							if((strlen(trim($params->get('target_path_id', ''))) > 0) && !empty($form->files[$field][$kk]['file_id'])){
								$form->files[$field][$kk][trim($params->get('target_path_id', ''))] = $form->files[$field][$kk]['file_id'];
							}
						}
					}
					//set target path if enabled
					if(strlen(trim($params->get('target_path', ''))) > 0){
						$form->data = $form->set_array_value($form->data, explode('.', trim($params->get('target_path', ''))), $form->files[$field]);
					}
				}
			}
			//process the new uploaded files
			$upload_files_details = $form->createAction('upload_files', array(
				'upload_path' => $upload_path,
				'files' => $params->get('fields', ''),
				'array_fields' => implode(',', $array_fields),
				'max_size' => $params->get('max_size', 1000),
			));
			$form->runAction($upload_files_details);
			//cut any extra files over the limit
			if(strlen(trim($params->get('limit', ''))) > 0 && is_numeric(trim($params->get('limit', '')))){
				$limit = (int)trim($params->get('limit', ''));
				foreach($array_fields as $k => $field){
					if(count($form->files[$field]) >= $limit){
						$form->files[$field] = array_slice($form->files[$field], 0, $limit);
						$form->data[$field] = array_slice($form->data[$field], 0, $limit);
					}
				}
			}
			
			if($form->last_action_result === false){
				$this->events['fail'] = 1;
			}else{
				$this->events['success'] = 1;
				//set target path if enabled
				if(strlen(trim($params->get('target_path', ''))) > 0){
					foreach($array_fields as $k => $field){
						//$form->data = $form->set_array_value($form->data, explode('.', trim($params->get('target_path', ''))), $form->files[$field]);
						foreach($form->files[$field] as $f => $file){
							if(strlen(trim($params->get('target_path_name', ''))) > 0){
								$form->files[$field][$f][trim($params->get('target_path_name', ''))] = $file['name'];
							}
							if((strlen(trim($params->get('target_path_original_name', ''))) > 0) && !empty($file['original_name'])){
								$form->files[$field][$f][trim($params->get('target_path_original_name', ''))] = $file['original_name'];
							}
							if((strlen(trim($params->get('target_path_id', ''))) > 0) && !empty($file['file_id'])){
								$form->files[$field][$f][trim($params->get('target_path_id', ''))] = $file['file_id'];
							}
							$form->data = $form->set_array_value($form->data, explode('.', trim($params->get('target_path', ''))), $form->files[$field]);
						}
					}
				}
			}
		}
	}
	
	function load($clear){
		if($clear){
			$action_params = array(
				'fields' => '',
				'limit' => 3,
				'max_size' => 1000,
				'upload_path' => '',
				'target_path' => '',
				'target_path_name' => '',
				'target_path_original_name' => '',
				'target_path_id' => '',
			);
		}
		return array('action_params' => $action_params);
	}
}
?>