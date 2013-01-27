<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
class CfactionJoomlaUserSave{
	var $formname;
	var $formid;
	var $group = array('id' => 'joomla_functions', 'title' => 'Joomla Functions');
	var $events = array('success' => 0, 'fail' => 0);
	var $details = array('title' => 'Joomla User Create/Update', 'tooltip' => 'Create or Update a Joomla user record.');
	var $params = null;
	
	function run($form, $actiondata){
		$this->params = $params = new JParameter($actiondata->params);
		$mainframe = JFactory::getApplication();
		//set activation link
		if(trim($this->params->get('activation_link', '')) == ''){
			$this->params->set('activation_link', 'index.php?option=com_users&task=registration.activate');
		}
		// Get required system objects
		//$user 		= clone(JFactory::getUser());
		//$pathway 	= $mainframe->getPathway();
		$config		= JFactory::getConfig();
		$authorize	= JFactory::getACL();
		$document   = JFactory::getDocument();
		$language = JFactory::getLanguage();
        $language->load('com_users');

		// Initialize new usertype setting
		$usersConfig = JComponentHelper::getParams('com_users');
		// Default to Registered.
		$defaultUserGroup = $params->get('new_usertype', '');
		if(empty($defaultUserGroup)){
			if(trim($params->get('new_usertype_field', ''))){
				$posted_groups_field = $params->get('new_usertype_field', '');
				$user_group_value = $form->get_array_value($form->data, explode('.', $params->get('new_usertype_field', '')));
				if(!empty($user_group_value)){
					if(!is_array($user_group_value)){
						$user_group_value = array($user_group_value);
					}
					$defaultUserGroup = $user_group_value;
				}
			}else{
				//$defaultUserGroup = $userConfig->get('new_usertype', array(2));
			}
		}else{
			$_groups = explode(",", trim($defaultUserGroup));
			$defaultUserGroup = array();
			foreach($_groups as $_group){
				$defaultUserGroup[] = (int)$_group;
			}
		}
		
		//set the post fields values
		$post_user_data['name'] = $form->get_array_value($form->data, explode('.', $params->get('name', '')));
		$post_user_data['username'] = $form->get_array_value($form->data, explode('.', $params->get('username', '')));
		$post_user_data['email'] = $form->get_array_value($form->data, explode('.', $params->get('email', '')));
		$post_user_data['password'] = $form->get_array_value($form->data, explode('.', $params->get('password', '')));
		$post_user_data['password2'] = $form->get_array_value($form->data, explode('.', $params->get('password2', '')));
		$post_user_data['id'] = $form->get_array_value($form->data, explode('.', $params->get('user_id', '')));
		
		if((bool)$params->get('enable_old_password', 0) === true){
			$post_user_data['old_password'] = $form->get_array_value($form->data, explode('.', $params->get('old_password', '')));
		}else{
			$post_user_data['old_password'] = '';
		}
		
		//check empty fields
		$checks = array('name', 'username', 'email');
		foreach($checks as $check){
			if(!trim($post_user_data[$check])){
				$this->events['fail'] = 1;
				$form->validation_errors[$params->get($check)] = 'You must provide your '.$check.'.';
				//return false;
			}
		}
		if($this->events['fail'] == 1){
			return false;
		}
		//case create/update
		$noPassword = false;
		if($params->get('function', 0) == 0){
			$user_id = empty($post_user_data['id']) ? 0 : $post_user_data['id'];
			$user = JFactory::getUser($user_id);
			//check if the password is empty
			if(!trim($post_user_data['password'])){
				//new user must have a password
				if(empty($post_user_data['id'])){
					$this->events['fail'] = 1;
					$form->validation_errors[$params->get('password')] = 'You must provide a Password.';
					return false;
				}else{
					unset($post_user_data['password']);
					unset($post_user_data['password2']);
					$noPassword = true;
				}
			}
			//check the 2 passwords
			if(isset($post_user_data['password']) && isset($post_user_data['password2']) && ($post_user_data['password'] != $post_user_data['password2'])){
				$this->events['fail'] = 1;
				$form->validation_errors[$params->get('password2')] = 'Passwords do NOT match.';
				$form->debug[] = "Couldn't create/update user, Passwords do NOT match.";
				return false;
			}
		}else if($params->get('function', 0) == 1){
			$user = JFactory::getUser();
			if(!$user->get('id')){
				$this->events['fail'] = 1;
				$form->validation_errors[] = 'No users logged in.';
				$form->debug[] = "Couldn't get logged in user data.";
				return false;
			}else{
				$post_user_data['id'] = $user->get('id');
			}
			//user is updating his own record
			if(trim($post_user_data['old_password']) || trim($post_user_data['password']) || trim($post_user_data['password2'])){
				//some password field has been changed, make sure they are correct
				//check the 2 passwords
				if($post_user_data['password'] != $post_user_data['password2']){
					$this->events['fail'] = 1;
					$form->validation_errors[$params->get('password2')] = 'Passwords do NOT match.';
					$form->debug[] = "Couldn't create/update user, Passwords do NOT match.";
					return false;
				}
				//chek old password
				if((bool)$params->get('enable_old_password', 0) === true){
					//print_r2($user);
					$parts = explode(":", $user->get('password'));
					$salt = $parts[1];
					$enc_pass = md5($post_user_data['old_password'].$salt).":".$salt;
					if($enc_pass != $user->get('password')){
						$this->events['fail'] = 1;
						$form->validation_errors[$params->get('old_password')] = 'Wrong password entered.';
						$form->debug[] = "Old password has been entered incorrectly.";
						return false;
					}else{
						//check if the password is empty
						if(!trim($post_user_data['password']) || !trim($post_user_data['password2'])){
							$this->events['fail'] = 1;
							$form->validation_errors[$params->get('password')] = 'Please enter a new password.';
							return false;
						}
					}
				}
				
			}else{
				$form->data = $form->set_array_value($form->data, explode('.', $params->get('old_password', '')), null);
				$form->data = $form->set_array_value($form->data, explode('.', $params->get('password', '')), null);
				$form->data = $form->set_array_value($form->data, explode('.', $params->get('password2', '')), null);
			}
		}
		// Bind the post array to the user object
		//$post_user_data = $form->data;
		if(!$user->bind($post_user_data)){
			//JError::raiseError( 500, $user->getError());
			$this->events['fail'] = 1;
			$form->validation_errors[] = $user->getError();
			$form->debug[] = "Couldn't bind new user, Joomla returned this error : ".$user->getError();
			return false;
		}

		if($params->get('function', 0) == 0){
			$user->set('groups', $defaultUserGroup);
			// Set some initial user values
			if(!isset($post_user_data['id']) || empty($post_user_data['id'])){
				$user->set('id', 0);
				$user->set('usertype', 'deprecated');
				//$user->set('groups', $defaultUserGroup);

				$date = JFactory::getDate();
				$user->set('registerDate', $date->toMySQL());
			}else{
				$user->set('id', (int)$post_user_data['id']);
				if($noPassword === true){
					$user->set('password', null);
				}
			}			
		}
		// If there was an error with registration, set the message and display form
		if(!$user->save()){
			/*JError::raiseWarning('', JText::_( $user->getError()));
			$this->register();*/
			$this->events['fail'] = 1;
			$form->validation_errors[] = $user->getError();
			$form->debug[] = "Couldn't save user, Joomla returned this error : ".$user->getError();
			return false;
		}else{
			$this->events['success'] = 1;
		}
		//store user data
		$user_data = (array)$user;
		$removes = array('params', '_params', 'guest', '_errorMsg', '_errors');
		foreach($removes as $remove){
			unset($user_data[$remove]);
		}
		$form->data['_PLUGINS_']['joomla_user_save'] = $user_data;
		//inject user data under the correct data path
		if(strlen(trim($params->get('user_data_path', ''))) > 0){
			$form->data = $form->set_array_value($form->data, explode('.', trim($params->get('user_data_path', ''))), $user_data);
		}
	}
	
	function load($clear){
		if($clear){
			$action_params = array(
				'content1' => '',
				'name' => '',
				'username' => '',
				'email' => '',
				'password' => '',
				'password2' => '',
				'old_password' => '',
				'user_id' => '',
				'new_usertype' => '',
				'new_usertype_field' => '',
				'function' => 0,
				'enable_old_password' => 0,
				'user_data_path' => 'User',
			);
		}
		return array('action_params' => $action_params);
	}
}
?>