<?php
/**
* @package		MijoSEF
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

class plgSystemMijosef extends JPlugin {

	public function __construct(& $subject, $config) {
		parent::__construct($subject, $config);
		
		if (!$this->_systemCheckup(false)) {
			return;
		}
	}

	public function onAfterInitialise() {
		jimport('joomla.language.helper');
		
		if (is_object($this->MijosefConfig) && $this->MijosefConfig->multilang == 1) {
			JFactory::getApplication()->set('menu_associations', 1);
		}
		
		if (!$this->_systemCheckup(false)) {
			return true;
		}

		Mijosef::get('plugin')->onAfterInitialise();
		
		return true;
	}

    public function onAfterRoute() {
		if (!$this->_systemCheckup()) {
			return true;
		}
		
		Mijosef::get('plugin')->onAfterRoute();
		
		return true;
    }

    public function onAfterDispatch() {
		if (!self::_systemCheckup()) {
			return true;
		}
		
		Mijosef::get('plugin')->onAfterDispatch();
		
		return true;
    }
	
	public function onContentPrepare($context, &$article, &$params = null, $limitstart = 0) {
		if (!self::_systemCheckup()) {
			return true;
		}
		
		Mijosef::get('plugin')->onContentPrepare($article->text);
		
		return;
	}
    
	public function onAfterRender() {
		if (!self::_systemCheckup()) {
			return true;
		}
		
		Mijosef::get('plugin')->onAfterRender();

		return true;
	}
	
	public function onMijosefTags(&$text) {
		if (!self::_systemCheckup()) {
			return;
		}
		
		Mijosef::get('plugin')->onMijosefTags($text);
		
		return;
	}
	
	public function onMijosefIlinks(&$text) {
		if (!self::_systemCheckup()) {
			return;
		}
		
		Mijosef::get('plugin')->onMijosefIlinks($text);
		
		return;
	}
	
	public function onMijosefBookmarks(&$text) {
		if (!self::_systemCheckup()) {
			return;
		}
		
		Mijosef::get('plugin')->onMijosefBookmarks($text);
		
		return;
	}
	
	public function _systemCheckup($check_raw = true) {
		static $status;
		
		if (!isset($status)) {
			$status = true;
			
			if (version_compare(PHP_VERSION, '5.2.0', '<')) {
				JError::raiseWarning('100', JText::sprintf('MijoSEF requires PHP 5.2.x to run, please contact your hosting company.'));
				$status = false;
				return $status;
			}
			
			$mijosef = JPATH_ADMINISTRATOR.'/components/com_mijosef/library/mijosef.php';
			if (!file_exists($mijosef)) {
				$status = false;
				return $status;
			}
			
			require_once($mijosef);
			$this->MijosefConfig = Mijosef::getConfig();
			
			// Is backend
			if (JFactory::getApplication()->isAdmin()) {
				$status = false;
				return $status;
			}

			// Joomla SEF is disabled
			if (!JFactory::getConfig()->get('sef')) {
				$status = false;
				return $status;
			}

			// Check if MijoSEF is enabled
			if ($this->MijosefConfig->mode == 0) {
				$status = false;
				return $status;
			}
			
			// Is plugin enabled
			if (!JPluginHelper::isEnabled('system', 'mijosef')) {
				$status = false;
				return $status;
			}
		}
		
		if ($check_raw == true) {
			$raw = ((JRequest::getCmd('format') == 'raw') || (JRequest::getCmd('format') == 'xml') || (JRequest::getCmd('tmpl') == 'raw'));
			
			if ($raw) {
				$status = false;
			}
		}
		
		return $status;
	}
}
?>