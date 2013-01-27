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

class TablePoll extends JTable {

	public $id					= 0;
	public $title				= '';
	public $alias				= '';
	public $checked_out			= 0;
	public $checked_out_time	= 0;
	public $published			= 0;
	public $publish_up			= 0;
	public $publish_down		= 0;
	public $params 				= null;
	public $access				= 0;
	public $lag					= 0;

	function __construct(&$db) {
		parent::__construct('#__acepolls_polls', 'id', $db);
	}

	function bind($array, $ignore = '') {
		if (isset($array['params']) && is_array($array['params'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = (string)$registry;
		}
		
		return parent::bind($array, $ignore);
	}
	
	function check() {
		$mainframe = JFactory::getApplication();
		
		// check for valid name
		if (trim($this->title) == '') {
			$this->setError(JText::_('Your Poll must contain a title.'));
			return false;
		}
		
		// check for valid lag
		$this->lag = floatval($this->lag * 60);
		if ($this->lag == 0) {
			$this->setError(JText::_('Your Poll must have a non-zero lag time.'));
			return false;
		}

		if (empty($this->alias)) {
			$this->alias = $this->title;
		}
		
		$this->alias = JFilterOutput::stringURLSafe($this->alias);
		if (trim(str_replace('-','',$this->alias)) == '') {

			$datenow =& JFactory::getDate();
			$datenow->setOffset($mainframe->getCfg('offset'));
			$this->alias = $datenow->toFormat("%Y-%m-%d-%H-%M-%S");
		}

		return true;
	}

	// overloaded delete function
	function delete($oid=null) {
		$k = $this->_tbl_key;
		if ($oid) {
			$this->$k = intval($oid);
		}

		if (parent::delete($oid)) {
			$db =& JFactory::getDBO();
			
			$query = "DELETE FROM ".$db->nameQuote('#__acepolls_options')." WHERE ".$db->nameQuote('poll_id')."=".(int)$oid.";";
			$query .= "DELETE FROM ". $db->nameQuote('#__acepolls_votes')." WHERE ".$db->nameQuote('poll_id')."=".(int)$oid.";";
			
			$db->setQuery($query);
			$db->queryBatch($query);
			
			if (!$db->query()) {
				$this->_error .= $db->getErrorMsg() . "\n";
			}

			return true;
		}

		return false;
	}
	
	// function to get the options for current poll
	function getOptions($poll_id) {
		$query = "SELECT o.*, COUNT(v.id) AS hits"
		." FROM ".$this->_db->nameQuote('#__acepolls_options')." AS o"
		." LEFT JOIN ".$this->_db->nameQuote('#__acepolls_votes')." AS v"
		." ON (o.id = v.option_id AND v.poll_id = ".(int) $poll_id . ")"
		." WHERE o.poll_id = ".(int) $poll_id
		." AND text <> '' GROUP BY o.id ORDER BY o.ordering";
		
		$this->_db->setQuery($query);
		
		return $this->_db->loadObjectList();	
	}
}