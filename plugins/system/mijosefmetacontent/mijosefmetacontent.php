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
defined('_JEXEC') or die('Restricted access');

// Imports

class plgSystemMijosefMetaContent extends JPlugin {

	function __construct(& $subject, $config) {
		parent::__construct($subject, $config);
		
		$factory_file = JPATH_ADMINISTRATOR.'/components/com_mijosef/library/mijosef.php';

		if (file_exists($factory_file)) {
			require_once($factory_file);
			
			if (!class_exists('MijoDatabase')) {
				require_once(JPATH_ADMINISTRATOR.'/components/com_mijosef/library/database.php');
			}
			
			require_once(JPATH_ADMINISTRATOR.'/components/com_mijosef/library/metadata.php');
			require_once(JPATH_ADMINISTRATOR.'/components/com_mijosef/library/utility.php');
			
			$this->MijosefConfig = Mijosef::getConfig();
		}
	}

    function onAfterDispatch() {
		if (!self::_systemCheckup(true)) {
			return;
		}
		
		$url_1 = "index.php?option=com_content";
		
		// Get item id
		$item_id = JRequest::getInt('id');
		$url_2 = "id={$item_id}&view=article";
		$url_3 = "format=pdf";
		
		// Get row
		$url = MijoDatabase::loadResult("SELECT url_sef FROM #__mijosef_urls WHERE url_real LIKE '{$url_1}%' AND url_real LIKE '%{$url_2}%' AND url_real NOT LIKE '%{$url_3}%'");
		
		if ($url && !Mijosef::get('utility')->JoomFishInstalled()) {
			$row = MijoDatabase::loadObject("SELECT id, url_sef, title, description, keywords, lang, robots, googlebot FROM #__mijosef_metadata WHERE url_sef = '{$url}'");
			
			if (!$row) {
				$row = new stdClass();
				$row->id = 0;
				$row->url_sef = $url;
				$row->title = '';
				$row->description = '';
				$row->keywords = '';
				$row->lang = '';
				$row->robots = '';
				$row->googlebot = '';
			}
			
			$mainframe = JFactory::getApplication();
			$mainframe->setUserState('com_mijosef.metadata', $row);
			
			$language = JFactory::getLanguage();
			$language->load('com_mijosef');

			// Render output
			$output	= Mijosef::get('utility')->render(JPATH_ROOT.'/plugins/system/mijosefmetacontent/mijosefmetacontent_tmpl.php');
			
			$document = JFactory::getDocument();
			$document->setBuffer($document->getBuffer('component').$output, 'component');
		}
		
		return true;
    }
	
	function onContentAfterSave($context, &$article, $isNew) {
		if ($isNew) {
			return true;
		}
		
		// Check we are manipulating a valid form.
		if (!in_array($context, array('com_content.article'))) {
			return true;
		}
		
		if (!self::_systemCheckup()) {
			return true;
		}
		
		$id 			= JRequest::getInt('mijosef_id');
		$url_sef 		= JRequest::getString('mijosef_url_sef');
		$title 			= Mijosef::get('utility')->replaceSpecialChars(JRequest::getString('mijosef_title'));
		$description 	= Mijosef::get('utility')->replaceSpecialChars(JRequest::getString('mijosef_desc'));
		$keywords 		= Mijosef::get('utility')->replaceSpecialChars(JRequest::getString('mijosef_key'));
		$lang 			= JRequest::getString('mijosef_lang');
		$robots 		= JRequest::getString('mijosef_robots');
		$googlebot 		= JRequest::getString('mijosef_googlebot');
		
		if ($id == 0) {
			MijoDatabase::query("INSERT IGNORE INTO #__mijosef_metadata (url_sef, title, description, keywords, lang, robots, googlebot) VALUES('{$url_sef}', '{$title}', '{$description}', '{$keywords}', '{$lang}', '{$robots}', '{$googlebot}')");
		}
		else {
			MijoDatabase::query("UPDATE #__mijosef_metadata SET title = '{$title}', description = '{$description}', keywords = '{$keywords}', lang = '{$lang}', robots = '{$robots}', googlebot = '{$googlebot}' WHERE id = {$id}");
		}
	}
	
	function _systemCheckup($layout = false) {		
		// Is backend
		$mainframe = JFactory::getApplication();
		if (!$mainframe->isAdmin()) {
			return false;
		}

		// Joomla SEF is disabled
		$config = JFactory::getConfig();
		if (!$config->getValue('sef')) {
			return false;
		}

		// Check if MijoSEF is enabled
		if ($this->MijosefConfig->mode == 0) {
			return false;
		}
		
		// Is plugin enabled
		if (!JPluginHelper::isEnabled('system', 'mijosef')) {
			return false;
		}
		
		// Is plugin enabled
		if (!JPluginHelper::isEnabled('system', 'mijosefmetacontent')) {
			return false;
		}
		
		// Is com_content
		if (JRequest::getCmd('option') != 'com_content') {
			return false;
		}
		
		// Is edit page
		if ($layout && JRequest::getCmd('layout') != 'edit') {
			return false;
		}
		
		return true;
	}
}
?>