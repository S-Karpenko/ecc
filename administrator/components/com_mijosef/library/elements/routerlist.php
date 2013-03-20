<?php
/**
* @package		MijoSEF
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.form.formfield');

class JFormFieldRouterList extends JFormField {

	protected $type = 'RouterList';

	function getInput() {
		$attribs = 'class="inputbox"';
		
        $extension = Mijosef::get('utility')->getExtensionFromRequest();
		
		$options = Mijosef::get('utility')->getRouterList($extension);

		return JHTML::_('select.genericlist', $options, $this->name, $attribs, 'value', 'text', $this->value, $this->name);
	}
}