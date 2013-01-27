<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
class CfactionCustomDatepickerMoo{
	var $formname;
	var $formid;
	var $details = array('title' => 'Custom Mootools Datepicker', 'tooltip' => 'Load a custom Mootools Datepicker class.');
	var $group = array('id' => 'power_fields', 'title' => 'Power Fields');
	
	function load($clear){
		if($clear){
			$action_params = array(
				'field_class' => '',
				'pickerClass' => 'datepicker_dashboard',
				'format' => '%d-%m-%Y %H:%M:%S',
				'allowEmpty' => 1,
				'timePicker' => 1,
				'pickOnly' => '',
				'content1' => '',
			);
		}
		return array('action_params' => $action_params);
	}
	
	function run($form, $actiondata){
		
	}	
}
?>