<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
class CfactionCheckFieldsHash{
	var $formname;
	var $formid;
	var $group = array('id' => 'form_security', 'title' => 'Security');
	var $events = array('success' => 0, 'fail' => 0);
	var $details = array('title' => 'Check Fields Hash', 'tooltip' => "Generates a new hash based on the new fields values and compares it to the existing one.<br />Your fields list here should match the list in the load action, and should be in the SAME ORDER.");
	
	function run($form, $actiondata){
		$mainframe = JFactory::getApplication();
		$session = JFactory::getSession();
		$params = new JParameter($actiondata->params);
		//get secret
		$secret = $mainframe->getCfg('secret');
		$fields = array();
		if(strlen(trim($params->get('fields', '')))){
			$fields = explode(',', trim($params->get('fields', '')));
		}
		$hashed_values = array();
		foreach($fields as $k => $field){
			$hashed_values[$field] = $form->get_array_value($form->data, explode('.', $field));
		}
		$form->debug['Check Fields Hash'][$actiondata->order] = $hashed_values;
		$hash = serialize($hashed_values);
		$hash = md5($hash);
		$hash = md5($hash.':'.$secret);
		
		$hash_field_name = trim($params->get('hash_field_name', 'cf_fields_hash'));
		if(!empty($form->data[$hash_field_name]) && ($form->data[$hash_field_name] == $hash)){
			$this->events['success'] = 1;
			return true;
		}else{
			$this->events['fail'] = 1;
			return false;
		}
	}
	
	function load($clear){
		if($clear){
			$action_params = array(
				'content1' => '',
				'enabled' => 1,
				'hash_field_name' => 'cf_fields_hash',
				'fields' => ''
			);
		}
		return array('action_params' => $action_params);
	}
}
?>