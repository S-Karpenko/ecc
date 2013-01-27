<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
class CfactionChronoConnectivityTask{
	var $formname;
	var $formid;
	var $details = array('title' => 'Chrono Connectivity Task', 'tooltip' => 'Run a Chrono Connectivity Task.');
	var $group = array('id' => 'x_chronoforms_apps', 'title' => 'ChronoForms Apps');
	var $events = array('success' => 0, 'fail' => 0);
	var $params = null;
	
	function run($form, $actiondata){
		$mainframe = JFactory::getApplication();
		$this->params = $params = new JParameter($actiondata->params);
		
		if(trim($params->get('connection_name', '')) != '' && trim($params->get('task', '')) != ''){
			$received_data = array(
				'connection_name' => $params->get('connection_name', ''),
				'task' => $params->get('task', ''),
				'field_name' => $params->get('field_name', ''),
			);
			if(trim($params->get('data', '')) != '' && isset($form->data[$params->get('data', '')])){
				$received_data['data'] = $form->data[$params->get('data', '')];
			}
			$this->processData($form, $actiondata, $received_data);
		}else if(isset($form->data['apps_data']['ChronoConnectivity']['action_'.$actiondata->order])){
			$received_data = $form->data['apps_data']['ChronoConnectivity']['action_'.$actiondata->order];
			//print_r2($session_data);
			if(isset($received_data['task']) && !empty($received_data['task'])){
				$this->processData($form, $actiondata, $received_data);
			}else{
				$this->events['fail'] = 1;
				if((bool)$params->get('show_returned_errors', 0) === true){
					$form->validation_errors[] = "Error occurred, session data couldn't be found.";
				}else{
					$form->validation_errors[] = $params->get('error_message', '');
					$form->debug['Chrono Connectivity Task'][$actiondata->order] = "Error occurred, session data couldn't be found.";
				}
			}			
		}
	}
	
	function processData($form, $actiondata, $received_data = array()){
		$params = $this->params;
		require_once(JPATH_SITE.DS.'components'.DS.'com_chronoconnectivity'.DS.'libraries'.DS.'chronoconnection.php');
		$task = $received_data['task'];
		$connection_name = $received_data['connection_name'];
		//get the data
		$data = array();
		if(isset($received_data['data'])){
			$data = $received_data['data'];
		}
		$return = true;
		//connection instance
		$MyConnection = CFChronoConnection::getInstance($connection_name);
		//print_r2(array('task' => $task, 'name' => $connection_name, 'data' => $data));
		switch($task){
			case 'delete_data':
				//check permissions
				$acl_result = $MyConnection->check_permissions('delete', $data);
				if($acl_result === false){
					$MyConnection->permission_deny();
					$return = false;
					break;
				}else if(is_array($acl_result)){
					$data = $acl_result;
				}
				//delete records code here
				$return = $MyConnection->delete_record_data($data);
				break;
			case 'save_data':
				//check permissions
				$r_id = null;
				if(is_array($data)){
					$form->data = array_merge($form->data, $data);
				}
				if(strlen($MyConnection->connection_model_id) > 0){
					if(isset($form->data[$MyConnection->connection_model_id][$MyConnection->connection_table_primary]) && !empty($form->data[$MyConnection->connection_model_id][$MyConnection->connection_table_primary])){
						$r_id = $form->data[$MyConnection->connection_model_id][$MyConnection->connection_table_primary];
					}
				}else{
					if(isset($form->data[$MyConnection->connection_table_primary]) && !empty($form->data[$MyConnection->connection_table_primary])){
						$r_id = $form->data[$MyConnection->connection_table_primary];
					}
				}
				
				$acl_result = $MyConnection->check_permissions('save', $r_id);
				if($acl_result === false){
					$MyConnection->permission_deny();
					$return = false;
					break;
				}else if(is_array($acl_result)){
					$r_id = $acl_result[0];
				}
				//save/update data
				$return = $MyConnection->save_action($data);
				break;
			case 'edit_data':
				//check permissions
				$acl_result = $MyConnection->check_permissions('edit', $data);
				if($acl_result === false){
					$MyConnection->permission_deny();
					$return = false;
					break;
				}else if(is_array($acl_result)){
					$data = $acl_result[0];
				}
				//edit/load record here
				$row_data = $MyConnection->read_record_data($data);
				if(is_array($row_data)){
					$form->data = array_merge($row_data, $form->data);
				}
				$form = $MyConnection->addFormExtension($form, 'edit');
				break;
			/*case 'binary_data':
				//check permissions
				$acl_result = $MyConnection->check_permissions('binary', $data);
				if($acl_result === false){
					$MyConnection->permission_deny();
					$return = false;
					break;
				}else if(is_array($acl_result)){
					$data = $acl_result;
				}
				//binary records code here
				$field_name = $received_data['field_name'];
				$return = $MyConnection->binary_record_data($field_name, $data);
				break;*/
			case 'list_data':
			default:
				//check permissions
				if($MyConnection->check_permissions('list', $data) === false){
					$this->permission_deny();
					$return = false;
					break;
				}
				//list records code here
				$actiondata->content1 = $MyConnection->get_list_output();
				break;
		}
		//check events
		if($return === true){
			$this->events['success'] = 1;
		}else{
			$this->events['fail'] = 1;
			if(is_string($return)){
				if((bool)$params->get('show_returned_errors', 0) === true){
					$form->validation_errors[] = $return;
				}else{
					$form->validation_errors[] = $params->get('error_message', '');
					$form->debug['Chrono Connectivity Task'][$actiondata->order] = $return;
				}
			}
		}
	}
	
	function load($clear){
		if($clear){
			$action_params = array(
				'task' => '',
				'field_name' => '',
				'data' => '',
				'connection_name' => '',
				'error_message' => 'An error has occurred.',
				'show_returned_errors' => 0,
				'purge_old_data' => 1,
				'purge_data_lifetime' => 15
			);
		}
		return array('action_params' => $action_params);
	}
}
?>