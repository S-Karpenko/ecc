<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
class CfactionJoomlaLogout{
	var $formname;
	var $formid;
	var $group = array('id' => 'joomla_functions', 'title' => 'Joomla Functions');
	var $events = array('success' => 0, 'fail' => 0);
	var $details = array('title' => 'Joomla Logout', 'tooltip' => 'Log out a logged in user or a specific user (defined by ID).');
	function run($form, $actiondata){
		$params = new JParameter($actiondata->params);
		$mainframe = JFactory::getApplication();
		$user_id = null;
		if(strlen(trim($params->get('user_id', null)))){
			$user_id = (int)trim($params->get('user_id', null));
		}
		
		if($mainframe->logout($user_id) === true){
			$this->events['success'] = 1;
			//redirect if so
			$redirect = $params->get('redirect_url', '');
			if(!empty($redirect)){
				$mainframe->redirect($redirect);
			}
		}else{
			$this->events['fail'] = 1;
			$form->validation_errors[] = 'Error occurred.';
			return false;
		}
	}
	
	function load($clear){
		if($clear){
			$action_params = array(
				'user_id' => '',
				'redirect_url' => 'index.php'
			);
		}
		return array('action_params' => $action_params);
	}
}
?>