<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
class CfactionShowHtml{
	var $formname;
	var $formid;
	var $details = array('title' => 'Show HTML (Display Form)', 'tooltip' => 'Eval and show the form content.');
	function load($clear){
		if($clear){
			$action_params = array(
				'data_republish' => 1,
				'display_errors' => 1,
				'load_token' => 1,
				'keep_alive' => 0,
				'curly_replacer' => 1,
				'submit_event' => 'submit',
				'form_container' => '',
				'page_number' => '1'
			);
		}
		return array('action_params' => $action_params);
	}
	
	function run($form, $actiondata){
		$params = new JParameter($actiondata->params);
	}	
}
?>