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
class MijosefViewMetadata extends MijosefView {

	// Edit URL
	function edit($tpl = null) {
		// Get data from model
		$model =& $this->getModel();
		$row = $model->getEditData('MijosefMetadata');
		
		// Toolbar
		JToolBarHelper::title(JText::_('COM_MIJOSEF_COMMON_METADATA').': '.$row->url_sef, 'mijosef');
		JToolBarHelper::custom('editSave', 'save1.png', 'save1.png', JTEXT::_('Save'), false);
		JToolBarHelper::custom('editApply', 'apply1.png', 'apply1.png', JTEXT::_('Apply'), false);
		JToolBarHelper::custom('editCancel', 'cancel1.png', 'cancel1.png', JTEXT::_('Cancel'), false);
		JToolBarHelper::divider();
		$this->toolbar->appendButton('Popup', 'help1', JText::_('Help'), 'http://www.mijosoft.com/support/docs/mijosef/user-manual/metadata?tmpl=component', 650, 500);
		
		// Get jQuery
		if ($this->MijosefConfig->jquery_mode == 1) {
			$this->document->addScript('components/com_mijosef/assets/js/jquery-1.4.2.min.js');
			$this->document->addScript('components/com_mijosef/assets/js/jquery.bgiframe.min.js');
			$this->document->addScript('components/com_mijosef/assets/js/jquery.autocomplete.js');
		}
		
		// Assign values
		$this->assignRef('row', $row);
		
		parent::display($tpl);
	}
	
	function getSefURL($id) {
		$url = "";
		
		if (is_numeric($id)) {
			$url = MijoDatabase::loadResult("SELECT url_sef FROM #__mijosef_sitemap WHERE id = {$id}");
		}
		
		return $url;
	}
}
?>