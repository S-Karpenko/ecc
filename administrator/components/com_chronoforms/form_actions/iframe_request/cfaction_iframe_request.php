<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
class CfactionIFrameRequestHelper{
	function loadAction($form, $actiondata){
		$params = new JParameter($actiondata->params);
		$document = JFactory::getDocument();
		JHTML::_('behavior.mootools');
		$mainframe = JFactory::getApplication();
		$uri = JFactory::getURI();
		$cf_url = $uri->root();
		$cf_url .= 'administrator/components/com_chronoforms/form_actions/iframe_request/';
		$document->addScript($cf_url.'iFrameFormRequest.js');
		//create the JS function
		ob_start();
		?>
		//<![CDATA[				
			window.addEvent('domready',function(){	
				iframe_<?php echo $form->form_name; ?> = new iFrameFormRequest('chronoform_<?php echo $form->form_name; ?>',{
					onRequest: function(){
						<?php
							if(strlen(trim($params->get('onrequest_fn'))) > 0){
								echo trim($params->get('onrequest_fn')).";";
							}
						?>
					},
					onComplete: function(response){
						<?php if(strlen(trim($params->get('response_element_id'))) > 0): ?>
						document.id('<?php echo trim($params->get('response_element_id')); ?>').set('html',response);
						<?php endif; ?>
						<?php
							if(strlen(trim($params->get('onsuccess_fn'))) > 0){
								echo trim($params->get('onsuccess_fn')).";";
							}
						?>
					}
				});
			});
		//]]>
		<?php
		$script = ob_get_clean();
		$document->addScriptDeclaration($script);		
	}
}
?>