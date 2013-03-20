<?php
/**
* @version		1.0.0
* @package		MijoSEF Library
* @subpackage	Controller
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license		GNU/GPL based on AceSEF www.joomace.net
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

if (!class_exists('MijosoftController')) {
	if (interface_exists('JController')) {
		abstract class MijosoftController extends JControllerLegacy {}
	}
	else {
		class MijosoftController extends JController {}
	}
}

class MijosefController extends MijosoftController {
	
	public $_mainframe;
	public $_option;
	public $_context;
	public $_table;
	public $_model;
	
	function __construct($context = '', $table = '') 	{
		parent::__construct();
		
		$this->_mainframe =& JFactory::getApplication();
		if ($this->_mainframe->isAdmin()) {
			$this->_option = JAdministratorHelper::findOption();
		} else {
			$this->_option = JRequest::getCmd('option');
		}
		$this->_context = $context;
		
		$this->_table = $table;
		if ($this->_table == '') {
			$this->_table = $this->_context;
		}
		
		$this->_model =& $this->getModel($context);
		$this->MijosefConfig = Mijosef::getConfig();
		
		// Register tasks
		$this->registerTask('add', 'edit');
	}
	
	function display() {
		parent::display();
	}

	// Display
	function view() {
		$view = $this->getView(ucfirst($this->_context), 'html');
		$view->setModel($this->_model, true);
		$view->view();
	}
	
	// Edit
	function edit() {
		JRequest::setVar('hidemainmenu', 1);
		
		$view = $this->getView(ucfirst($this->_context), 'edit');
		$view->setModel($this->_model, true);
		$view->edit('edit');
	}
	
	// Delete
	function delete() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Action
		if (!self::deleteRecord($this->_table, $this->_model)) {
			$msg = JText::_('COM_MIJOSEF_COMMON_RECORDS_DELETED_NOT');
		} else {
			$msg = JText::_('COM_MIJOSEF_COMMON_RECORDS_DELETED');
		}
		
		// Return
		self::route($msg);
	}
	
	// Publish
	function publish() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Action
		self::updateField($this->_table, 'published', 1, $this->_model);
		
		// Return
		self::route();
	}
	
	// Unpublish
	function unpublish() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Action
		self::updateField($this->_table, 'published', 0, $this->_model);
		
		// Return
		self::route();
	}
	
    function backup() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		Mijosef::get('utility')->import('library.backuprestore');
		
		// Get where
		$where = self::_getWhere($this->_model);
		
		$class = new MijosefBackupRestore(array('_table' => $this->_table, '_where' => $where));
		$function = 'backup' . ucfirst($this->_context);
		
		list($query, $filename, $fields, $line) = $class->$function();
		
		// Action
		if (!Mijosef::get('utility')->backupDB($query, $filename, $fields, $line)){
			$msg = JText::_('COM_MIJOSEF_COMMON_RECORDS_BACKUPED_NOT');
		} else {
			$msg = JText::_('COM_MIJOSEF_COMMON_RECORDS_BACKUPED');
		}
		
		// Return
		return $msg;
    }
	
	function route($msg = ""){
		if ($msg != "") {
			parent::setRedirect('index.php?option='.$this->_option.'&controller='.$this->_context.'&task=view', $msg);
		} else {
			parent::setRedirect('index.php?option='.$this->_option.'&controller='.$this->_context.'&task=view');
		}
	}
	
	// Save changed record
	function saveRecord($post, $table, $id = 0) {
		// Get row
		$row = Mijosef::getTable($table);
		
		// Bind the form fields to the table
		if (!$row->bind($post)) {
			return JError::raiseWarning(500, $row->getError());
		}

		// Make sure the record is valid
		if (!$row->check()) {
			return JError::raiseWarning(500, $row->getError());
		}
		
		// Save record
		if (!$row->store()) {
			return JError::raiseWarning(500, $row->getError());
		}
		
		return true;
	}
	
	function deleteRecord($table, $model, $where = true) {
		if ($where === true) {
			$where = self::_getWhere($model);
		}
		
		if (!MijoDatabase::query("DELETE FROM #__mijosef_{$table}{$where}")) {
			return false;
		}

		return true;
    }
	
	// Save changes
	function editSave() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Get post
		$post = JRequest::get('post');
		
		// Save record
		$table = 'Mijosef' . ucfirst($this->_context);
		if (!self::saveRecord($post, $table, $post['id'])) {
			return JError::raiseWarning(500, JText::_('COM_MIJOSEF_COMMON_RECORD_SAVED_NOT'));
		} else {
			if ($post['modal'] == '1') {
				// Display message
				JFactory::getApplication()->enqueueMessage(JText::_('COM_MIJOSEF_COMMON_RECORD_SAVED'));
			} else {
				// Return
				self::route(JText::_('COM_MIJOSEF_COMMON_RECORD_SAVED'));
			}
		}
	}
	
	// Apply changes
	function editApply() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Get post
		$post = JRequest::get('post');
		
		// Save record
		$table = 'Mijosef' . ucfirst($this->_context);
		if (!self::saveRecord($post, $table, $post['id'])) {
			return JError::raiseWarning(500, JText::_('COM_MIJOSEF_COMMON_RECORD_SAVED_NOT'));
		} else {
			if ($post['modal'] == '1') {
				// Return
				$this->setRedirect('index.php?option='.$this->_option.'&controller='.$this->_context.'&task=edit&cid[]='.$post['id'].'&tmpl=component', JText::_('COM_MIJOSEF_COMMON_RECORD_SAVED'));
			} else {
				// Return
				$this->setRedirect('index.php?option='.$this->_option.'&controller='.$this->_context.'&task=edit&cid[]='.$post['id'], JText::_('COM_MIJOSEF_COMMON_RECORD_SAVED'));
			}
		}
	}
	
	// Cancel changes
	function editCancel() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Get vars
		$modal = JRequest::getVar('modal', 0, 'method', 'int');
		
		if ($modal == '1') {
			// Display message
			JFactory::getApplication()->enqueueMessage(JText::_('COM_MIJOSEF_COMMON_RECORD_SAVED_NOT'));
		} else {
			// Return
			self::route(JText::_('COM_MIJOSEF_COMMON_RECORD_SAVED_NOT'));
		}
	}
	
	// Update field
	function updateField($table, $field, $value, $model, $where = true) {
		if ($where === true) {
			$where = self::_getWhere($model);
		}
		
		if (!MijoDatabase::query("UPDATE #__mijosef_{$table} SET {$field} = '{$value}' {$where}")) {
			return false;
		}

		return true;
	}
	
	// Update param
	function updateParam($table, $table_m, $field, $param, $value, $model, $where = true) {
		if (!$ids = self::_getIDs($table, $model, $where)) {
			return;
		}
		
		$row = Mijosef::getTable($table_m);
		
		if (!empty($ids) && is_array($ids)) {
            Mijosef::get('utility')->import('library.parameter');

			foreach ($ids as $index => $id) {
				if (!$row->load($id)) {
					continue;
				}
				
				$params = new JRegistry($row->$field);
				$params->set($param, $value);
				
				$row->$field = $params->toString();
				
				if (!$row->check()) {
					continue;
				}
				
				if (!$row->store()) {
					continue;
				}
			}
		}
	}
	
	// Update cache
	function updateCache($table, $index, $fields, $value, $model, $where = true) {
		if ($where === true) {
			$where = self::_getWhere($model);
		}
		
		if (!$records = MijoDatabase::loadObjectList("SELECT {$fields} FROM #__mijosef_{$table} {$where}", $index)) {
			return false;
		}
		
		$cache = Mijosef::getCache();
		$cached_records = $cache->load($table);
		
		foreach ($records as $i => $record) {
			if ($value == 1) {
				unset($cached_records[$i]);
				$cached_records[$i] = $record;
			}
			elseif ($value == 0) {
				unset($cached_records[$i]);
			}
		}
		
		$cache->save($cached_records, $table);

		return true;
	}
	
	// Get IDs
	function _getIDs($table, $model, $where = true) {
		if ($where === true) {
			$where = self::_getWhere($model);
		}
		
		if (!$ids = MijoDatabase::loadResultArray("SELECT id FROM #__mijosef_{$table} {$where}")) {
			return false;
		}
		
		return $ids;
	}
    
    function _getWhere($model, $prefix = "") {
        $where = '';
		
        $sel = JRequest::getVar('selection', 'selected', 'post');
        if ($sel == 'selected') {
            $where = self::_buildSelectedWhere($prefix);
        } elseif ($sel == 'filtered') {
            $where = $model->_buildViewWhere($prefix);
        }
        
        return $where;
    }
	
	// Get the id's of selected records
	function _buildSelectedWhere($prefix = "") {
		$cid = JRequest::getVar('cid', array(), 'post', 'array');
		JArrayHelper::toInteger($cid);
		
		$where = '';
		if(count($cid) > 0){
			$where = " WHERE {$prefix}id IN (".implode(", ", $cid).")";
		}

		return $where;
	}
}