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
class MijosefViewSefUrls extends MijosefView {

	// View URLs
	function view($tpl = null) {
		$toolbar = $this->get('ToolbarSelections');
		
	    $this->type = JFactory::getApplication()->getUserStateFromRequest('com_mijosef.urls.type', 'type', 'sef');
		
		// Toolbar
		JToolBarHelper::title(JText::_('COM_MIJOSEF_COMMON_URLS' ), 'mijosef');
		if ($this->type == 'notfound') {
			$this->toolbar->appendButton('Popup', 'new1', JText::_('Create 301'), 'index.php?option=com_mijosef&controller=movedurls&task=add&tmpl=component', 600, 340);
			JToolBarHelper::divider();
		}
		elseif ($this->type != 'trashed') {
			$this->toolbar->appendButton('Popup', 'new1', JText::_('New'), 'index.php?option=com_mijosef&controller=sefurls&task=add&tmpl=component', 700, 500);
			JToolBarHelper::custom('edit', 'edit1.png', 'edit1.png', JText::_('Edit'), true, true);
			JToolBarHelper::divider();
		}
		
		if ($this->type != 'trashed') {
			if ($this->type == "quickedit") {
				$tpl = "quickedit";
				JToolBarHelper::custom('apply', 'apply1.png', 'apply1.png', JText::_('Apply'), false);
			}
			
			if ($this->type != 'notfound') {
				$this->toolbar->appendButton('Popup', 'generateurls', JText::_('COM_MIJOSEF_TOOLBAR_GENERATE_URLS'), 'index.php?option=com_mijosef&controller=sefurls&task=generate&tmpl=component', 400, 220);
			}
			JToolBarHelper::spacer();
		}
		$this->toolbar->appendButton('Custom', $toolbar->action);
		$this->toolbar->appendButton('Custom', $toolbar->newtags . $toolbar->newilinks . $toolbar->newbookmarks . $toolbar->newtag);
		$this->toolbar->appendButton('Custom', $toolbar->selection);
		$this->toolbar->appendButton('Custom', $toolbar->button);
		JToolBarHelper::divider();
		$this->toolbar->appendButton('Popup', 'cache', JText::_('COM_MIJOSEF_CACHE_CLEAN'), 'index.php?option=com_mijosef&amp;controller=purgeupdate&amp;task=cache&amp;tmpl=component', 300, 380);
		JToolBarHelper::divider();
		$this->toolbar->appendButton('Popup', 'help1', JText::_('Help'), 'http://www.mijosoft.com/support/docs/mijosef/user-manual/urls?tmpl=component', 650, 500);
		
		// Get behaviors
		JHTML::_('behavior.tooltip');
		JHTML::_('behavior.modal', 'a.modal', array('onClose'=>'\function(){location.reload(true);}'));
		
		// Footer colspan
		$colspan = 5;
		if ($this->MijosefConfig->ui_sef_published == 1) {
			$colspan = $colspan + 1;
		}
		if ($this->MijosefConfig->ui_sef_used == 1) {
			$colspan = $colspan + 1;
		}
		if ($this->MijosefConfig->ui_sef_locked == 1) {
			$colspan = $colspan + 1;
		}
		if ($this->MijosefConfig->ui_sef_blocked == 1) {
			$colspan = $colspan + 1;
		}
		if ($this->MijosefConfig->ui_sef_cached == 1) {
			$colspan = $colspan + 1;
			$this->assignRef('cache', $this->get('Cache'));
		}
		if ($this->MijosefConfig->ui_sef_date == 1) {
			$colspan = $colspan + 2;
		}
		if ($this->MijosefConfig->ui_sef_hits == 1) {
			$colspan = $colspan + 1;
		}
		if ($this->MijosefConfig->ui_sef_id == 1) {
			$colspan = $colspan + 1;
		}
		
		// Get jQuery
		/*if ($this->MijosefConfig->jquery_mode == 1) {
			$this->document->addScript('components/com_mijosef/assets/js/jquery-1.4.2.min.js');
			$this->document->addScript('components/com_mijosef/assets/js/jquery.bgiframe.min.js');
			$this->document->addScript('components/com_mijosef/assets/js/jquery.autocomplete.js');
		}*/
		
		// Get data from the model
		$this->assignRef('lists',		$this->get('Lists'));
		$this->assignRef('items',		$this->get('Items'));
		$this->assignRef('duplicates',	$this->get('Duplicates'));
		$this->assignRef('pagination',	$this->get('Pagination'));
		$this->assignRef('colspan',		$colspan);

		parent::display($tpl);
	}
}
?>