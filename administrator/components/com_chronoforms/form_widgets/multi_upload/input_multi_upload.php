<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
class ChronoFormsInputMultiUpload{
	function load($clear){
		if($clear){
			$element_params = array(
				'label_text' => 'Attachments',
				'input_name' => 'attachments_{n}[]',
				'hide_label' => '0',
				'label_over' => '0',
				'validations' => '',
				'instructions' => '',
				'tooltip' => '',
				'container_id' => 0,
				'data_path' => '',
				'data_path_name' => '',
				'data_path_original_name' => '',
				'data_path_id' => '',
				'limit' => 3,
			);
		}
		return array('element_params' => $element_params);
	}
}
?>