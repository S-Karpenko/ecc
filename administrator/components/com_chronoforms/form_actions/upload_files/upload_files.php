<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
class CfactionUploadFiles{
	var $formname;
	var $formid;
	var $events = array('success' => 0, 'fail' => 0);
	var $fail = array('actions' => array('show_HTML'));
	var $details = array('title' => 'Upload Files', 'tooltip' => "Control the files uploaded through your form's files fields.");
	
	var $upload_path = '';
	var $params = null;
		
	function run($form, $actiondata){
		$this->params = $params = new JParameter($actiondata->params);
		$files_config = $this->params->get('files', '');
		if($actiondata->enabled == 1 && !empty($files_config)){
			jimport('joomla.utilities.error');
			jimport('joomla.filesystem.file');
			$upload_path = $this->params->get('upload_path');
			if(!empty($upload_path)){
				$upload_path = str_replace(array("/", "\\"), DS, $upload_path);
				if(substr($upload_path, -1) == DS){
					$upload_path = substr_replace($upload_path, '', -1);
				}
				$upload_path = str_replace("JOOMLA_PATH", JPATH_SITE, $upload_path).DS;
				$this->params->set('upload_path', $upload_path);
			}else{
				$upload_path = JPATH_SITE.DS.'components'.DS.'com_chronoforms'.DS.'uploads'.DS.$form->form_details->name.DS;
			}
			$this->upload_path = $upload_path;
			
			if(!JFile::exists($this->upload_path.DS.'index.html')){
				if(!JFolder::create($this->upload_path)){
					JError::raiseWarning(100, 'Couldn\'t create upload directroy 1');
					$this->events['fail'] = 1;
					return;
				}
				$dummy_c = '<html><body bgcolor="#ffffff"></body></html>';
				if(!JFile::write($this->upload_path.DS.'index.html', $dummy_c)){
					JError::raiseWarning(100, 'Couldn\'t create upload directroy 2');
					$this->events['fail'] = 1;
					return;
				}
			}
			$files_array = explode(",", trim($this->params->get('files', '')));
			//get array fields
			$array_fields = array();
			if(trim($this->params->get('array_fields', ''))){
				$array_fields = explode(",", trim($this->params->get('array_fields', '')));				
			}
	
			foreach($files_array as $file_string){
				if(strpos($file_string, ':') !== false){
					$file_data = explode(':', trim($file_string));
					$file_extensions = explode('-', $file_data[1]);
					//convert all extensions to lower case
					foreach($file_extensions as $k => $file_extension){
						$file_extensions[$k] = strtolower($file_extension);
					}
					//get the posted file details
					$field_name = $file_data[0];
					$file_post = JRequest::getVar($field_name, array('error' => 99999), 'files', 'array');
					if(in_array($field_name, $array_fields) && !empty($file_post['name']) && ($file_post['name'] === array_values($file_post['name']))){
						foreach($file_post['name'] as $k => $v){
							$uploaded_file_data = $this->processUpload($form, array('name' => $file_post['name'][$k], 'tmp_name' => $file_post['tmp_name'][$k], 'error' => $file_post['error'][$k], 'size' => $file_post['size'][$k]), $file_data[0], $file_extensions);
							if(is_array($uploaded_file_data)){
								$form->files[$field_name][] = $uploaded_file_data;
								$form->data[$field_name][] = $uploaded_file_data['name'];
							}elseif($uploaded_file_data === false){
								return false;
							}
						}
					}else{
						$uploaded_file_data = $this->processUpload($form, $file_post, $file_data[0], $file_extensions);
						if(is_array($uploaded_file_data)){
							$form->files[$field_name] = $uploaded_file_data;
							$form->data[$field_name] = $uploaded_file_data['name'];
						}elseif($uploaded_file_data === false){
							return false;
						}
					}
				}				
			}
			//add the data key
			if(!isset($form->data['_PLUGINS_']['upload_files'])){
				$form->data['_PLUGINS_']['upload_files'] = array();
			}
			$form->data['_PLUGINS_']['upload_files'] = array_merge($form->data['_PLUGINS_']['upload_files'], $form->files);
		}
	}
	
	function processUpload($form, $file_post = array(), $field_name, $file_extensions){
		//check errors
		if(isset($file_post['error']) && !empty($file_post['error'])){
			if($file_post['error'] == 99999){
				//the file field type is not present in the posted data
				//continue;
				return;
			}else if($file_post['error'] == 4 && isset($file_post['name']) && empty($file_post['name']) && isset($file_post['size']) && ($file_post['size'] == 0)){
				//No file has been selected
				//continue;
				return;
			}
			$form->debug[] = 'PHP returned this error for file upload by : '.$field_name.', PHP error is: '.$file_post['error'];
			$form->validation_errors[$field_name] = $file_post['error'];
			$this->events['fail'] = 1;
			return false;
		}else{
			$form->debug[] = 'Upload routine started for file upload by : '.$field_name;
		}
		if((bool)$this->params->get('safe_file_name', 1) === true){
			$file_name = JFile::makeSafe($file_post['name']);
		}else{
			$file_name = utf8_decode($file_post['name']);
		}
		$real_file_name = $file_name;
		$file_tmp_name = $file_post['tmp_name'];
		$file_info = pathinfo($file_name);
		//mask the file name
		if(strlen($this->params->get('forced_file_name', '')) > 0){
			$file_name = str_replace('FILE_NAME', $file_name, $this->params->get('forced_file_name', ''));
		}else{
			$file_name = date('YmdHis').'_'.$file_name;
		}
		//check the file size
		if($file_tmp_name){
			//check max size
			if(($file_post["size"] / 1024) > (int)$this->params->get('max_size', 100)){
				$form->debug[] = 'File : '.$field_name.' size is over the max limit.';
				$form->validation_errors[$field_name] = $this->params->get('max_error', 'Sorry, Your uploaded file size ('.($file_post["size"] / 1024).' KB) exceeds the allowed limit.');
				$this->events['fail'] = 1;
				return false;
			}else if(($file_post["size"] / 1024) < (int)$this->params->get('min_size', 0)){
				$form->debug[] = 'File : '.$field_name.' size is less than the minimum limit.';
				$form->validation_errors[$field_name] = $this->params->get('min_error', 'Sorry, Your uploaded file size ('.($file_post["size"] / 1024).' KB) is less than the minimum limit.');
				$this->events['fail'] = 1;
				return false;
			}else if(!in_array(strtolower($file_info['extension']), $file_extensions)){
				$form->debug[] = 'File : '.$field_name.' extension is not allowed.';
				$form->validation_errors[$field_name] = $this->params->get('type_error', 'Sorry, Your uploaded file type is not allowed.');
				$this->events['fail'] = 1;
				return false;
			}else{
				//$this->upload_path = $this->params->get('upload_path', JPATH_SITE.DS.'components'.DS.'com_chronoforms'.DS.'uploads'.DS.$form->form_details->name.DS);
				$uploaded_file = JFile::upload($file_tmp_name, $this->upload_path.$file_name);
				if($uploaded_file){
					$uploaded_file_data = array();
					$uploaded_file_data = array('name' => $file_name, 'original_name' => $real_file_name, 'path' => $this->upload_path.$file_name, 'size' => $file_post["size"]);
					//Try to generate an auto file link
					$site_link = JURI::Base();
					if(substr($site_link, -1) == "/"){
						$site_link = substr_replace($site_link, '', -1);
					}
					$uploaded_file_data['link'] = str_replace(array(JPATH_SITE, DS), array($site_link, "/"), $this->upload_path.$file_name);
					//$form->data[$field_name] = $file_name;
					$form->debug[] = $this->upload_path.$file_name.' has been uploaded successfully.';
					$this->events['success'] = 1;
					return $uploaded_file_data;
				}else{
					$form->debug[] = $this->upload_path.$file_name.' could not be uploaded!!';
					$this->events['fail'] = 1;
					return false;
				}
			}
		}
	}
	
	function load($clear){
		if($clear){
			$action_params = array(
				'files' => '',
				'array_fields' => '',
				'upload_path' => '',
				'forced_file_name' => '',
				'max_size' => '100',
				'min_size' => '0',
				'enabled' => 1,
				'safe_file_name' => 1,
				'max_error' => 'Sorry, Your uploaded file size exceeds the allowed limit.',
				'min_error' => 'Sorry, Your uploaded file size is less than the minimum limit.',
				'type_error' => 'Sorry, Your uploaded file type is not allowed.',
			);
		}
		return array('action_params' => $action_params);
	}
}
?>