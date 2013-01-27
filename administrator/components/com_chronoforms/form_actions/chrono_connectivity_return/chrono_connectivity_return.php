<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
class CfactionChronoConnectivityReturn{
	var $formname;
	var $formid;
	var $details = array('title' => 'Chrono Connectivity Return', 'tooltip' => 'Return to the Connection listing page.');
	var $group = array('id' => 'x_chronoforms_apps', 'title' => 'ChronoForms Apps');
	
	function run($form, $actiondata){
		$mainframe = JFactory::getApplication();
		$params = new JParameter($actiondata->params);		
		
		if(trim($params->get('connection_name', '')) != ''){
			$received_data = array(
				'connection_name' => $params->get('connection_name', '')
			);
			$this->processData($received_data);
		}else if(isset($form->data['apps_data']['ChronoConnectivity']['action_'.$actiondata->order])){
			$received_data = $form->data['apps_data']['ChronoConnectivity']['action_'.$actiondata->order];
			if(isset($received_data['connection_name'])){
				$this->processData($received_data);
			}
		}
	}
	
	function processData($received_data = array()){
		$mainframe = JFactory::getApplication();
		$connection_name = $received_data['connection_name'];
		require_once(JPATH_SITE.DS.'components'.DS.'com_chronoconnectivity'.DS.'libraries'.DS.'chronoconnection.php');
		$MyConnection = CFChronoConnection::getInstance($connection_name);
		/*$redirect = $MyConnection->connection_url;
		if($MyConnection->connection_area != 'admin'){
			//$redirect = JRoute::_($MyConnection->connection_url, true);
		}*/
		$mainframe->redirect(JRoute::_($MyConnection->connection_url, false));
	}
	
	function load($clear){
		if($clear){
			$action_params = array(
				'connection_name' => '',
				'purge_old_data' => 1,
			);
		}
		return array('action_params' => $action_params);
	}
}
?>