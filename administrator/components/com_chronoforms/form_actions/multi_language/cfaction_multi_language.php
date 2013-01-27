<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
class CfactionMultiLanguageHelper{
	function translate($form, $actiondata){
		$params = new JParameter($actiondata->params);
		if((bool)$params->get('translate_output', 0) === true){
			if(isset($form->form_actions) && !empty($form->form_actions)){				
				$lang = JFactory::getLanguage();
				if($lang->getTag() == $params->get('lang_tag', '')){
					$lang_strings = explode("\n", $actiondata->content1);
					usort($lang_strings, array('CfactionMultiLanguage', 'sortByLength'));
					foreach($lang_strings as $lang_string){
						if(!empty($lang_string) && strpos($lang_string, "=") !== false){
							$texts = explode("=", $lang_string, 2);
							$original = trim($texts[0]);
							$new = trim($texts[1]);
							$form->form_output = str_replace($original, $new, $form->form_output);
						}					
					}
				}
			}
		}
    }
}
?>