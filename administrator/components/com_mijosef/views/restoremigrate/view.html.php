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

// Import View Class
class MijosefViewRestoreMigrate extends MijosefView {

	// View
	function view($tpl = null) {		
		// Toolbar
		JToolBarHelper::title(JText::_('COM_MIJOSEF_COMMON_RESTOREMIGRATE'), 'mijosef');
		$this->toolbar->appendButton('Popup', 'help1', JText::_('Help'), 'http://www.mijosoft.com/support/docs/mijosef/user-manual/restore-migrate?tmpl=component', 650, 500);
		
		parent::display($tpl);
	}
}