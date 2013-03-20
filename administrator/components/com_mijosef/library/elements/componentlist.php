<?php
/**
 * @package		MijoSEF
 * @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// No Permission
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.form.formfield');

require_once(JPATH_ADMINISTRATOR.'/components/com_mijosef/library/mijosef.php');

class JFormFieldComponentList extends JFormField {

	protected $type = 'ComponentList';

	protected function getInput() {
		// Construct the various argument calls that are supported
		$attribs = 'class="inputbox" multiple="multiple" size="15"';

		$filter = Mijosef::get('utility')->getSkippedComponents();
        $rows = MijoDatabase::loadResultArray("SELECT `element` FROM `#__extensions` WHERE `type` = 'component' AND `element` NOT IN ({$filter}) ORDER BY `element`");

        $lang = JFactory::getLanguage();

        $options = array();
        $options[] = array('option' => 'all', 'name' => JText::_('- All Components -'));

		foreach ($rows as $row){
            $lang->load($row.'.sys', JPATH_ADMINISTRATOR);
			$options[] = array('option' => $row, 'name' => JText::_($row));
		}
		
		return JHTML::_('select.genericlist', $options, $this->name, $attribs, 'option', 'name', $this->value, $this->name);
	}
}