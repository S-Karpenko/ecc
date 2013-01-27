<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
class CfactionChronoAppTask{
	var $formname;
	var $formid;
	var $details = array('title' => 'Chrono App Task', 'tooltip' => 'Run a Chrono App task.');
	var $group = array('id' => 'x_chronoforms_apps', 'title' => 'ChronoForms Apps');
	
	function run($form, $actiondata){
		$mainframe = JFactory::getApplication();
		$params = new JParameter($actiondata->params);
		
		$option = $params->get('option', '');
		$task = $params->get('task', '');
		if(!empty($option) && !empty($task)){
			require_once(JPATH_SITE.DS.'components'.DS.'com_chronoconnectivity'.DS.'libraries'.DS.'chronoapp.php');
			/*if(strpos($task, '.') !== false){
				$pcs = explode('.', $task);
				$task = $pcs[0];
				$fn = $pcs[1];
			}else{
				$fn = 'index';
			}*/
			$new_app_instance = new CEChronoApp($option, $task, false);
			$new_app_instance->taskControl = false;
			$new_app_instance->runTask();
			//$Controller = $new_app_instance->controllers[trim($option).'.'.trim($task)];
			//$Controller = CEChronoApp::getInstance($option, $task);
			//$Controller->$fn();
		}
	}
	
	function load($clear){
		if($clear){
			$action_params = array(
				'content1' => '',
				'option' => '',
				'task' => '',
				'action_label' => '',
				'mode' => 'controller'
			);
		}
		return array('action_params' => $action_params);
	}
}
?>