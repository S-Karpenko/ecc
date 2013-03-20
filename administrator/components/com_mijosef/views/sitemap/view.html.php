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
class MijosefViewSitemap extends MijosefView {

	// View URLs
	function view($tpl = null) {
		JToolBarHelper::title(JText::_('COM_MIJOSEF_COMMON_SITEMAP'), 'mijosef');
		parent::display($tpl);
	}
}
?>