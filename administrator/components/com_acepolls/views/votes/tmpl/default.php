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

defined('_JEXEC') or die('Restricted access');
?>

<form action="index.php?option=com_acepolls&amp;controller=votes&amp;task=view" method="post" name="adminForm" id="adminForm">
	<table>
		<tr>
			<td align="left" width="100%">
				<?php echo JText::_('COM_ACEPOLLS_FILTER_USERS'); ?>:
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_('COM_ACEPOLLS_GO'); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_('COM_ACEPOLLS_RESET'); ?></button>
			</td>
			<td>
			<?php echo JText::_('COM_ACEPOLLS_VIEW_RESULTS_FOR').':'; ?>
			<?php echo $this->lists['polls']; ?>
			</td>
		</tr>
	</table>
	
	<table class="adminlist" align="center" width="90%" cellspacing="2" cellpadding="2" border="0" >
		<thead>
			<tr>
				<th width="1%"><?php echo JText::_('#'); ?></th>
				<th width="1%"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->votes); ?>);" /></th>
				<th width="10%"><?php echo JHTML::_('grid.sort', JText::_('COM_ACEPOLLS_DATE'), 'v.date', @$this->lists['order_Dir'], @$this->lists['order']); ?></th>
				<th><?php echo JHTML::_('grid.sort', JText::_('COM_ACEPOLLS_USER'), 'u.name', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
				<th><?php echo JHTML::_('grid.sort', JText::_('IP'), 'ip', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
				<th width="80%"><?php echo JHTML::_('grid.sort', JText::_('COM_ACEPOLLS_OPTION'), 'o.text', @$this->lists['order_Dir'], @$this->lists['order']); ?></th>
			</tr>
		</thead>
		<?php
		$i = 0;
		foreach ($this->votes as $vote) { 
			$checkBox = JHTML::_('grid.id', $i++, $vote->id);
		?>
			<tr class="row<?php echo $i%2; ?>">
				<td valign="top" height="30"><?php echo $i; ?></td>
				<td valign="top"><?php echo $checkBox; ?></td>
				<td valign="top"><?php echo $vote->date; ?></td>
				<td valign="top"><?php echo $vote->name; ?></td>
				<td valign="top"><?php echo $vote->ip; ?></td>
				<td valign="top"><?php echo $vote->text; ?></td>
			</tr>

		<?php } ?>
		<tfoot>
			<tr>
				<td colspan="6">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>	
	</table>

	<input type="hidden" name="option" value="com_acepolls" />
	<input type="hidden" name="task" value="view" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="poll_id" value="<?php echo $this->poll_id; ?>" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>