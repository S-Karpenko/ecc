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
?>
<table class="noshow">
	<tr>
		<td width="50%">
			<form enctype="multipart/form-data" action="index.php?option=com_mijosef&amp;controller=restoremigrate&amp;task=view" method="post" name="adminForm">
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_MIJOSEF_RESTOREMIGRATE_COM_MIJOSEF_BACKUP'); ?></legend>
					<table class="adminform">
						<tr>
							<td>
								<input class="button" type="submit" name="backup_sefurls" value="<?php echo JText::_('COM_MIJOSEF_COMMON_URLS_SEF'); ?>" />
								&nbsp;
								<input class="button" type="submit" name="backup_movedurls" value="<?php echo JText::_('COM_MIJOSEF_COMMON_URLS_MOVED'); ?>" />
								&nbsp;
								<input class="button" type="submit" name="backup_metadata" value="<?php echo JText::_('COM_MIJOSEF_COMMON_METADATA'); ?>" />
								&nbsp;
								<input class="button" type="submit" name="backup_sitemap" value="<?php echo JText::_('COM_MIJOSEF_COMMON_SITEMAP'); ?>" />
								&nbsp;
								<input class="button" type="submit" name="backup_tags" value="<?php echo JText::_('COM_MIJOSEF_COMMON_TAGS'); ?>" />
								&nbsp;
								<input class="button" type="submit" name="backup_ilinks" value="<?php echo JText::_('COM_MIJOSEF_COMMON_ILINKS'); ?>" />
								&nbsp;
								<input class="button" type="submit" name="backup_bookmarks" value="<?php echo JText::_('COM_MIJOSEF_COMMON_BOOKMARKS'); ?>" />
							</td>
						</tr>
					</table>
				</fieldset>

				<input type="hidden" name="option" value="com_mijosef" />
				<input type="hidden" name="controller" value="restoremigrate" />
				<input type="hidden" name="task" value="backup" />
				<?php echo JHTML::_('form.token'); ?>
			</form>
			<form enctype="multipart/form-data" action="index.php?option=com_mijosef&amp;controller=restoremigrate&amp;task=view" method="post" name="adminForm">
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_MIJOSEF_RESTOREMIGRATE_COM_MIJOSEF_RESTORE'); ?></legend>
					<table class="adminform">
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td width="120">
								<label for="install_package"><?php echo JText::_('COM_MIJOSEF_COMMON_SELECT_FILE'); ?>:</label>
								&nbsp;&nbsp;&nbsp;
								<input class="input_box" id="file_restore" name="file_restore" type="file" size="50" />
							</td>
						</tr>
						<tr>
							<td>
								<input class="button" type="submit" name="restore_sefurls" value="<?php echo JText::_('COM_MIJOSEF_COMMON_URLS_SEF'); ?>" />
								&nbsp;
								<input class="button" type="submit" name="restore_movedurls" value="<?php echo JText::_('COM_MIJOSEF_COMMON_URLS_MOVED'); ?>" />
								&nbsp;
								<input class="button" type="submit" name="restore_metadata" value="<?php echo JText::_('COM_MIJOSEF_COMMON_METADATA'); ?>" />
								&nbsp;
								<input class="button" type="submit" name="restore_sitemap" value="<?php echo JText::_('COM_MIJOSEF_COMMON_SITEMAP'); ?>" />
								&nbsp;
								<input class="button" type="submit" name="restore_tags" value="<?php echo JText::_('COM_MIJOSEF_COMMON_TAGS'); ?>" />
								&nbsp;
								<input class="button" type="submit" name="restore_ilinks" value="<?php echo JText::_('COM_MIJOSEF_COMMON_ILINKS'); ?>" />
								&nbsp;
								<input class="button" type="submit" name="restore_bookmarks" value="<?php echo JText::_('COM_MIJOSEF_COMMON_BOOKMARKS'); ?>" />
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
					</table>
				</fieldset>

				<input type="hidden" name="option" value="com_mijosef" />
				<input type="hidden" name="controller" value="restoremigrate" />
				<input type="hidden" name="task" value="restore" />
				<?php echo JHTML::_('form.token'); ?>
			</form>
		</td>
		<td width="50%">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_MIJOSEF_RESTOREMIGRATE_CORE_SEF'); ?></legend>
				<table>
					<tr>
						<td>
							<?php echo JText::_('COM_MIJOSEF_RESTOREMIGRATE_CORE_SEF_DESC'); ?>
						</td>
					</tr>
				</table>
			</fieldset>
            
			<form enctype="multipart/form-data" action="index.php?option=com_mijosef&amp;controller=restoremigrate&amp;task=view" method="post" name="adminForm">
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_MIJOSEF_RESTOREMIGRATE_ACESEF_RESTORE'); ?></legend>
					<table class="adminform">
						<tr>
							<td width="120">
								<label for="install_package"><?php echo JText::_('COM_MIJOSEF_COMMON_SELECT_FILE'); ?>:</label>
								&nbsp;&nbsp;&nbsp;
								<input class="input_box" id="file_restore" name="file_restore" type="file" size="35" />
								<br /><br />
                                <input class="button" type="submit" name="restore_acesefurls" value="<?php echo JText::_('COM_MIJOSEF_COMMON_URLS_SEF'); ?>" />
                                &nbsp;
                                <input class="button" type="submit" name="restore_acemovedurls" value="<?php echo JText::_('COM_MIJOSEF_COMMON_URLS_MOVED'); ?>" />
                                &nbsp;
                                <input class="button" type="submit" name="restore_acemetadata" value="<?php echo JText::_('COM_MIJOSEF_COMMON_METADATA'); ?>" />
                                &nbsp;
                                <input class="button" type="submit" name="restore_acesitemap" value="<?php echo JText::_('COM_MIJOSEF_COMMON_SITEMAP'); ?>" />
                                &nbsp;
                                <input class="button" type="submit" name="restore_acetags" value="<?php echo JText::_('COM_MIJOSEF_COMMON_TAGS'); ?>" />
                                &nbsp;
                                <input class="button" type="submit" name="restore_aceilinks" value="<?php echo JText::_('COM_MIJOSEF_COMMON_ILINKS'); ?>" />
                                &nbsp;
                                <input class="button" type="submit" name="restore_acebookmarks" value="<?php echo JText::_('COM_MIJOSEF_COMMON_BOOKMARKS'); ?>" />
							</td>
						</tr>
					</table>
				</fieldset>

				<input type="hidden" name="option" value="com_mijosef" />
				<input type="hidden" name="controller" value="restoremigrate" />
				<input type="hidden" name="task" value="restore" />
				<?php echo JHTML::_('form.token'); ?>
			</form>

			<form enctype="multipart/form-data" action="index.php?option=com_mijosef&amp;controller=restoremigrate&amp;task=view" method="post" name="adminForm">
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_MIJOSEF_RESTOREMIGRATE_JOOMSEF_RESTORE'); ?></legend>
					<table class="adminform">
						<tr>
							<td width="120">
								<label for="install_package"><?php echo JText::_('COM_MIJOSEF_COMMON_SELECT_FILE'); ?>:</label>
								&nbsp;&nbsp;&nbsp;
								<input class="input_box" id="file_restore" name="file_restore" type="file" size="35" />
								&nbsp;&nbsp;&nbsp;
								<input class="button" type="submit" name="restore_joomsef" value="<?php echo JText::_('COM_MIJOSEF_RESTOREMIGRATE_BTN_SEF_META'); ?>" />
							</td>
						</tr>
					</table>
				</fieldset>

				<input type="hidden" name="option" value="com_mijosef" />
				<input type="hidden" name="controller" value="restoremigrate" />
				<input type="hidden" name="task" value="restore" />
				<?php echo JHTML::_('form.token'); ?>
			</form>
			
			<form enctype="multipart/form-data" action="index.php?option=com_mijosef&amp;controller=restoremigrate&amp;task=view" method="post" name="adminForm">
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_MIJOSEF_RESTOREMIGRATE_SH404SEF_RESTORE'); ?></legend>
					<table class="adminform">
						<tr>
							<td width="120">
								<label for="install_package"><?php echo JText::_('COM_MIJOSEF_COMMON_SELECT_FILE'); ?>:</label>
								&nbsp;&nbsp;&nbsp;
								<input class="input_box" id="file_restore" name="file_restore" type="file" size="35" />
								&nbsp;
								<input class="button" type="submit" name="restore_shUrl" value="<?php echo JText::_('COM_MIJOSEF_COMMON_URLS_SEF'); ?>" />
								&nbsp;
								<input class="button" type="submit" name="restore_shMetadata" value="<?php echo JText::_('COM_MIJOSEF_COMMON_METADATA'); ?>" />
								&nbsp;
								<input class="button" type="submit" name="restore_sh2" value="<?php echo JText::_('Version 2.x'); ?>" />
								&nbsp;
								<input class="button" type="submit" name="restore_shAliases" value="<?php echo JText::_('Aliases'); ?>" />
							</td>
						</tr>
					</table>
				</fieldset>

				<input type="hidden" name="option" value="com_mijosef" />
				<input type="hidden" name="controller" value="restoremigrate" />
				<input type="hidden" name="task" value="restore" />
				<?php echo JHTML::_('form.token'); ?>
			</form>
		</td>
	</tr>
	<tr>
		<td>
			&nbsp;
		</td>
	</tr>
</table>