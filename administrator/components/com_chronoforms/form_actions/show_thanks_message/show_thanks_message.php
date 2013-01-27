<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
class CfactionShowThanksMessage{
	var $formname;
	var $formid;
	var $details = array('title' => 'Show Thanks Message', 'tooltip' => 'Display a formatted thank you message (WYSIWYG Editor available).');
	
	function run($form, $actiondata){
		$message = $actiondata->content1;
		//build template from defined fields and posted fields
		//echo $form->curly_replacer($message, $form->data);
	}	
	
	function load($clear){
		if($clear){
			$action_params = array(
				'content1' => ''
			);
		}
		return array('action_params' => $action_params);
	}
}
?>