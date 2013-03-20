<?php
/*
* @package		MijoSEF
* @subpackage	Wrapper
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license		GNU/GPL based on AceSEF www.joomace.net
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

class MijoSEF_com_wrapper extends MijosefExtension {
	
	function beforeBuild(&$uri) {
        if (is_null($uri->getVar('view'))) {
            $uri->setVar('view', 'wrapper');
		}
    }

	function build(&$vars, &$segments, &$do_sef, &$metadata, &$item_limitstart) {
		// Extract variables
        extract($vars);
		
		unset($vars['view']);
		
		$metadata = parent::getMetaData($vars, $item_limitstart);
		
		unset($vars['limit']);
		unset($vars['limitstart']);
	}
}
?>