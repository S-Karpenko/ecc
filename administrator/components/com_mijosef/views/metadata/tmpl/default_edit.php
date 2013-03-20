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

// Get task and tmpl vars
$tmpl = JRequest::getVar('tmpl');
?>

<script language="javascript">
	function submitbutton(pressbutton){
		var form = document.adminForm;
		
		// Check if is modal ivew
		<?php if ($tmpl == 'component') { ?>
		form.modal.value = '1';
		<?php } ?>
		
		if (pressbutton == 'editCancel') {
			submitform(pressbutton);
			return;
		}

		submitform(pressbutton);
	}
	
	<?php if ($this->MijosefConfig->jquery_mode == 1) { ?>
	$(document).ready(function(){
		$("#url_sef").autocomplete('components/com_mijosef/library/autocompleters/sefurls.php');
	});
	<?php } ?>
</script>

<form action="index.php?option=com_mijosef&amp;controller=metadata&amp;task=edit&cid[]=<?php echo $this->row->id; ?>&amp;tmpl=component" method="post" name="adminForm" id="adminForm">
	<?php
	if ($tmpl == 'component') {
		?>
		<fieldset class="adminform">
			<table class="toolbar1">
				<tr>
					<td class="desc" width="550px">
						<?php echo '<h3>'.JText::_('COM_MIJOSEF_METADATA_NEW').'</h3>';	?>
					</td>
					<td>
						<a href="#" onclick="javascript: submitbutton('editSave'); window.top.setTimeout('SqueezeBox.close();', 1000);" class="toolbar1"><span class="icon-32-save1" title="<?php echo JText::_('Save'); ?>"></span><?php echo JText::_('Save'); ?></a>
					</td>
					<td>
						<a href="#" onclick="javascript: submitbutton('editCancel'); window.top.setTimeout('SqueezeBox.close();', 1000);" class="toolbar1"><span class="icon-32-cancel1" title="<?php echo JText::_('Cancel'); ?>"></span><?php echo JText::_('Cancel'); ?></a>
					</td>
				</tr>
			</table>
		</fieldset>
		<?php
	}
	?>
	<fieldset class="adminform" style="background: #ffffff;">
		<legend><?php echo JText::_('COM_MIJOSEF_METADATA_EDIT_LEGEND'); ?></legend>
		<table class="admintable">
			<tr>
				<td width="20%" class="key2">
					<label for="name">
						<?php echo JText::_('COM_MIJOSEF_URL_SEF_COMMON_URL_SEF'); ?>
					</label>
				</td>
				<td width="80%">
					<?php
					$url_sef = $this->getSefURL(JRequest::getVar('sitemapid'));
					if (!empty($url_sef)) { ?>
						<input class="inputbox" type="text" name="url_sef" id="url_sef" size="80" value="<?php echo $url_sef; ?>" />
					<?php } else { ?>
						<input class="inputbox" type="text" name="url_sef" id="url_sef" size="80" value="<?php echo $this->row->url_sef; ?>" />
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td width="23%" class="key2">
					<label for="name">
						<?php echo JText::_('COM_MIJOSEF_COMMON_TITLE'); ?>
					</label>
				</td>
				<td width="77%">
					<input class="inputbox" type="text" name="title" size="80" value="<?php echo Mijosef::get('utility')->replaceSpecialChars(htmlspecialchars($this->row->title), true); ?>" />
				</td>
			</tr>
			<tr>
				<td width="23%" class="key2">
					<label for="name">
						<?php echo JText::_('COM_MIJOSEF_COMMON_META') . ' ' . JText::_('COM_MIJOSEF_COMMON_DESCRIPTION'); ?>
					</label>
				</td>
				<td width="77%">
					<textarea class="text_area" name="description" rows="5" cols="57"><?php echo Mijosef::get('utility')->replaceSpecialChars(htmlspecialchars($this->row->description), true); ?></textarea>
				</td>
			</tr>
			<tr>
				<td width="23%" class="key2">
					<label for="name">
						<?php echo JText::_('COM_MIJOSEF_COMMON_META') . ' ' . JText::_('COM_MIJOSEF_COMMON_KEYWORDS'); ?>
					</label>
				</td>
				<td width="77%">
					<textarea class="text_area" name="keywords" rows="3" cols="57"><?php echo Mijosef::get('utility')->replaceSpecialChars(htmlspecialchars($this->row->keywords), true); ?></textarea>
				</td>
			</tr>
			<tr>
				<td width="23%" class="key2">
					<label for="name">
						<?php echo JText::_('COM_MIJOSEF_COMMON_META') . ' ' . JText::_('COM_MIJOSEF_URL_EDIT_METALANG'); ?>
					</label>
				</td>
				<td width="77%">
					<input class="inputbox" type="text" name="lang" size="30" value="<?php echo Mijosef::get('utility')->replaceSpecialChars(htmlspecialchars($this->row->lang), true); ?>" />
				</td>
			</tr>
			<tr>
				<td width="23%" class="key2">
					<label for="name">
						<?php echo JText::_('COM_MIJOSEF_COMMON_META') . ' ' . JText::_('COM_MIJOSEF_URL_EDIT_METAROBOTS'); ?>
					</label>
				</td>
				<td width="77%">
					<input class="inputbox" type="text" name="robots" size="30" value="<?php echo Mijosef::get('utility')->replaceSpecialChars(htmlspecialchars($this->row->robots), true); ?>" />
				</td>
			</tr>
			<tr>
				<td width="23%" class="key2">
					<label for="name">
						<?php echo JText::_('COM_MIJOSEF_COMMON_META') . ' ' . JText::_('COM_MIJOSEF_URL_EDIT_METAGOOGLEBOT'); ?>
					</label>
				</td>
				<td width="77%">
					<input class="inputbox" type="text" name="googlebot" size="30" value="<?php echo Mijosef::get('utility')->replaceSpecialChars(htmlspecialchars($this->row->googlebot), true); ?>" />
				</td>
			</tr>
			<tr>
				<td width="23%" class="key2">
					<label for="name">
						<?php echo JText::_('COM_MIJOSEF_COMMON_META') . ' ' . JText::_('COM_MIJOSEF_URL_EDIT_METACANONICAL'); ?>
					</label>
				</td>
				<td width="77%">
					<input class="inputbox" type="text" name="canonical" size="80" value="<?php echo Mijosef::get('utility')->replaceSpecialChars(htmlspecialchars($this->row->canonical), true); ?>" />
				</td>
			</tr>
		</table>
	</fieldset>
	<input type="hidden" name="option" value="com_mijosef" />
	<input type="hidden" name="controller" value="metadata" />
	<input type="hidden" name="task" value="edit" />
	<input type="hidden" name="modal" value="0" />
	<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>