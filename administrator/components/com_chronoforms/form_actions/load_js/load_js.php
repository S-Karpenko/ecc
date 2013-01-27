<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
class CfactionLoadJs{
	var $formname;
	var $formid;
	var $group = array('id' => 'form_utilities', 'title' => 'Utilities');
	var $details = array('title' => 'Load JS', 'tooltip' => 'Process and load custom JS code in the form page.');
	
	function load($clear){
		if($clear){
			$action_params = array(
				'content1' => '',
				'dynamic_file' => 0
			);
		}
		return array('action_params' => $action_params);
	}
	
	function run($form, $actiondata){
		
	}	
}
?>