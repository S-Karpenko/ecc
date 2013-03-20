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

jimport( 'joomla.application.component.model' );

class AcepollsModelAjaxvote extends JModel {

	var $_query = null;
	var $_data = null;
	var $_total = null;
	var $_voted = null;

	function getVoted() {
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		$mainframe 	= JFactory::getApplication();
		$poll_id	= JRequest::getInt('id', 0);
		$option_id	= JRequest::getInt('voteid', 0);
		$poll 		=& JTable::getInstance('Poll','Table');
		
		if (!$poll->load($poll_id) || $poll->published != 1) {
			$mainframe->redirect('index.php', JText::_('deneme'));
			//JError::raiseWarning(404, JText::_('ALERTNOTAUTH'));
			return;
		}

        require_once(JPATH_COMPONENT.'/models/poll.php');
		$model = new AcepollsModelPoll();

		$cookieName	= JUtility::getHash($mainframe->getName().'poll'.$poll_id);
		$voted_cookie = JRequest::getVar($cookieName, '0', 'COOKIE', 'INT');
        $voted_ip = $model->ipVoted($poll, $poll_id);
		
		if ($voted_cookie || $voted_ip || !$option_id) {
			if ($voted_cookie || $voted_ip) {
				$msg = JText::_('COM_ACEPOLLS_ALREADY_VOTED');
				$tom = "error";
			}
			
			if (!$option_id){
				$msg = JText::_('COM_ACEPOLLS_NO_SELECTED');
				$tom = "error";
			}
			
			$this->_voted = 0;
		}
		else {			
			if ($model->vote($poll_id, $option_id)) {
				$this->_voted = 1;
				//Set cookie showing that user has voted
				setcookie($cookieName, '1', time() + 60 * $poll->lag);
			}
			else {
				$this->_voted = 0;
			}
		}
		
		return $this->_voted = 1;
	}

	function getData() {
		if (empty($this->_data)) {
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query);
		}
		
		return $this->_data;
	}
	
	function getTotal() {
		if (empty($this->_total)) {
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}
		
		return $this->_total;
	}
	
	function _buildQuery() {
		if (empty($this->_query)) {
			$db	=& JFactory::getDBO();
			$poll_id = JRequest::getVar('id', 0, 'POST', 'int');
			
			$this->_query = "SELECT o.id, o.text, o.color, COUNT(v.id) AS votes" 
			." FROM ".$db->nameQuote('#__acepolls_options')." AS o "
			." LEFT JOIN ".$db->nameQuote('#__acepolls_votes')." AS v "
			." ON o.id = v.option_id "
			." WHERE o.poll_id = ".(int)$poll_id
			." GROUP BY o.id "
			;
		}
		
		return $this->_query;
	}
}