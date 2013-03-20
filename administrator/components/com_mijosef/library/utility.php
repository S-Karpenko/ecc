<?php
/**
* @version		1.0.0
* @package		MijoSEF Library
* @subpackage	Utility
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license		GNU/GPL based on AceSEF www.joomace.net
*/

// No Permission
defined('_JEXEC') or die('Restricted Access');

// Imports
jimport('joomla.filesystem.file');

// Utility class
class MijosefUtility {
	
    public static $props = array();
	
    public function __construct() {
		// Get config object
		$this->MijosefConfig = Mijosef::getConfig();
	}
	
    public function is16() {
		static $status;
		
		if (!isset($status)) {
			if (version_compare(JVERSION, '1.6.0', 'ge')) {
				$status = true;
			} else {
				$status = false;
			}
		}
		
		return $status;
	}

    public function is30() {
		static $status;

		if (!isset($status)) {
			if (version_compare(JVERSION, '3.0.0', 'ge')) {
				$status = true;
			} else {
				$status = false;
			}
		}

		return $status;
	}
	
	function import($path) {
		require_once(JPATH_ADMINISTRATOR . '/components/com_mijosef/' . str_replace('.', '/', $path).'.php');
	}
	
	function render($path) {
		ob_start();
		require_once($path);
		$contents = ob_get_contents();
		ob_end_clean();
		
		return $contents;
	}
    
    function get($name, $default = null) {
        if (!is_array(self::$props) || !isset(self::$props[$name])) {
            return $default;
        }
        
        return self::$props[$name];
    }
    
    function set($name, $value) {
        if (!is_array(self::$props)) {
            self::$props = array();
        }
        
        $previous = self::get($name);
        self::$props[$name] = $value;
        
        return $previous;
    }
	
	function getConfigState($params, $cfg_name, $prm = "") {
		if (!is_object($params)) {
			return false;
		}
		
		$prm_name = $cfg_name;
		if ($prm != "") {
			$prm_name = $prm;
		}
		
		$param = $params->get($prm_name, 'global');
		if (($param == 'no') || ($param == 'global' && $this->MijosefConfig->$cfg_name == '0')) {
			return false;
		}
		
		return true;
    }
	
	function &getMenu() {
		jimport('joomla.application.menu');
		$options = array();
		
		$menu = JMenu::getInstance('site', $options);
		
		if (JError::isError($menu)) {
			$null = null;
			return $null;
		}
		
		return $menu;
	}
	
	function getComponents() {
		static $components;
		
		if (!isset($components)) {
            $components = array();
            
			$filter = self::getSkippedComponents();
			$rows = MijoDatabase::loadResultArray("SELECT `element` FROM `#__extensions` WHERE `type` = 'component' AND `element` NOT IN ({$filter}) ORDER BY `element`");

            $lang = JFactory::getLanguage();

			foreach($rows as $row) {
                $lang->load($row.'.sys', JPATH_ADMINISTRATOR);
				$components[] = JHTML::_('select.option', $row, JText::_($row));
			}
		}
		
		return $components;
	}

    function getSkippedComponents() {
        return "'com_sef', 'com_sh404sef', 'com_joomfish', 'com_config', 'com_media', 'com_installer', 'com_templates', 'com_plugins', 'com_modules', 'com_cpanel', 'com_cache', 'com_messages', 'com_menus', 'com_massmail', 'com_languages', 'com_admin', 'com_login', 'com_checkin', 'com_categories', 'com_redirect'";
    }
	
	function getExtensionFromRequest() {
		static $extension;
		
		if (!isset($extension)) {
			$cid = JRequest::getVar('cid', array(0), 'method', 'array');
			$extension = MijoDatabase::loadResult("SELECT extension FROM #__mijosef_extensions WHERE id = ".$cid[0]);
		}
		
		return $extension;
	}
	
	function getOptionFromRealURL($url) {
		$url = str_replace('&amp;', '&', $url);
		$url = str_replace('index.php?', '', $url);		
		parse_str($url, $vars);
		
		if (isset($vars['option'])) {
			return $vars['option'];
		} else {
			return '';
		}
	}
	
	// Get the list of languages
	function getLanguages() {
		static $languages;
		
		if (!isset($languages)) {
			$languages = array();
			
            jimport('joomla.language.helper');
            $langs = JLanguageHelper::getLanguages();
            
			if (!empty($langs)) {
				foreach($langs as $lang) {
					$languages[] = JHTML::_('select.option', $lang->sef, $lang->title);
				}
			}
		}
		
		return $languages;
	}

	function getBooleanRadio($name, $attrs, $selected) {
        $html  = '<fieldset class="radio">';
        $html .= JHTML::_('select.booleanlist', $name, $attrs, $selected);
        $html .= '</fieldset>';

        return $html;
    }
	
	function getRouterList($component) {
		static $routers = array();
		
		if (!isset($routers[$component])) {
			$extension_file = JPATH_MIJOSEF_ADMIN.'/extensions/'.$component.'.php';
			if (file_exists($extension_file)) {
				$routers[$component][] = JHTML::_('select.option', 3, JText::_('COM_MIJOSEF_EXTENSIONS_VIEW_SELECT_EXTENSION'));
			}
			
			$router_file = JPATH_SITE.'/components/'.$component.'/router.php';
			if (file_exists($router_file)) {
				$routers[$component][] = JHTML::_('select.option', 2, JText::_('COM_MIJOSEF_EXTENSIONS_VIEW_SELECT_15_ROUTER'));
			}
			
			$routers[$component][] = JHTML::_('select.option', 1, JText::_('COM_MIJOSEF_EXTENSIONS_VIEW_SELECT_MIJOSEF'));
			$routers[$component][] = JHTML::_('select.option', 0, JText::_('COM_MIJOSEF_EXTENSIONS_VIEW_SELECT_DISABLE'));
		}
		
		return $routers[$component];
	}
	
	function getRemoteInfo() {
		// Get config object
		if (!isset($this->MijosefConfig)) {
			$this->MijosefConfig = Mijosef::getConfig();
		}
		
		static $information;
		
		if ($this->MijosefConfig->cache_versions == 1) {
			$cache = Mijosef::getCache('86400');
			$information = $cache->load('versions');
		}
		
		if (!is_array($information)) {
			$information = array();
			$information['mijosef'] = '?.?.?';
			
			$components = self::getRemoteData('http://www.mijosoft.com/index.php?option=com_mijoextensions&view=xml&format=xml&catid=1');
			$extensions = self::getRemoteData('http://www.mijosoft.com/index.php?option=com_mijoextensions&view=xml&format=xml&catid=2');
			
			if (strstr($components, '<?xml version="1.0" encoding="UTF-8" ?>')) {
				$manifest = JFactory::getXML($components, false);

				if (is_null($manifest)) {
					return $information;
				}

				$category = $manifest->category;
				if (!is_a($category, 'JXMLElement') || (count($category->children()) == 0)) {
					return $information;
				}

				foreach ($category->children() as $component) {
					$option = $component->getAttribute('option');
					$compability = $component->getAttribute('compability');

					if ($option == 'com_mijosef' && ($compability == 'all' || $compability == '1.6' || $compability == '1.5_1.6')) {
						$information['mijosef'] = trim($component->getAttribute('version'));
						break;
					}
				}
			}

			if (strstr($extensions, '<?xml version="1.0" encoding="UTF-8" ?>')) {
                $manifest = JFactory::getXML($extensions, false);

                if (is_null($manifest)) {
                    return $information;
                }

                $category = $manifest->category;
                if (!is_a($category, 'JXMLElement') || (count($category->children()) == 0)) {
                    return $information;
                }

				foreach ($category->children() as $extension) {
					$option = $extension->getAttribute('option');
					$compability = $extension->getAttribute('compability');

					if ($compability == 'all' || $compability == '1.6' || $compability == '1.5_1.6') {
						$ext = new stdClass();
						$ext->version		= trim($extension->getAttribute('version'));
						$ext->link			= trim($extension->getAttribute('download'));
						$ext->description	= trim($extension->getAttribute('description'));
					
						$information[$option] = $ext;
					}
				}
			}
			
			if ($this->MijosefConfig->cache_versions == 1 && !empty($information)) {
				$cache->save($information, 'versions');
			}
		}
		
		return $information;
	}
	
    function replaceLoop($search, $replace, $text) {
        $count = 0;
		
		while ((strpos($text, $search) !== false) && ($count < 10)) {
            $text = str_replace($search, $replace, $text);
			$count++;
        }

        return $text;
    }
	
	function storeConfig($MijosefConfig) {
        $reg = new JRegistry($MijosefConfig);
        $config = $reg->toString();

        $db = JFactory::getDBO();
        $db->setQuery('UPDATE #__extensions SET params = '.$db->Quote($config).' WHERE element = "com_mijosef" AND type = "component"');
        $db->query();
	}
	
	function _multiLayeredCheckup($section, $text, $ext_params, $area, $url_params, $component, $real_url, $own_page = false) {
		$_area = $section."_area";
		$_components = $section."_components";
		$_cats = $section."_cats";
		$_enable_cats = $section."_enable_cats";
		$_in_cats = $section."_in_cats";
		$cat = Mijosef::get('utility')->get('category.param');
		
		if (empty($text)) {
			return false;
		}
		
		$cfg_area = self::_getArea($ext_params->get($_area, 'global'), $this->MijosefConfig->$_area);
		if (strcasecmp($cfg_area, $area) != 0) {
			return false;
		}
		
		if (!in_array($component, $this->MijosefConfig->$_components)) {
			return false;
		}
		
		if (self::getConfigState($ext_params, $_enable_cats) && ($cat[$_cats.'_status'] == 0 && $cat['_flag'] == 1)) {
			return false;
		}
		if (!self::getConfigState($ext_params, $_in_cats) && $cat['_is_cat'] == 1) {
			return false;
		}
		
		if (self::getParam($url_params, $section) != 1) {
			return false;
		}
		
		if ($own_page && (strcasecmp($real_url, $cat['_real_url']) != 0)) {
			return false;
		}
		
		return true;
	}

	function _getArea($param, $config) {
        if (($param == '1') || ($param == 'global' && $config == '1')) {
            return 'content';
        } elseif (($param == '2') || ($param == 'global' && $config == '2')) {
            return 'component';
        } elseif (($param == '3') || ($param == 'global' && $config == '3')) {
            return 'trigger';
        } 
    }

	function _urlFilter($section, $sef_url, $real_url, $ext_params) {
		$var_s = $section.'_auto_filter_s';
		$var_r = $section.'_auto_filter_r';
		
		$ext_s = $ext_params->get($var_s, '');
		$ext_r = $ext_params->get($var_r, '');
		
		$in_filter = false;
		
		// Get filters
		$global_s_filter = explode(', ', $this->MijosefConfig->$var_s);
		$global_r_filter = explode(', ', $this->MijosefConfig->$var_r);
		$extension_s_filter = explode(', ', $ext_s);
		$extension_r_filter = explode(', ', $ext_r);
		
		// Combine filters
		$s_filters = array_unique(array_merge($global_s_filter, $extension_s_filter));
		$r_filters = array_unique(array_merge($global_r_filter, $extension_r_filter));
		
		foreach ($s_filters as $filter) {
			if (!empty($filter)) {
				$pos_sef = strpos($sef_url, $filter);
				if ($pos_sef !== false) {
					$in_filter = true;
				}
			}
		}
		
		foreach ($r_filters as $filter) {
			if (!empty($filter)) {
				$pos_real = strpos($real_url, $filter);
				if ($pos_real !== false) {
					$in_filter = true;
				}
			}
		}
		
        return $in_filter;
    }
    
    function getSefStatus() {
        static $status;
        
        if (!isset($status)) {
			$JoomlaConfig = JFactory::getConfig();
			
            $status = array();
            $status['version_checker'] = (bool)$this->MijosefConfig->version_checker;
            $status['php'] = (bool)version_compare(PHP_VERSION, '5.2.0', '>');
            
			$status['s_mod_rewrite'] = '';
			if (function_exists('apache_get_modules')) {
				$modules = apache_get_modules();
				$status['s_mod_rewrite'] = (bool)in_array('mod_rewrite', $modules);
			}

            $status['sef'] = (bool)$JoomlaConfig->get('sef');
            $status['mod_rewrite'] = (bool)$JoomlaConfig->get('sef_rewrite');
			$status['htaccess'] = false;

			if (file_exists(JPATH_ROOT.'/.htaccess')) {
				$filesize = filesize(JPATH_ROOT.'/.htaccess');
				$status['htaccess'] = (bool)($filesize > 2060);
			}

            $status['live_site'] = !is_null($JoomlaConfig->get('live_site')) ? $JoomlaConfig->get('live_site') : '';
            
			if (Mijosef::get('utility')->JoomfishInstalled()) {
				$status['jfrouter'] = JPluginHelper::isEnabled('system', 'jfrouter');
			}
            
			if ($this->MijosefConfig->multilang == 1) {
				$status['languagefilter'] = JPluginHelper::isEnabled('system', 'languagefilter');
			}

            $status['mijosef'] = (bool)$this->MijosefConfig->mode;
            $status['plugin'] = JPluginHelper::isEnabled('system', 'mijosef');
            $status['generate_sef'] = (bool)$this->MijosefConfig->generate_sef;
        }
        
        return $status;
    }
	
	function JoomfishInstalled() {
        static $installed;
		
        if (!isset($installed)) {
            $installed = JFile::exists(JPATH_ROOT.'/administrator/components/com_joomfish/joomfish.php');
        }
		
        return $installed;
    }

	function FalangInstalled() {
        static $installed;

        if (!isset($installed)) {
            $installed = JFile::exists(JPATH_ROOT.'/administrator/components/com_falang/falang.php');
        }

        return $installed;
    }
	
	function backupDB($query, $file_name, $fields, $line) {
		$sql_data = '';
		
		$rows = MijoDatabase::loadObjectList($query);

		if (!empty($rows)) {
			foreach ($rows as $row) {
				$values = array();
				foreach ($fields as $field) {
					if (isset($row->$field)) {
						$values[] = "'".self::_cleanBackupFields($row->$field)."'";
					} else {
						$values[] = "''";
					}
				}
				$sql_data .= $line." VALUES (".implode(', ', $values).");\n";;
			}
		} else {
			return false;
		}

		if(!headers_sent()) {
			// flush the output buffer
			while(ob_get_level() > 0) {
				ob_end_clean();
			}

			ob_start();
			header ('Expires: 0');
			header ('Last-Modified: '.gmdate ('D, d M Y H:i:s', time()) . ' GMT');
			header ('Pragma: public');
			header ('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header ('Accept-Ranges: bytes');
			header ('Content-Length: ' . strlen($sql_data));
			header ('Content-Type: Application/octet-stream');
			header ('Content-Disposition: attachment; filename="'.$file_name.'"');
			header ('Connection: close');

			echo($sql_data);

			ob_end_flush();
			die();
			return true;
		} else {
			return false;
		}
    }
	
	// Clean backup fields
	function _cleanBackupFields($text) {
		$text = str_replace(array('\r\n', '\r', '\n', '\t', '\n\n', '`', '”', '“', '¿', '\0', '\x0B'), ' ', $text);
		$text = preg_replace('/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/', ' ', $text);
		$text = preg_replace('/\s/u', ' ', $text);
		$text = stripslashes($text);
		$text = self::replaceSpecialChars($text);
		$text = str_replace('\\', '\\', $text);
		
		return $text;
	}
	
	function getParam($text, $param) {
		$params = new JRegistry($text);
		return $params->get($param);
	}
	
	function storeParams($table, $id, $db_field, $new_params) {
		$row = Mijosef::getTable($table);
		if (!$row->load($id)) {
			return false;
		}

        Mijosef::get('utility')->import('library.parameter');
		
		$params = new JRegistry($row->$db_field);
		
		foreach ($new_params as $name => $value) {
			$params->set($name, $value);
		}
		
		$row->$db_field = $params->toString();
		
		if (!$row->check()) {
			return false;
		}
		
		if (!$row->store()) {
			return false;
		}
	}
	
	function setData($table, $id, $db_field, $new_field) {
		$row = Mijosef::getTable($table);
		if (!$row->load($id)) {
			return false;
		}
		$row->$db_field = $new_field;	

		if (!$row->check()) {
			return false;
		}
		
		if (!$row->store()) {
			return false;
		}
	}
	
	function getRemoteData($url) {
		$user_agent = "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)";
		$data = false;

		// cURL
		if (extension_loaded('curl')) {
			$process = @curl_init($url);

            @curl_setopt($process, CURLOPT_HEADER, false);
            @curl_setopt($process, CURLOPT_USERAGENT, $user_agent);
            @curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
            @curl_setopt($process, CURLOPT_SSL_VERIFYPEER, false);
            @curl_setopt($process, CURLOPT_AUTOREFERER, true);
            @curl_setopt($process, CURLOPT_FAILONERROR, true);
            @curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
            @curl_setopt($process, CURLOPT_TIMEOUT, 10);
            @curl_setopt($process, CURLOPT_CONNECTTIMEOUT, 10);
            @curl_setopt($process, CURLOPT_MAXREDIRS, 20);

            $data = @curl_exec($process);

            @curl_close($process);
			
			return $data;
		}

		// fsockopen
		if (function_exists('fsockopen')) {
			$errno = 0;
			$errstr = '';
			
			$url_info = parse_url($url);
			if($url_info['host'] == 'localhost')  {
				$url_info['host'] = '127.0.0.1';
			}

			// Open socket connection
			$fsock = @fsockopen($url_info['scheme'].'://'.$url_info['host'], 80, $errno, $errstr, 5);
		
			if ($fsock) {				
				@fputs($fsock, 'GET '.$url_info['path'].(!empty($url_info['query']) ? '?'.$url_info['query'] : '').' HTTP/1.1'."\r\n");
				@fputs($fsock, 'HOST: '.$url_info['host']."\r\n");
				@fputs($fsock, "User-Agent: ".$user_agent."\n");
				@fputs($fsock, 'Connection: close'."\r\n\r\n");
		
				// Set timeout
				@stream_set_blocking($fsock, 1);
				@stream_set_timeout($fsock, 5);
				
				$data = '';
				$passed_header = false;
				while (!@feof($fsock)) {
					if ($passed_header) {
						$data .= @fread($fsock, 1024);
					} else {
						if (@fgets($fsock, 1024) == "\r\n") {
							$passed_header = true;
						}
					}
				}
				
				// Clean up
				@fclose($fsock);
				
				// Return data
				return $data;
			}
		}

		// fopen
		if (function_exists('fopen') && ini_get('allow_url_fopen')) {
			// Set timeout
			if (ini_get('default_socket_timeout') < 5) {
				ini_set('default_socket_timeout', 5);
			}
			
			@stream_set_blocking($handle, 1);
			@stream_set_timeout($handle, 5);
			@ini_set('user_agent',$user_agent);
			
			$url = str_replace('://localhost', '://127.0.0.1', $url);
			
			$handle = @fopen($url, 'r');
			
			if ($handle) {
				$data = '';
				while (!feof($handle)) {
					$data .= @fread($handle, 8192);
				}
				
				// Clean up
				@fclose($handle);
			
				// Return data
				return $data;
			}
		}
		
		// file_get_contents
		if (function_exists('file_get_contents') && ini_get('allow_url_fopen')) {
			$url = str_replace('://localhost', '://127.0.0.1', $url);
			@ini_set('user_agent',$user_agent);
			$data = @file_get_contents($url);
			
			// Return data
			return $data;
		}
		
		return $data;
	}
	
	// Clear texts from unwanted chars
	function cleanText($text) {
		$text = strip_tags($text);
		$text = preg_replace(array('/&amp;quot;/', '/&amp;nbsp;/', '/&amp;lt;/', '/&amp;gt;/', '/&amp;copy;/', '/&amp;amp;/', '/&amp;euro;/', '/&amp;hellip;/'), ' ', $text);
		$text = preg_replace(array('/&quot;/', '/&nbsp;/', '/&lt;/', '/&gt;/', '/&copy;/', '/&amp;/', '/&euro;/', '/&hellip;/'), ' ', $text);
		$text = preg_replace("'<script[^>]*>.*?</script>'si", ' ', $text);
		$text = preg_replace('/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is', '\2 (\1)', $text);
		$text = preg_replace('/<!--.+?-->/', ' ', $text);
		$text = preg_replace('/{.+?}/', ' ', $text);
		$text = preg_replace('(\{.*?\})', ' ', $text);
		$text = preg_replace('/\s\s+/', ' ', $text);
		$text = preg_replace('/\n\n+/s', ' ', $text);
		$text = preg_replace('/<[^<|^>]*>/u', ' ', $text);
		$text = preg_replace('/{[^}]*}[\s\S]*{[^}]*}/u', ' ', $text);
		$text = preg_replace('/{[^}]*}/u', ' ', $text);
        $text = trim($text);
		$text = str_replace(array('\r\n', '\r', '\n', '\t', '\n\n', '<', '>', ':', '©', '#', '`', '”', '“', '¿', '\0', '\x0B', '"', '&quot;', '&quot'), ' ', $text);
		$text = preg_replace('/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/', ' ', $text);
		while(strpos($text, '  ')) {
			$text = str_replace('  ', ' ', $text);
		}
		
		// Space
		$text = preg_replace('/\s/u', ' ', $text);
		
		// Special chars
		$text = self::replaceSpecialChars($text);
		
		$text = rtrim($text, "'");
		$text = rtrim($text, "\\");
		
        return $text;
    }
	
	// Replace some special chars
	function replaceSpecialChars($text, $reverse = false) {
		if (is_string($text)) {
			if (!$reverse) {
				$text = str_replace("\'", "'", $text);
				$text = addslashes($text);
			} else {
				$text = stripslashes($text);
			}
		}
		
		return $text;
	}
	
	// Get text from XML
	function getXmlText($file, $variable) {
        jimport('joomla.filesystem.file');

        $value = '';

        if (JFile::exists($file)) {
            $xml = simplexml_load_file($file, 'SimpleXMLElement');

            if (is_null($xml) || !($xml instanceof SimpleXMLElement)) {
                return $value;
            }

            $value = $xml->$variable;
        }

        return $value;
    }
	
	// Get Menu title
	function getMenuTitle($Itemid, $start_level = 0, $length_level = 0) {
		if (empty($Itemid)) {
			return array();
		}
		
		static $menus = array();
		
		$id = $Itemid;
		if (!isset($menus[$id])) {
			$joomfish = $this->MijosefConfig->joomfish_trans_url ? ', id' : '';
			
			// Title or Alias
			$part = 'title';
			if ($this->MijosefConfig->menu_url_part == 'alias') {
				$part = 'alias';
			}
			
			$menus[$id] = array();
			
			while ($Itemid > 0) {
				$row = MijoDatabase::loadObject("SELECT {$part} AS name, parent_id{$joomfish} FROM #__menu WHERE id = '{$Itemid}' AND published > 0 AND id > 1"); 
				
				if (is_null($row)) {
					break;
				}
				
				array_unshift($menus[$id], $row->name);
				
				$Itemid = $row->parent_id;
				if ($this->MijosefConfig->parent_menus == '0') {
					break; //  Only last one
				}
			}
		}
		
		if ($this->MijosefConfig->parent_menus == '1' && ($start_level != 0 || $length_level != 0) && !empty($menus[$id])) {
			if ($length_level != 0) {
				return array_slice($menus[$id], $start_level, $length_level);
			}
			else {
				return array_slice($menus[$id], $start_level);
			}
		}
        
        return $menus[$id];
    }
	
	// No follow links
	function noFollow($match) {
		$uri = JFactory::getURI();
		$host = $uri->getHost();
		
		if (substr_count($host, '.') > 1) {
			$host = join('.', array_slice(explode('.', $host), -2));
		}
	
		if (self::_getDomainFromLink($match[3]) != $host && !self::_alreadyNofollow($match[1]) && !self::_alreadyNofollow($match[4])) {
			$attrs = $match[1].$match[4];
			if (self::_alreadyRelAttr($attrs)) {
				$attrs = preg_replace("/(rel=[\"\'].*?)([\"\'])/i", "\\1 nofollow\\2", $attrs);
			} else {
				$attrs .= ' rel="nofollow"';
			}
			return '<a href="'.$match[2].'//'.$match[3].'"'.$attrs.'>'.$match[5].'</a>';
		} else {
			return '<a href="'.$match[2].'//'.$match[3].'"'.$match[1].$match[4].'>'.$match[5].'</a>';
		}
	}
	
	function _getDomainFromLink($url) {
		preg_match("/^(http:\/\/)?([^\/]+)/i", $url, $match);
		$domain = $match[2];
		preg_match("/[^\.\/]+\.[^\.\/]+$/", $domain, $match);
		return $match[0];
	}
	
	function _alreadyNofollow($text) {
		return (preg_match("/rel=[\"\'].*?nofollow.*?[\"\']/i", $text )) ? true : false ;
	}
	
	function _alreadyRelAttr($text) {
		return (preg_match("/rel=[\"\'].*?[\"\']/i", $text )) ? true : false ;
	}
}
?>