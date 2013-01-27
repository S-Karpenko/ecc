<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
class CfactionJoomlaUserActivation extends JObject{
	var $formname;
	var $formid;
	var $group = array('id' => 'joomla_functions', 'title' => 'Joomla Functions');
	var $events = array('success' => 0, 'fail' => 0);
	var $details = array('title' => 'Joomla User Activation', 'tooltip' => 'Activate a Joomla user account through token.');
	function run($form, $actiondata){
		$params = new JParameter($actiondata->params);
		$mainframe = JFactory::getApplication();
		
		$user = JFactory::getUser();
		$uParams = JComponentHelper::getParams('com_users');
		$language = JFactory::getLanguage();
        $language->load('com_users');
		// If the user is logged in, return them back to the homepage.
		if ($user->get('id')) {
			$mainframe->redirect('index.php');
			return true;
		}

		// If user registration or account activation is disabled, throw a 403.
		if (($uParams->get('useractivation') == 0 || $uParams->get('allowUserRegistration') == 0) && !$params->get('override_allow_user_registration', 0)) {
			JError::raiseError(403, JText::_('JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN'));
			return false;
		}
		
		$token = JRequest::getVar('token', null, 'request', 'alnum');
		// Check that the token is in a valid format.
		if ($token === null || strlen($token) !== 32) {
			JError::raiseError(403, JText::_('JINVALID_TOKEN'));
			return false;
		}
		
		// Attempt to activate the user.
		$return = $this->activate($token);

		// Check for errors.
		if ($return === false) {
			// Redirect back to the homepage.
			JError::raiseWarning(100, JText::sprintf('COM_USERS_REGISTRATION_SAVE_FAILED', $this->getError()));
			$this->events['fail'] = 1;
			if((bool)$params->get('allow_redirects', 0) === true){
				$mainframe->redirect('index.php');
			}
			return false;
		}

		$useractivation = $uParams->get('useractivation');

		// Redirect to the login screen.
		if ($useractivation == 0){
			$mainframe->enqueueMessage(JText::_('COM_USERS_REGISTRATION_SAVE_SUCCESS'));
			$this->events['success'] = 1;
			if((bool)$params->get('allow_redirects', 0) === true){
				$mainframe->redirect(JRoute::_('index.php?option=com_users&view=login', false));
			}
		}elseif ($useractivation == 1){
			$mainframe->enqueueMessage(JText::_('COM_USERS_REGISTRATION_ACTIVATE_SUCCESS'));
			$this->events['success'] = 1;
			if((bool)$params->get('allow_redirects', 0) === true){
				$mainframe->redirect(JRoute::_('index.php?option=com_users&view=login', false));
			}
		}elseif ($return->getParam('activate')){
			$mainframe->enqueueMessage(JText::_('COM_USERS_REGISTRATION_VERIFY_SUCCESS'));
			$this->events['success'] = 1;
			if((bool)$params->get('allow_redirects', 0) === true){
				$mainframe->redirect(JRoute::_('index.php?option=com_users&view=registration&layout=complete', false));
			}
		}else{
			$mainframe->enqueueMessage(JText::_('COM_USERS_REGISTRATION_ADMINACTIVATE_SUCCESS'));
			$this->events['success'] = 1;
			if((bool)$params->get('allow_redirects', 0) === true){
				$mainframe->redirect(JRoute::_('index.php?option=com_users&view=registration&layout=complete', false));
			}
		}
		return true;
		
		
		/*if((int)$params->get('auto_login', 0) == 1){
			$credentials = array();
			$credentials['username'] = $form->data['username'];
			$credentials['password'] = $form->data['password'];
			$mainframe->login($credentials);
		}*/
	}
	
	function activate($token){
		$config	= JFactory::getConfig();
		$userParams	= JComponentHelper::getParams('com_users');
		$db	= JFactory::getDBO();
		
		// Get the user id based on the token.
		$db->setQuery(
			'SELECT `id` FROM `#__users`' .
			' WHERE `activation` = '.$db->Quote($token) .
			' AND `block` = 1' .
			' AND `lastvisitDate` = '.$db->Quote($db->getNullDate())
		);
		$userId = (int) $db->loadResult();

		// Check for a valid user id.
		if (!$userId) {
			$this->setError(JText::_('COM_USERS_ACTIVATION_TOKEN_NOT_FOUND'));
			return false;
		}

		// Load the users plugin group.
		JPluginHelper::importPlugin('user');

		// Activate the user.
		$user = JFactory::getUser($userId);

		// Admin activation is on and user is verifying their email
		if (($userParams->get('useractivation') == 2) && !$user->getParam('activate', 0))
		{
			$uri = JURI::getInstance();
			jimport('joomla.user.helper');

			// Compile the admin notification mail values.
			$data = $user->getProperties();
			$data['activation'] = JUtility::getHash(JUserHelper::genRandomPassword());
			$user->set('activation', $data['activation']);
			$data['siteurl']	= JUri::base();
			$base = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
			$data['activate'] = $base.JRoute::_('index.php?option=com_users&task=registration.activate&token='.$data['activation'], false);
			$data['fromname'] = $config->get('fromname');
			$data['mailfrom'] = $config->get('mailfrom');
			$data['sitename'] = $config->get('sitename');
			$user->setParam('activate', 1);
			$emailSubject	= JText::sprintf(
				'COM_USERS_EMAIL_ACTIVATE_WITH_ADMIN_ACTIVATION_SUBJECT',
				$data['name'],
				$data['sitename']
			);

			$emailBody = JText::sprintf(
				'COM_USERS_EMAIL_ACTIVATE_WITH_ADMIN_ACTIVATION_BODY',
				$data['sitename'],
				$data['name'],
				$data['email'],
				$data['username'],
				$data['siteurl'].'index.php?option=com_users&task=registration.activate&token='.$data['activation']
			);

			// get all admin users
			$query = 'SELECT name, email, sendEmail' .
						' FROM #__users' .
						' WHERE sendEmail=1';

			$db->setQuery( $query );
			$rows = $db->loadObjectList();

			// Send mail to all superadministrators id
			foreach( $rows as $row ){
				$return = JUtility::sendMail($data['mailfrom'], $data['fromname'], $row->email, $emailSubject, $emailBody);

				// Check for an error.
				if ($return !== true) {
					$this->setError(JText::_('COM_USERS_REGISTRATION_ACTIVATION_NOTIFY_SEND_MAIL_FAILED'));
					return false;
				}
			}
		}

		//Admin activation is on and admin is activating the account
		else if (($userParams->get('useractivation') == 2) && $user->getParam('activate', 0)){
			$user->set('activation', '');
			$user->set('block', '0');

			$uri = JURI::getInstance();
			jimport('joomla.user.helper');

			// Compile the user activated notification mail values.
			$data = $user->getProperties();
			$user->setParam('activate', 0);
			$data['fromname'] = $config->get('fromname');
			$data['mailfrom'] = $config->get('mailfrom');
			$data['sitename'] = $config->get('sitename');
			$data['siteurl']	= JUri::base();
			$emailSubject	= JText::sprintf(
				'COM_USERS_EMAIL_ACTIVATED_BY_ADMIN_ACTIVATION_SUBJECT',
				$data['name'],
				$data['sitename']
			);

			$emailBody = JText::sprintf(
				'COM_USERS_EMAIL_ACTIVATED_BY_ADMIN_ACTIVATION_BODY',
				$data['name'],
				$data['siteurl'],
				$data['username']
			);

			$return = JUtility::sendMail($data['mailfrom'], $data['fromname'], $data['email'], $emailSubject, $emailBody);

			// Check for an error.
			if ($return !== true) {
				$this->setError(JText::_('COM_USERS_REGISTRATION_ACTIVATION_NOTIFY_SEND_MAIL_FAILED'));
				return false;
			}
		}
		else
		{
			$user->set('activation', '');
			$user->set('block', '0');
		}

		// Store the user object.
		if (!$user->save()) {
			$this->setError(JText::sprintf('COM_USERS_REGISTRATION_ACTIVATION_SAVE_FAILED', $user->getError()));
			return false;
		}
		
		return $user;		
	}
		
	function load($clear){
		if($clear){
			$action_params = array(
				'content1' => '',
				'override_allow_user_registration' => 1,
				'allow_redirects' => 0,
			);
		}
		return array('action_params' => $action_params);
	}
}
?>