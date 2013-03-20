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

class TableMijosefTags extends JTable {

	var $id 	 		= null;
	var $title 			= null;
	var $alias 			= null;
	var $description	= null;
	var $published		= null;
	var $ordering		= null;
	var $hits			= null;

	function __construct(&$db) {
		parent::__construct('#__mijosef_tags', 'id', $db);
	}
	
	function check() {
		if (empty($this->alias)) {
			$this->alias = JFilterOutput::stringURLSafe($this->title);
		}
		
		return true;
	}
	
	function loadByTitle($title) {
		$k = $this->_tbl_key;

		if ($title !== null) {
			$this->$k = $title;
		}

		$title = $this->$k;

		if ($title === null) {
			return false;
		}

		$this->reset();

		$this->_db->setQuery('SELECT * FROM '.$this->_db->nameQuote($this->_tbl).' WHERE title = '.$this->_db->Quote($title));

		if ($result = $this->_db->loadAssoc()) {
			return $this->bind($result);
		}
		else {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
	}
}