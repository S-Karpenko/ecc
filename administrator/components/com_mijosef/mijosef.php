<?php
/**
* @package		MijoSEF
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

// Access check
if (!JFactory::getUser()->authorise('core.manage', 'com_mijosef')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

JHTML::_('behavior.framework');

$lang =& JFactory::getLanguage();
$lang->load('com_mijosef', JPATH_ADMINISTRATOR, 'en-GB', true);
$lang->load('com_mijosef', JPATH_ADMINISTRATOR, $lang->getDefault(), true);
$lang->load('com_mijosef', JPATH_ADMINISTRATOR, null, true);

require_once(JPATH_ADMINISTRATOR.'/components/com_mijosef/library/mijosef.php');

JLoader::register('MijosefController', JPATH_MIJOSEF_LIB.'/controller.php');
JLoader::register('MijosefModel', JPATH_MIJOSEF_LIB.'/model.php');
JLoader::register('MijosefView', JPATH_MIJOSEF_LIB.'/view.php');

require_once(JPATH_MIJOSEF_ADMIN.'/toolbar.php');

JTable::addIncludePath(JPATH_MIJOSEF_ADMIN.'/tables');

// Get controller
if($controller = JRequest::getCmd('controller')) {
	$path = JPATH_COMPONENT.'/controllers/'.$controller.'.php';
	if(file_exists($path)) {
	    require_once($path);
	} else {
	    $controller = '';
	}
}

$class_name = 'MijosefController'.$controller;

$controller = new $class_name();
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();