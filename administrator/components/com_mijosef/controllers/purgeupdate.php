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
class MijosefControllerPurgeUpdate extends MijosefController {
	
	// Main constructer
	function __construct() {
        if (!JFactory::getUser()->authorise('purgeupdate', 'com_mijosef')) {
            return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
        }

        parent::__construct('purgeupdate');
    }
	
	// Cache
	function cache() {
		$view = $this->getView('PurgeUpdate', 'cache');
		$view->setModel($this->_model, true);
		$view->display('cache');
	}
	
	// Update function
    function deleteUpdate() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Get buttons
		$deleteURLs		= JRequest::getVar('deleteurls', 0, 'post');
		$updateURLs 	= JRequest::getVar('updateurls', 0, 'post');
		$deleteMeta 	= JRequest::getVar('deletemeta', 0, 'post');
		$updateMeta 	= JRequest::getVar('updatemeta', 0, 'post');
		
		// Get model
		$model =& $this->getModel('PurgeUpdate');
		
		// Delete URLs
		if ($deleteURLs) {
			if ($model->deleteURLs()) {
				JFactory::getApplication()->enqueueMessage(JText::_('COM_MIJOSEF_PURGE_PURGED'));
			} else {
				JError::raiseWarning(500, JText::_('COM_MIJOSEF_PURGE_NOT_PURGED'));
			}
		}
		
		// Update URLs
		if ($updateURLs) {
			$count = "";
			$count = $model->updateURLs();
			JFactory::getApplication()->enqueueMessage(JText::_('COM_MIJOSEF_COMMON_UPDATED_URLS').' '.$count);
		}
		
		// Delete Meta
		if ($deleteMeta) {
			if ($model->deleteUpdateMeta('delete')) {
				JFactory::getApplication()->enqueueMessage(JText::_('COM_MIJOSEF_PURGE_PURGED'));
			} else {
				JError::raiseWarning(500, JText::_('COM_MIJOSEF_PURGE_NOT_PURGED'));
			}
		}
		
		// Update Meta
		if ($updateMeta) {
			$model->deleteUpdateMeta('update');
			JFactory::getApplication()->enqueueMessage(JText::_('COM_MIJOSEF_COMMON_UPDATED_META'));
		}
    }
	
	// Show cache
	function cleanCache() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		if (!$this->_model->cleanCache()) {
			return JError::raiseWarning(500, JText::_('COM_MIJOSEF_CACHE_CLEANED_NOT'));
		} else {
			JFactory::getApplication()->enqueueMessage(JText::_('COM_MIJOSEF_CACHE_CLEANED'));
		}
	}
}
?>