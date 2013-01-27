<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
class CfactionIFrameRequest{
	var $formname;
	var $formid;
	var $group = array('id' => 'redirect', 'title' => 'Redirect/Remote Submit');
	var $details = array('title' => 'iFrame Request', 'tooltip' => 'Submit the form using a hidden iFrame, similar to AJAX and supports file uploading.');
	
	function load($clear){
		if($clear){
			$action_params = array(
				'enabled' => 0,
				'response_element_id' => '',
				'onrequest_fn' => '',
				'onsuccess_fn' => '',
			);
		}
		return array('action_params' => $action_params);
	}
	
	function run($form, $actiondata){
		
	}	
}
?>