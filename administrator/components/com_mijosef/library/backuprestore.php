<?php
/**
* @version		1.0.0
* @package		MijoSEF Library
* @subpackage	Backup/Restore
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license		GNU/GPL based on AceSEF www.joomace.net
*/

// No Permission
defined('_JEXEC') or die('Restricted Access');

// Backup/Restore class
class MijosefBackupRestore {
	
	protected $_dbprefix;
	protected $_table;
	protected $_where;

	function __construct($options = "") {
		if (is_array($options)) {
			if (isset($options['_table'])) {
				$this->_table = $options['_table'];
			}
			
			if (isset($options['_where'])) {
				$this->_where = $options['_where'];
			}
		}
		
		$this->_dbprefix = JFactory::getConfig()->get('dbprefix');
	}
	
	function backupSefUrls() {
		$filename = "mijosef_urls.sql";
		$fields = array('url_sef', 'url_real', 'used', 'cdate', 'mdate', 'hits', 'source', 'params');
		$line = "INSERT INTO {$this->_dbprefix}mijosef_{$this->_table} (".implode(', ', $fields).")";
		$query = "SELECT url_sef, url_real, used, cdate, mdate, hits, source, params FROM #__mijosef_{$this->_table}{$this->_where}";
		
		return array($query, $filename, $fields, $line);
	}
	
	function backupMovedUrls() {
		$filename = "mijosef_moved_urls.sql";
		$fields = array('url_new', 'url_old', 'published', 'hits', 'last_hit');
		$line = "INSERT INTO {$this->_dbprefix}mijosef_{$this->_table} (".implode(', ', $fields).")";
		$query = "SELECT url_new, url_old, published, hits, last_hit FROM #__mijosef_{$this->_table} {$this->_where}";
		
		return array($query, $filename, $fields, $line);
	}
	
	function backupMetadata() {
		$filename = "mijosef_metadata.sql";
		$fields = array('url_sef', 'published', 'title', 'description', 'keywords', 'lang', 'robots', 'googlebot', 'canonical');
		$line = "INSERT INTO {$this->_dbprefix}mijosef_{$this->_table} (".implode(', ', $fields).")";
		$query = "SELECT url_sef, published, title, description, keywords, lang, robots, googlebot, canonical FROM #__mijosef_{$this->_table} {$this->_where}";
		
		return array($query, $filename, $fields, $line);
	}
	
	function backupSitemap() {
		$filename = "mijosef_sitemap.sql";
		$fields = array('url_sef', 'title', 'published', 'sdate', 'frequency', 'priority', 'sparent', 'sorder');
		$line = "INSERT INTO {$this->_dbprefix}mijosef_{$this->_table} (".implode(', ', $fields).")";
		$query = "SELECT url_sef, title, published, sdate, frequency, priority, sparent, sorder FROM #__mijosef_{$this->_table} {$this->_where}";
		
		return array($query, $filename, $fields, $line);
	}
	
	function backupTags() {
		$filename = "mijosef_tags.sql";
		$fields = array('title', 'alias', 'published', 'description', 'ordering', 'hits');
		$line = "INSERT INTO {$this->_dbprefix}mijosef_{$this->_table} (".implode(', ', $fields).")";
		$query = "SELECT title, alias, published, description, ordering, hits FROM #__mijosef_{$this->_table} {$this->_where}";
		
		return array($query, $filename, $fields, $line);
	}
	
	function backupIlinks() {
		$filename = "mijosef_internal_links.sql";
		$fields = array('word', 'link', 'published', 'nofollow', 'iblank', 'ilimit');
		$line = "INSERT INTO {$this->_dbprefix}mijosef_{$this->_table} (".implode(', ', $fields).")";
		$query = "SELECT word, link, published, nofollow, iblank, ilimit FROM #__mijosef_{$this->_table}{$this->_where}";
		
		return array($query, $filename, $fields, $line);
	}
	
	function backupBookmarks() {
		$filename = "mijosef_bookmarks.sql";
		$fields = array('name', 'html', 'btype', 'placeholder', 'published');
		$line = "INSERT INTO {$this->_dbprefix}mijosef_{$this->_table} (".implode(', ', $fields).")";
		$query = "SELECT name, html, btype, placeholder, published FROM #__mijosef_{$this->_table} {$this->_where}";
		
		return array($query, $filename, $fields, $line);
	}
	
	function restoreSefUrls($line) {
		$preg = '/^INSERT INTO `?(\w)+mijosef_urls`?/';

        $qq = explode(") VALUES ('", $line);
        $line = trim(str_replace("');", "", $qq[1]));

        // Get fields
        $fields = explode("', '", $line);

        $params = '';

        if (!empty($fields[7])) {
            if (strpos($line, 'custom=0 published=') || strpos($line, 'custom=1 published=')) {
                $prms = array();
                $tmp1 = explode(' ', $fields[7]);

                if (is_array($tmp1)) {
                    foreach ($tmp1 as $prm) {
                        $tmp2 = explode('=', $prm);

                        if ($tmp2[0] == 'notes') {
                            $prms[$tmp2[0]] = $tmp2[1];
                        }
                        else {
                            $prms[$tmp2[0]] = (int)$tmp2[1];
                        }
                    }
                }

                $reg = new JRegistry($prms);
                $params = $reg->toString();
            }
            else {
                $params = $fields[7];
            }
        }

        $f = 'url_sef, url_real, used, cdate, mdate, hits, source, params';
        $v = "'{$fields[0]}', '{$fields[1]}', '{$fields[2]}', '{$fields[3]}', '{$fields[4]}', '{$fields[5]}', '{$fields[6]}', '{$params}'";
        $line = "INSERT INTO {$this->_dbprefix}mijosef_urls ({$f}) VALUES ({$v});";
		
		return array($preg, $line);
	}
	
	function restoreMovedUrls($line) {
		$preg = '/^INSERT INTO `?(\w)+mijosef_urls_moved`?/';
		
		return array($preg, $line);
	}
	
	function restoreMetadata($line) {
		$preg = '/^INSERT INTO `?(\w)+mijosef_metadata`?/';
		
		// 1.3 compatibility
		if (strpos($line, ',b,')) {
			$fields = explode(',b,', $line);
			
			$line = "";
			
			$url_sef = MijoDatabase::query("SELECT url_sef FROM #__mijosef_urls WHERE params LIKE '%notfound=0%' AND url_real = '{$fields[0]}'");
			
			if ($url_sef) {
				$f = "url_sef, title, description, keywords, lang, robots, googlebot, canonical";
				$v = "'{$url_sef}', '{$fields[1]}', '{$fields[2]}', '{$fields[3]}', '{$fields[4]}', '{$fields[5]}', '{$fields[6]}', '{$fields[7]}'";
				$line = "INSERT INTO {$this->_dbprefix}mijosef_metadata ({$f}) VALUES ({$v});";
			}
		}
		
		return array($preg, $line);
	}
	
	function restoreSitemap($line) {
		$preg = '/^INSERT INTO `?(\w)+mijosef_sitemap`?/';
		
		// 1.3 compatibility
		if (strpos($line, ',b,')) {
			$fields = explode(',b,', $line);
			
			$line = "";
			
			$url_sef = MijoDatabase::query("SELECT url_sef FROM #__mijosef_urls WHERE params LIKE '%notfound=0%' AND url_real = '{$fields[0]}'");
			
			if ($url_sef) {
				$f = "url_sef, published, sdate, frequency, priority, sparent, sorder";
				$v = "'{$url_sef}', '{$fields[1]}', '{$fields[2]}', '{$fields[3]}', '{$fields[4]}', '0', '1000'";
				$line = "INSERT INTO {$this->_dbprefix}mijosef_sitemap ({$f}) VALUES ({$v});";
			}
		}
		
		return array($preg, $line);
	}
	
	function restoreTags($line) {
		$preg = '/^INSERT INTO `?(\w)+mijosef_tags`?/';
		
		return array($preg, $line);
	}
	
	function restoreIlinks($line) {
		$preg = '/^INSERT INTO `?(\w)+mijosef_ilinks`?/';
		
		return array($preg, $line);
	}
	
	function restoreBookmarks($line) {
		$preg = '/^INSERT INTO `?(\w)+mijosef_bookmarks`?/';
		
		return array($preg, $line);
	}

	function restoreAceSefUrls($line) {
		$preg = '/^INSERT INTO `?(\w)+mijosef_urls`?/';

        $qq = explode(") VALUES ('", $line);
        $line = trim(str_replace("');", "", $qq[1]));

        // Get fields
        $fields = explode("', '", $line);

        $params = '';

        if (!empty($fields[7])) {
            if (strpos($line, 'custom=0 published=') || strpos($line, 'custom=1 published=')) {
                $prms = array();
                $tmp1 = explode(' ', $fields[7]);

                if (is_array($tmp1)) {
                    foreach ($tmp1 as $prm) {
                        $tmp2 = explode('=', $prm);

                        if ($tmp2[0] == 'notes') {
                            $prms[$tmp2[0]] = $tmp2[1];
                        }
                        else {
                            $prms[$tmp2[0]] = (int)$tmp2[1];
                        }
                    }
                }

                $reg = new JRegistry($prms);
                $params = $reg->toString();
            }
            else {
                $params = $fields[7];
            }
        }

        $f = 'url_sef, url_real, used, cdate, mdate, hits, source, params';
        $v = "'{$fields[0]}', '{$fields[1]}', '{$fields[2]}', '{$fields[3]}', '{$fields[4]}', '{$fields[5]}', '{$fields[6]}', '{$params}'";
        $line = "INSERT INTO {$this->_dbprefix}mijosef_urls ({$f}) VALUES ({$v});";

		return array($preg, $line);
	}

    function restoreAceMovedUrls($line) {
        $preg = '/^INSERT INTO `?(\w)+mijosef_urls_moved`?/';

        $line = str_replace('_acesef_', '_mijosef_', $line);

        return array($preg, $line);
    }

    function restoreAceMetadata($line) {
        $preg = '/^INSERT INTO `?(\w)+mijosef_metadata`?/';

        $line = str_replace('_acesef_', '_mijosef_', $line);

        return array($preg, $line);
    }

    function restoreAceSitemap($line) {
        $preg = '/^INSERT INTO `?(\w)+mijosef_sitemap`?/';

        $line = str_replace('_acesef_', '_mijosef_', $line);

        return array($preg, $line);
    }

    function restoreAceTags($line) {
        $preg = '/^INSERT INTO `?(\w)+mijosef_tags`?/';

        $line = str_replace('_acesef_', '_mijosef_', $line);

        return array($preg, $line);
    }

    function restoreAceIlinks($line) {
        $preg = '/^INSERT INTO `?(\w)+mijosef_ilinks`?/';

        $line = str_replace('_acesef_', '_mijosef_', $line);

        return array($preg, $line);
    }

    function restoreAceBookmarks($line) {
        $preg = '/^INSERT INTO `?(\w)+mijosef_bookmarks`?/';

        $line = str_replace('_acesef_', '_mijosef_', $line);

        return array($preg, $line);
    }
	
	function restoreJoomsef($line) {
		$preg = '/^INSERT INTO `?(\w)+mijosef_urls`?/';		
		
		$qq = explode(") VALUES ('", $line);
		$line = trim(str_replace("');", "", $qq[1]));
		
		if (empty($line)) {
			return array($preg, '');
		}
		
		// Get fields
		$fields = explode("', '", $line);
		$this->_cleanFields($field);
		
		$real_url = $fields[2];
		
		// Sort JoomSEF URL structure according to MijoSEF
		if(!empty($real_url) && !empty($fields[3])){
			$urlArray = explode("&", $real_url); // explode url to insert itemid after option
			$real_url = $urlArray[0]."&Itemid=".$fields[3]; // join itemid
			if(!empty($urlArray[1])) $real_url .= '&'.$urlArray[1];
			if(!empty($urlArray[2])) $real_url .= '&'.$urlArray[2];
			if(!empty($urlArray[3])) $real_url .= '&'.$urlArray[3];
			if(!empty($urlArray[4])) $real_url .= '&'.$urlArray[4];
			if(!empty($urlArray[5])) $real_url .= '&'.$urlArray[5];
			if(!empty($urlArray[6])) $real_url .= '&'.$urlArray[6];
			if(!empty($urlArray[7])) $real_url .= '&'.$urlArray[7];
			if(!empty($urlArray[8])) $real_url .= '&'.$urlArray[8];
		}		
		
		$vars = array();
		$vars['url_sef'] = $fields[1];
		$vars['url_real'] = $real_url;
		$vars['mdate'] = $fields[11];
		$vars['used'] = 1;
		$vars['source'] = '';
		$vars['published'] = 1;
		$vars['locked'] = 0;
		$vars['blocked'] = 0;
		$vars['notes'] = '';			
		$line = self::_getMijosefLine($vars);

		// Metadata
		$no_meta = (empty($fields[6]) && empty($fields[4]) && empty($fields[5]));
		if (!$no_meta && !is_object(MijoDatabase::loadObject("SELECT url_sef FROM #__mijosef_metadata WHERE url_sef = '{$fields[1]}'"))) {
			$f = "url_sef, title, description, keywords, lang, robots, googlebot, canonical";
			$v = "'{$fields[1]}', '{$fields[6]}', '{$fields[4]}', '{$fields[5]}', '{$fields[7]}', '{$fields[8]}', '{$fields[9]}', '{$fields[10]}'";
			MijoDatabase::query("INSERT INTO #__mijosef_metadata ({$f}) VALUES ({$v})");
		}
		
		return array($preg, $line);
	}
	
	function restoreShUrl($line) {
		$preg = '/^INSERT INTO `?(\w)+mijosef_urls`?/';

		$delete = '"id","Count","Rank","SEF URL","non-SEF URL","Date added"\n';
		$line = str_replace($delete, " ", $line);
		$line = trim(str_replace("\n", " ", $line));
		
		if (empty($line)) {
			return array($preg, '');
		}
		
		$fields = explode('","', $line);
		$this->_cleanFields($fields);
		$this->_shUnEmpty($fields);
		
		// Remove lang string if JoomFish not installed :   index.php?option=com_banners&bid=3&lang=en&task=click
		if (!Mijosef::get('utility')->JoomFishInstalled() && !empty($fields[4])) {
			$pos = strpos($fields[4], 'lang=');
			$lang = substr($fields[4], $pos, 8);
			$fields[4] = str_replace($lang, "", $fields[4]);
		}
		
		$vars = array();
		$vars['url_sef'] = $fields[3];
		$vars['url_real'] = $fields[4];
		$vars['mdate'] = $fields[5];
		$vars['used'] = 1;
		$vars['source'] = '';
		$vars['published'] = 1;
		$vars['locked'] = 0;
		$vars['blocked'] = 0;
		$vars['notes'] = '';			
		$line = self::_getMijosefLine($vars);
		
		return array($preg, $line);
	}
	
	function restoreShMetadata($line) {
		$preg = '/^INSERT INTO `?(\w)+mijosef_metadata`?/';
		
		$line = trim(str_replace(";;", "", $line));
		$delete = '"id","newurl","metadesc","metakey","metatitle","metalang","metarobots"';
		$line = str_replace($delete, " ", $line);
		$line = trim(str_replace("\n", " ", $line));
		
		if (empty($line)) {
			return array($preg, '');
		}
		
		$field = explode('","', $line);
		$this->_cleanFields($field);
		$this->_shUnEmpty($field);
		
		// Remove lang string if JoomFish not installed :   index.php?option=com_banners&bid=3&lang=en&task=click
		$path = JPATH_ROOT.'/administrator/components/com_joomfish/joomfish.php';
		if (!Mijosef::get('utility')->JoomFishInstalled() && !empty($field[1])) {
			$pos = strpos($field[1], 'lang=');
			$lang = substr($field[1], $pos, 8);
			$field[1] = str_replace($lang, "", $field[1]);
		}
		
		$line = "";
		
		$url_sef = MijoDatabase::query("SELECT url_sef FROM #__mijosef_urls WHERE params LIKE '%notfound=0%' AND url_real = '{$fields[1]}'");
		if ($url_sef && !is_object(MijoDatabase::loadObject("SELECT url_sef FROM #__mijosef_metadata WHERE url_sef = '{$url_sef}'"))) {
			$f = "url_sef, title, description, keywords, lang, robots";
			$v = "'{$url_sef}', '{$fields[4]}', '{$fields[2]}', '{$fields[3]}', '{$fields[5]}', '{$fields[6]}'";
			$line = "INSERT INTO {$this->_dbprefix}mijosef_metadata ({$f}) VALUES ({$v});";
		}
		
		return array($preg, $line);
	}
	
	function restoreSh2($line) {
		$preg = '/^INSERT INTO `?(\w)+mijosef_urls`?/';

		$delete = '"Nbr","Sef url","Non sef url","Hits","Rank","Date added","Page title","Page description","Page keywords","Page language","Robots tag"\n';
		$line = str_replace($delete, " ", $line);
		$line = trim(str_replace("\n", " ", $line));
		
		if (empty($line)) {
			return array($preg, '');
		}
		
		$fields = explode('","', $line);
		$this->_cleanFields($fields);
		$this->_shUnEmpty($fields);
		
		$real_url = $fields[2];
		
		// Remove lang string if JoomFish not installed :   index.php?option=com_banners&bid=3&lang=en&task=click
		if (!Mijosef::get('utility')->JoomFishInstalled() && !empty($real_url)) {
			$pos = strpos($real_url, 'lang=');
			$lang = substr($real_url, $pos, 8);
			$real_url = str_replace($lang, "", $real_url);
		}
		
		$vars = array();
		$vars['url_sef'] = $fields[1];
		$vars['url_real'] = $real_url;
		$vars['mdate'] = $fields[5];
		$vars['used'] = 1;
		$vars['source'] = '';
		$vars['published'] = 1;
		$vars['locked'] = 0;
		$vars['blocked'] = 0;
		$vars['notes'] = '';			
		$line = self::_getMijosefLine($vars);
		
		// Metadata
		$no_meta = (empty($fields[6]) && empty($fields[7]) && empty($fields[8]));
		if (!$no_meta && !is_object(MijoDatabase::loadObject("SELECT url_sef FROM #__mijosef_metadata WHERE url_sef = '{$fields[1]}'"))) {
			$f = "url_sef, title, description, keywords, lang, robots";
			$v = "'{$fields[1]}', '{$fields[6]}', '{$fields[7]}', '{$fields[8]}', '{$fields[9]}', '{$fields[10]}'";
			MijoDatabase::query("INSERT INTO #__mijosef_metadata ({$f}) VALUES ({$v})");
		}
		
		return array($preg, $line);
	}
	
	function restoreShAliases($line) {
		$preg = '/^INSERT INTO `?(\w)+mijosef_urls_moved`?/';

		$delete = '"Nbr","Alias","Sef url","Non sef url","Type","Hits"';
		$line = str_replace($delete, " ", $line);
		$line = trim(str_replace("\n", " ", $line));
		
		if (empty($line)) {
			return array($preg, '');
		}
		
		$fields = explode('","', $line);
		$this->_cleanFields($fields);
		$this->_shUnEmpty($fields);
		
		$line = '';
		
		if (!is_object(MijoDatabase::loadObject("SELECT url_old FROM #__mijosef_urls_moved WHERE url_old = '{$fields[1]}'"))) {
			$f = "url_new, url_old, hits";
			$v = "'{$fields[2]}', '{$fields[1]}', '{$fields[5]}'";
			$line = "INSERT INTO {$this->_dbprefix}mijosef_urls_moved ({$f}) VALUES ({$v});";
		}
		
		return array($preg, $line);
	}
	
	function _getMijosefLine($vars) {		
		$f = "url_sef, url_real, mdate, used, source, params";
		
		$url_real = $vars['url_real'];
		$notfound = 0;
		if ($url_real == "") {
			$url_real = $vars['url_sef'];
			$notfound = 1;
		}
		
		$found = MijoDatabase::loadObject("SELECT url_sef FROM #__mijosef_urls WHERE url_real = '{$url_real}'");
		if (is_object($found)) {
			return "";
		}
		
		$custom = 0;
		if ($vars['mdate'] != '0000-00-00') {
			$custom = 1;
		}
		
		switch($vars['used']) {
			case 10:
				$used = 2;
				break;
			case 0:
				$used = 1;
				break;
			case 5:
				$used = 0;
				break;
			default:
				$used = 0;
				break;
		}
		
		$trashed = 0;
		if (isset($vars['trashed'])) {
			$trashed = $vars['trashed'];
		}

        $prms = array();
        $prms['custom'] = (int)$custom;
        $prms['published'] = (int)$vars['published'];
        $prms['locked'] = (int)$vars['locked'];
        $prms['blocked'] = (int)$vars['blocked'];
        $prms['trashed'] = (int)$trashed;
        $prms['notfound'] = (int)$notfound;
        $prms['tags'] = 0;
        $prms['ilinks'] = 0;
        $prms['bookmarks'] = 0;
        $prms['visited'] = 0;
        $prms['notes'] = $vars['notes'];

        $reg = new JRegistry($prms);
        $params = $reg->toString();
		
		$v = "'{$vars['url_sef']}', '{$url_real}', '{$vars['mdate']} 00:00:00', '{$used}', '{$vars['source']}', '{$params}'";
		$line = "INSERT INTO {$this->_dbprefix}mijosef_urls ({$f}) VALUES ({$v});";
		
		return $line;
	}
	
	function _cleanFields(&$fields) {
		for ($i = 0, $n = count($fields); $i < $n; $i++) {
			$replace = array('\"', "\'", '', '');
			$fields[$i] = str_replace(array('"', "'", '#', '`'), $replace, $fields[$i]);
		}
    }

    function _shUnEmpty(&$fields) {
		for ($i = 0, $n = count($fields); $i < $n; $i++) {
			if ($fields[$i] == '&nbsp') {
				$fields[$i] = '';
			}
		}
    }
}