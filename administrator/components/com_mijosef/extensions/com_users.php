<?php
/*
* @package		MijoSEF
* @subpackage	User
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license		GNU/GPL based on AceSEF www.joomace.net
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

class MijoSEF_com_users extends MijosefExtension {
	
	function build(&$vars, &$segments, &$do_sef, &$metadata, &$item_limitstart) {
		// Extract variables
        extract($vars);
		
		if (isset($activation) || isset($return)) {
			$do_sef = false;
			return;
		}

		if (isset($view)) {
			$segments[] = $view;
			unset($vars['view']);
		}
		
		if (isset($task)) {
			switch($task) {
				case 'completereset':
				case 'requestreset':
				case 'remindusername':
				case 'confirmreset':
					$do_sef = false;
					return;
				default:
					$segments[] = $task;
					break;
			}
			unset($vars['task']);
		}
		
		$metadata = parent::getMetaData($vars, $item_limitstart);
		
		unset($vars['limit']);
		unset($vars['limitstart']);
	}
}
?>