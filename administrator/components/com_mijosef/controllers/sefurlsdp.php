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
class MijosefControllerSefUrlsDp extends MijosefController {

	// Main constructer
	function __construct() 	{
        if (!JFactory::getUser()->authorise('urls', 'com_mijosef')) {
            return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
        }

		parent::__construct('sefurlsdp', 'urls');
	}
	
	function publish() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Action
		parent::updateParam($this->_table, 'MijosefSefUrls', 'params', 'published', 1, $this->_model);
		
		// Redirect
		$id = JRequest::getInt('id');
		$this->setRedirect('index.php?option='.$this->_option.'&controller='.$this->_context.'&task=view&id='.$id.'&tmpl=component');
    }
	
	function unpublish() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Action
		parent::updateParam($this->_table, 'MijosefSefUrls', 'params', 'published', 0, $this->_model);
		
		// Redirect
		$id = JRequest::getInt('id');
		$this->setRedirect('index.php?option='.$this->_option.'&controller='.$this->_context.'&task=view&id='.$id.'&tmpl=component');
	}
	
	// Use URL
	function used() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		$ids = parent::_getIDs($this->_table, $this->_model);
		
		Mijosef::get('utility')->import('models.sefurls');
		$model = new MijosefModelSefUrls();
		
		foreach ($ids as $index => $id) {
			$model->used($id);
		}
		
		// Redirect
		$id = JRequest::getInt('id');
		$this->setRedirect('index.php?option='.$this->_option.'&controller='.$this->_context.'&task=view&id='.$id.'&tmpl=component');
	}
	
	function resetUsed() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Action
		parent::updateField($this->_table, 'used', 0, $this->_model);
		
		// Redirect
		$id = JRequest::getInt('id');
		$this->setRedirect('index.php?option='.$this->_option.'&controller='.$this->_context.'&task=view&id='.$id.'&tmpl=component');
    }
	
	function lock() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Action
		parent::updateParam($this->_table, 'MijosefSefUrls', 'params', 'locked', 1, $this->_model);
		
		// Redirect
		$id = JRequest::getInt('id');
		$this->setRedirect('index.php?option='.$this->_option.'&controller='.$this->_context.'&task=view&id='.$id.'&tmpl=component');
    }
	
	function unlock() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Action
		parent::updateParam($this->_table, 'MijosefSefUrls', 'params', 'locked', 0, $this->_model);
		
		// Redirect
		$id = JRequest::getInt('id');
		$this->setRedirect('index.php?option='.$this->_option.'&controller='.$this->_context.'&task=view&id='.$id.'&tmpl=component');
	}
	
	function block() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Action
		parent::updateParam($this->_table, 'MijosefSefUrls', 'params', 'blocked', 1, $this->_model);
		
		// Redirect
		$id = JRequest::getInt('id');
		$this->setRedirect('index.php?option='.$this->_option.'&controller='.$this->_context.'&task=view&id='.$id.'&tmpl=component');
    }
	
	function unblock() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Action
		parent::updateParam($this->_table, 'MijosefSefUrls', 'params', 'blocked', 0, $this->_model);
		
		// Redirect
		$id = JRequest::getInt('id');
		$this->setRedirect('index.php?option='.$this->_option.'&controller='.$this->_context.'&task=view&id='.$id.'&tmpl=component');
	}
	
	function cache() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Action
		$fields = "id, url_sef, url_real, used, meta, sitemap, tags, ilinks, bookmarks, params";
		parent::updateCache($this->_table, 'url_real', $fields, 1, $this->_model);
		
		// Redirect
		$id = JRequest::getInt('id');
		$this->setRedirect('index.php?option='.$this->_option.'&controller='.$this->_context.'&task=view&id='.$id.'&tmpl=component');
	}
	
	function uncache() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		// Action
		$fields = "id, url_sef, url_real, used, meta, sitemap, tags, ilinks, bookmarks, params";
		parent::updateCache($this->_table, 'url_real', $fields, 0, $this->_model);
		
		// Redirect
		$id = JRequest::getInt('id');
		$this->setRedirect('index.php?option='.$this->_option.'&controller='.$this->_context.'&task=view&id='.$id.'&tmpl=component');
	}
}
?>