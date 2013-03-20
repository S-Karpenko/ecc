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

// Control Panel Model Class
class MijosefModelMijosef extends MijosefModel {
	
	// Main constructer
	function __construct() {
        parent::__construct('mijosef');
    }
    
	function sefStatus() {
        $type = JRequest::getVar('sefStatusType', '', 'post', 'string');
        $value = JRequest::getVar('sefStatusValue', '', 'post', 'string');
        $types = array('version_checker', 'sef', 'mod_rewrite', 'live_site', 'jfrouter', 'mijosef', 'plugin', 'languagefilter', 'generate_sef');
        $msg = '';
        
        if (in_array($type, $types)) {
            // Joomla settings
            if ($type == 'sef' || $type == 'mod_rewrite' || $type == 'live_site') {
                $JoomlaConfig =& JFactory::getConfig();
                
                if ($type == 'sef') {
                    $JoomlaConfig->set('sef', $value);
                } 
				elseif ($type == 'mod_rewrite') {
                    $JoomlaConfig->set('sef_rewrite', $value);
                } 
				elseif ($type == 'live_site') {
					$live_site = JRequest::getVar('live_site', '', 'post', 'string');
					
					if (!empty($live_site) && (strpos($live_site, 'http') === false)) {
						$live_site = 'http://'.$live_site;
					}
					
					$JoomlaConfig->set('live_site', trim($live_site));
                }
                
                // Store the configuration
                $file = JPATH_CONFIGURATION.'/configuration.php';
        		if (!JFile::write($file, $JoomlaConfig->toString('PHP', array('class' => 'JConfig', 'closingtag' => false))) ) {
        			$msg = JText::_('Error writing Joomla! configuration, make the changes from Joomla Global Configuration page.');
        		}
            }
            elseif ($type == 'mijosef' || $type == 'generate_sef' || $type == 'version_checker') {
                // MijoSEF settings
				$MijosefConfig = Mijosef::getConfig();
                
                if ($type == 'mijosef') {
                    $MijosefConfig->mode = $value;
                }
                elseif ($type == 'generate_sef') {
                    $MijosefConfig->generate_sef = $value;
                }
                elseif ($type == 'version_checker') {
                    $MijosefConfig->version_checker = $value;
                }
				
				Mijosef::get('utility')->storeConfig($MijosefConfig);
            }
            elseif ($type == 'plugin' || $type == 'jfrouter' || $type == 'languagefilter') {
                if ($type == 'plugin') {
                    $type = 'mijosef';
                }
                
                if (!MijoDatabase::query("UPDATE `#__extensions` SET `enabled` = '{$value}' WHERE (`element` = '{$type}') AND (`folder` = 'system') LIMIT 1")) {
                    $msg = JText::_('Error writing changing plugin status');
                }
            }
        }
        
        return $msg;
    }
	
	function saveDownloadID() {
		$pid = trim(JRequest::getVar('pid', '', 'post', 'string'));
		
		if (!empty($pid)) {
			$MijosefConfig = Mijosef::getConfig();
			$MijosefConfig->pid = $pid;
			
			Mijosef::get('utility')->storeConfig($MijosefConfig);
		}
	}

	// Check info
	function getInfo() {
		static $info;
		
		if (!isset($info)) {
			$info = array();
			if ($this->MijosefConfig->version_checker == 1){
				$info['version_installed'] = Mijosef::get('utility')->getXmlText(JPATH_MIJOSEF_ADMIN.'/a_mijosef.xml', 'version');
				$version_info = Mijosef::get('utility')->getRemoteInfo();
				
				$info['version_latest'] = $version_info['mijosef'];
				
				// Set the version status
				$info['version_status'] = version_compare($info['version_installed'], $info['version_latest']);
				$info['version_enabled'] = 1;
			} else {
				$info['version_status'] = 0;
				$info['version_enabled'] = 0;
			}
			
			$info['pid'] = $this->MijosefConfig->pid;

			$info['urls_sef'] = MijoDatabase::loadResult("SELECT COUNT(*) FROM #__mijosef_urls");
			$info['urls_moved'] = MijoDatabase::loadResult("SELECT COUNT(*) FROM #__mijosef_urls_moved");
			$info['metadata'] = MijoDatabase::loadResult("SELECT COUNT(*) FROM #__mijosef_metadata");
			$info['sitemap'] = MijoDatabase::loadResult("SELECT COUNT(*) FROM #__mijosef_sitemap");
			$info['tags'] = MijoDatabase::loadResult("SELECT COUNT(*) FROM #__mijosef_tags");
			$info['ilinks'] = MijoDatabase::loadResult("SELECT COUNT(*) FROM #__mijosef_ilinks");
			$info['bookmarks'] = MijoDatabase::loadResult("SELECT COUNT(*) FROM #__mijosef_bookmarks");
		}
		
		return $info;
	}
	
	// Get extensions list
	function getExtensions() {
		static $extensions;
		
		if(!isset($extensions)) {
			$extensions = MijoDatabase::loadObjectList("SELECT * FROM #__mijosef_extensions WHERE name != '' ORDER BY name");
		}
		
		return $extensions;
	}
}
?>