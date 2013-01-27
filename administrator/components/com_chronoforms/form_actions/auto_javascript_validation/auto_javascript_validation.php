<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
class CfactionAutoJavascriptValidation{
	var $formname;
	var $formid;
	var $group = array('id' => '1_validation', 'title' => 'Validation');
	var $details = array('title' => 'Auto JavaScript Validation', 'tooltip' => 'Auto validate specific fields, useful only when your form has custom code.');
	function run($form, $actiondata){
				
	}
	
	function load($clear){
		if($clear){
			$action_params = array(
				'required' => '',
				'alpha' => '',
				'alphanum' => '',
				'digit' => '',
				'nodigit' => '',
				'number' => '',
				'email' => '',
				'phone' => '',
				'phone_inter' => '',
				'url' => '',
				'image' => ''
			);
		}
		return array('action_params' => $action_params);
	}
}
?>