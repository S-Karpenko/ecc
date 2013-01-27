<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
class CfactionCustomDatepickerMooHelper{
	function load($form = null, $actiondata = null){
		$params = new JParameter($actiondata->params);
		$document = JFactory::getDocument();
		JHTML::_('behavior.mootools');
		$mainframe = JFactory::getApplication();
		$uri = JFactory::getURI();
		//check picker types
		$jversion = new JVersion();
		$script = "";
		if(($jversion->RELEASE > 1.5) && $form->form_params->get('datepicker_type', 0) == 1){		
			$cf_url = $uri->root();
			$cf_url .= 'components/com_chronoforms/js/datepicker_moo/';

			// you can change the uncommented line here to change the style
			$datepicker_style = $params->get('pickerClass', 'datepicker_dashboard');
			$document->addStyleSheet($cf_url.$datepicker_style.'/'.$datepicker_style.'.css');
			$document->addScript($cf_url.'Locale.en-US.DatePicker.js');
			$document->addScript($cf_url.'Picker.js');
			$document->addScript($cf_url.'Picker.Attach.js');
			$document->addScript($cf_url.'Picker.Date.js');
			if((bool)$params->get('pickOnly', 0) === false){
				$pickOnly = 'false';
			}else{
				$pickOnly = "'".$params->get('pickOnly', 0)."'";
			}
			$con_str = "$$('.".$params->get('field_class', 'cf_datetime_picker')."'), {pickerClass: '".$params->get('pickerClass', 'datepicker_dashboard')."', format: '".$params->get('format', 'd-m-Y H:i:s')."', allowEmpty: ".$params->get('allowEmpty', 'true').", timePicker: ".$params->get('timePicker', 'true').", pickOnly: ".$pickOnly."";
			
			$pickerClass = "Picker.Date";
			ob_start();
			eval('?>'.$actiondata->content1);
			$actiondata->content1 = ob_get_clean();
			
			if(!empty($actiondata->content1)){
				$con_str .= ", ".$actiondata->content1;
				$con_str .= "}";
			}else{
				$con_str .= "}";
			}
			ob_start();
			?>
				window.addEvent('load', function() {
					new <?php echo $pickerClass; ?>(<?php echo $con_str; ?>);
				});
			<?php
			$script = ob_get_clean();
		}
		
		if((bool)$form->form_params->get('dynamic_files', 0) === false){
			$document->addScriptDeclaration("//<![CDATA["."\n".$script."\n"."//]]>");
		}else{
			//load the action class
			$form->loadActionHelper('load_js');
			$CfactionLoadJsHelper = new CfactionLoadJsHelper();
			$JSactiondata = new stdClass();
			$JSactiondata->content1 = $script;
			$JSParams = new JParameter('');
			$JSParams->set('dynamic_file', $form->form_params->get('dynamic_files', 0));
			$JSactiondata->params = $JSParams->toString();
			$CfactionLoadJsHelper->load($form, $JSactiondata);
		}
	}
}
?>