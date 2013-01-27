<?php
/**
* @version		1.0.0
* @package		AcePolls
* @subpackage	AcePolls
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

// Imports
jimport('joomla.installer.installer');

$db = &JFactory::getDBO();

$db->setQuery("CREATE TABLE IF NOT EXISTS `#__acepolls_polls` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `alias` varchar(255) NOT NULL default '',
  `checked_out` int(11) NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `published` tinyint(1) NOT NULL default '0',
  `publish_up` datetime NOT NULL default '0000-00-00 00:00:00',
  `publish_down` datetime default '0000-00-00 00:00:00',
  `params` text NOT NULL,
  `access` int(11) NOT NULL default '0',
  `lag` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;");
$db->query();

$db->setQuery("CREATE TABLE IF NOT EXISTS `#__acepolls_options` (
  `id` int(11) NOT NULL auto_increment,
  `poll_id` int(11) NOT NULL default '0',
  `text` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `color` varchar(6)  NOT NULL,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `poll_id` (`poll_id`,`text`(1))
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;");
$db->query();

$db->setQuery("CREATE TABLE IF NOT EXISTS `#__acepolls_votes` (
  `id` bigint(20) NOT NULL auto_increment,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `option_id` int(11) NOT NULL default '0',
  `poll_id` int(11) NOT NULL default '0',
  `ip` int(10) unsigned NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY  (`id`),
  KEY `poll_id` (`poll_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;");
$db->query();

/***********************************************************************************************
* ---------------------------------------------------------------------------------------------
* MODULE INSTALLATION SECTION
* ---------------------------------------------------------------------------------------------
***********************************************************************************************/
$installer =& new JInstaller();
$installer->install($this->parent->getPath('source').'/extensions/mod_acepolls');

/***********************************************************************************************
* ---------------------------------------------------------------------------------------------
* PLUGIN INSTALLATION SECTION
* ---------------------------------------------------------------------------------------------
***********************************************************************************************/
$installer = new JInstaller();
$installer->install($this->parent->getPath('source').'/extensions/plg_acepolls');

$installer = new JInstaller();
$installer->install($this->parent->getPath('source').'/extensions/plg_acepollssearch');

$installer = new JInstaller();
$installer->install($this->parent->getPath('source').'/extensions/plg_alphauserpoints');

$installer = new JInstaller();
$installer->install($this->parent->getPath('source').'/extensions/plg_jomsocial');

$installer = new JInstaller();
$installer->install($this->parent->getPath('source').'/extensions/plg_mightytouch');
?>

<img src="components/com_acepolls/assets/images/logo.png" alt="Joomla! Polls" width="60" height="89" align="left" />

<h2>AcePolls Installation</h2>
<h2><a href="index.php?option=com_acepolls">Go to AcePolls</a></h2>
<table class="adminlist">
	<thead>
		<tr>
			<th class="title" colspan="2"><?php echo JText::_('Extension'); ?></th>
			<th width="30%"><?php echo JText::_('Status'); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="3"></td>
		</tr>
	</tfoot>
	<tbody>
		<tr>
			<th colspan="3"><?php echo JText::_('Core'); ?></th>
		</tr>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'AcePolls '.JText::_('Component'); ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
		<tr class="row1">
			<td class="key" colspan="2"><?php echo 'AcePolls '.JText::_('Module'); ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
		<tr>
			<th colspan="3"><?php echo JText::_('Plugins'); ?></th>
		</tr>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'AcePolls - AlphaUserPoints'; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
		<tr class="row1">
			<td class="key" colspan="2"><?php echo 'AcePolls - JomSocial'; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'AcePolls - Mighty Touch'; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
		<tr class="row1">
			<td class="key" colspan="2"><?php echo 'Content - Load AcePolls'; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'Search - AcePolls'; ?></td>
			<td><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
	</tbody>
</table>