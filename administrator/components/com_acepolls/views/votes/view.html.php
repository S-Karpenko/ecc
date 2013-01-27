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
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class AcepollsViewVotes extends JView {

	function display($tpl = null) {
		$document =& JFactory::getDocument();
  		$document->addStyleSheet('components/com_acepolls/assets/css/acepolls.css');
		
		$title = $this->get('Title');
		
		$t_title = ($title) ? JText::_('COM_ACEPOLLS_VOTES_FOR').': '.$title : JText::_('COM_ACEPOLLS_SELECT_POLL');
		JToolBarHelper::title($t_title, 'acepolls');
		JToolBarHelper::deleteList(JText::_('COM_ACEPOLLS_DELETE_CONFIRM'), "deleteVotes", JText::_('COM_ACEPOLLS_DELETE'), true);
		JToolBarHelper::divider();
		JToolBarHelper::preferences('com_acepolls');

		$this->mainframe = JFactory::getApplication();
		$this->option = JRequest::getWord('option');

		$filter_order		= $this->mainframe->getUserStateFromRequest($this->option.'.votes.filter_order',		'filter_order',		'v.date',	'cmd');
		$filter_order_Dir	= $this->mainframe->getUserStateFromRequest($this->option.'.votes.filter_order_Dir',	'filter_order_Dir',	'',			'word');
		$search				= $this->mainframe->getUserStateFromRequest($this->option.'.votes.search',				'search',			'',			'string');
		
		// Get data from the model
		$lists =& $this->get('List');
		
		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;

		// search filter
		$lists['search']= $search;

		$this->assignRef('title',		$title);
		$this->assignRef('lists',		$lists);
		$this->assignRef('votes', 		$this->get('Data'));
		$this->assignRef('pagination',	$this->get('Pagination'));
		$this->assignRef('poll_id', 	JRequest::getInt('id', 0));
		
		parent::display($tpl);
	}
}
