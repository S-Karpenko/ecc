<?php
/**
* @version		1.0.0
* @package		MijoSEF Library
* @subpackage	Metadata
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license		GNU/GPL based on AceSEF www.joomace.net
*/

// No Permission
defined('_JEXEC') or die('Restricted Access');

// Metadata class
class MijosefMetadata {
	
	function __construct() {
		// Get config object
		$this->MijosefConfig = Mijosef::getConfig();
	}
	
	function autoMetadata($sef_url, $meta) {
		$no_auto_meta = (empty($meta['title']) && empty($meta['description']) && empty($meta['keywords']));
		
		if ($no_auto_meta) {
			return;
		}
		
		static $checked = array();
        
		if (!isset($checked[$sef_url])) {
			$m = Mijosef::get('cache')->checkMetadata($sef_url);
			$checked[$sef_url] = "checked";
		}
		
		if ($checked[$sef_url] != "saved" && isset($m) && !is_object($m)) {
			$meta['description'] = ltrim($meta['description']);
			$values = "(".MijoDatabase::quote($sef_url).", '{$meta['title']}', '{$meta['description']}', '{$meta['keywords']}')";
			
			MijoDatabase::query("INSERT IGNORE INTO #__mijosef_metadata (url_sef, title, description, keywords) VALUES {$values}");
			
			$checked[$sef_url] = "saved";
		}
	}
	
	// Clean metadata
	function clean($where = "", $fields) {
		// Get records
		$records = MijoDatabase::loadObjectList("SELECT id FROM #__mijosef_metadata {$where}");

		if (!empty($records)) {
			foreach ($records as $i => $record) {
				$id = $record->id;
				
				$metadata = "";
				if(is_array($fields) && count($fields) > 0) {
					foreach($fields as $index => $field) {
						$metadata .= "{$field} = '', ";
					}
					
					if (!empty($metadata)) {
						$metadata = rtrim($metadata, ', ');
						MijoDatabase::query("UPDATE #__mijosef_metadata SET {$metadata} WHERE id = {$id}");
					}
				}
			}
			return true;
		} else {
			return false;
		}
	}
	
	// Delete metadata
	function delete($where = "", $fields) {
		$metadata = "";
		if (is_array($fields) && count($fields) > 0) {
			foreach($fields as $index => $field) {
				$metadata .= "{$field} != '' AND ";
			}
			
			if (!empty($metadata)) {
				if ($where == "") {
					$where = " WHERE ";
				}
				
				$metadata = rtrim($metadata, 'AND ');
				MijoDatabase::query("DELETE FROM #__mijosef_metadata {$where}{$metadata}");
			}
			
			return true;
		} 
		else {
			return false;
		}
	}
	
	// Update meta
	function update($where = "", $fields) {
		// Get records
		$urls = MijoDatabase::loadObjectList("SELECT m.id, m.url_sef, u.url_real FROM #__mijosef_metadata AS m, #__mijosef_urls AS u WHERE m.url_sef = u.url_sef {$where}");

		if (!empty($urls)) {
			$ret = "";
			foreach ($urls as $i => $record) {
				$id = $record->id;
				$sef_url = $record->url_sef;
				$real_url = $record->url_real;
				
				if (!strpos($real_url, "option=")) {
					continue;
				}
				
				$component = Mijosef::get('utility')->getOptionFromRealURL($real_url);
				
				if (file_exists(JPATH_MIJOSEF_ADMIN.'/extensions/'.$component.'.php')) {
					$mijosef_ext = Mijosef::getExtension($component);
					
					$uri = Mijosef::get('uri')->_createURI($real_url);
					
					$mijosef_ext->beforeBuild($uri);
					$segments = array();
					$do_sef = true;
					$meta = null;
					$item_limitstart = false;
                    $vars = $uri->getQuery(true);
					$mijosef_ext->build($vars, $segments, $do_sef, $meta, $item_limitstart);
					
					if (is_array($meta) && count($meta) > 0 && !empty($fields) && is_array($fields)) {
						$metadata = "";
						foreach ($fields as $index => $field) {
							if (!empty($meta[$field])) {
								$metadata .= "{$field} = '{$meta[$field]}', ";
							}
						}
						
						if (!empty($metadata)) {
							$metadata = rtrim($metadata, ', ');
							MijoDatabase::query("UPDATE #__mijosef_metadata SET {$metadata} WHERE id = {$id}");
							$ret .= $sef_url.' ===> '.JText::_('COM_MIJOSEF_METADATA_UPDATED_OK').'<br/>';
						} else {
							$ret .= $sef_url.' ===> '.JText::_('COM_MIJOSEF_METADATA_UPDATED_CANT').'<br/>';
						}
					} else {
						$ret .= JText::_('COM_MIJOSEF_METADATA_UPDATED_NO');
					}
				} else {
					$ret .= $sef_url.' ===> '.JText::_('COM_MIJOSEF_METADATA_UPDATED_NO_EXTENSION').'<br/>';
				}
			}
			return $ret;
		} else {
			return JText::_('COM_MIJOSEF_METADATA_UPDATED_NO');
		}
	}
	
	function plugin($document) {
		$mainframe =& JFactory::getApplication();
		
		// Meta
		$url_id 		= $mainframe->get('mijosef.url.id');
		$url_sef 		= $mainframe->get('mijosef.url.sef');
		$auto_desc		= Mijosef::get('utility')->replaceSpecialChars($mainframe->get('mijosef.meta.autodesc'), true);
		$auto_key		= Mijosef::get('utility')->replaceSpecialChars($mainframe->get('mijosef.meta.autokey'), true);
		$meta_title		= Mijosef::get('utility')->replaceSpecialChars($mainframe->get('mijosef.meta.title'), true);
		$meta_desc		= Mijosef::get('utility')->replaceSpecialChars($mainframe->get('mijosef.meta.desc'), true);
		$meta_key		= Mijosef::get('utility')->replaceSpecialChars($mainframe->get('mijosef.meta.key'), true);
		$meta_lang   	= $mainframe->get('mijosef.meta.lang');
		$meta_robots	= $mainframe->get('mijosef.meta.robots');
		$meta_google	= $mainframe->get('mijosef.meta.google');
		$link_canonical	= $mainframe->get('mijosef.link.canonical');
		$generator  	= $this->MijosefConfig->meta_generator;
		$abstract  		= $this->MijosefConfig->meta_abstract;
		$revisit  		= $this->MijosefConfig->meta_revisit;
		$direction 		= $this->MijosefConfig->meta_direction;
		$google_key  	= $this->MijosefConfig->meta_googlekey;
		$live_key  		= $this->MijosefConfig->meta_livekey;
		$yahoo_key  	= $this->MijosefConfig->meta_yahookey;
		$alexa_key  	= $this->MijosefConfig->meta_alexa;
		$name_1  		= $this->MijosefConfig->meta_name_1;
		$name_2  		= $this->MijosefConfig->meta_name_2;
		$name_3  		= $this->MijosefConfig->meta_name_3;
		$con_1  		= $this->MijosefConfig->meta_con_1;
		$con_2  		= $this->MijosefConfig->meta_con_2;
		$con_3  		= $this->MijosefConfig->meta_con_3;
		
		// Core tags
		if ($this->MijosefConfig->meta_core == 1) {
			// Get original title, desc and keys
			$org_title	= $document->getTitle();
			$org_desc	= $document->getDescription();
			$org_key	= $document->getMetaData('keywords');
			
			// Meta that need to be updated
			$update_meta = array();
			
			// Title
			$title = self::_pluginTitle($url_id, $org_title, $meta_title);
			if (!empty($title)) {
				$document->setTitle($title);
				if (empty($meta_title) && $this->MijosefConfig->meta_title == '1') {
					$update_meta['title'] = $title;
				}
				if ($this->MijosefConfig->meta_title_tag == 1) {
					$title_t = str_replace('"', '', $title);
					$document->setMetaData('title', $title_t);
				}
			}
			
			// Description
			$description = self::_pluginDescKey($url_id, $org_desc, $meta_desc, $auto_desc, 'desc');
			if (!empty($description)) {
				$description = str_replace('"', '', $description);
				$document->setDescription($description);
				if (empty($meta_desc) && ($this->MijosefConfig->meta_desc == '1' || $this->MijosefConfig->meta_desc == '3')) {
					$update_meta['description'] = ltrim($description);
				}
			}
			
			// Keywords
			$keywords = self::_pluginDescKey($url_id, $org_key, $meta_key, $auto_key, 'key');
			if (!empty($keywords)) {
				$keywords = str_replace('"', '', $keywords);
				$document->setMetaData('keywords', $keywords);
				if (empty($meta_key) && ($this->MijosefConfig->meta_key == '1' || $this->MijosefConfig->meta_key == '3')) {
					$update_meta['keywords'] = $keywords;
				}
			}
			
			// Update meta
			$uri = JFactory::getURI();
			if (!empty($update_meta) && !Mijosef::get('uri')->_isHomePage($uri)) {
				$meta_list = Mijosef::get('cache')->checkMetadata($url_sef);
				if (is_array($meta_list) && !empty($meta_list)) {
					$metadata = "";
					foreach ($update_meta as $field => $value) {
						$val = Mijosef::get('utility')->cleanText($value);
						$metadata .= "{$field} = '{$val}', ";
					}
				
					$metadata = rtrim($metadata, ', ');
					MijoDatabase::query("UPDATE #__mijosef_metadata SET {$metadata} WHERE url_sef = '{$url_sef}'");
				}
			}
		}
		
		// Extra tags
		if (!empty($meta_robots))		$document->setMetaData('robots', $meta_robots);
		if (!empty($meta_lang))			$document->setMetaData('language', $meta_lang);
		if (!empty($meta_google))		$document->setMetaData('googlebot', $meta_google);
		if (!empty($link_canonical) && $document->getType() == 'html') 	$document->addHeadLink($link_canonical, 'canonical');
		if (!empty($generator)) 		$document->setGenerator($generator);
		if (!empty($abstract)) 			$document->setMetaData('abstract', $abstract);
		if (!empty($revisit)) 			$document->setMetaData('revisit', $revisit);
		if (!empty($direction)) 		$document->setDirection($direction);
		if (!empty($google_key))		$document->setMetaData('google-site-verification', $google_key);
		if (!empty($live_key))			$document->setMetaData('msvalidate.01', $live_key);
		if (!empty($yahoo_key))			$document->setMetaData('y_key', $yahoo_key);
		if (!empty($alexa_key))			$document->setMetaData('alexaVerifyID', $alexa_key);
		if (!empty($name_1))			$document->setMetaData($name_1, $con_1);
		if (!empty($name_2))			$document->setMetaData($name_2, $con_2);
		if (!empty($name_3))			$document->setMetaData($name_3, $con_3);
	}
	
	// Title
	function _pluginTitle($url_id, $org_title, $db_title) {
		// Modify original title
		$modified_title = $org_title;
		if (!empty($modified_title)) {
			$JoomlaConfig = &JFactory::getConfig();
			$sitename = $JoomlaConfig->getValue('sitename');
			
			$component = JRequest::getVar('option');
			if (file_exists(JPATH_MIJOSEF_ADMIN.'/extensions/'.$component.'.xml')) {
				$ext_params			= Mijosef::get('cache')->getExtensionParams($component);
				$separator			= $ext_params->get('separator', '-');
				$custom_sitename	= $ext_params->get('custom_sitename', '');
				$use_sitename		= $ext_params->get('use_sitename', '2');
				$title_prefix		= $ext_params->get('title_prefix', '');
				$title_suffix		= $ext_params->get('title_suffix', '');
			} else {
				$separator			= $this->MijosefConfig->meta_t_seperator;
				$custom_sitename	= $this->MijosefConfig->meta_t_sitename;
				$use_sitename		= $this->MijosefConfig->meta_t_usesitename;
				$title_prefix		= $this->MijosefConfig->meta_t_prefix;
				$title_suffix		= $this->MijosefConfig->meta_t_suffix;
			}
		
			if (!empty($custom_sitename)) {
				$sitename = $custom_sitename;
			}
			
			if ($use_sitename == 1) {
				$modified_title = $sitename." ".$separator." ".$modified_title;
			} elseif ($use_sitename == 2) {
				$modified_title = $modified_title." ".$separator." ".$sitename;
			}
			
			if (!empty($title_prefix)) {
				$modified_title = $title_prefix." ".$separator." ".$modified_title;
			}
			
			if (!empty($title_suffix)) {
				$modified_title = $modified_title." ".$separator." ".$title_suffix;
			}
		}
		
		if (empty($db_title)) {
			return $modified_title;
		} else {
			return $db_title;
		}
	}
	
	// Description & Keywords
	function _pluginDescKey($url_id, $org_val, $db_val, $auto_val, $type) {
		if ($type == 'desc') {
			$AConfig = 'meta_desc';
			$JConfig = 'MetaDesc';
		} elseif ($type == 'key') {
			$AConfig = 'meta_key';
			$JConfig = 'MetaKeys';
		}
	
		// Let's play
		if ($this->MijosefConfig->$AConfig == '1' || $this->MijosefConfig->$AConfig == '3') {
			// Get global description
			$JoomlaConfig = & JFactory::getConfig();
			$global_val = $JoomlaConfig->getValue($JConfig);
			
			// Clean out original description if it's the global one
			if ($org_val == $global_val) {
				$org_val = '';
			}
			
			$val1 = $val2 = '';
			if ($this->MijosefConfig->$AConfig == '1') {
				$val1 = $auto_val;
				$val2 = $org_val;
			} else {
				$val1 = $org_val;
				$val2 = $auto_val;
			}
			if (empty($db_val)) {
				if (empty($val1)) {
					return $val2;
				} else {
					return $val1;
				}
			} else {
				return $db_val;
			}
		} else {
			if (empty($db_val)) {
				return $org_val;
			} else {
				return $db_val;
			}
		}
	}
}