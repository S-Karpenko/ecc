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
class MijosefControllerExtensions extends MijosefController {

	// Main constructer
	function __construct() 	{
        if (!JFactory::getUser()->authorise('extensions', 'com_mijosef')) {
            return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
        }

		parent::__construct('extensions');
	}

	// Display
	function view() {
		$this->_model->checkComponents();
		
		$view = $this->getView(ucfirst($this->_context), 'html');
		$view->setModel($this->_model, true);
		$view->view();
	}
	
	// Uninstall extensions
	function uninstall() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Uninstall selected extensions
		$this->_model->uninstall();

		// Return
		parent::route(JText::_('COM_MIJOSEF_EXTENSIONS_VIEW_REMOVED'));
	}
	
	// Save changes
	function save() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Save
		$this->_model->save();
		
		// Return
		parent::route(JText::_('COM_MIJOSEF_EXTENSIONS_VIEW_SAVED'));
	}
	
	// Save changes & Delete URLs
	function saveDeleteURLs() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		$ids = parent::_getIDs($this->_context, $this->_model);
		
		foreach ($ids as $id) {
			$this->_model->save($id, 'deleteURLs');
		}
		// Return
		parent::route(JText::_('COM_MIJOSEF_EXTENSIONS_VIEW_SAVED_URL_PURGED'));
	}
	
	// Save changes & Update URLs
	function saveUpdateURLs() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		$count = 0;
		$ids = parent::_getIDs($this->_context, $this->_model);
		
		foreach ($ids as $id) {
			$urls = $this->_model->save($id, 'updateURLs');
			$count += $urls;
		}
		// Return
		parent::route(JText::_('COM_MIJOSEF_COMMON_UPDATED_URLS').' '.$count);
	}
	
	// Install a new extension
	function installUpgrade() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		if(!$this->_model->installUpgrade()){
			JError::raiseWarning('1001', JText::_('COM_MIJOSEF_EXTENSIONS_VIEW_NOT_INSTALLED'));
		} else {
			parent::route(JText::_('COM_MIJOSEF_EXTENSIONS_VIEW_INSTALLED'));
		}
	}
}
?>