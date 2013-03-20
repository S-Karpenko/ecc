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
class MijosefViewConfig extends MijosefView {

	// Edit configuration
	function edit($tpl = null) {		
		// Toolbar
		JToolBarHelper::title(JText::_('COM_MIJOSEF_COMMON_CONFIGURATION'), 'mijosef');
		JToolBarHelper::custom('save', 'save1.png', 'save1.png', JText::_('Save'), false);
		JToolBarHelper::custom('apply', 'apply1.png', 'apply1.png', JText::_('Apply'), false);
		JToolBarHelper::custom('cancel', 'cancel1.png', 'cancel1.png', JText::_('Cancel'), false);
		JToolBarHelper::divider();
		$this->toolbar->appendButton('Popup', 'purgeupdate', JText::_('COM_MIJOSEF_COMMON_PURGEUPDATE'), 'index.php?option=com_mijosef&amp;controller=purgeupdate&amp;task=view&amp;tmpl=component', 470, 320);
		$this->toolbar->appendButton('Popup', 'cache', JText::_('COM_MIJOSEF_CACHE_CLEAN'), 'index.php?option=com_mijosef&amp;controller=purgeupdate&amp;task=cache&amp;tmpl=component', 300, 380);
		JToolBarHelper::divider();
		$this->toolbar->appendButton('Popup', 'help1', JText::_('Help'), 'http://www.mijosoft.com/support/docs/mijosef/user-manual/configuration?tmpl=component', 650, 500);

		// Get behaviors
  		JHTML::_('behavior.mootools');
		JHTML::_('behavior.tooltip');

		// Import Editor
		$editor = JFactory::getEditor();
		
		if ($this->MijosefConfig->sm_auto_cron_last == "") {
			$this->MijosefConfig->sm_auto_cron_last = time();
		}

        $permissions = JForm::getInstance('permissions', JPATH_MIJOSEF_ADMIN.'/permissions.xml', array(), true, 'config');
        $permissions->bind(array('permissions' => json_decode($this->get('Permissions'))));
		
		// Get data from the model
		$this->editor = $editor;
		$this->lists = $this->get('Lists');
		$this->permissions = $permissions;

		parent::display($tpl) ;
	}
}
?>