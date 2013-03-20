<?php
/**
* @version		1.0.0
* @package		MijoSEF
* @subpackage	MijoSEF
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license		GNU/GPL based on AceSEF www.joomace.net
*/

// No permission
defined('_JEXEC') or die('Restricted Access');

// Controller Class
class MijosefControllerMetadata extends MijosefController {

	// Main constructer
	function __construct() 	{
        if (!JFactory::getUser()->authorise('metadata', 'com_mijosef')) {
            return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
        }

		parent::__construct('metadata');
	}
	
	// Apply changes
	function apply() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Save
		$this->_model->apply();
		
		// Return
		parent::route(JTEXT::_('COM_MIJOSEF_METADATA_SAVED'));
	}
	
	function generateMetadata() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
	
		// Action
		if ($this->_model->generateMetadata()) {
			$msg = JText::_('COM_MIJOSEF_METADATA_GENERATED_OK');
		} else {
			$msg = JText::_('COM_MIJOSEF_METADATA_GENERATED_NO');
		}
		
		// Return
		parent::route($msg);
	}
	
	function deleteEmptyReal() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		$where = parent::_getWhere($this->_model);
	
		// Action
		if ($this->_model->deleteEmptyReal($where)) {
			$msg = JText::_('COM_MIJOSEF_COMMON_RECORDS_DELETED');
		} else {
			$msg = JText::_('COM_MIJOSEF_COMMON_RECORDS_DELETED_NOT');
		}
		
		// Return
		parent::route($msg);
	}
	
	function clean() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Get where
		$where = parent::_getWhere($this->_model);
		
		$meta_fields = JRequest::getVar('fields', 'all', 'post');
		
		$fields = array();
		if ($meta_fields == 'all') {
			$fields[] = 'title';
			$fields[] = 'description';
			$fields[] = 'keywords';
		} else {
			$fields[] = $meta_fields;
		}
		
		// Action
		if (!Mijosef::get('metadata')->clean($where, $fields)) {
			$msg = JText::_('COM_MIJOSEF_METADATA_CLEANED_NO');
		} else {
			$msg = JText::_('COM_MIJOSEF_METADATA_CLEANED_OK');
		}
		
		// Return
		parent::route();
	}
	
	function update() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Get where
		$where = parent::_getWhere($this->_model, "m.");
		
		$meta_fields = JRequest::getVar('fields', 'all', 'post');
		
		$fields = array();
		if ($meta_fields == 'all') {
			$fields[] = 'title';
			$fields[] = 'description';
			$fields[] = 'keywords';
		} else {
			$fields[] = $meta_fields;
		}
		
		// Action
		$where = str_replace(' WHERE ', ' AND ', $where);
		$msg = Mijosef::get('metadata')->update($where, $fields);
		
		// Return
		parent::route($msg);
	}
	
	function cache() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
	
		// Action
		parent::updateCache($this->_context, 'url_sef', '*', 1, $this->_model);
		
		// Return
		parent::route();
	}
	
	function uncache() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
	
		// Action
		parent::updateCache($this->_context, 'url_sef', '*', 0, $this->_model);
		
		// Return
		parent::route();
	}
}
?>