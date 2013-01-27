<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
class CfactionStyleFormHelper{
	function load($form = null, $actiondata = null){
		$params = new JParameter($actiondata->params);
		$output = '';
		$document = JFactory::getDocument();
		ob_start();
		?>
			#chronoform_<?php echo $form->form_name; ?>{
				width:<?php echo $params->get('form_width', 'auto'); ?>;
				background-color:<?php echo $params->get('background_color', 'transparent'); ?>;
			}			
			#chronoform_<?php echo $form->form_name; ?> .cfdiv_radio label,
			#chronoform_<?php echo $form->form_name; ?> .cfdiv_checkbox label,
			#chronoform_<?php echo $form->form_name; ?> .cfdiv_checkboxgroup label{
				width:<?php echo $params->get('sub_label_width', 'auto'); ?>;
				font-size:<?php echo $params->get('sub_label_font_size', '100%'); ?>;
				font-weight:<?php echo $params->get('sub_label_font_weight', 'normal'); ?>;
				font-family:<?php echo $params->get('sub_label_font_family', 'arial,helvetica,sans-serif'); ?>;
			}
			#chronoform_<?php echo $form->form_name; ?> .ccms_form_element label:first-child,
			#chronoform_<?php echo $form->form_name; ?> .cfdiv_radio label:first-child,
			#chronoform_<?php echo $form->form_name; ?> .cfdiv_checkbox label:first-child,
			#chronoform_<?php echo $form->form_name; ?> .cfdiv_checkboxgroup label:first-child{
				width:<?php echo $params->get('label_width', '150px'); ?>;
				font-size:<?php echo $params->get('label_font_size', '100%'); ?>;
				font-weight:<?php echo $params->get('label_font_weight', 'bold'); ?>;
				font-family:<?php echo $params->get('label_font_family', 'arial,helvetica,sans-serif'); ?>;
			}
		<?php
		$output .= ob_get_clean();
		$document->addStyleDeclaration($output);
	}
}
?>