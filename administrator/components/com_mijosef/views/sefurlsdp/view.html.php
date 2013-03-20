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
class MijosefViewSefUrlsDp extends MijosefView {

	// View URLs
	function view($tpl = null) {
		// Get data from the model
		$this->assignRef('lists',		$this->get('Lists'));
		$this->assignRef('items',		$this->get('Items'));
		$this->assignRef('pagination',	$this->get('Pagination'));
		$this->assignRef('toolbar',		$this->get('ToolbarSelections'));
		$this->assignRef('sef',			$this->get('SefUrl'));

		parent::display($tpl);
	}
}
?>