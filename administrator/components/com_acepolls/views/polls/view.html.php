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

jimport( 'joomla.application.component.view');

class AcepollsViewPolls extends JView {

	function display($tpl = null) {
		$document =& JFactory::getDocument();
  		$document->addStyleSheet('components/com_acepolls/assets/css/acepolls.css');
		
		JToolBarHelper::title(JText::_('COM_ACEPOLLS_POLLS'), 'acepolls');
		JToolBarHelper::addNewX();
		JToolBarHelper::editListX();
		JToolBarHelper::deleteList();
		JToolBarHelper::divider();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::divider();
		JToolBarHelper::custom('resetVotes', 'cancel.png', 'cancel.png', JText::_('COM_ACEPOLLS_RESET_VOTES'), true, false);
		JToolBarHelper::preferences('com_acepolls');
	
		$this->mainframe = JFactory::getApplication();
		$this->option = JRequest::getWord('option');

		$filter_order		= $this->mainframe->getUserStateFromRequest($this->option.'.polls.filter_order',		'filter_order',		'm.title',	'string');
		$filter_order_Dir	= $this->mainframe->getUserStateFromRequest($this->option.'.polls.filter_order_Dir',	'filter_order_Dir',	'',			'word');
		$filter_state		= $this->mainframe->getUserStateFromRequest($this->option.'.polls.filter_state',		'filter_state',		'',			'word');
		$search				= $this->mainframe->getUserStateFromRequest($this->option.'.polls.search',				'search',			'',			'string');
		
		JHTML::_('behavior.tooltip');
		
		// state filter
		$lists['state']	= JHTML::_('grid.state', $filter_state);

		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;

		// search filter
		$lists['search']= $search;

		$this->assignRef('user',		JFactory::getUser());
		$this->assignRef('lists',		$lists);
		$this->assignRef('items',		$this->get('Data'));
		$this->assignRef('pagination',	$this->get('Pagination'));

		parent::display($tpl);
	}
}