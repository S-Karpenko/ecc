<?php
/**
* @version		1.0.0
* @package		MijoSEF Library
* @subpackage	View
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license		GNU/GPL based on AceSEF www.joomace.net
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

// Imports
jimport('joomla.application.component.view');

if (!class_exists('MijosoftView')) {
	if (interface_exists('JView')) {
		abstract class MijosoftView extends JViewLegacy {}
	}
	else {
		class MijosoftView extends JView {}
	}
}

class MijosefView extends MijosoftView {

	public $toolbar;
	public $document;
	
	function __construct() 	{
		parent::__construct();
		
		// Get toolbar object
		$this->toolbar =& JToolBar::getInstance();
		
		// Import CSS
		$this->document =& JFactory::getDocument();
		$this->document->addStyleSheet('components/com_mijosef/assets/css/mijosef.css');
		
		// Get config object
		$this->MijosefConfig = Mijosef::getConfig();
	}
	
	function getIcon($i, $task, $img) {
		$html = '<a href="javascript:void(0);" onclick="return listItemTask(\'cb'.$i.'\',\''.$task.'\')">';
		$html .= '<img src="components/com_mijosef/assets/images/'.$img.'" border="0" />';
		$html .= '</a>';
		
		return $html;
	}
}