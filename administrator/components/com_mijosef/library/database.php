<?php
/**
* @package		MijoSEF
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license		GNU/GPL based on AceSEF www.joomace.net
*/

// No Permission
defined('_JEXEC') or die('Restricted Access');

// Database class, extends JDatabase
abstract class MijoDatabase {

	protected static $_dbo;

	public static function getDBO() {
		if (!isset(self::$_dbo)) {
			self::$_dbo = JFactory::getDBO();
		}
	}
	
	//
	// Quote
	//
	public function quote($text, $escaped = true) {
		self::getDBO();
		$result = self::$_dbo->Quote($text, $escaped);
		
		self::showError();
	
		return $result;
	}
	
	//
	// Escape
	//
	public function getEscaped($text, $extra = false) {
		self::getDBO();

		$result = self::$_dbo->escape($text, $extra);

		self::showError();
	
		return $result;
	}
	
	//
	// Run
	//
	public function query($query) {
		// Run query
		self::getDBO();
		self::$_dbo->setQuery($query);
		$result = self::$_dbo->query();
		
		self::showError();
	
		return $result;
	}
	
	//
	// Single value result
	//
	public function loadResult($query) {
		// Run query
		self::getDBO();
		self::$_dbo->setQuery($query);
		$result = self::$_dbo->loadResult();
		
		self::showError();

		return $result;
	}
	
	//
	// Single row results
	//
	public function loadRow($query) {
		// Run query
		self::getDBO();
		self::$_dbo->setQuery($query);
		$result = self::$_dbo->loadRow();
		
		self::showError();

		return $result;
	}
	
	public function loadAssoc($query) {
		// Run query
		self::getDBO();
		self::$_dbo->setQuery($query);
		$result = self::$_dbo->loadAssoc();
		
		self::showError();

		return $result;
	}
	
	public function loadObject($query) {
		// Run query
		self::getDBO();
		self::$_dbo->setQuery($query);
		$result = self::$_dbo->loadObject();
		
		self::showError();

		return $result;
	}
	
	//
	// Single column results
	//
	public function loadResultArray($query, $index = 0) {
		// Run query
		self::getDBO();
		self::$_dbo->setQuery($query);

		$result = self::$_dbo->loadColumn($index);

		self::showError();

		return $result;
	}

	//
	// Multi-Row results
	//
	public function loadRowList($query, $offset = 0, $limit = 0) {
		// Run query
		self::getDBO();
		self::$_dbo->setQuery($query, $offset, $limit);
		$result = self::$_dbo->loadRowList();
		
		self::showError();

		return $result;
	}
	
	public function loadAssocList($query, $key = '', $offset = 0, $limit = 0) {
		// Run query
		self::getDBO();
		self::$_dbo->setQuery($query, $offset, $limit);
		$result = self::$_dbo->loadAssocList($key);
		
		self::showError();

		return $result;
	}

	public function loadObjectList($query, $key = '', $offset = 0, $limit = 0) {
		// Run query
		self::getDBO();
		self::$_dbo->setQuery($query, $offset, $limit);
		$result = self::$_dbo->loadObjectList($key);
		
		self::showError();

		return $result;
	}
	
	protected function showError() {
		if (Mijosef::getConfig()->show_db_errors == 1 && self::$_dbo->getErrorNum()) {
			throw new Exception(__METHOD__.' failed. ('.self::$_dbo->getErrorMsg().')');
		}
	}
}