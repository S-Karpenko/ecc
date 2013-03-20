<?php
/*
* @package		MijoSEF
* @subpackage	Search
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license		GNU/GPL based on AceSEF www.joomace.net
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

class MijoSEF_com_search extends MijosefExtension {
	
	function beforeBuild(&$uri) {
        $ord = $uri->getVar('ordering', null);
        if ($ord == '') {
            $uri->delVar('ordering');
        }
		
		if (!is_null($uri->getVar('view')) && $uri->getVar('view') == 'search') {
			$uri->delVar('view');
		}

		if (is_null($uri->getVar('limitstart')) && !is_null($uri->getVar('limit'))){
			$uri->delVar('limit');
		}
        
        $phrase = $uri->getVar('searchphrase', null);
        if ($phrase == 'all') {
            $uri->delVar('searchphrase');
        }
    }
	
	function build(&$vars, &$segments, &$do_sef, &$metadata, &$item_limitstart) {
        extract($vars);
		
		if (isset($searchword)) {
            $segments[] = $searchword;
			$this->meta_desc = $this->meta_title[] = $searchword;
			unset($vars['searchword']);
		}
        
        if (isset($searchphrase)) {
            $segments[] = $searchphrase;
			unset($vars['searchphrase']);
		}
		
		if (isset($ordering)) {
            $segments[] = $ordering;
			unset($vars['ordering']);
		}
        
        if (isset($submit)) {
            $segments[] = $submit;
			unset($vars['submit']);
		}
		
		$metadata = parent::getMetaData($vars, $item_limitstart);
		
		unset($vars['limit']);
		unset($vars['limitstart']);
	}
}
?>