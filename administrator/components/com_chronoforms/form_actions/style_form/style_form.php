<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
class CfactionStyleForm{
	var $formname;
	var $formid;
	var $details = array('title' => 'Style Form', 'tooltip' => 'Apply some styling to your form and any form elements.');
	
	function load($clear){
		if($clear){
			$action_params = array(
				'content1' => '',
				'form_width' => 'auto',
				'background_color' => 'transparent',
				'label_width' => '150px',
				'label_font_size' => '100%',
				'label_font_weight' => 'bold',
				'label_font_family' => 'arial,helvetica,sans-serif',
				'sub_label_width' => 'auto',
				'sub_label_font_size' => '100%',
				'sub_label_font_weight' => 'normal',
				'sub_label_font_family' => 'arial,helvetica,sans-serif',
			);
		}
		return array('action_params' => $action_params);
	}
	
	function run($form, $actiondata){
		
	}
}
?>