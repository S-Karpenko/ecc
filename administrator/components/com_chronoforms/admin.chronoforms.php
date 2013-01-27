<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
/* ensure that this file is not called from another file */
defined('_JEXEC') or die('Restricted access');
require_once(JApplicationHelper::getPath('admin_html')); 
require_once(JApplicationHelper::getPath('class'));

$jversion = new JVersion();
require_once(JPATH_SITE.DS."administrator".DS."components".DS."com_chronoforms".DS."controller.php");
require_once(JPATH_SITE.DS."administrator".DS."components".DS."com_chronoforms".DS."versions".DS."j".$jversion->RELEASE.DS."admin.chronoforms.php");
?>
<?php
$task = JRequest::getString('task');
$option = strtolower(JRequest::getCmd('option'));
//clean the $_POST data
$_POST = JRequest::get('post', JREQUEST_ALLOWRAW);
// case differentiation
switch($task){
	case "form_wizard":
	case "apply_wizard_changes":
		ChronoFormsAdmin::form_wizard($task);
		break;
	case "wizard_preview":
		ChronoFormsAdmin::wizard_preview();
		break;
	case "remove":
		ChronoFormsAdmin::delete_form();
		break;
	case "copy":
		ChronoFormsAdmin::copy_form();
		break;
	case "add":
	case "edit":
		ChronoFormsAdmin::edit_form();
		break;
	case "save":
	case "apply":
		ChronoFormsAdmin::save_form($task);
		break;
	case "create_table":
	case "save_table":
		ChronoFormsAdmin::create_table($task);
		break;
	case "list_data":
		ChronoFormsAdmin::list_data($task);
		break;
	case "show_data":
		ChronoFormsAdmin::show_data($task);
		break;
	case "delete_data":
		ChronoFormsAdmin::delete_data($task);
		break;
	case "publish":
	case "unpublish":
		ChronoFormsAdmin::publish($task);
		break;
	case "validatelicense":
		ChronoFormsAdminVersion::validatelicense($task);
		break;
	case "backup_forms":
		ChronoFormsAdmin::backup_forms();
		break;
	case "restore_forms":
		ChronoFormsAdmin::restore_forms();
		break;
	case "install_action":
		ChronoFormsAdmin::install_action();
		break;
	case "updates":
		ChronoFormsAdmin::updates();
		break;
	case "action_task":
		ChronoFormsAdmin::action_task();
		break;
	case "admin_form":
		ChronoFormsAdmin::admin_form();
		break;
	default:
		if(strpos($task, ":") !== false){
			$details = explode(":", $task);
			JRequest::setVar('task', $details[0]);
			JRequest::setVar('event', $details[1]);
			ChronoFormsAdmin::admin_form();
			break;
		}
		ChronoFormsAdmin::index();
		//delete any temp forms
		//ChronoFormsAdmin::delete_temp();
		break;
}

//define the print_r2 function
function print_r2($array = array()){
	echo '<pre>';
	print_r($array);
	echo '</pre>';
}
?>