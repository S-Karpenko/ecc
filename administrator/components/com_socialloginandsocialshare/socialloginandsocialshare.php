<?php
/**
 * @package   	SocialLogin
 * @copyright 	Copyright 2012 http://www.loginradius.com - All rights reserved.
 * @license   	GNU/GPL 2 or later
 */
defined ('_JEXEC') or die ('Direct Access to this location is not allowed.');
jimport ('joomla.application.component.controller');

// Get an instance of the controller
$controller = JController::getInstance ('SocialLoginAndSocialShare');

// Perform the requested task
$controller->execute (JRequest::getCmd ('task', 'display'));

// Redirect if set by the controller
$controller->redirect ();
