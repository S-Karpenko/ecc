<?php
/**
* @version		1.7.0
* @package		MijoSEF Library
* @subpackage	Language
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license		GNU/GPL based on AceSEF www.joomace.net
*/

// No Permission
defined('_JEXEC') or die('Restricted Access');

// Language class
class MijosefLanguage {

    static $_lang = null;

	public function multilangFix() {
        if (Mijosef::getConfig()->multilang == 0) {
            return;
        }

		$app = JFactory::getApplication();

		if ($app->isAdmin()) {
			return;
		}

		$app->setLanguageFilter(true);
		$app->setDetectBrowser(Mijosef::getConfig()->joomfish_browser == 1);

		$uri = JFactory::getURI();

        $lang = $uri->getVar('lang');

        if (!empty($lang)) {
            return;
        }

        $path = JString::substr($uri->toString(), JString::strlen($uri->base()));
        $parts = explode('/', $path);
		
		if (JString::strlen($parts[0]) != 2) {
			return;
		}

        $sef = $parts[0];

		$lang_codes 	= JLanguageHelper::getLanguages('lang_code');
		$default_lang 	= JComponentHelper::getParams('com_languages')->get('site', 'en-GB');
		$default_sef 	= $lang_codes[$default_lang]->sef;

        if ($sef == $default_sef) {
            return;
        }

        JRequest::setVar('lang', $default_sef);
	}

    public function parseLang($vars) {
        if (Mijosef::getConfig()->multilang == 0) {
            return;
        }

        if (empty($vars['lang'])) {
            $lang = JRequest::getWord('lang', '');

            if (empty($lang)) {
                return;
            }

            $vars['lang'] = $lang;
        }

        $languages = JLanguageHelper::getLanguages('sef');
        $lang_code = $languages[$vars['lang']]->lang_code;

        // if current language, don't bother
        if ($lang_code == JFactory::getLanguage()->getTag()) {
            //self::checkHomepage($vars['lang']);

            return;
        }

        // Create a cookie
        $conf = JFactory::getConfig();
        $cookie_domain 	= $conf->get('config.cookie_domain', '');
        $cookie_path 	= $conf->get('config.cookie_path', '/');
        setcookie(JUtility::getHash('language'), $lang_code, time() + 365 * 86400, $cookie_path, $cookie_domain);

        // set the request var
        JRequest::setVar('language', $lang_code);

        // set current language
        jimport('joomla.language.language');
        $conf	= JFactory::getConfig();
        $debug	= $conf->get('debug_lang');
        $lang	= JLanguage::getInstance($lang_code, $debug);
        JFactory::$language = $lang;

        self::$_lang = $vars['lang'];
    }
	
	public function getLang(&$uri) {
        if (Mijosef::getConfig()->multilang == 0) {
            return '';
        }

		$lang = $uri->getVar('lang');

		if (empty($lang)) {
			$lang = self::$_lang;

            if (empty($lang)) {
                $lang = JRequest::getWord('lang', '');
            }
		}

        if (empty($lang)) {
            return '';
        }

        $uri->setVar('lang', $lang);

		return $lang;
	}

    public function checkHomepage($lang) {
        if (empty($lang)) {
            return;
        }

        $uri = JFactory::getURI();
        $app = JFactory::getApplication();

        $full_url = MijoSef::get('uri')->getFullUrl();

        if ($uri->toString() != $full_url) {
            return;
        }

        $app->redirect($full_url.$lang);
    }

    public function _getLangVar() {
        $uri = JFactory::getURI();

        $lang = $uri->getVar('lang');

        if (!empty($lang)) {
            return $lang;
        }

		$lang = '';
		
        $path = JString::substr($uri->toString(), JString::strlen($uri->base()));
        $parts = explode('/', $path);
		
		if (JString::strlen($parts[0]) != 2) {
			return $lang;
		}

        $lang = $parts[0];

        return $lang;
    }
}