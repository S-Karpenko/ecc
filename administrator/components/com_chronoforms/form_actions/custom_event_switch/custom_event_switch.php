<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
class CfactionCustomEventSwitch{
	var $formname;
	var $formid;
	var $events = array('success' => 0, 'fail' => 0);
	var $group = array('id' => '1_validation', 'title' => 'Validation');
	var $details = array('title' => 'Custom Event Switcher', 'tooltip' => 'Run PHP code and switch the execution path based on the result.');
	
	function run($form, $actiondata){
		$code = $actiondata->content1;
		$return = eval('?>'.$code);
		$this->events[$return] = 1;
	}
	
	function load($clear){
		if($clear){
			$action_params = array(
				'content1' => '',
				'events' => 'success,fail',
			);
		}
		return array('action_params' => $action_params);
	}
}
?>