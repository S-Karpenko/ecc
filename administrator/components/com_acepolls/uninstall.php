<?php
/**
* @version		1.0.0
* @package		AcePolls
* @subpackage	AcePolls
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die( 'Restricted access');

// Imports
jimport('joomla.installer.installer');

$db = &JFactory::getDBO();

$db->setQuery("DROP TABLE IF EXISTS `#__acepolls_polls_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__acepolls_polls` TO `#__acepolls_polls_backup`");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__acepolls_options_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__acepolls_options` TO `#__acepolls_options_backup`");
$db->query();

$db->setQuery("DROP TABLE IF EXISTS `#__acepolls_votes_backup`");
$db->query();
$db->setQuery("RENAME TABLE `#__acepolls_votes` TO `#__acepolls_votes_backup`");
$db->query();

$db->setQuery("SELECT extension_id FROM #__extensions WHERE type = 'module' AND element = 'mod_acepolls' LIMIT 1");
$id = $db->loadResult();
if ($id) {
	$installer = new JInstaller();
	$installer->uninstall('module', $id);
}

$db->setQuery("SELECT extension_id FROM #__extensions WHERE type = 'plugin' AND element = 'acepolls' AND folder = 'content' LIMIT 1");
$id = $db->loadResult();
if ($id) {
	$installer = new JInstaller();
	$installer->uninstall('plugin', $id);
}

$db->setQuery("SELECT extension_id FROM #__extensions WHERE type = 'plugin' AND element = 'acepollssearch' AND folder = 'search' LIMIT 1");
$id = $db->loadResult();
if ($id) {
	$installer = new JInstaller();
	$installer->uninstall('plugin', $id);
}

$db->setQuery("SELECT extension_id FROM #__extensions WHERE type = 'plugin' AND element = 'alphauserpoints' AND folder = 'acepolls' LIMIT 1");
$id = $db->loadResult();
if ($id) {
	$installer = new JInstaller();
	$installer->uninstall('plugin', $id);
}

$db->setQuery("SELECT extension_id FROM #__extensions WHERE type = 'plugin' AND element = 'jomsocial' AND folder = 'acepolls' LIMIT 1");
$id = $db->loadResult();
if ($id) {
	$installer = new JInstaller();
	$installer->uninstall('plugin', $id);
}

$db->setQuery("SELECT extension_id FROM #__extensions WHERE type = 'plugin' AND element = 'mightytouch' AND folder = 'acepolls' LIMIT 1");
$id = $db->loadResult();
if ($id) {
	$installer = new JInstaller();
	$installer->uninstall('plugin', $id);
}

?>

<h2>AcePolls Removal</h2>
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
			<td class="key" colspan="2"><?php echo JText::_('Component'); ?></td>
			<td><strong><?php echo JText::_('Removed'); ?></strong></td>
		</tr>
		<tr class="row1">
			<td class="key" colspan="2"><?php echo 'AcePolls '.JText::_('Module'); ?></td>
			<td><strong><?php echo JText::_('Removed'); ?></strong></td>
		</tr>
		<tr>
			<th colspan="3"><?php echo JText::_('Plugins'); ?></th>
		</tr>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'AcePolls - AlphaUserPoints'; ?></td>
			<td><strong><?php echo JText::_('Removed'); ?></strong></td>
		</tr>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'AcePolls - JomSocial'; ?></td>
			<td><strong><?php echo JText::_('Removed'); ?></strong></td>
		</tr>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'AcePolls - Mighty Touch'; ?></td>
			<td><strong><?php echo JText::_('Removed'); ?></strong></td>
		</tr>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'Content - Load AcePolls'; ?></td>
			<td><strong><?php echo JText::_('Removed'); ?></strong></td>
		</tr>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'Search - AcePolls'; ?></td>
			<td><strong><?php echo JText::_('Removed'); ?></strong></td>
		</tr>
	</tbody>
</table>