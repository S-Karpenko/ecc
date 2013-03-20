<?php
/**
* @package		MijoSEF
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die('Restricted Access');

define('MIJOSEF_PACK', 'basic');
define('JPATH_MIJOSEF', JPATH_ROOT.'/components/com_mijosef');
define('JPATH_MIJOSEF_ADMIN', JPATH_ROOT.'/administrator/components/com_mijosef');
define('JPATH_MIJOSEF_LIB', JPATH_MIJOSEF_ADMIN.'/library');

JLoader::register('JRouterMijosef', JPATH_MIJOSEF_LIB.'/router.php');

if (!class_exists('MijoDatabase')) {
	JLoader::register('MijoDatabase', JPATH_MIJOSEF_LIB.'/database.php');
}

abstract class MijoSef {

    public static function &get($class, $options = null) {
        static $instances = array();
			
		if (!isset($instances[$class])) {			
			require_once(JPATH_MIJOSEF_LIB.'/'.$class.'.php');
			
			if ($class == 'router') {
				$class_name = 'JRouterMijosef';
			}
			else {
				$class_name = 'Mijosef'.ucfirst($class);
			}
			
			$instances[$class] = new $class_name($options);
		}

		return $instances[$class];
    }
	
	public static function &getConfig() {
		static $instance;

        if (version_compare(PHP_VERSION, '5.2.0', '<')) {
			JError::raiseWarning('100', JText::sprintf('MijoSEF requires PHP 5.2.x to run, please contact your hosting company.'));
			return false;
		}
		
		if (!is_object($instance)) {
			jimport('joomla.application.component.helper');

			$reg = new JRegistry(JComponentHelper::getParams('com_mijosef'));

            $instance = $reg->toObject()->data;
		}
		
		return $instance;
	}
	
	public static function &getCache($lifetime = '315360000') {
		static $instances = array();
		
		if (!isset($instances[$lifetime])) {
			require_once(JPATH_ADMINISTRATOR.'/components/com_mijosef/library/cache.php');
			
			$instances[$lifetime] = new MijosefCache($lifetime);
		}
		
		return $instances[$lifetime];
	}
	
	public static function getTable($name) {
		static $tables = array();
		
		if (!isset($tables[$name])) {
			JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_mijosef/tables');
			$tables[$name] = JTable::getInstance($name, 'Table');
		}
		
		return $tables[$name];
	}
	
	public static function &getExtension($option) {
		static $instances = array();
		
		if (!isset($instances[$option])) {
			require_once(JPATH_ADMINISTRATOR.'/components/com_mijosef/library/extension.php');
			
			$file = JPATH_ADMINISTRATOR.'/components/com_mijosef/extensions/'.$option.'.php';
			
			if (!file_exists($file)) {
				$instances[$option] = null;
				
				return $instances[$option];
			}
			
			require_once($file);
			
			$cache = self::getCache();
			$ext_params = $cache->getExtensionParams($option);
			if (!$ext_params) {
				$ext_params = new JRegistry('');
			}
			
			$class_name = 'MijoSEF_'.$option;
			
			$instances[$option] = new $class_name($ext_params);
		}
		
		$instances[$option]->resetMetadata();
		$instances[$option]->skipMenu(false);
		
		return $instances[$option];
	}
}

require_once(JPATH_MIJOSEF_LIB.'/factory.php');