<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
class CfactionDynamicDropdown{
	var $formname;
	var $formid;
	var $details = array('title' => 'Dynamic Dropdown', 'tooltip' => 'Attach one of your Drop Downs to another dropdown or to an AJAX event so that the data is changed dynamically.');
	var $group = array('id' => 'power_fields', 'title' => 'Power Fields');
	
	function load($clear){
		if($clear){
			$action_params = array(
				'target_dropdown_id' => '',
				'source_dropdown_id' => '',
				'enable_ajax' => 0,
				'ajax_event_name' => '',
				'action_label' => '',
				'content1' => '',
			);
		}
		return array('action_params' => $action_params);
	}
	
	function run($form, $actiondata){
		
	}	
}
?>