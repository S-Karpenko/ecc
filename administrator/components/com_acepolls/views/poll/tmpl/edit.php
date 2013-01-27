<?php
/**
* @version		1.0.0
* @package		AcePolls
* @subpackage	AcePolls
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*
* Based on Apoll Component
* @copyright (C) 2009 - 2011 Hristo Genev All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @link http://www.afactory.org
*/

defined('_JEXEC') or die('Restricted access'); ?>

<?php
	JHTML::_('behavior.calendar');
    $row = $this->row;
    JFilterOutput::objectHTMLSafe($row, ENT_QUOTES);
?>

<style type="text/css">
tr.dragable { cursor: move;background-color:#f6f6f6; }

div.color_picker {
	position:absolute;
	top:-2px; left:0;
	height: 16px; width: 16px;
	padding: 0 !important;
	border: 1px solid #ccc;
	background: url(../media/acepolls/images/poll-arrow.gif) no-repeat top right;
	cursor: pointer;
	line-height: 16px;
}

div#color_selector {
  width: 110px;
  position: absolute;
  border: 1px solid #598FEF;
  background-color: #EFEFEF;
  padding: 2px;
}
  div#color_custom {width: 100%; float:left }
  div#color_custom label {font-size: 95%; color: #2F2F2F; margin: 5px 2px; width: 25%}
  div#color_custom input {margin: 5px 2px; padding: 0; font-size: 95%; border: 1px solid #000; width: 65%; }

div.color_swatch {
  height: 12px;
  width: 12px;
  border: 1px solid #000;
  margin: 2px;
  float: left;
  cursor: pointer;
  line-height: 12px;
}
</style>

<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		<?php if (count($this->options)) { ?>
		var polloption1 = document.getElementById('polloption<?php echo $this->options[0]->id; ?>');
		var polloption2 = document.getElementById('polloption<?php echo $this->options[1]->id; ?>');
		<?php } else { ?>		
		var polloption1 = document.getElementById('polloption1');
		var polloption2 = document.getElementById('polloption2');
		<?php } ?>
		
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
		
		// do field validation
		// check if the publish_up date is smaller than publish_down date
		var publish_up = form.publish_up.value;
		var publish_down = form.publish_down.value;
		
		publish_up = parseInt(publish_up.replace(/[ :-]/g,''));
		publish_down = parseInt(publish_down.replace(/[ :-]/g,''));

		if (publish_up >= publish_down) {
			alert("<?php echo JText::_('Please correct the start or end date of the poll'); ?>");
			form.publish_down.focus();
			return false;
		}
		
		// check for empty fields
		if (form.title.value == "") {
			alert( "<?php echo JText::_( 'Poll must have a title', true ); ?>" );
			form.title.focus();
		} else if(isNaN(parseInt(form.lag.value))) {
			alert( "<?php echo JText::_( 'Poll must have a non-zero lag time', true ); ?>");
			form.lag.focus();
		}
		else if (polloption1.value == ""){
			alert( "<?php echo JText::_( 'Poll must have at least 2 options', true ); ?>" );
			polloption1.focus();
		}
		else if (polloption2.value == ""){
			alert( "<?php echo JText::_( 'Poll must have at least 2 options', true ); ?>" );
			polloption2.focus();
		
		} else {
			submitform(pressbutton);
		}
	}
</script>

<form action="index.php" method="post" name="adminForm">
	<div class="width-40" style="float:right;">
		<fieldset class="panelform">
			<legend><?php echo JText::_('COM_ACEPOLLS_PARAMS'); ?></legend>
			<?php echo JHTML::_('sliders.start', 'parameters'); ?>
			
			<?php echo JHTML::_('sliders.panel', JText::_('COM_ACEPOLLS_PARAMS_GENERAL'), 'general-params');?>
			<ul class="adminformlist">
				<?php foreach($this->params->getFieldset('general') as $field) { ?>
                    <li><?php echo $field->label;echo $field->input;?></li>
				<?php } ?>
            </ul>
			<?php echo JHTML::_('sliders.panel', JText::_('COM_ACEPOLLS_RESULTS'), 'results-params');?>
			<ul class="adminformlist">
				<?php foreach($this->params->getFieldset('results') as $field) { ?>
                    <li><?php echo $field->label;echo $field->input;?></li>
				<?php } ?>
            </ul>
			<?php echo JHTML::_('sliders.panel', JText::_('COM_ACEPOLLS_PARAMS_PIE'), 'pie-params');?>
			<ul class="adminformlist">
				<?php foreach($this->params->getFieldset('pie') as $field) { ?>
                    <li><?php echo $field->label;echo $field->input;?></li>
				<?php } ?>
            </ul>
			<?php echo JHTML::_('sliders.panel', JText::_('COM_ACEPOLLS_PARAMS_JOOMLA'), 'joomla-params');?>
			<ul class="adminformlist">
				<?php foreach($this->params->getFieldset('joomla') as $field) { ?>
                    <li><?php echo $field->label;echo $field->input;?></li>
				<?php } ?>
            </ul>
			<?php echo JHTML::_('sliders.end'); ?>
		</fieldset>
	</div>

	<div class="col width-60">
		<fieldset class="adminform">
		<legend><?php echo JText::_('COM_ACEPOLLS_DETAILS'); ?></legend>
			<table class="admintable">
				<tr>
					<td width="110" class="key">
						<label for="title">
							<?php echo JText::_('COM_ACEPOLLS_TITLE'); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="title" id="title" size="60" value="<?php echo $row->title; ?>" />
					</td>
				</tr>
				<tr>
					<td width="110" class="key">
						<label for="alias">
							<?php echo JText::_('COM_ACEPOLLS_ALIAS'); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="alias" id="alias" size="60" value="<?php echo $row->alias; ?>" />
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="lag">
							<?php echo JText::_('COM_ACEPOLLS_LAG'); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="lag" id="lag" size="5" value="<?php echo $row->lag / 60; ?>" />
						<?php echo JText::_('COM_ACEPOLLS_HOURS_BETWEEN_VOTES'); ?>
					</td>
				</tr>		
				<tr>
					<td class="key">
						<label for="start">
							<?php echo JText::_('COM_ACEPOLLS_START_DATE'); ?>:
						</label>
					</td>
					<td>
						<?php
						//get the time now if we are creating new poll      
						$date = &JFactory::getDate();
						$date->setOffset($this->mainframe->getCfg('offset'));
						$end_date = JFactory::getDate('+1 month');

						$publish_up = ($row->publish_up == '') ? $date->toFormat() : $row->publish_up;
						
						echo JHTML::_('calendar', $publish_up, 'publish_up', 'publish_up', '%Y-%m-%d 00:00:00', array('class'=>'inputbox', 'size'=>'30')); ?>
						<?php echo JText::_('COM_ACEPOLLS_START_DATE_DESC'); ?>
					</td>
				</tr>		
				<tr>
					<td class="key">
						<label for="end">
							<?php echo JText::_('COM_ACEPOLLS_END_DATE'); ?>:
						</label>
					</td>
					<td>
						<?php 
						$publish_down = ($row->publish_down == '') ? $end_date->toFormat() : $row->publish_down;

						echo JHTML::_('calendar', $publish_down, 'publish_down', 'publish_down', '%Y-%m-%d 00:00:00', array('class'=>'inputbox', 'size'=>'30')); ?>
						<?php echo JText::_('COM_ACEPOLLS_END_DATE_DESC'); ?>
					</td>
				</tr>
				<tr>
					<td width="120" class="key">
						<?php echo JText::_('COM_ACEPOLLS_PUBLISHED'); ?>:
					</td>
					<td>
						<fieldset class="radio">
						<?php echo JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $row->published); ?>
						</fieldset>
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
	<div class="col width-60">
		<?php
		$document =& JFactory::getDocument();
		$document->addScript('../media/acepolls/js/jquery.js');
		$document->addScript('../media/acepolls/js/jquery.noconflict.js');
		$document->addScript('../media/acepolls/js/jquery.tablednd.js');
		$document->addScript('../media/acepolls/js/jquery.colorpicker.js');
		$document->addScript('../media/acepolls/js/jquery.color.js');
		$document->addScript('../media/acepolls/js/jquery.acepolls.js');
		?>

		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_ACEPOLLS_OPTIONS_DRAG_DROP'); ?></legend>

			<table class="admintable" id="reorder">
				<tr style=" font-weight:bold;" class="nodrag" >
					<td style="width:40px;">
						<a href="#" id="options-add<?php if ($this->edit) echo '-extra'; ?>">
						<img src="../media/acepolls/images/poll-add.png" style=" margin-right:3px; border:none;" alt="<?php echo JText::_('COM_ACEPOLLS_OPTION_ADD'); ?>" title="<?php echo JText::_('COM_ACEPOLLS_OPTION_ADD'); ?>" /></a>
						<a href="#" id="options-remove<?php if($this->edit) echo '-extra'; ?>">
						<img src="../media/acepolls/images/poll-remove.png" style="margin-right:3px; border:none;" alt="<?php echo JText::_('COM_ACEPOLLS_OPTION_REMOVE'); ?>" title="<?php echo JText::_('COM_ACEPOLLS_OPTION_REMOVE'); ?>"  /></a></td>
					<td>
						<b><?php echo JText::_('COM_ACEPOLLS_OPTION'); ?></b>
					</td>
					<td>
						<?php echo JText::_('COM_ACEPOLLS_COLOR'); ?>
					</td>
					<td>
						<?php echo JText::_('COM_ACEPOLLS_VOTES'); ?>
					</td>
				</tr>
				<?php
				$n = count($this->options);
				for ($i = 0; $i < $n; $i++) {
				?>
					<tr class="dragable" id="<?php echo $i+1; ?>" >
						<td align="center"><b><?php echo $i+1; ?></b></td>
						<td>
							<input class="inputbox checkit" type="text" name="polloption[<?php echo $this->options[$i]->id; ?>]" id="polloption<?php echo $this->options[$i]->id; ?>" value="<?php echo $this->options[$i]->text; ?>" size="60" />
							<input type="hidden" name="ordering[<?php echo $this->options[$i]->id; ?>]" id="ordering<?php echo $this->options[$i]->id; ?>" value="<?php echo $this->options[$i]->ordering; ?>" size="1" class="ordering" />
						</td>
						<td>
							<div style="position:relative;">
								<input type="hidden" size="7"  name="color[<?php echo $this->options[$i]->id; ?>]" id="color<?php echo $this->options[$i]->id; ?>" value="#<?php echo $this->options[$i]->color; ?>" class="colorpicker" />&nbsp;
							</div>
						</td>
						<td align="center">
							<div class="vote">
								<?php echo $this->options[$i]->hits; ?>
							</div>
						</td>
					</tr>
					<?php 
				}

				for (; $i < 2; $i++) {  ?>
					<tr class="dragable" id="<?php echo $i+1; ?>">
						<td align="center"><b><?php echo $i+1; ?></b></td>
						<td>
							<input class="inputbox checkit" type="text" name="polloption[]" id="polloption<?php echo $i+1; ?>" value="" size="60" />
							<input type="hidden" name="ordering[]" id="ordering<?=$i; ?>" value="<?=$i; ?>" class="ordering" />
						</td>
						<td>
							<div style="position:relative;">
								<input type="hidden" size="7"  name="color[]" id="color<?=$i; ?>" value="#<?php echo $this->color[$i]; ?>" class="colorpicker" />&nbsp;
							</div>
						</td>
						<td></td>
					</tr>					
					<?php 
					 }
					?>
			</table>
		</fieldset>
			
		<?php 
		if ($this->edit) { ?>
			<div id="options-reset-box">
				<a href="#" id="options-reset">
					<?php echo JText::_('COM_ACEPOLLS_RESET_VOTES'); ?></a>
				<span style="color:red; display:none;">
				<?php echo JText::_('COM_ACEPOLLS_RESET_VOTES_DESC'); ?></span>
			</div>
		<?php 
		}
		?>
	</div>

	<div class="clr"></div>

	<input type="hidden" name="option" value="com_acepolls" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" id="reset" name="reset" value="0" />
	<input type="hidden" id="is_there_extra" name="is_there_extra" value="0" />
	<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
	<input type="hidden" name="cid[]" value="<?php echo $row->id; ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>