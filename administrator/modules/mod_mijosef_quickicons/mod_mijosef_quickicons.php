<?php
/**
* @package		MijoSEF
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

function getMijosefIcon($link, $image, $text) {
	$mainframe =& JFactory::getApplication();
	$img_path 	= '/components/com_mijosef/assets/images/';
	$lang		=& JFactory::getLanguage();
	?>
	<div class="icon-wrapper" style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
		<div class="icon">
			<a href="<?php echo $link; ?>">
				<?php echo JHtml::_('image.site', $image, $img_path, NULL, NULL, $text); ?>
				<span><?php echo $text; ?></span>
			</a>
		</div>
	</div>
	<?php
}

function getMijosefVersion() {
	$mijosef = JPATH_ADMINISTRATOR.'/components/com_mijosef/library/mijosef.php';
	
	if (!file_exists($mijosef)) {
		return 0;
	}
	
	require_once($mijosef);
	$utility = Mijosef::get('utility');
	
	$installed = $utility->getXmlText(JPATH_ADMINISTRATOR.'/components/com_mijosef/a_mijosef.xml', 'version');
	$version_info = $utility->getRemoteInfo();
	$latest = $version_info['mijosef'];
	
	$version = version_compare($installed, $latest);
	
	return $version;
}

?>

<div id="cpanel">
	<?php
	// MijoSEF icons
	if ($params->get('mijosef_version', '1') == 1) {
		$link = 'index.php?option=com_mijosef&amp;controller=upgrade&amp;task=view';
		$version = getMijosefVersion();
		if ($version != 0) {
			getMijosefIcon($link, 'icon-48-version-up.png', JText::_('COM_MIJOSEF_UPGRADE_AVAILABLE'));
		} else {
			getMijosefIcon($link, 'icon-48-version-ok.png', JText::_('COM_MIJOSEF_UP_TO_DATE'));
		}
	}

	if ($params->get('mijosef_configuration', '0') == 1) {
		$link = 'index.php?option=com_mijosef&amp;controller=config&amp;task=edit';
		getMijosefIcon($link, 'icon-48-config.png', JText::_('COM_MIJOSEF_CONFIGURATION'));
	}

	if ($params->get('mijosef_extensions', '0') == 1) {
		$link = 'index.php?option=com_mijosef&amp;controller=extensions&amp;task=view';
		getMijosefIcon($link, 'icon-48-extensions.png', JText::_('COM_MIJOSEF_EXTENSIONS'));
	}

	if ($params->get('mijosef_urls', '1') == 1) {
		$link = 'index.php?option=com_mijosef&amp;controller=sefurls&amp;task=view';
		getMijosefIcon($link, 'icon-48-urls.png', JText::_('COM_MIJOSEF_URLS'));
	}

	if ($params->get('mijosef_metadata', '1') == 1) {
		$link = 'index.php?option=com_mijosef&amp;controller=metadata&amp;task=view';
		getMijosefIcon($link, 'icon-48-metadata.png', JText::_('COM_MIJOSEF_METADATA'));
	}

	if ($params->get('mijosef_sitemap', '1') == 1) {
		$link = 'index.php?option=com_mijosef&amp;controller=sitemap&amp;task=view';
		getMijosefIcon($link, 'icon-48-sitemap.png', JText::_('COM_MIJOSEF_SITEMAP'));
	}

	if ($params->get('mijosef_tags', '1') == 1) {
		$link = 'index.php?option=com_mijosef&amp;controller=tags&amp;task=view';
		getMijosefIcon($link, 'icon-48-tags.png', JText::_('COM_MIJOSEF_TAGS'));
	}

	if ($params->get('mijosef_ilinks', '0') == 1) {
		$link = 'index.php?option=com_mijosef&amp;controller=ilinks&amp;task=view';
		getMijosefIcon($link, 'icon-48-ilinks.png', JText::_('COM_MIJOSEF_INTERNAL_LINKS'));
	}

	if ($params->get('mijosef_bookmarks', '0') == 1) {
		$link = 'index.php?option=com_mijosef&amp;controller=bookmarks&amp;task=view';
		getMijosefIcon($link, 'icon-48-bookmarks.png', JText::_('COM_MIJOSEF_SOCIAL_BOOKMARKS'));
	}
	?>
</div>