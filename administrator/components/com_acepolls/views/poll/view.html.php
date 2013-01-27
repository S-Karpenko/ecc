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

jimport('joomla.html.pane');
jimport('joomla.application.component.view');

class AcepollsViewPoll extends JView {

	function display($tpl = null) {
		$document =& JFactory::getDocument();
  		$document->addStyleSheet('components/com_acepolls/assets/css/acepolls.css');
		
		$cid = JRequest::getVar('cid', array(0), '', 'array');
		$edit = JRequest::getVar('edit', true);
		$text = (($edit) ? JText::_('Edit') : JText::_('New'));

		JToolBarHelper::title(JText::_('COM_ACEPOLLS_POLL').': <small><small>[ '.$text.' ]</small></small>', 'acepolls');
		JToolBarHelper::Preview('index.php?option=com_acepolls&cid[]='.$cid[0]);
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
		
		$this->mainframe = JFactory::getApplication();
		$user =& JFactory::getUser();
		
		$row = $this->get('ItemData');

		// fail if checked out not by 'me'
		if ($row->isCheckedOut($user->get('id'))) {
			$msg = JText::sprintf('DESCBEINGEDITTED', JText::_('COM_ACEPOLLS_THE_POLL'), $row->title);
			$this->setRedirect('index.php?option=com_acepolls', $msg);
		}

		if ($row->id == 0) {
			// defaults
			$row->published	= 1;
		}

		$options = array();
		$ordering = array();

		if ($edit) {
			$options = $row->getOptions($row->id);
		}
		else {
			$row->lag = 24*60;
		}
		
		//default colors for slices
		$colors = array("ff0000","ffff99","00ccff","66ff99","ffcc00","d7ebff","ccffcc", "cccccc", "ffff00", "006699", "660000", "ffddee");

		$this->assignRef('row',		$row);
		$this->assignRef('options',	$options);
		$this->assignRef('params',	$this->get('Form'));
		$this->assignRef('color',	$colors);
		$this->assignRef('edit',	$edit);
		
		parent::display($tpl);
	}
}