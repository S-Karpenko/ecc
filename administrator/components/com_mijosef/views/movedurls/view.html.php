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
class MijosefViewMovedUrls extends MijosefView {

	// View
	function view($tpl = null) {
		$toolbar = $this->get('ToolbarSelections');
		$this->type = JFactory::getApplication()->getUserStateFromRequest('com_mijosef.urls.type', 'type', 'moved');
		
		// Toolbar
		JToolBarHelper::title(JText::_('COM_MIJOSEF_COMMON_URLS' ), 'mijosef');
		$this->toolbar->appendButton('Popup', 'new1', JText::_('New'), 'index.php?option=com_mijosef&controller=movedurls&task=add&tmpl=component', 600, 340);
		JToolBarHelper::custom('edit', 'edit1.png', 'edit1.png', JText::_('Edit'), true, true);
		JToolBarHelper::divider();
		JToolBarHelper::spacer();
		$this->toolbar->appendButton('Custom', $toolbar->action);
		$this->toolbar->appendButton('Custom', $toolbar->selection);
		$this->toolbar->appendButton('Custom', $toolbar->button);
		JToolBarHelper::divider();
		$this->toolbar->appendButton('Popup', 'cache', JText::_('COM_MIJOSEF_CACHE_CLEAN'), 'index.php?option=com_mijosef&amp;controller=purgeupdate&amp;task=cache&amp;tmpl=component', 300, 380);
		JToolBarHelper::divider();
		$this->toolbar->appendButton('Popup', 'help1', JText::_('Help'), 'http://www.mijosoft.com/support/docs/mijosef/user-manual/urls?tmpl=component', 650, 500);
		
		// Get behaviors
		JHTML::_('behavior.modal', 'a.modal', array('onClose'=>'\function(){location.reload(true);}'));
	
		// Footer colspan
		$colspan = 4;
		if ($this->MijosefConfig->ui_moved_published == 1) {
			$colspan = $colspan + 1;
		}
		if ($this->MijosefConfig->ui_moved_hits == 1) {
			$colspan = $colspan + 1;
		}
		if ($this->MijosefConfig->ui_moved_clicked == 1) {
			$colspan = $colspan + 1;
		}
		if ($this->MijosefConfig->ui_moved_cached == 1) {
			$colspan = $colspan + 1;
			$this->assignRef('cache', $this->get('Cache'));
		}
		if ($this->MijosefConfig->ui_moved_id == 1) {
			$colspan = $colspan + 1;
		}
		
		// Get data from the model
		$this->assignRef('lists',		$this->get('Lists'));
		$this->assignRef('items',		$this->get('Items'));
		$this->assignRef('pagination',	$this->get('Pagination'));
		$this->assignRef('colspan',		$colspan);
		
		parent::display($tpl);
	}
}
?>