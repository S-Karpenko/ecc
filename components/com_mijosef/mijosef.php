<?php
/**
* @version		1.0.0
* @package		MijoSEF
* @subpackage	MijoSEF
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU GPL
*/

//No Permision
defined('_JEXEC') or die('Restricted access');

$lang =& JFactory::getLanguage();
$lang->load('com_mijosef', JPATH_SITE, 'en-GB', true);
$lang->load('com_mijosef', JPATH_SITE, $lang->getDefault(), true);
$lang->load('com_mijosef', JPATH_SITE, null, true);

// Get controller
if($controller = JRequest::getCmd('view')) {
	$path = JPATH_COMPONENT.'/controllers/'.$controller.'.php';
	if(file_exists($path)) {
	    require_once($path);
	} else {
	    $controller = '';
	}
}

$classname  = 'MijosefController'.$controller;
$controller = new $classname();

// Perform the Request task
$controller->execute(JRequest::getCmd('view'));

// Redirect if set by the controller
$controller->redirect();