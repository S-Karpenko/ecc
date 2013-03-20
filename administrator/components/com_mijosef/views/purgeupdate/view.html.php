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
class MijosefViewPurgeUpdate extends MijosefView {

	// Display purge
	function view($tpl = null) {
		// Get data from the model
		$this->assignRef('urls', $this->get('CountUrls'));
		$this->assignRef('meta', $this->get('CountMeta'));

		parent::display($tpl);
	}
}
