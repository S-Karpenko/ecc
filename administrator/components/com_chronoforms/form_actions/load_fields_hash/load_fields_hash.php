<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
class CfactionLoadFieldsHash{
	var $formname;
	var $formid;
	var $group = array('id' => 'form_security', 'title' => 'Security');
	var $details = array('title' => 'Load Fields Hash', 'tooltip' => 'Injects a security hash representing the values of specific fields values.');
	
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
		$form->debug['Load Fields Hash'][$actiondata->order] = $hashed_values;
		$hash = serialize($hashed_values);
		$hash = md5($hash);
		$hash = md5($hash.':'.$secret);
		
		$form->extra_content .= '<input type="hidden" alt="ghost" name="'.trim($params->get('hash_field_name', 'cf_fields_hash')).'" value="'.$hash.'" />';
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