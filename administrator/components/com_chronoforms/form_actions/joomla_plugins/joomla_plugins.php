<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
class CfactionJoomlaPlugins{
	var $formname;
	var $formid;
	var $details = array('title' => 'Joomla Plugins', 'tooltip' => 'Renders the Joomla content plugins inside your form contents.');
	var $group = array('id' => 'joomla_functions', 'title' => 'Joomla Functions');
	
	function run($form, $actiondata){
		$mainframe = JFactory::getApplication();
		$params = new JParameter($actiondata->params);
	}
	
	function load($clear){
		if($clear){
			$action_params = array(
				'content1' => '',
			);
		}
		return array('action_params' => $action_params);
	}
}
?>