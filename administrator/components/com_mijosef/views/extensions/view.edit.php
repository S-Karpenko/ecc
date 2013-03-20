<?php
/**
* @package		MijoSEF
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die('Restricted Access');

// Imports
Mijosef::get('utility')->import('library.elements.routerlist');
Mijosef::get('utility')->import('library.elements.categorylist');
JLoader::register('JHtmlSelect', JPATH_MIJOSEF_ADMIN.'/library/joomla/select.php');
JLoader::register('JElementRadio', JPATH_MIJOSEF_ADMIN.'/library/joomla/radio.php');
JLoader::register('JElementSpacer', JPATH_MIJOSEF_ADMIN.'/library/joomla/spacer.php');

// Edit Extension View Class
class MijosefViewExtensions extends MijosefView {

	// Edit extension
	function edit($tpl = NULL) {
		$row = $this->getModel()->getEditData('MijosefExtensions');
		
		$ext_form = JForm::getInstance('extensionForm', JPATH_MIJOSEF_ADMIN.'/extensions/'.$row->extension.'.xml', array(), true, 'config');
		$ext_values = array('params' => json_decode($row->params));
		$ext_form->bind($ext_values);
		
		$default_form = JForm::getInstance('commonForm', JPATH_MIJOSEF_ADMIN.'/extensions/default_params.xml', array(), true, 'config');
		$default_values = array('params' => json_decode($row->params));
		$default_form->bind($default_values);
		
		$row->description = '';
		$row->hasCats = 0;

		$xml_file = JPATH_MIJOSEF_ADMIN.'/extensions/'.$row->extension.'.xml';
		if (file_exists($xml_file)) {
			$row->description = Mijosef::get('utility')->getXmlText($xml_file, 'description');
			$row->hasCats = (int) Mijosef::get('utility')->getXmlText($xml_file, 'hasCats');
		}

		// Get behaviors
		JHTML::_('behavior.combobox');
		JHTML::_('behavior.tooltip');

		// Assign data
		$this->assignRef('row', 				$row);
		$this->assignRef('ext_params', 			$ext_form);
		$this->assignRef('default_params', 		$default_form);

		parent::display($tpl);
	}
}