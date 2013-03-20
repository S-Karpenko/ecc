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
class MijosefViewSupport extends MijosefView {

	function display($tpl = null) {
		// Toolbar
		JToolBarHelper::title(JText::_('COM_MIJOSEF_COMMON_SUPPORT'), 'mijosef');		
		JToolBarHelper::back(JText::_('Back'), 'index.php?option=com_mijosef');
		
		if (JRequest::getCmd('task', '') == 'translators') {
			$this->document->setCharset('iso-8859-9');
		}
		
		parent::display($tpl);
	}
}