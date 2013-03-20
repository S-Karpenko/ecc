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
defined('_JEXEC') or die('Restricted Access');

// Get metadata
$mainframe =& JFactory::getApplication();
$row = $mainframe->getUserState('com_mijosef.metadata');

?>

<script type="text/javascript">
	window.addEvent('load', function(){
		$$('.pane-sliders').each(
			function(slider) {
				$$('#mijosefpanel-container').getFirst().inject(slider);
				$$('#mijosefpanel-container').erase();
			}
		);
		
		/*var sliders = new Element('div', {class: 'pane-sliders'});
		var mijosefpanel = new Element('div', {id: 'mijosefpanel-container'});
		
		mijosefpanel.getFirst().inject(sliders);*/
	});
</script>

<div id="mijosefpanel-container">
	<div class="panel">
		<h3 class="pane-toggler title" id="mijosef-pane">
			<a href="javascript:void(0);">
				<span><?php echo JText::_('MijoSEF') . ' ' . JText::_('COM_MIJOSEF_COMMON_METADATA'); ?></span>
			</a>
		</h3>

		<div class="pane-slider content" style="padding-top: 0px; border-top: medium none; padding-bottom: 0px; border-bottom: medium none; overflow: hidden; height: auto;">
			<table class="admintable">
				<tr>
					<td width="25%" class="key">
						<label for="name">
							<?php echo JText::_('COM_MIJOSEF_COMMON_TITLE'); ?>
						</label>
					</td>
					<td width="75%">
						<input class="inputbox" type="text" name="mijosef_title" size="45" value="<?php echo Mijosef::get('utility')->replaceSpecialChars(htmlspecialchars($row->title), true); ?>" />
					</td>
				</tr>
				<tr>
					<td width="25%" class="key">
						<label for="name">
							<?php echo JText::_('COM_MIJOSEF_COMMON_DESCRIPTION'); ?>
						</label>
					</td>
					<td width="75%">
						<textarea name="mijosef_desc" rows="6" cols="30" class="text_area"><?php echo Mijosef::get('utility')->replaceSpecialChars(htmlspecialchars($row->description), true); ?></textarea>
					</td>
				</tr>
				<tr>
					<td width="25%" class="key">
						<label for="name">
							<?php echo JText::_('COM_MIJOSEF_COMMON_KEYWORDS'); ?>
						</label>
					</td>
					<td width="75%">
						<textarea name="mijosef_key" rows="5" cols="30" class="text_area"><?php echo Mijosef::get('utility')->replaceSpecialChars(htmlspecialchars($row->keywords), true); ?></textarea>
						</td>
				</tr>
				<tr>
					<td width="25%" class="key">
						<label for="name">
							<?php echo JText::_('COM_MIJOSEF_URL_EDIT_METALANG'); ?>
						</label>
					</td>
					<td width="75%">
						<input class="inputbox" type="text" name="mijosef_lang" size="20" value="<?php echo Mijosef::get('utility')->replaceSpecialChars(htmlspecialchars($row->lang), true); ?>" />
					</td>
				</tr>
				<tr>
					<td width="25%" class="key">
						<label for="name">
							<?php echo JText::_('COM_MIJOSEF_URL_EDIT_METAROBOTS'); ?>
						</label>
					</td>
					<td width="75%">
						<input class="inputbox" type="text" name="mijosef_robots" size="20" value="<?php echo Mijosef::get('utility')->replaceSpecialChars(htmlspecialchars($row->robots), true); ?>" />
					</td>
				</tr>
				<tr>
					<td width="25%" class="key">
						<label for="name">
							<?php echo JText::_('COM_MIJOSEF_URL_EDIT_METAGOOGLEBOT'); ?>
						</label>
					</td>
					<td width="75%">
						<input class="inputbox" type="text" name="mijosef_googlebot" size="20" value="<?php echo Mijosef::get('utility')->replaceSpecialChars(htmlspecialchars($row->googlebot), true); ?>" />
					</td>
				</tr>
			</table>
			<input type="hidden" name="mijosef_id" value="<?php echo $row->id; ?>">
			<input type="hidden" name="mijosef_url_sef" value="<?php echo $row->url_sef; ?>">
		</div>
	</div>
</div>