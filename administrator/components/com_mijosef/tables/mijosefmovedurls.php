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

class TableMijosefMovedUrls extends JTable {

	var $id 	 	= null;
	var $url_new 	= null;
	var $url_old 	= null;
	var $published	= null;
	var $hits		= null;
	var $last_hit	= null;

	function __construct(&$db) {
		parent::__construct('#__mijosef_urls_moved', 'id', $db);
	}
}