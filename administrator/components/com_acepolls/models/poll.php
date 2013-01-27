<?php
/**
* @version		1.0.0
* @package		AcePolls
* @subpackage	AcePolls
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*
* Based on Apoll Component
* @copyright (C) 2009 - 2011 Hristo Genev All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @link http://www.afactory.org
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.modeladmin');

class AcepollsModelPoll extends JModelAdmin {

	var $_id = null;
	var $_data = null;	
	
	function __construct()	{
		parent::__construct();

		$array = JRequest::getVar('cid', array(0), '', 'array');
		$edit = JRequest::getVar('edit', true);
		
		if ($edit) {
			$this->setId((int)$array[0]);
		}
	}
	
	function setId($id)	{
		$this->_id		= $id;
		$this->_data	= null;
	}
	
	function isCheckedOut($uid = 0) {
		if ((boolean)$this->_data)	{
			if ($uid) {
				return ($this->_data->checked_out && $this->_data->checked_out != $uid);
			}
			else {
				return $this->_data->checked_out;
			}
		}
	}
	
	function checkin() {
		if ($this->_id) {
			$poll = & $this->getTable();
			
			if (!$poll->checkin($this->_id)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		
		return false;
	}
	
	function checkout($uid = null) {
		if ($this->_id) {
			// Make sure we have a user id to checkout the article with
			if (is_null($uid)) {
				$user	=& JFactory::getUser();
				$uid	= $user->get('id');
			}
			
			// Lets get to it and checkout the thing...
			$poll =& $this->getTable();
			
			if(!$poll->checkout($uid, $this->_id)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}

			return true;
		}
		
		return false;
	}
	
	function getItemData() {
		static $row;
		
		if (!isset($row)) {
			$row =& JTable::getInstance('Poll', 'Table');
			$row->load($this->_id);
		}
		
		return $row;
	}
	
	public function getForm($data = array(), $loadData = true) {
		// Get the form.
		$form = $this->loadForm('com_acepolls.poll', 'poll', array('control' => 'params', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}
	
	protected function loadFormData() {
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_acepolls.edit.poll.data', array());

		if (empty($data)) {
			$row = self::getItemData();
			
			if ($row->id != 0) {
				$registry = new JRegistry;
				$registry->loadJSON($row->params);
				$data = $registry->toArray();
			}
		}

		return $data;
	}
	
	function store($data) {
		$row =& $this->getTable();
		
		// Bind the form fields to the web link table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Create the timestamp for the date
		$row->date = gmdate('Y-m-d H:i:s');

		// Make sure the web link table is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}
	
	//delete empty options to clear the db
	function deleteOption($id) {
		$query = "DELETE FROM ".$this->_db->nameQuote('#__acepolls_options')." WHERE ".$this->_db->nameQuote('id')."=".$this->_db->Quote((int)$id)." LIMIT 1";
		$this->_db->setQuery($query);
		
		if (!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		return true;
	}
}
?>