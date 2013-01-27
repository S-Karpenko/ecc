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

<script language="javascript" type="text/javascript">
	function tableOrdering(order, dir, task) {
	var form = document.adminForm;

	form.filter_order.value 	= order;
	form.filter_order_Dir.value	= dir;
	document.adminForm.submit(task);
}
</script>

<h1><?php echo $this->params->get('page_title'); ?></h1>
<form action="<?php echo JRoute::_(JFactory::getURI()->toString()); ?>" method="post" name="adminForm">
	<table>
		<tr>
			<td align="left" width="100%">
				<?php if($this->params->get('show_filter_box')) :
				echo JText::_('Filter'); ?>:
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_('Go'); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
				<?php endif; ?>
			</td>
			<td nowrap="nowrap">
				<?php
			echo JText::_('COM_ACEPOLLS_DISPLAY_NUM') .'&nbsp;';
			echo $this->pagination->getLimitBox();
		?>
			</td>
		</tr>
	</table>

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<thead>
			<tr>
				<td width="5" height="20" style="" class="sectiontableheader">
					<?php echo JText::_('Num'); ?>
				</td>
				<td class="sectiontableheader">
					<?php echo JHTML::_('grid.sort', JText::_('Title'), 'm.title', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</td>
					<?php if($this->params->get('show_start_date')) { ?>
				<td width="10%" class="sectiontableheader" style="text-align:center;" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', JText::_('COM_ACEPOLLS_START'), 'm.publish_up', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</td><?php } ?>						
				<?php if($this->params->get('show_end_date')) { ?>
				<td width="10%" class="sectiontableheader" style="text-align:center;" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', JText::_('COM_ACEPOLLS_END'), 'm.publish_down', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</td><?php } ?>					
				<?php if($this->params->get('show_status')) { ?>
				<td width="5%" class="sectiontableheader" style="text-align:center;" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', JText::_('COM_ACEPOLLS_STATUS'), 'status', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</td><?php } ?>				
				<?php if($this->params->get('show_num_voters')) { ?>
				<td width="5%" class="sectiontableheader" style="text-align:center;" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', JText::_('COM_ACEPOLLS_VOTES'), 'voters', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</td><?php } ?>
					<?php if($this->params->get('show_num_options')) { ?>
				<td width="5%" class="sectiontableheader" style="text-align:center;" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', JText::_('COM_ACEPOLLS_OPTIONS'), 'numoptions', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</td><?php } ?>
			</tr>
		</thead>

		<tbody>
		<?php
		$k = 0;
		$n = count($this->items);
		for ($i=0; $i < $n; $i++) {
			$row = &$this->items[$i];
			
			//find the Itemid that correspondents to the link if any.
			$component 	=& JComponentHelper::getComponent('com_acepolls');
			$menus		=& JApplication::getMenu('site', array());
			$menu_items	= $menus->getItems('component_id', $component->id);
			$itemid		= null;
			
			if (isset($menu_items)) {
				foreach ($menu_items as $item) {
					if ((@$item->query['view'] == 'poll') && (@$item->query['id'] == $row->id)) {
						$itemid = '&Itemid='.$item->id;
						break;
					}			
				}
			}
			
			$link = JRoute::_('index.php?option=com_acepolls&view=poll&id='.$row->slug.$itemid); 
		
		?>
			<tr class="sectiontableentry<?php echo $k + 1; ?>">
				<td>
					<?php echo $this->pagination->getRowOffset($i); ?>
				</td>
				<td>
					<span class="hasTip" title="<?php echo JText::_('COM_ACEPOLLS_VIEW');?>">
						<a href="<?php echo $link;?>"><?php echo $row->title; ?></a>
					</span>
				</td>
				<?php if ($this->params->get('show_start_date')) { ?>
				<td align="center">
					<?php echo JFactory::getDate($row->publish_up)->toFormat($this->params->get('date_format')); ?>
				</td>
				<?php } ?>	
				<?php if($this->params->get('show_end_date')) { ?>
				<td>
					<?php echo JFactory::getDate($row->publish_down)->toFormat($this->params->get('date_format')); ?>
				</td>
				<?php } ?>				
				<?php if ($this->params->get('show_status')) { ?>
				<td align="center" style="text-transform:capitalize;">
					<?php 
					if ($this->params->get('show_status_as')) { ?>
						<img src="<?php echo JURI::base(); ?>media/acepolls/images/poll-<?php echo $row->status; ?>.gif" />
					<?php 
					} else {
						echo JText::_('COM_ACEPOLLS_'.$row->status);
					} ?>
				</td>			
				<?php 
				}
				if ($this->params->get('show_num_voters')) { ?>
				<td align="center">
					<?php echo $row->voters; ?>
				</td>
				<?php } ?>
				<?php if($this->params->get('show_num_options')) { ?>
				<td align="center">
					<?php echo $row->numoptions; ?>
				</td>
				<?php } ?>
			</tr>
			<?php $k = 1 - $k;
		}	?>
		</tbody>
	</table>
	<div>
		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
	<div class="pagecounter">
		<?php echo $this->pagination->getPagesCounter(); ?>
	</div>

	<input type="hidden" name="option" value="com_acepolls" />
	<input type="hidden" name="view" value="polls" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />
	<?php echo JHTML::_('form.token'); ?>
</form>
<br />