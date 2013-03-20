<?php
/**
* @version		1.0.0
* @package		MijoSEF
* @subpackage	MijoSEF
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license		GNU/GPL based on AceSEF www.joomace.net
*/

// No permission
defined('_JEXEC') or die('Restricted Access');

// Controller Class
class MijosefControllerConfig extends MijosefController {

	// Main constructer
 	function __construct() {
        if (!JFactory::getUser()->authorise('core.admin', 'com_mijosef')) {
            return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
        }

		parent::__construct('config');
	}
	
	// Save changes
	function save() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		$this->_model->save();
		
		$this->setRedirect('index.php?option=com_mijosef', JTEXT::_('COM_MIJOSEF_CONFIG_SAVED'));
	}
	
	// Apply changes
	function apply() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		$this->_model->save();
		
		$this->setRedirect('index.php?option=com_mijosef&controller=config&task=edit', JTEXT::_('COM_MIJOSEF_CONFIG_SAVED'));
	}
	
	// Cancel saving changes
	function cancel() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		$this->setRedirect('index.php?option=com_mijosef', JTEXT::_('COM_MIJOSEF_CONFIG_NOT_SAVED'));
	}
}
?>