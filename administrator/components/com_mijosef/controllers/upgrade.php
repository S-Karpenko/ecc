<?php
/**
* @package		MijoSEF
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No permission
defined('_JEXEC') or die('Restricted Access');

// Controller Class
class MijosefControllerUpgrade extends MijosefController {

	// Main constructer
	function __construct() {
        if (!JFactory::getUser()->authorise('upgrade', 'com_mijosef')) {
            return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
        }

		parent::__construct('upgrade');
	}
	
	// Upgrade
    function upgrade() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Upgrade
        if ($this->_model->upgrade()) {
            $msg = JText::_('COM_MIJOSEF_UPGRADE_SUCCESS');
        }
        else {
            $msg = '';
        }
		
		// Return
		$this->setRedirect('index.php?option=com_mijosef&controller=upgrade&task=view', $msg);
    }
}