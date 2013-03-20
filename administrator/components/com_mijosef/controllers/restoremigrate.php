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
class MijosefControllerRestoreMigrate extends MijosefController {

	// Main constructer
    function __construct() {
        if (!JFactory::getUser()->authorise('restoremigrate', 'com_mijosef')) {
            return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
        }

        parent::__construct('restoremigrate');
    }
	
	// Backup data
    function backup() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Backup
		if(!$this->_model->backup()){
			JError::raiseWarning(500, JText::_('COM_MIJOSEF_RESTOREMIGRATE_MSG_BACKUP_NO'));
		}
    }
    
	// Restore data
    function restore() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Restore
		if(!$this->_model->restore()){
			$msg = JText::_('COM_MIJOSEF_RESTOREMIGRATE_MSG_RESTORE_NO');
		} else {
			$msg = JText::_('COM_MIJOSEF_RESTOREMIGRATE_MSG_RESTORE_OK');
		}
		
		// Return
		parent::route($msg);
    }
}
?>