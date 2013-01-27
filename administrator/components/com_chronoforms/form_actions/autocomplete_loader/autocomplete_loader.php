<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
class CfactionAutocompleteLoader{
	var $formname;
	var $formid;
	var $details = array('title' => 'Autocomplete Loader', 'tooltip' => 'Load the auto complete basic CSS and code');
	var $group = array('id' => 'power_fields', 'title' => 'Power Fields');
	
	function load($clear){
		if($clear){
			$action_params = array(
				'field_id' => '',
				'field_name' => '',
				'minLength' => 3,
				'maxChoices' => 10,
				'ajax_delay' => 300,
				'results_cache' => 'true',
				'ajax_event' => ''
			);
		}
		return array('action_params' => $action_params);
	}
	
	function run($form, $actiondata){
		
	}	
}
?>