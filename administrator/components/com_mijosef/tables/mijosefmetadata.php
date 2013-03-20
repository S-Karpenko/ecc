<?php
/**
* @version		1.0.0
* @package		MijoSEF
* @subpackage	MijoSEF
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license		GNU/GPL based on AceSEF www.joomace.net
*/

// No Permission
defined('JPATH_BASE') or die('Restricted Access');

class TableMijosefMetadata extends JTable {

	var $id 	 		= null;
	var $url_sef 		= null;
	var $published		= null;
	var $title 			= null;
	var $description	= null;
	var $keywords		= null;
	var $lang			= null;
	var $robots			= null;
	var $googlebot		= null;
	var $canonical		= null;

	function __construct(&$db) {
		parent::__construct('#__mijosef_metadata', 'id', $db);
	}
}