<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
class CfactionDbSave{
	var $formname;
	var $formid;
	var $group = array('id' => 'data_operations', 'title' => 'Data/DB Operations');
	var $details = array('title' => 'DB Table save', 'tooltip' => 'Create/Update a DB table record using the available form data.');
	
	function run($form, $actiondata){
		$mainframe = JFactory::getApplication();
		$database = JFactory::getDBO();
		$params = new JParameter($actiondata->params);
		$return = true;
		//check if a different database connection is needed
		if($params->get('ndb_enable', 0)){
			$option = array();
			$option['driver'] = $params->get('ndb_driver', 'mysql');// Database driver name
			$option['host'] = $params->get('ndb_host', 'localhost');// Database host name
			$option['user'] = $params->get('ndb_user', '');// User for database authentication
			$option['password'] = $params->get('ndb_password', '');// Password for database authentication
			$option['database'] = $params->get('ndb_database', '');// Database name
			$option['prefix'] = $params->get('ndb_prefix', 'jos_');// Database prefix (may be empty)
			 
			$database = & JDatabase::getInstance($option);
			$params->set('table_name', $params->get('ndb_table_name', ''));
		}
		//end new db connection
		
		$table_name = $params->get('table_name', '');
		if(!empty($table_name)){
			$model_id = $params->get('model_id', '');
			if(empty($model_id)){
				$model_id = 'chronoform_data';
			}
			//generate a dynamic model for the table
			$result = $database->getTableFields(array($table_name), false);
			$table_fields = $result[$table_name];
			$dynamic_model_code = array();
			$dynamic_model_code[] = "<?php";	
			$dynamic_model_code[] = "if (!class_exists('Table".str_replace($mainframe->getCfg('dbprefix'), '', $table_name)."')) {";
			$dynamic_model_code[] = "class Table".str_replace($mainframe->getCfg('dbprefix'), '', $table_name)." extends JTable {";	
			$primary = 'id';
			foreach($table_fields as $table_field => $field_data){
				$dynamic_model_code[] = "var \$".$table_field." = null;";
				if($field_data->Key == 'PRI')$primary = $table_field;
			}
			$dynamic_model_code[] = "function __construct(&\$database) {";
			if($params->get('ndb_enable', 0)){
				$dynamic_model_code[] = "\$db_inst = JDatabase::getInstance(".var_export($option, true).");";
				$dynamic_model_code[] = "parent::__construct('".$table_name."', '".$primary."', \$db_inst);";
			}else{
				$dynamic_model_code[] = "parent::__construct('".$table_name."', '".$primary."', \$database);";
			}
			$dynamic_model_code[] = "}";
			$dynamic_model_code[] = "}";
			$dynamic_model_code[] = "}";
			$dynamic_model_code[] = "?>";
			$dynamic_model = implode("\n", $dynamic_model_code);
			eval("?>".$dynamic_model);
			//load some variables
			$user = JFactory::getUser();
			$defaults = array(
				'cf_uid' => md5(uniqid(rand(), true)),
				'cf_created' => date('Y-m-d H:i:s', time()),
				'cf_created_by' => $user->id,
				'cf_ipaddress' => $_SERVER["REMOTE_ADDR"],
				'cf_user_id' => $user->id
			);
			$row = JTable::getInstance(str_replace($mainframe->getCfg('dbprefix'), '', $table_name), 'Table');
			
			//get the data array under the model id if exists
			$form_data = $form->get_array_value($form->data, explode('.', $model_id));
			
			if((bool)$params->get('save_under_modelid', 0) === false){
				$form_data = $form->data;// = $form->set_array_value($form->data, explode('.', $model_id), $form->data);
			}else{
				//if it didn't exist then create an empty one
				if(!isset($form_data)){
					$form_data = array();
					//$form->data = $form->set_array_value($form->data, explode('.', $model_id), $form_data);
				}
			}
			//check Parameters fields list
			if(strlen(trim($params->get('params_fields', ''))) > 0){
				$params_fields = explode(",", trim($params->get('params_fields', '')));
				foreach($params_fields as $params_field){
					if(isset($form_data[$params_field])){
						$param_value = $form_data[$params_field];//$form->get_array_value($form->data, explode('.', $params_field));
						if(!is_null($param_value) && is_array($param_value)){
							$params_field_data = new JParameter('');
							foreach($param_value as $k => $v){
								if(is_array($v)){
									$v = implode(",", $v);
								}
								$params_field_data->set($k, $v);
							}
							$form_data[$params_field] = $params_field_data->toString();
							//$form->data = $form->set_array_value($form->data, explode('.', $params_field), $params_field_data->toString());
						}
					}
				}
			}
			//check if new record or updated one
			if(isset($form_data[$primary]) && !empty($form_data[$primary])){
				//don't merge, just set a modified date
				$form_data = array_merge(array('cf_modified' => date('Y-m-d H:i:s', time()), 'cf_modified_by' => $user->id), $form_data);
				$form->data = $form->set_array_value($form->data, explode('.', $model_id), $form_data);
			}else{
				$form_data = array_merge($defaults, $form_data);
				$form->data = $form->set_array_value($form->data, explode('.', $model_id), $form_data);
			}
			if(!$row->bind($form_data)){
				$form->debug[] = $row->getError();
				$return = false;
			}
			if(!$row->store()){
				$form->debug[] = $row->getError();
				$return = false;
			}
			$form->data[strtolower($model_id.'_'.$primary)] = $row->$primary;
			$form->data = $form->set_array_value($form->data, explode('.', $model_id.'.'.$primary), $row->$primary);
			return $return;
		}
	}
	
	function load_tables(){
		//print_r2($_GET);
		$option = array();
		$option['driver'] = JRequest::getVar('dbdriver', 'mysql');// Database driver name
		$option['host'] = JRequest::getVar('dbhost', 'localhost');// Database host name
		$option['user'] = JRequest::getVar('dbuser', '');// User for database authentication
		$option['password'] = JRequest::getVar('dbpass', '');// Password for database authentication
		$option['database'] = JRequest::getVar('dbname', '');// Database name
		$option['prefix'] = JRequest::getVar('dbprefix', 'jos_');// Database prefix (may be empty)
		//print_r2($option);
		$database = & JDatabase::getInstance($option);
		if(get_class($database) == 'JDatabase'){
			$tables = $database->getTableList();
			$options = array();
			foreach($tables as $table){
				$options[$table] = $table;
			}
			return implode(",", $options);
		}else{
			return "DB Connection Failed!";
		}
	}
	
	function load($clear){
		if($clear){
			$action_params = array(
				'table_name' => '',
				'enabled' => 1,
				'model_id' => 'chronoform_data',
				'save_under_modelid' => 0,
				'params_fields' => '',
				'ndb_enable' => 0,
				'ndb_driver' => 'mysql',
				'ndb_host' => 'localhost',
				'ndb_user' => '',
				'ndb_password' => '',
				'ndb_database' => '',
				'ndb_table_name' => '',
				'ndb_prefix' => 'jos_'
			);
		}
		return array('action_params' => $action_params);
	}
}
?>