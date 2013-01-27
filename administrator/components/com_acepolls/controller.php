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

jimport('joomla.application.component.controller');

class AcepollsController extends JController {
	
	function __construct($default = array()) {
		parent::__construct($default);

		$this->registerTask('apply', 		'save');
		$this->registerTask('unpublish', 	'publish');
		$this->registerTask('deleteVotes', 	'deleteVotes');
		$this->registerTask('importPolls', 	'importPolls');
	}

	function display() {
		$controller = JRequest::getVar('controller', 'polls');
		JRequest::setVar('view', $controller);

		parent::display();
	}
	
	function add() {
		JRequest::setVar('view', 'poll');
		JRequest::setVar('layout', 'edit');
		JRequest::setVar('edit', false);
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}
	
	function edit() {
		JRequest::setVar('view', 'poll');
		JRequest::setVar('layout', 'edit');
		JRequest::setVar('edit', true);
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}
	
	function preview() {
		JRequest::setVar('tmpl', 'component');
		JRequest::setVar('view', 'poll');

		parent::display();
	}

	function save() {
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		$db	=& JFactory::getDBO();

		// save the apoll parent information
		$row =& JTable::getInstance('Poll', 'Table');
		
		$post = JRequest::get('post');
		if (!$row->bind($post)) {
			JError::raiseError(500, $row->getError());
		}
		
		$isNew = ($row->id == 0);

		//reset the poll, erases hits and voters
		if ($optionReset = JRequest::getVar('reset')) {
			$model =& $this->getModel('polls');
			$model->resetVotes((int)$row->id);
		}
		
		if (!$row->check()) {
			JError::raiseError(500, $row->getError());
		}

		if (!$row->store()) {
			JError::raiseError(500, $row->getError());
		}
		$row->checkin();
		
		// put all poll options and their colors and ordering in arrays
		$options 	= JArrayHelper::getValue($post, 'polloption', array(), 'array');
		$colors 	= JArrayHelper::getValue($post, 'color', array(), 'array');
		$orderings	= JArrayHelper::getValue($post, 'ordering', array(), 'array');
	
		//options represented by id=>text
 		foreach ($options as $i => $text) {
			// turns ' into &#039;
			$text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

			if ($isNew) {
				if ($text != ''){
					$obj = new stdClass();
					$obj->poll_id  = (int)$row->id;
					$obj->text     = $text;
					$obj->color    = substr($colors[$i], -6);
					$obj->ordering = $orderings[$i];
					$db->insertObject('#__acepolls_options', $obj);
				}
			} 
			else {
				if ($text != ''){
					$obj = new stdClass();
					$obj->id  	  	= (int)$i;
					$obj->text 	   	= $text;
					$obj->color	   	= substr($colors[$i], -6);
					$obj->ordering	= $orderings[$i];
					$db->updateObject('#__acepolls_options', $obj, 'id');
				}
				else {
					//If there are empty options delete them so we don't waste database space
					$model =& $this->getModel('poll');
					if (!$model->deleteOption($i)) {
						JError::raiseError(500, $model->getError());
					}	
				}
			}
		}
		
		// Are there any new options that are added
		if (JRequest::getVar('is_there_extra')) {
			$extra_options	 = JArrayHelper::getValue($post, 'polloptionextra', array(), 'array');
			$extra_ordering	 = JArrayHelper::getValue($post, 'extra_ordering', array(), 'array');
			$extra_colors 	 = JArrayHelper::getValue($post, 'extra_colors', array(), 'array');
			
			//Insert in the database the newly created options
			foreach ($extra_options as $k => $text) {
				if ($text != ''){
					$obj = new stdClass();
					$obj->poll_id  = (int)$row->id;
					$obj->text     = $text;
					$obj->color    = substr($extra_colors[$k], -6);
					$obj->ordering = $extra_ordering[$k];
					$db->insertObject('#__acepolls_options', $obj);	
				}				
			}
		}
		
		switch (JRequest::getCmd('task')) {
			case 'apply':
				$msg = JText::_('COM_ACEPOLLS_POLL_SAVED');
				$link = 'index.php?option=com_acepolls&controller=poll&task=edit&cid[]='.$row->id;
				break;
			case 'save':
			default:
				$msg = JText::_('COM_ACEPOLLS_POLL_SAVED');
				$link = 'index.php?option=com_acepolls';
				break;
		}

		$this->setRedirect($link, $msg);
	}

	function remove() {
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		$db	=& JFactory::getDBO();
		$cid = JRequest::getVar('cid', array(), '', 'array');

		JArrayHelper::toInteger($cid);
		$msg = '';

		for ($i=0, $n=count($cid); $i < $n; $i++) {
			$apoll =& JTable::getInstance('poll', 'Table');
			if (!$apoll->delete( $cid[$i] )) {
				$msg .= $apoll->getError();
				$tom = "error";
			}
			else {
				$msg = JTEXT::_('COM_ACEPOLLS_POLL_DELETED');
				$tom = "";
			}
		}
		
		$this->setRedirect('index.php?option=com_acepolls', $msg, $tom);
	}
	
	function deleteVotes() {
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );
		
		$poll_id = JRequest::getVar('poll_id', 0, 'POST', 'INT');
		$model =& $this->getModel('votes');
	
		if($model->deleteVotes()) {
			$msg = Jtext::_("COM_ACEPOLLS_DELETED_VOTES_YES");
			$tom = "";
		} else {
			$msg = Jtext::_("COM_ACEPOLLS_DELETED_VOTES_NO");
			$tom = "error";
		}
		
		$this->setRedirect('index.php?option=com_acepolls&controller=votes&task=view&id='.$poll_id, $msg, $tom);
	}

	function publish() {
		$mainframe = JFactory::getApplication();

		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		$user =& JFactory::getUser();
		
		$cid = JRequest::getVar( 'cid', array(), '', 'array' );
		$publish = (JRequest::getCmd('task') == 'publish' ? 1 : 0);
		
		$table =& JTable::getInstance('poll', 'Table');
		JArrayHelper::toInteger($cid);

		if (!$table->publish($cid, $publish, $user->get('id'))) {
			$table->getError();
		}

		if (count($cid ) < 1) {
			$action = $publish ? 'publish' : 'unpublish';
			JError::raiseError(500, JText::_('Select an item to' .$action, true));
		}

		$mainframe->redirect('index.php?option=com_acepolls');
	}

	function cancel() {
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$id	= JRequest::getVar('id', 0, '', 'int');
		$row =& JTable::getInstance('poll', 'Table');

		$row->checkin($id);
		
		$this->setRedirect( 'index.php?option=com_acepolls' );
	}

	function resetVotes() {
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');
		
		$model =& $this->getModel('polls');
		
		if ($model->resetVotes()) {
			$msg = Jtext::_("COM_ACEPOLLS_DELETED_POLL_VOTES_YES");
			$tom = "";
		}
		else {
			$msg = Jtext::_("VCOM_ACEPOLLS_DELETED_POLL_VOTES_NO");
			$tom = "error";
		}
		
		$this->setRedirect('index.php?option=com_acepolls&controller=polls', $msg, $tom);
	}
}