<?php
/**
* @version		1.0.0
* @package		MijoSEF
* @subpackage	MijoSEF
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license		GNU/GPL based on AceSEF www.joomace.net
*/

// No Permission
defined('_JEXEC') or die('Restricted Access');

// Model Class
class MijosefModelMovedUrls extends MijosefModel {
	
	// Main constructer
	function __construct()	{
		parent::__construct('movedurls', 'urls_moved');
		
		$this->_getUserStates();
		$this->_buildViewQuery();
	}
	
	function _getUserStates() {
		$this->filter_order		= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_order',		'filter_order',		'url_new');
		$this->filter_order_Dir	= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_order_Dir',	'filter_order_Dir',	'ASC');
		$this->type				= parent::_getSecureUserState($this->_option . '.' . 'urls.type', 							'type', 			'moved');
        $this->search_new		= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.search_new', 		'search_new', 		'');
        $this->search_old		= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.search_old', 		'search_old', 		'');
		$this->filter_published	= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_published',	'filter_published',	'-1');
		$this->filter_hit_val	= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_hit_val',	'filter_hit_val',	'0');
		$this->search_hit		= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.search_hit', 		'search_hit', 		'');
        $this->filter_date		= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_date', 		'filter_date', 		'');
        $this->search_id		= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.search_id', 		'search_id', 		'');
		$this->search_new		= JString::strtolower($this->search_new);
		$this->search_old		= JString::strtolower($this->search_old);
		$this->search_hit		= JString::strtolower($this->search_hit);
		$this->search_id		= JString::strtolower($this->search_id);
	}
	
	function getToolbarSelections() {
		$toolbar = new stdClass();
		
        // Actions
        $act[] = JHTML::_('select.option', 'delete', JText::_('Delete'));
		$act[] = JHTML::_('select.option', 'sep', '---');
		if ($this->MijosefConfig->ui_moved_published == 1) {
	        $act[] = JHTML::_('select.option', 'publish', JText::_('Publish'));
	        $act[] = JHTML::_('select.option', 'unpublish', JText::_('COM_MIJOSEF_TOOLBAR_PUBLISH_UN'));
			$act[] = JHTML::_('select.option', 'sep', '---');
		}
		if ($this->MijosefConfig->ui_moved_cached == 1) {
	        $act[] = JHTML::_('select.option', 'cache', JText::_('COM_MIJOSEF_TOOLBAR_CACHE'));
	        $act[] = JHTML::_('select.option', 'uncache', JText::_('COM_MIJOSEF_TOOLBAR_CACHE_UN'));
			$act[] = JHTML::_('select.option', 'sep', '---');
		}
        $act[] = JHTML::_('select.option', 'backup', JText::_('COM_MIJOSEF_TOOLBAR_BACKUP'));
        $toolbar->action = JHTML::_('select.genericlist', $act, 'movedurls_action', 'class="inputbox" style="margin-top: 17px;" size="1"');
		
		// Selections
        $sel[] = JHTML::_('select.option', 'selected', JText::_('COM_MIJOSEF_TOOLBAR_SELECTED'));
        $sel[] = JHTML::_('select.option', 'filtered', JText::_('COM_MIJOSEF_TOOLBAR_FILTERED'));
        $toolbar->selection = JHTML::_('select.genericlist', $sel, 'movedurls_selection', 'class="inputbox" style="margin-top: 17px;" size="1"');
		
		// Button
        $toolbar->button = '<input type="button" value="'.JText::_('Apply').'" style="margin-top: 17px;" onclick="apply();" />';
		
		return $toolbar;
	}
	
	function getLists() {		
		// Table ordering
		$lists['order_dir'] = $this->filter_order_Dir;
		$lists['order'] 	= $this->filter_order;
		
		// Reset filters
		$lists['reset_filters'] = '<button onclick="resetFilters();">'. JText::_('Reset') .'</button>';
	
		// Filter's action
		$javascript = 'onchange="document.adminForm.submit();"';
		
		// To URL search
        $lists['search_new'] = "<input type=\"text\" name=\"search_new\" value=\"{$this->search_new}\" size=\"50\" maxlength=\"255\" onchange=\"document.adminForm.submit();\" />";
		
		// From URL search
        $lists['search_old'] = "<input type=\"text\" name=\"search_old\" value=\"{$this->search_old}\" size=\"50\" maxlength=\"255\" onchange=\"document.adminForm.submit();\" />";

		// Published Filter
        if ($this->MijosefConfig->ui_moved_published == 1) {
			$published_list[] = JHTML::_('select.option', '-1', JText::_('COM_MIJOSEF_COMMON_SELECT'));
			$published_list[] = JHTML::_('select.option', '1', JText::_('Yes'));
			$published_list[] = JHTML::_('select.option', '0', JText::_('No'));
	   	   	$lists['published_list'] = JHTML::_('select.genericlist', $published_list, 'filter_published', 'class="inputbox" size="1"'.$javascript, 'value', 'text', $this->filter_published);
        }
        
		// Hits value
        if ($this->MijosefConfig->ui_moved_hits == 1) {
			$hit_val[] = JHTML::_('select.option', '0', '=');
			$hit_val[] = JHTML::_('select.option', '1', '>');
			$hit_val[] = JHTML::_('select.option', '2', '<');
	   	   	$lists['hit_val'] = JHTML::_('select.genericlist', $hit_val, 'filter_hit_val', 'class="inputbox" size="1"'.$javascript, 'value', 'text', $this->filter_hit_val);

			// Search hit
        	$lists['search_hit'] = "<input type=\"text\" name=\"search_hit\" value=\"{$this->search_hit}\" size=\"3\" maxlength=\"10\" onchange=\"document.adminForm.submit();\" />";
        }
        
		// Date
        if ($this->MijosefConfig->ui_moved_clicked == 1) {
			$lists['filter_date'] = JHTML::_('calendar', $this->filter_date, 'filter_date', 'filter_date', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'10', 'onchange'=>'document.adminForm.submit();',  'maxlength'=>'20'));
        }
        
		// Search ID
        if ($this->MijosefConfig->ui_moved_id == 1) {
        	$lists['search_id'] = "<input type=\"text\" name=\"search_id\" value=\"{$this->search_id}\" size=\"3\" maxlength=\"10\" onchange=\"document.adminForm.submit();\" />";
        }
        
		return $lists;
	}
	
	function getCache() {
		$urls = array();
		
		$cache = Mijosef::getCache();
		$urls = $cache->load('urls_moved');
		
		return $urls;
	}

	// Query filters
	function _buildViewWhere() {
		$where = array();
		
		// Type Filter
		$task = JRequest::getString('task');
		$tasks = array('add', 'edit', 'editSave', 'editApply', 'editCancel');
		$type = $this->_db->getEscaped($this->type, true);
		if ($type != 'moved' && !in_array($task, $tasks)) {
			$this->_mainframe->setUserState($this->_option . '.urls.type', $type);
			$this->_mainframe->redirect('index.php?option=com_mijosef&controller=sefurls&task=view');
		}
		
		// Search New URL
		if ($this->search_new != '') {
			$src = parent::secureQuery($this->search_new, true);
			$where[] = "LOWER(url_new) LIKE {$src}";
		}
		
		// Search Old URL
		if ($this->search_old != '') {
			$src = parent::secureQuery($this->search_old, true);
			$where[] = "LOWER(url_old) LIKE {$src}";
		}
		
		// Published Filter
		if ($this->filter_published != '-1') {
			$src = parent::secureQuery($this->filter_published);
			$where[] = "published = {$src}";
		}
		
		// Search hit
		if ($this->search_hit != '') {
			$val = parent::secureQuery($this->filter_hit_val);
			$val = ($val == 0) ? '=' : (($val == 1) ? '>' : '<');
			$hit = parent::secureQuery($this->search_hit);
			$where[]= "hits {$val} {$hit}";
		}
		
		// Date Filter
		if ($this->filter_date != '') {
			$src = parent::secureQuery($this->filter_date, true);
			$where[] = "LOWER(last_hit) LIKE {$src}";
		}
		
		// Search ID
		if ($this->search_id != '') {
			$src = parent::secureQuery($this->search_id);
			$where[]= "id = {$src}";
		}
		
		// Execute
		$where = (count($where) ? ' WHERE '. implode(' AND ', $where) : '');
		return $where;
	}
}
?>