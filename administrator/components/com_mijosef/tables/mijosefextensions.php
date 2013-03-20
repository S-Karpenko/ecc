<?php
/**
* @package		MijoSEF
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('JPATH_BASE') or die('Restricted Access');

class TableMijosefExtensions extends JTable {

	var $id 	 			= null;
	var $name				= null;
	var $extension			= null;
	var $params				= null;

	function __construct(& $db) {
		parent::__construct('#__mijosef_extensions', 'id', $db);
	}
	
	function bind($array) {
		if (is_array($array['params'])) {
            $params = new JRegistry($array['params'], true);

			$array['params'] = $params->toString();
		}
		
		return parent::bind($array);
	}
}