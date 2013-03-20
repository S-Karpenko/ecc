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

// Imports
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.installer.helper');
jimport('joomla.installer.installer');
require_once(JPATH_MIJOSEF_ADMIN.'/adapters/mijosef_ext.php');

// Extensions Model Class
class MijosefModelExtensions extends MijosefModel {
	
	// Main constructer
	function __construct()	{
		parent::__construct('extensions');
		
		$this->_getUserStates();
		$this->_buildViewQuery();
	}
	
	function _getUserStates() {
		$this->filter_order		= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_order',		'filter_order',		'name');
		$this->filter_order_Dir	= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_order_Dir',	'filter_order_Dir',	'ASC');
		$this->search_name		= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.search_name', 		'search_name', 		'');
        $this->filter_router	= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_router', 	'filter_router', 	'-1');
		$this->search_prefix	= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.search_prefix', 	'search_prefix', 	'');
        $this->filter_skipmenu	= parent::_getSecureUserState($this->_option . '.' . $this->_context . '.filter_skipmenu', 	'filter_skipmenu', 	'-1');
		$this->search_name		= JString::strtolower($this->search_name);
		$this->search_prefix	= JString::strtolower($this->search_prefix);
	}
	
	function getToolbarSelections() {
		$toolbar = new stdClass();
		
        // Actions
        $act[] = JHTML::_('select.option', 'savedeleteurls', JText::_('Save') .' & '. JText::_('COM_MIJOSEF_TOOLBAR_DELETE_URLS'));
        $act[] = JHTML::_('select.option', 'saveupdateurls', JText::_('Save') .' & '. JText::_('COM_MIJOSEF_TOOLBAR_UPDATE_URLS'));
        $toolbar->action = JHTML::_('select.genericlist', $act, 'ext_action', 'class="inputbox" style="margin-top: 17px;" size="1"');
		
		// Selections
        $sel[] = JHTML::_('select.option', 'selected', JText::_('COM_MIJOSEF_TOOLBAR_SELECTED'));
        $sel[] = JHTML::_('select.option', 'filtered', JText::_('COM_MIJOSEF_TOOLBAR_FILTERED'));
        $toolbar->selection = JHTML::_('select.genericlist', $sel, 'ext_selection', 'class="inputbox" style="margin-top: 17px;" size="1"');
		
		// Button
        $toolbar->button = '<input type="button" value="'.JText::_('Apply').'" style="margin-top: 17px;" onclick="apply();" />';
		
		return $toolbar;
	}
	
	function getLists() {
		$lists = array();
		
		// Table ordering
		$lists['order_dir'] = $this->filter_order_Dir;
		$lists['order'] 	= $this->filter_order;
		
		// Reset filters
		$lists['reset_filters'] = '<button onclick="resetFilters();">'. JText::_('Reset') .'</button>';
		
		// Filter's action
		$javascript = 'onchange="document.adminForm.submit();"';
		
		// Search name
        $lists['search_name'] = "<input type=\"text\" name=\"search_name\" value=\"{$this->search_name}\" size=\"25\" maxlength=\"255\" onchange=\"document.adminForm.submit();\" />";

        // Search prefix
        $lists['search_prefix'] = "<input type=\"text\" name=\"search_prefix\" value=\"{$this->search_prefix}\" size=\"25\" maxlength=\"255\" onchange=\"document.adminForm.submit();\" />";
        
		// Router Filter
		$router_list[] = JHTML::_('select.option', '-1', JText::_('COM_MIJOSEF_COMMON_SELECT'));
		$router_list[] = JHTML::_('select.option', '3', JText::_('COM_MIJOSEF_EXTENSIONS_VIEW_SELECT_EXTENSION'));
		$router_list[] = JHTML::_('select.option', '2', JText::_('COM_MIJOSEF_EXTENSIONS_VIEW_SELECT_15_ROUTER'));
		$router_list[] = JHTML::_('select.option', '1', JText::_('COM_MIJOSEF_EXTENSIONS_VIEW_SELECT_MIJOSEF'));
		$router_list[] = JHTML::_('select.option', '0', JText::_('COM_MIJOSEF_EXTENSIONS_VIEW_SELECT_DISABLE'));
   	   	$lists['router_list'] = JHTML::_('select.genericlist', $router_list, 'filter_router', 'class="inputbox" size="1"'.$javascript,'value', 'text', $this->filter_router);
		
		// Skip title Filter
		$skip_list[] = JHTML::_('select.option', '-1', JText::_('COM_MIJOSEF_COMMON_SELECT'));
		$skip_list[] = JHTML::_('select.option', '1', JText::_('Yes'));
		$skip_list[] = JHTML::_('select.option', '0', JText::_('No'));
   	   	$lists['skip_list'] = JHTML::_('select.genericlist', $skip_list, 'filter_skipmenu', 'class="inputbox" size="1"'.$javascript,'value', 'text', $this->filter_skipmenu);
		
		return $lists;
	}
	
	// Routers state
	function checkComponents() {
		$filter = Mijosef::get('utility')->getSkippedComponents();
		$components = MijoDatabase::loadResultArray("SELECT `element` FROM `#__extensions` WHERE `type` = 'component' AND `element` NOT IN ({$filter}) ORDER BY `element`");

		foreach ($components as $component) {
			// Check if there is already a record available
			$total = MijoDatabase::loadResult("SELECT COUNT(*) FROM #__mijosef_extensions WHERE extension = '{$component}'");
			
			if ($total < 1) {
				$name = "";
				$routed = false;
				
				if (!$routed) {
					$ext = JPATH_MIJOSEF_ADMIN.'/extensions/'.$component.'.php';
					if (file_exists($ext)) {
						$name = Mijosef::get('utility')->getXmlText(JPATH_MIJOSEF_ADMIN.'/extensions/'.$component.'.xml', 'name');
						$router = 3;
						$routed = true;
					}
				}
				
				if (!$routed) {
					$router = JPATH_SITE.'/components/'.$component.'/router.php';
					if (file_exists($router)) {
						$router = 2;
						$routed = true;
					}
				}
				
				if (!$routed) {
					$router = 1;
					$routed = true;
				}
				
				if ($routed) {
					$prms = array();
					$prms['router'] = "{$router}";
					$prms['prefix'] = "";
					$prms['skip_menu'] = "0";
					
					$reg = new JRegistry($prms);
					$params = $reg->toString();
		
					MijoDatabase::query("INSERT INTO #__mijosef_extensions (name, extension, params) VALUES ('{$name}', '{$component}', '{$params}')");
				}
			}
		}
	}
	
	// Install / Upgrade extensions
	function installUpgrade() {
		// Check if the extensions directory is writable
		$directory = JPATH_MIJOSEF_ADMIN.'/extensions';
		if (!is_writable($directory)) {
			JError::raiseWarning('1001', JText::_('COM_MIJOSEF_EXTENSIONS_VIEW_INSTALL_DIR_CHMOD_ERROR'));
		}
		
		$result = false;
		
		// Get vars
		$userfile 	= JRequest::getVar('install_package', null, 'files', 'array');
		$ext_url 	= JRequest::getVar('joomaceurl');
		
		// Manual upgrade or install
		Mijosef::get('utility')->import('library.installer');
		if ($userfile) {
			$package = MijosefInstaller::getPackageFromUpload($userfile);
		}
		// Automatic upgrade
		elseif($ext_url) {
			// Download the package
			$package = MijosefInstaller::getPackageFromServer($ext_url);
		}

		// Get an installer instance
		$installer =& JInstaller::getInstance();
        $adapter = new JInstallerMijosef_Ext($installer);
		$installer->setAdapter('mijosef_ext', $adapter);

		// Install the package
		if (!$installer->install($package['dir'])) {
			// There was an error installing the package
			$msg = JText::sprintf('INSTALLEXT', JText::_($package['type']), JText::_('Error'));
			$result = false;
		} else {
			// Package installed sucessfully
			$msg = JText::sprintf('INSTALLEXT', JText::_($package['type']), JText::_('Success'));
			$result = true;
		}

		return $result;
	}
	
	// Uninstall extensions
	function uninstall() {
		// Get where
		$where = MijosefController::_buildSelectedWhere();
		
		// Get extensions
		$extensions = MijoDatabase::loadAssocList("SELECT id, extension, params FROM #__mijosef_extensions {$where}", "id");
		
		// Action
		foreach ($extensions as $id => $record) {
			$extension = $record['extension'];
			
			// Remove already created URLs for this extension from database
			if ($this->MijosefConfig->purge_ext_urls == 1) {
				MijoDatabase::query("DELETE FROM #__mijosef_urls WHERE (url_real LIKE '%option={$extension}&%' OR url_real LIKE '%option={$extension}') AND params LIKE '%\"locked\":0%'");
			}
			
            if (JFolder::exists(JPATH_SITE.'/components/'.$extension)) {
                $params = array();
                $router = 1;
                if (file_exists(JPATH_SITE.'/components/'.$extension.'/router.php')) {
                    $router = 2;
                }

                $p = new JRegistry($record['params']);

                $params['router'] = $router;
                $params['prefix'] = $p->get('prefix', '');
                $params['skip_menu'] = $p->get('skip_menu', '0');
                Mijosef::get('utility')->storeParams('MijosefExtensions', $id, 'params', $params);

                Mijosef::get('utility')->setData('MijosefExtensions', $id, 'name', '');
            }
            else {
                MijoDatabase::query("DELETE FROM #__mijosef_extensions WHERE extension = '{$extension}'");
            }

			// Remove the extension files
			if (file_exists(JPATH_MIJOSEF_ADMIN.'/extensions/'.$extension.'.php')){
				JFile::delete(JPATH_MIJOSEF_ADMIN.'/extensions/'.$extension.'.xml');
				JFile::delete(JPATH_MIJOSEF_ADMIN.'/extensions/'.$extension.'.php');
			}
		}
		
		return;
	}
	
	// Save changes
	function save($ext_id = null, $function = "", $action = "") {
		$ids 		= JRequest::getVar('id');
		$router 	= JRequest::getVar('router');
		$prefix 	= JRequest::getVar('prefix');
		$skip_menu 	= JRequest::getVar('skip_menu');

		foreach ($ids as $id => $val) {
			$params = array();
			$params['router'] = $router[$id];
			$params['prefix'] = $prefix[$id];
			$params['skip_menu'] = $skip_menu[$id];
			Mijosef::get('utility')->storeParams('MijosefExtensions', $id, 'params', $params);
			
			if(!empty($function) && $id == $ext_id) {
				include_once(JPATH_MIJOSEF_ADMIN.'/models/purgeupdate.php');
				$model = new MijosefModelPurgeUpdate();
				return $model->$function($ext_id, $action);
			}
		}
	}
	
	function getInfo() {
		static $information;
		
		$information = array();
		if ($this->MijosefConfig->version_checker == 1) {
			$information = Mijosef::get('utility')->getRemoteInfo();
			unset($information['mijosef']);
		}
		
		return $information;
    }
	
	function getParams() {
		$params = MijoDatabase::loadObjectList("SELECT extension, params FROM #__mijosef_extensions", "extension");
		return $params;
	}
	
	function _buildViewQuery() {
		$where		= $this->_buildViewWhere();
		$orderby	= " ORDER BY {$this->filter_order} {$this->filter_order_Dir}, extension";
		
		$this->_query = "SELECT * FROM #__mijosef_{$this->_table} {$where}{$orderby}";
	}
	
	// Filters function
	function _buildViewWhere() {
		$where = array();
		
		if ($this->search_name) {
			$src = parent::secureQuery($this->search_name, true);
			$where[] = "LOWER(name) LIKE {$src} OR LOWER(extension) LIKE {$src}";
		}
		
		if ($this->filter_router != -1) {
			$src = $this->_db->getEscaped($this->filter_router, true);
			$where[] = "params LIKE '%router={$src}%'";
		}
		
		if ($this->search_prefix) {
			$src = $this->_db->getEscaped($this->search_prefix, true);
			$where[] = "params LIKE '%prefix={$src}%'";
		}
		
		if ($this->filter_skipmenu != -1) {
			$src = $this->_db->getEscaped($this->filter_skipmenu, true);
			$where[] = "params LIKE '%skip_menu={$src}%'";
		}
		
		$where = (count($where) ? ' WHERE '. implode(' AND ', $where) : '');
		return $where;
	}
}
?>