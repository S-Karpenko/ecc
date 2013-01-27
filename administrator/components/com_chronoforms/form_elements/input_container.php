<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
class ChronoFormsInputContainer{
	var $advanced = true;
	function load($clear){
		if($clear){
			$element_params = array(
								'area_label' => 'Container #{n}',
								'container_id' => 0,
								'collapsed' => 0,
								'container_type' => '',
								'start_code' => '',
								'end_code' => '',
								'container_class' => '',
								);
		}
		return array('element_params' => $element_params);
	}
}
?>