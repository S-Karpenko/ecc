<?php
/**
* @package		MijoSEF
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

// Tmpl var
$tmpl = JRequest::getWord('tmpl');
?>

<script language="javascript">
	function submitbutton(pressbutton){
		// Check if is modal ivew
		<?php if ($tmpl == 'component') { ?>
		document.adminForm.modal.value = '1';
		<?php } ?>
		
		submitform(pressbutton);
	}
</script>

<form action="index.php?option=com_mijosef&amp;controller=extensions&amp;task=edit&amp;cid[]=<?php echo $this->row->id; ?>&amp;tmpl=component" method="post" name="adminForm">
	<fieldset class="panelform">
		<table class="toolbar1">
			<tr>
				<td class="desc" width="550px">
					<?php echo '<h3>'.$this->row->description.'</h3>'; ?>
				</td>
				<td>
					<a href="#" onclick="javascript: submitbutton('editSave'); window.top.setTimeout('SqueezeBox.close();', 1000);" class="toolbar1"><span class="icon-32-save1" title="<?php echo JText::_('Save'); ?>"></span><?php echo JText::_('Save'); ?></a>
				</td>
				<td>
					<a href="#" onclick="javascript: submitbutton('editApply'); " class="toolbar1"><span class="icon-32-apply1" title="<?php echo JText::_('Apply'); ?>"></span><?php echo JText::_('Apply'); ?></a>
				</td>
				<td>
					<a href="#" onclick="javascript: submitbutton('editCancel'); window.top.setTimeout('SqueezeBox.close();', 1000);" class="toolbar1"><span class="icon-32-cancel1" title="<?php echo JText::_('Cancel'); ?>"></span><?php echo JText::_('Cancel'); ?></a>
				</td>
			</tr>
		</table>
	</fieldset>
	
	<fieldset class="panelform">
		<legend><?php echo JText::_('Parameters'); ?></legend>
            <?php echo JHtml::_('tabs.start', 'extension'); ?>

            <?php echo JHtml::_('tabs.panel', JText::_('COM_MIJOSEF_COMMON_URL'), 'url'); ?>
                <?php echo JHtml::_('sliders.start', 'url'); ?>
                <?php echo JHtml::_('sliders.panel', JText::_('COM_MIJOSEF_PARAMS_URL_EXTENSION'), 'url_ext'); ?>
				<?php if ($fields = $this->ext_params->getFieldset('url')) { ?>
                    <fieldset class="panelform">
                        <ul class="adminformlist">
                            <?php foreach($fields as $field) { ?>
                                <li><?php echo $field->label; echo $field->input; ?></li>
                            <?php } ?>
                        </ul>
                    </fieldset>
				<?php }	else { ?>
                    <fieldset class="panelform">
                        <ul class="adminformlist">
                            <li><?php echo JText::_('COM_MIJOSEF_PARAMS_URL_EXTENSION_EMPTY'); ?></li>
                        </ul>
                    </fieldset>
				<?php } ?>
				<?php if ($fields = $this->default_params->getFieldset('url')) { ?>
                    <?php echo JHtml::_('sliders.panel', JText::_('COM_MIJOSEF_PARAMS_URL_COMMON'), 'url_common'); ?>
                    <fieldset class="panelform">
                        <ul class="adminformlist">
                            <?php foreach($fields as $field) { ?>
                                <li><?php echo $field->label; echo $field->input; ?></li>
                            <?php } ?>
                        </ul>
                    </fieldset>
				<?php }	?>
                <?php echo JHtml::_('sliders.end'); ?>

            <?php echo JHtml::_('tabs.panel', JText::_('COM_MIJOSEF_COMMON_METADATA'), 'metadata'); ?>
				<?php if ($fields = $this->default_params->getFieldset('metadata')) { ?>
                    <fieldset class="panelform">
                        <ul class="adminformlist">
                            <?php foreach($fields as $field) { ?>
                                <li><?php echo $field->label; echo $field->input; ?></li>
                            <?php } ?>
                        </ul>
                    </fieldset>
				<?php }	?>
				<?php if ($fields = $this->ext_params->getFieldset('metadata')) { ?>
                    <fieldset class="panelform">
                        <ul class="adminformlist">
                            <?php foreach($fields as $field) { ?>
                                <li><?php echo $field->label; echo $field->input; ?></li>
                            <?php } ?>
                        </ul>
                    </fieldset>
				<?php }	?>

            <?php echo JHtml::_('tabs.panel', JText::_('COM_MIJOSEF_COMMON_SITEMAP'), 'sitemap'); ?>
                <?php if ($this->row->hasCats && ($fields = $this->default_params->getFieldset('sitemap_cats'))) { ?>
                    <fieldset class="panelform">
                        <ul class="adminformlist">
                            <?php foreach($fields as $field) { ?>
                                <li><?php echo $field->label; echo $field->input; ?></li>
                            <?php } ?>
                        </ul>
                    </fieldset>
                <?php }	?>
                <?php if ($fields = $this->default_params->getFieldset('sitemap_filter')) { ?>
                    <fieldset class="panelform">
                        <ul class="adminformlist">
                            <?php foreach($fields as $field) { ?>
                                <li><?php echo $field->label; echo $field->input; ?></li>
                            <?php } ?>
                        </ul>
                    </fieldset>
                <?php }	?>
                <?php if ($fields = $this->ext_params->getFieldset('sitemap')) { ?>
                    <fieldset class="panelform">
                        <ul class="adminformlist">
                            <?php foreach($fields as $field) { ?>
                                <li><?php echo $field->label; echo $field->input; ?></li>
                            <?php } ?>
                        </ul>
                    </fieldset>
                <?php }	?>
                <?php if ($fields = $this->default_params->getFieldset('sitemap_cron')) { ?>
                    <fieldset class="panelform">
                        <ul class="adminformlist">
                            <?php foreach($fields as $field) { ?>
                                <li><?php echo $field->label; echo $field->input; ?></li>
                            <?php } ?>
                        </ul>
                    </fieldset>
                <?php }	?>

            <?php echo JHtml::_('tabs.panel', JText::_('COM_MIJOSEF_COMMON_TAGS'), 'tags'); ?>
			<?php if ($fields = $this->default_params->getFieldset('tags')) { ?>
                <fieldset class="panelform">
                    <ul class="adminformlist">
                        <?php foreach($fields as $field) { ?>
                            <li><?php echo $field->label; echo $field->input; ?></li>
                        <?php } ?>
                    </ul>
                </fieldset>
			<?php }	?>
            <?php if ($this->row->hasCats && ($fields = $this->default_params->getFieldset('tags_cats'))) { ?>
                <fieldset class="panelform">
                    <ul class="adminformlist">
                        <?php foreach($fields as $field) { ?>
                            <li><?php echo $field->label; echo $field->input; ?></li>
                        <?php } ?>
                    </ul>
                </fieldset>
            <?php }	?>
			<?php if ($fields = $this->ext_params->getFieldset('tags')) { ?>
                <fieldset class="panelform">
                    <ul class="adminformlist">
                        <?php foreach($fields as $field) { ?>
                            <li><?php echo $field->label; echo $field->input; ?></li>
                        <?php } ?>
                    </ul>
                </fieldset>
			<?php }	?>

            <?php echo JHtml::_('tabs.panel', JText::_('COM_MIJOSEF_COMMON_ILINKS'), 'ilinks'); ?>
			<?php if ($fields = $this->default_params->getFieldset('ilinks')) { ?>
                <fieldset class="panelform">
                    <ul class="adminformlist">
                        <?php foreach($fields as $field) { ?>
                            <li><?php echo $field->label; echo $field->input; ?></li>
                        <?php } ?>
                    </ul>
                </fieldset>
			<?php }	?>
            <?php if ($this->row->hasCats && ($fields = $this->default_params->getFieldset('ilinks_cats'))) { ?>
                <fieldset class="panelform">
                    <ul class="adminformlist">
                        <?php foreach($fields as $field) { ?>
                            <li><?php echo $field->label; echo $field->input; ?></li>
                        <?php } ?>
                    </ul>
                </fieldset>
            <?php }	?>
			<?php if ($fields = $this->ext_params->getFieldset('ilinks')) { ?>
                <fieldset class="panelform">
                    <ul class="adminformlist">
                        <?php foreach($fields as $field) { ?>
                            <li><?php echo $field->label; echo $field->input; ?></li>
                        <?php } ?>
                    </ul>
                </fieldset>
			<?php }	?>

            <?php echo JHtml::_('tabs.panel', JText::_('COM_MIJOSEF_COMMON_BOOKMARKS'), 'bookmarks'); ?>
			<?php if ($fields = $this->default_params->getFieldset('bookmarks')) { ?>
                <fieldset class="panelform">
                    <ul class="adminformlist">
                        <?php foreach($fields as $field) { ?>
                            <li><?php echo $field->label; echo $field->input; ?></li>
                        <?php } ?>
                    </ul>
                </fieldset>
			<?php }	?>
            <?php if ($this->row->hasCats && ($fields = $this->default_params->getFieldset('bookmarks_cats'))) { ?>
                <fieldset class="panelform">
                    <ul class="adminformlist">
                        <?php foreach($fields as $field) { ?>
                            <li><?php echo $field->label; echo $field->input; ?></li>
                        <?php } ?>
                    </ul>
                </fieldset>
            <?php }	?>
			<?php if ($fields = $this->ext_params->getFieldset('bookmarks')) { ?>
                <fieldset class="panelform">
                    <ul class="adminformlist">
                        <?php foreach($fields as $field) { ?>
                            <li><?php echo $field->label; echo $field->input; ?></li>
                        <?php } ?>
                    </ul>
                </fieldset>
			<?php }	?>
			
        <?php echo JHtml::_('tabs.end'); ?>
	</fieldset>
	
	<input type="hidden" name="option" value="com_mijosef" />
	<input type="hidden" name="controller" value="extensions" />
	<input type="hidden" name="task" value="edit" />
	<input type="hidden" name="modal" value="0" />
	<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>