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
		$this->mainframe = JFactory::getApplication();
		$this->option = JRequest::getCmd('option');

		$filter_order		= $this->mainframe->getUserStateFromRequest($this->option.'.polls.filter_order',		'filter_order',			'm.title',	'string');
		$filter_order_Dir	= $this->mainframe->getUserStateFromRequest($this->option.'.polls.filter_order_Dir',	'filter_order_Dir',		'',			'word');
		$search				= $this->mainframe->getUserStateFromRequest($this->option.'.polls.search',				'search',				'',			'string');

		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;

		// search filter
		$lists['search'] = $search;
		
		JHTML::_('behavior.tooltip');
		
		$menu = JSite::getMenu()->getActive();
		
		$menu_params = new JRegistry();
		$menu_params->loadJSON($menu->params);
		$params = clone($this->mainframe->getParams());
		$params->merge($menu_params);
		
		
		$this->assignRef('lists',		$lists);
		$this->assignRef('params',		$params);
		$this->assignRef('items',		$this->get('Data'));
		$this->assignRef('pagination',	$this->get('Pagination'));
		
		parent::display($tpl);
	}
}