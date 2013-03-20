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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

class AcepollsController extends JController {

	function display() {
		//Set the default view, just in case
		$view = JRequest::getCmd('view');
		if (empty($view)) {
			JRequest::setVar('view', 'polls');
		}
		
		parent::display();
	}

	function vote()	{
		// Check for request forgeries
		JRequest::checkToken() or jexit('Invalid Token');

		$mainframe 	= JFactory::getApplication();
		$poll_id	= JRequest::getInt('id', 0);
		$option_id	= JRequest::getInt('voteid', 0);
		$poll 		=& JTable::getInstance('Poll', 'Table');
		
		if (!$poll->load($poll_id) || $poll->published != 1) {
			JError::raiseWarning(404, JText::_('ALERTNOTAUTH'));
			return;
		}

        $model = $this->getModel('Poll');

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
		}
		else {
			if ($model->vote($poll_id, $option_id)) {
				//Set cookie showing that user has voted
				setcookie($cookieName, '1', time() + 60*$poll->lag);
            }

			$msg = JText::_('COM_ACEPOLLS_THANK_YOU');
			$tom = "";
			
			if (JFactory::getUser()->id != 0) {
				JPluginHelper::importPlugin('acepolls');
				$dispatcher =& JDispatcher::getInstance();
				$dispatcher->trigger('onAfterVote', array($poll, $option_id));
			}
		}

		// set Itemid id for links
		$menu	 = &JSite::getMenu();
		$items   = $menu->getItems('link', 'index.php?option=com_acepolls');

		$itemid  = isset($items[0]) ? '&Itemid='.$items[0]->id : '';

		$this->setRedirect(JRoute::_('index.php?option=com_acepolls&view=poll&id='. $poll_id.':'.$poll->alias.$itemid, false), $msg, $tom);
	}
}
?>