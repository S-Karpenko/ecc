<?php
/**
* @version		1.0.0
* @package		MijoSEF Library
* @subpackage	Cache
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license		GNU/GPL based on AceSEF www.joomace.net
*/

// No Permission
defined('_JEXEC') or die('Restricted Access');

// Imports
jimport('joomla.cache.cache');
jimport('joomla.html.parameter');

// Cache class
class MijosefCache extends JCache {

	function __construct($lifetime) {
		$this->MijosefConfig = Mijosef::getConfig();
		
		$JoomlaConfig =& JFactory::getConfig();
        
		$options = array(
			'defaultgroup' 	=> 'com_mijosef',
			'cachebase'		=> JPATH_SITE.'/cache',
			'lifetime' 		=> $lifetime,
			'language' 		=> 'en-GB',
			'storage'		=> $JoomlaConfig->get('cache_handler', 'file'),
            'caching'       => true,
		);
		
		parent::__construct($options);
	}

    function load($id) {
        $content = parent::get($id);
        
        if ($content === false) {
            return false;
        }
        
        $cache = @unserialize($content);
		
		if ($cache === false || !is_array($cache)) {
            return false;
        }
		
		return $cache;
    }
	
	function save($content, $id) {
		// Store the cache string
		for ($i = 0; $i < 5; $i++) {
            if (parent::store(serialize($content), $id)) {
                return;
            }
        }
		
		parent::remove($id);
	}
	
	function getExtensionParams($option) {
		if ($this->MijosefConfig->cache_extensions == 1) {
			$cache = Mijosef::getCache();
			$cached_params = $cache->load('extensions');
		
			if (isset($cached_params[$option])) {
				$prm = new JRegistry($cached_params[$option]->params);
				return $prm;
			}
		}

        static $params;
		if (!isset($params)) {
			$fields = "extension, params";
			$params = MijoDatabase::loadObjectList("SELECT {$fields} FROM #__mijosef_extensions", "extension");
		}
		
		if (isset($params[$option])) {
			if ($this->MijosefConfig->cache_extensions == 1) {
				$cache->save($params, 'extensions');
			}
			
			$prm = new JRegistry($params[$option]->params);
			return $prm;
		} else {
			$data = self::_setExtension($option);
		}
		
		if ($data) {
			$params[$option] = new stdClass();
			$params[$option]->extension = $option;
			$params[$option]->params = $data;
			
			if ($this->MijosefConfig->cache_extensions == 1) {
				$cached_params[$option] = $params[$option];
				$cache->save($cached_params, 'extensions');
			}
			
			$prm = new JRegistry($data);
			return $prm;
		}
		
		return false;
    }
	
	function checkURL($url, $is_sef = false, $is_id = false, $ignore_multi_itemid = false) {
		static $urls = array();
		static $urls_db;
		
		if ($this->MijosefConfig->cache_urls == 1) {
			$cache = Mijosef::getCache();
			$urls_cached = $cache->load('urls');
			
			if ($is_sef) {
				$row_1 = self::_checkURLbySEF($urls_cached, $url);
			}
			elseif ($is_id) {
				$row_1 = self::_checkURLbyID($urls_cached, $url);
			}
			else {
				$row_1 = false;
				if (isset($urls_cached[$url])) {
					$row_1 = $urls_cached[$url];
				}
			}
			
			if (is_object($row_1)) {
				return $row_1;
			}
		}
		
		if ($this->MijosefConfig->cache_instant == 1) {			
			if (!isset($urls_db)) {
				$fields = "id, url_sef, url_real, used, params";
				$urls_db = MijoDatabase::loadObjectList("SELECT {$fields} FROM #__mijosef_urls WHERE params LIKE '%\"notfound\":0%' ORDER BY used DESC, cdate DESC, id ASC", "url_real");
			}
			
			if ($is_sef) {
				$row_2 = self::_checkURLbySEF($urls_db, $url);
			}
			elseif ($is_id) {
				$row_2 = self::_checkURLbyID($urls_db, $url);
			}
			else {
				$row_2 = false;
				if (isset($urls_db[$url])) {
					$row_2 = $urls_db[$url];
				}
			}
			
			if (is_object($row_2)) {
				$i = $url;
				if ($is_sef || $is_id) {
					$i = $row_2->url_real;
				}
			
				if ($this->MijosefConfig->cache_urls == 1 && (count($urls_db) < $this->MijosefConfig->cache_urls_size)) {
					$urls_cached[$i] = $row_2;
					$cache->save($urls_cached, 'urls');
				}
				
				return $row_2;
			}
		}
		
		if (!isset($urls[$url])) {
			$u = MijoDatabase::quote($url);
			$fields = "id, url_sef, url_real, used, params";
			
			if ($is_sef) {
				if($this->MijosefConfig->tolerant_to_trailing_slash == 1) {
					$find_url = rtrim($u, '/');
					$find_url2 = rtrim($url, '/');
					$find_url2 = $find_url2.'/';
					$find_url2 = MijoDatabase::quote($find_url2);
					$where_url = "(url_sef = {$find_url}) OR (url_sef = ".$find_url2.")";
				} else {
					$where_url = "url_sef = {$u}";
				}
				
				$row_3 = MijoDatabase::loadObject("SELECT {$fields} FROM #__mijosef_urls WHERE {$where_url} AND params LIKE '%\"notfound\":0%' ORDER BY used DESC, cdate ASC, id ASC LIMIT 1");
			}
			elseif ($is_id) {
				$row_3 = MijoDatabase::loadObject("SELECT {$fields} FROM #__mijosef_urls WHERE id = {$u} AND params LIKE '%\"notfound\":0%' LIMIT 1");
			}
			elseif ($ignore_multi_itemid) {
				$url_itemid = str_replace('&amp;', '&', $url);
				$url_itemid = str_replace('index.php?', '', $url_itemid);
				parse_str($url_itemid, $vars_itemid);

				$skipped_components = array('com_wrapper');
				if (!empty($vars_itemid['option']) && !in_array($vars_itemid['option'], $skipped_components)) {
					unset($vars_itemid['Itemid']);

					$url_1 = 'index.php?option='.$vars_itemid['option'];
					unset($vars_itemid['option']);

					$url_2 = '';

					foreach ($vars_itemid as $var => $value) {
						$url_2 .= '&'.$var.'='.$value;
					}

					$row_3 = MijoDatabase::loadObject("SELECT {$fields} FROM #__mijosef_urls WHERE url_real LIKE '{$url_1}%' AND url_real LIKE '%{$url_2}' AND params LIKE '%\"notfound\":0%' LIMIT 1");
				}
			}
			else {
				$row_3 = MijoDatabase::loadObject("SELECT {$fields} FROM #__mijosef_urls WHERE url_real = {$u} AND params LIKE '%\"notfound\":0%' LIMIT 1");
			}
			
			if (is_object($row_3)) {
				$urls[$url] = $row_3;
			}
		}
		
		if (isset($urls[$url])) {
			$i = $url;
			if ($is_sef || $is_id) {
				$i = $urls[$url]->url_real;
			}
			
			if ($this->MijosefConfig->cache_instant == 1) {
				$urls_db[$i] = $urls[$url];
			}
			
			if ($this->MijosefConfig->cache_urls == 1 && (count($urls_cached) < $this->MijosefConfig->cache_urls_size)) {
				$urls_cached[$i] = $urls[$url];
				$cache->save($urls_cached, 'urls');
			}
			
			return $urls[$url];
		}
		
		return false;
	}
	
	function _checkURLbySEF($urls, $url) {
		if ($urls && is_array($urls) && !empty($urls)) {
			self::_checkSefTrailingSlash($urls, $url);
			
			foreach ($urls as $index => $object) {
				if ((strcasecmp($object->url_sef, $url) == 0)) {
					$sef = $object;
					if ($object->used == '2') {
						return $object;
					}
				}
			}
			
			if (isset($sef)) {
				return $sef;
			}
		}
		
		return false;
    }
	
	function _checkURLbyID($urls, $id) {
		if ($urls && is_array($urls) && !empty($urls)) {			
			foreach ($urls as $index => $object) {
				if ($object->id == $id) {
					$sef = $object;
					if ($object->used == '2') {
						return $object;
					}
				}
			}
			
			if (isset($sef)) {
				return $sef;
			}
		}
		
		return false;
    }
	
	function checkMovedURL($url) {
		static $moved = array();
		static $moved_db;
		
		if ($this->MijosefConfig->cache_urls_moved == 1) {
			$cache = Mijosef::getCache();
			$moved_cached = $cache->load('urls_moved');
			
			self::_checkMovedTrailingSlash($moved_cached, $url);
		
			if (isset($moved_cached[$url])) {
				return $moved_cached[$url];
			}
		}
		
		if ($this->MijosefConfig->cache_instant == 1) {
			if (!isset($moved_db)) {
				$moved_db = MijoDatabase::loadObjectList("SELECT * FROM #__mijosef_urls_moved WHERE published = '1'", "url_old");
			}
		
			self::_checkMovedTrailingSlash($moved_db, $url);
			
			if (isset($moved_db[$url])) {
				if ($this->MijosefConfig->cache_urls_moved == 1) {
					$moved_cached[$url] = $moved_db[$url];
					$cache->save($moved_db, 'urls_moved');
				}
				
				return $moved_db[$url];
			}
		}
		
		if (!isset($moved[$url])) {
			$u = MijoDatabase::quote($url);
			
			if($this->MijosefConfig->tolerant_to_trailing_slash == 1) {
				$find_url = rtrim($u, '/');
					$find_url2 = rtrim($url, '/');
					$find_url2 = $find_url2.'/';
					$find_url2 = MijoDatabase::quote($find_url2);
				$where_url = "(url_old = {$find_url}) OR (url_old = ".$find_url2.")";
			} else {
				$where_url = "url_old = {$u}";
			}
			
			$row = MijoDatabase::loadObject("SELECT * FROM #__mijosef_urls_moved WHERE {$where_url} AND published = '1'");
			
			if (is_object($row)) {
				$moved[$url] = $row;
			}
		}
		
		if (isset($moved[$url])) {
			if ($this->MijosefConfig->cache_instant == 1) {
				$moved_db[$url] = $moved[$url];
			}
			
			if ($this->MijosefConfig->cache_urls_moved == 1) {
				$moved_cached[$url] = $moved[$url];
				$cache->save($moved_cached, 'urls_moved');
			}
			
			return $moved[$url];
		}
		
		return false;
    }
	
	function checkMetadata($url) {
		static $meta = array();
		static $meta_db;
		
		if ($this->MijosefConfig->cache_metadata == 1) {
			$cache = Mijosef::getCache();
			$meta_cached = $cache->load('metadata');
		
			if (isset($meta_cached[$url])) {
				return $meta_cached[$url];
			}
		}
		
		if ($this->MijosefConfig->cache_instant == 1) {
			if (!isset($meta_db)) {
				$meta_db = MijoDatabase::loadObjectList("SELECT * FROM #__mijosef_metadata WHERE published = '1'", "url_sef");
			}
			
			if (isset($meta_db[$url])) {
				if ($this->MijosefConfig->cache_metadata == 1) {
					$meta_cached[$url] = $meta_db[$url];
					$cache->save($meta_cached, 'metadata');
				}
				
				return $meta_db[$url];
			}
		}
		
		if (!isset($meta[$url])) {
			$u = MijoDatabase::quote($url);
			
			$row = MijoDatabase::loadObject("SELECT * FROM #__mijosef_metadata WHERE url_sef = {$u} AND published = '1'");
			
			if (is_object($row)) {
				$meta[$url] = $row;
			}
		}
		
		if (isset($meta[$url])) {
			if ($this->MijosefConfig->cache_instant == 1) {
				$meta_db[$url] = $meta[$url];
			}
			
			if ($this->MijosefConfig->cache_metadata == 1) {
				$meta_cached[$url] = $meta[$url];
				$cache->save($meta_cached, 'metadata');
			}
			
			return $meta[$url];
		}
		
		return false;
    }
	
	function checkSitemap($url) {
		static $sitemap = array();
		static $sitemap_db;
		
		if ($this->MijosefConfig->cache_sitemap == 1) {
			$cache = Mijosef::getCache();
			$sitemap_cached = $cache->load('sitemap');
		
			if (isset($sitemap_cached[$url])) {
				return $sitemap_cached[$url];
			}
		}
		
		if ($this->MijosefConfig->cache_instant == 1) {
			if (!isset($sitemap_db)) {
				$sitemap_db = MijoDatabase::loadObjectList("SELECT * FROM #__mijosef_sitemap WHERE published = '1'", "url_sef");
			}
			
			if (isset($sitemap_db[$url])) {
				if ($this->MijosefConfig->cache_sitemap == 1) {
					$cache->save($sitemap_db, 'sitemap');
				}
				
				return $sitemap_db[$url];
			}
		}
		
		if (!isset($sitemap[$url])) {
			$u = MijoDatabase::quote($url);
			
			$row = MijoDatabase::loadObject("SELECT * FROM #__mijosef_sitemap WHERE url_sef = {$u} AND published = '1'");
			
			if (is_object($row)) {
				$sitemap[$url] = $row;
			}
		}
		
		if (isset($sitemap[$url])) {
			if ($this->MijosefConfig->cache_instant == 1) {
				$sitemap_db[$url] = $sitemap[$url];
			}
			
			if ($this->MijosefConfig->cache_sitemap == 1) {
				$sitemap_cached[$url] = $sitemap[$url];
				$cache->save($sitemap_db, 'sitemap');
			}
			
			return $sitemap[$url];
		}
		
		return false;
    }
	
	function checkTags($tag, $all = false) {
		static $tags = array();
		static $tags_db;
		
		if ($this->MijosefConfig->cache_tags == 1) {
			$cache = Mijosef::getCache();
			$tags_cached = $cache->load('tags');
			
			if ($all && !empty($tags_cached)) {
				return $tags_cached;
			}
			elseif (!empty($tags_cached[$tag])) {
				return $tags_cached[$tag];
			}
		}
		
		if ($this->MijosefConfig->cache_instant == 1 || ($this->MijosefConfig->cache_instant == 0 && $all)) {			
			if (!isset($tags_db)) {
				$tags_db = MijoDatabase::loadObjectList("SELECT * FROM #__mijosef_tags WHERE published = '1' ORDER BY {$this->MijosefConfig->tags_order}", "title");
			}
			
			if ($all) {
				if ($this->MijosefConfig->cache_tags == 1) {
					$cache->save($tags_db, 'tags');
				}
				
				return $tags_db;
			}
			elseif (isset($tags_db[$tag])) {
				if ($this->MijosefConfig->cache_tags == 1) {
					$tags_cached[$tag] = $tags_db[$tag];
					$cache->save($tags_cached, 'tags');
				}
				
				return $tags_db[$tag];
			}
		}
		
		if (!$all) {
			if (!isset($tags[$tag])) {
				$t = MijoDatabase::quote($tag);
			
				$row = MijoDatabase::loadObject("SELECT * FROM #__mijosef_tags WHERE title = {$t} AND published = '1'");
			
				if (is_object($row)) {
					$tags[$tag] = $row;
				}
			}
			
			if (isset($tags[$tag])) {
				if ($this->MijosefConfig->cache_instant == 1) {
					$tags[$tag] = $tags[$tag];
				}
				
				if ($this->MijosefConfig->cache_tags == 1) {
					$tags_cached[$tag] = $tags[$tag];
					$cache->save($tags_cached, 'tags');
				}
				
				return $tags[$tag];
			}
		}
		
		return false;
    }
	
	function getTagsMap($sef_url) {
		static $map = array();
		
		if (!isset($map[$sef_url])) {
			$map[$sef_url] = MijoDatabase::loadAssocList("SELECT tag FROM #__mijosef_tags_map WHERE url_sef = '{$sef_url}'", "tag");
		}
		
		return $map[$sef_url];
	}
	
	function getInternalLinks() {
		static $ilinks_db;
		
		if ($this->MijosefConfig->cache_ilinks == 1) {
			$cache = Mijosef::getCache();
			$ilinks_cached = $cache->load('ilinks');
		
			if (!empty($ilinks_cached)) {
				return $ilinks_cached;
			}
		}
		
		if (!isset($ilinks_db)) {
			$ilinks_db = MijoDatabase::loadObjectList("SELECT * FROM #__mijosef_ilinks WHERE published = '1'", "word");
		}
		
		if (!empty($ilinks_db)) {
			if ($this->MijosefConfig->cache_ilinks == 1) {
				$cache->save($ilinks_db, 'ilinks');
			}
			
			return $ilinks_db;
		}
		
		return false;
    }
	
	function getBookmarks() {
		static $bookmarks;
		
		if (!isset($bookmarks)) {
			$bookmarks = MijoDatabase::loadObjectList("SELECT name, btype, html, placeholder FROM #__mijosef_bookmarks WHERE published = 1 ORDER BY name", "name");
		}
		
		if (!empty($bookmarks)) {
			return $bookmarks;
		}
		
		return false;
    }
	
	function _checkSefTrailingSlash($urls, &$url) {
		if ($this->MijosefConfig->tolerant_to_trailing_slash == 1) {
            $url = rtrim($url, '/');
			foreach ($urls as $index => $object) {
				if ((strcasecmp($object->url_sef, $url) == 0)) {
					return;
				}
			}
			$url .= '/';
        }
	}
	
	function _checkMovedTrailingSlash($urls, &$url) {
		if ($this->MijosefConfig->tolerant_to_trailing_slash == 1) {
            $url = rtrim($url, '/');
			if(!isset($urls[$url])) {
				$url .= '/';
			}
        }
	}
	
	function _setExtension($option) {
		static $components = array();
		
		if (!isset($components[$option])) {
			$filter = Mijosef::get('utility')->getSkippedComponents();
			$component = MijoDatabase::loadResult("SELECT `element` FROM `#__extensions` WHERE `type` = 'component' AND `element` NOT IN ({$filter}) AND `element` = '{$option}'");

			if (!is_null($component)) {
				$routed = false;
				$name = "";
				
				if (!$routed) {
					if (file_exists(JPATH_MIJOSEF_ADMIN.'/extensions/'.$component.'.php')) {
						$name = Mijosef::get('utility')->getXmlText(JPATH_MIJOSEF_ADMIN.'/extensions/'.$component.'.xml', 'name');
						$router = 3;
						$routed = true;
					}
				}
				
				if (!$routed) {
					if (file_exists(JPATH_SITE.'/components/'.$component.'/router.php')) {
						$router = 2;
						$routed = true;
					}
				}
				
				if (!$routed) {
					$router = 1;
				}

				$prms = array();
				$prms['router'] = "{$router}";
				$prms['prefix'] = "";
				$prms['skip_menu'] = "0";
				
				$reg = new JRegistry($prms);
				$params = $reg->toString();
				
				MijoDatabase::query("INSERT IGNORE INTO #__mijosef_extensions (name, extension, params) VALUES ('{$name}', '{$component}', '{$params}')");
				
				$components[$option] = $params;
			} else {
				$components[$option] = false;
			}
		}
		
		return $components[$option];
	}
}
?>