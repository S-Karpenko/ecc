<?php
/**
* @package		MijoSEF
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

$controller	= JRequest::getCmd('controller', 'mijosef');

JHTML::_('behavior.switcher');

// Load submenus
$views = array(''										=> JText::_('COM_MIJOSEF_COMMON_CONTROLPANEL'),
				'&controller=config&task=edit'			=> JText::_('COM_MIJOSEF_COMMON_CONFIGURATION'),
				'&controller=extensions&task=view'		=> JText::_('COM_MIJOSEF_COMMON_EXTENSIONS'),
				'&controller=sefurls&task=view'			=> JText::_('COM_MIJOSEF_COMMON_URLS'),
				'&controller=metadata&task=view'		=> JText::_('COM_MIJOSEF_COMMON_METADATA'),
				'&controller=sitemap&task=view'			=> JText::_('COM_MIJOSEF_COMMON_SITEMAP'),
				'&controller=tags&task=view'			=> JText::_('COM_MIJOSEF_COMMON_TAGS'),
				'&controller=ilinks&task=view'			=> JText::_('COM_MIJOSEF_COMMON_ILINKS'),
				'&controller=bookmarks&task=view'		=> JText::_('COM_MIJOSEF_COMMON_BOOKMARKS'),
				'&controller=upgrade&task=view'			=> JText::_('COM_MIJOSEF_COMMON_UPGRADE'),
				'&controller=support&task=support'		=> JText::_('COM_MIJOSEF_COMMON_SUPPORT')
				);	

foreach($views as $key => $val) {
	$active	= ($controller == $key);
	
	if ($key == '') {
		$img = 'mijosef.png';
	} else {
		$a = explode('&', $key);
		$c = explode('=', $a[1]);
		if ($c[1] == 'sefurls') {
			$img = 'icon-16-urls.png';
		} else {
			$img = 'icon-16-'.$c[1].'.png';
		}
	}
	
	JSubMenuHelper::addEntry('<img src="components/com_mijosef/assets/images/'.$img.'" style="margin-right: 2px;" align="absmiddle" />'.$val, 'index.php?option=com_mijosef'.$key, $active);
}