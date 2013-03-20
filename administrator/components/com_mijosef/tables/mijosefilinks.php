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

class TableMijosefIlinks extends JTable {

	var $id 	 	= null;
	var $word 		= null;
	var $link 		= null;
	var $published	= null;
	var $nofollow	= null;
	var $iblank		= null;
	var $ilimit		= null;

	function __construct(&$db) {
		parent::__construct('#__mijosef_ilinks', 'id', $db);
	}
}