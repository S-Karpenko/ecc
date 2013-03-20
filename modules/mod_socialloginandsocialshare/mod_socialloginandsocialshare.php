<?php
/**
 * @version		$Id: mod_SocialLoginAndSocialShare.php 1.4 16 Team LoginRadius
 * @copyright	Copyright (C) 2011 - till Open Source Matters. All rights reserved.
 * @license		GNU/GPL
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';
$params->def('greeting', 1);
$type = modSocialLoginAndSocialShareHelper::getType();
$lr_settings = modSocialLoginAndSocialShareHelper::sociallogin_getSettings();
$return = modSocialLoginAndSocialShareHelper::getReturnURL($params, $type);
$user = JFactory::getUser();
require JModuleHelper::getLayoutPath('mod_socialloginandsocialshare', $params->get('layout', 'default'));
