<?php
/**
* @version		1.0.0
* @package		MijoSEF Library
* @subpackage	Sitemap
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license		GNU/GPL based on AceSEF www.joomace.net
*/

// No Permission
defined('_JEXEC') or die('Restricted Access');

// Sitemap class
class MijosefSitemap {

	function getPriorityList() {		
		static $list;
		
		if(!isset($list)) {
			$list[] = JHTML::_('select.option', '0.0', '0.0');
			$list[] = JHTML::_('select.option', '0.1', '0.1');
			$list[] = JHTML::_('select.option', '0.2', '0.2');
			$list[] = JHTML::_('select.option', '0.3', '0.3');
			$list[] = JHTML::_('select.option', '0.4', '0.4');
			$list[] = JHTML::_('select.option', '0.5', '0.5');
			$list[] = JHTML::_('select.option', '0.6', '0.6');
			$list[] = JHTML::_('select.option', '0.7', '0.7');
			$list[] = JHTML::_('select.option', '0.8', '0.8');
			$list[] = JHTML::_('select.option', '0.9', '0.9');
			$list[] = JHTML::_('select.option', '1.0', '1.0');
		}
		
		return $list;
	}
	
	function getFrequencyList() {		
		static $list;
		
		if(!isset($list)) {
			$list[] = JHTML::_('select.option', 'always', JText::_('COM_MIJOSEF_SITEMAP_SELECT_ALWAYS'));
			$list[] = JHTML::_('select.option', 'hourly', JText::_('COM_MIJOSEF_SITEMAP_SELECT_HOURLY'));
			$list[] = JHTML::_('select.option', 'daily', JText::_('COM_MIJOSEF_SITEMAP_SELECT_DAILY'));
			$list[] = JHTML::_('select.option', 'weekly', JText::_('COM_MIJOSEF_SITEMAP_SELECT_WEEKLY'));
			$list[] = JHTML::_('select.option', 'monthly', JText::_('COM_MIJOSEF_SITEMAP_SELECT_MONTHLY'));
			$list[] = JHTML::_('select.option', 'yearly', JText::_('COM_MIJOSEF_SITEMAP_SELECT_YEARLY'));
			$list[] = JHTML::_('select.option', 'never', JText::_('COM_MIJOSEF_SITEMAP_SELECT_NEVER'));
		}
		
		return $list;
	}
}