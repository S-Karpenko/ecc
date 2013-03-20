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
class MijosefControllerMovedUrls extends MijosefController {

	// Main constructer
	function __construct() {
        if (!JFactory::getUser()->authorise('urls', 'com_mijosef')) {
            return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
        }

		parent::__construct('movedurls', 'urls_moved');
	}
	
	function cache() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
	
		// Action
		parent::updateCache($this->_table, 'url_old', '*', 1, $this->_model);
		
		// Return
		parent::route();
	}
	
	function uncache() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
	
		// Action
		parent::updateCache($this->_table, 'url_old', '*', 0, $this->_model);
		
		// Return
		parent::route();
	}
	
	//
	// Edit methods
	//
	
	// Save changes
	function editSave() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Get post
		$post = JRequest::get('post');
		
		// Save record
		if (!parent::saveRecord($post, 'MijosefMovedUrls', $post['id'])) {
			return JError::raiseWarning(500, JText::_('COM_MIJOSEF_COMMON_RECORD_SAVED_NOT'));
		} else {
			$sefid = JRequest::getInt('sefid', 0);
			if (!empty($sefid)) {
				MijoDatabase::query("DELETE FROM #__mijosef_urls WHERE id = {$sefid}");
			}
			
			if ($post['modal'] == '1') {
				// Display message
				JFactory::getApplication()->enqueueMessage(JText::_('COM_MIJOSEF_COMMON_RECORD_SAVED'));
			} else {
				// Return
				parent::route(JText::_('COM_MIJOSEF_COMMON_RECORD_SAVED'));
			}
		}
	}
}
?>