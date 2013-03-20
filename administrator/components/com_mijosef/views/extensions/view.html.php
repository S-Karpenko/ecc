<?php
/**
* @version		1.0.0
* @package		MijoSEF
* @subpackage	MijoSEF
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license		GNU/GPL based on AceSEF www.joomace.net
*/

// No Permission
defined('_JEXEC') or die('Restricted Access');

// View Class
class MijosefViewExtensions extends MijosefView {

	// Display extensions
	function view($tpl = null) {
		$toolbar = $this->get('ToolbarSelections');
		
		// Toolbar
		JToolBarHelper::title(JText::_('COM_MIJOSEF_COMMON_EXTENSIONS'), 'mijosef');
		$this->toolbar->appendButton('Confirm', JText::_('COM_MIJOSEF_EXTENSIONS_VIEW_BTN_REMOVE_WARN'), 'uninstall', JText::_('Uninstall'), 'uninstall', true, false);
		JToolBarHelper::custom('save', 'save1.png', 'save1.png', JText::_('Save'), false);
		JToolBarHelper::divider();
		JToolBarHelper::spacer();
		$this->toolbar->appendButton('Custom', $toolbar->action);
		$this->toolbar->appendButton('Custom', $toolbar->selection);
		$this->toolbar->appendButton('Custom', $toolbar->button);
		JToolBarHelper::divider();
		$this->toolbar->appendButton('Popup', 'cache', JText::_('COM_MIJOSEF_CACHE_CLEAN'), 'index.php?option=com_mijosef&amp;controller=purgeupdate&amp;task=cache&amp;tmpl=component', 300, 380);
		JToolBarHelper::divider();
		$this->toolbar->appendButton('Popup', 'help1', JText::_('Help'), 'http://www.mijosoft.com/support/docs/mijosef/user-manual/extensions?tmpl=component', 650, 500);
		
		// Get behaviors
		JHTML::_('behavior.tooltip');
		JHTML::_('behavior.modal', 'a.modal', array('onClose'=>'\function(){location.reload(true);}'));
		
		// Get data from the model
		$this->assignRef('lists',		$this->get('Lists'));
		$this->assignRef('info',		$this->get('Info'));
		$this->assignRef('params',		$this->get('Params'));
		$this->assignRef('items',		$this->get('Items'));
		$this->assignRef('pagination',	$this->get('Pagination'));

		parent::display($tpl);
	}
}
?>