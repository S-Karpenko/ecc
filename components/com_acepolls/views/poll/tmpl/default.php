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

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<?php if ($this->params->get( 'show_title', 1)) { ?>
<div class="componentheading<?php echo $this->params->get('pageclass_sfx') ?>">
	<?php echo $this->escape($this->params->get('page_title')); ?>
</div>
<?php } ?>

<div class="contentpane<?php echo $this->params->get('pageclass_sfx') ?>">

<?php if ($this->allowToVote) { ?>
<div id="poll_comp_form">
	<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="poll_form2">
	<?php 
		$k=0; $i=0; $tabcnt = 0;
	    $tabclass = array ('sectiontableentry2', 'sectiontableentry1');
	    foreach ($this->options as $poll_option) { 
			?>
			<label for="voteid<?php echo $poll_option->id;?>" class="poll<?php echo $tabclass[$tabcnt]; ?>" style="display:block; padding:2px;">
				<input type="radio" name="voteid" id="voteid<?php echo $poll_option->id;?>" value="<?php echo $poll_option->id;?>" alt="<?php echo $poll_option->id;?>" />
				<?php echo $poll_option->text; ?>
			</label>
			<?php $tabcnt = 1 - $tabcnt; 
	    }
		?>
	    
	    <div style="padding:2px; text-align:left;">    
	    	<input type="submit" name="task_button" class="button" value="<?php echo JText::_('COM_ACEPOLLS_VOTE'); ?>" />
	    </div>    
	    
	    <input type="hidden" name="option" value="com_acepolls" />
	    <input type="hidden" name="task" value="vote" />
	    <input type="hidden" name="id" value="<?php echo $this->poll->id;?>" />
	    <?php echo JHTML::_('form.token'); ?>
	</form>
</div>
<br />

<?php 
	//if users are not allowed to vote for some reason (voted or not registered) show warning    
	} else { 
	    if ($this->params->get('show_component_msg')) { 
	    	echo "<p>".JText::_($this->msg)."</p>"; 
	    }
	} ?>

<?php if($this->params->get('show_dropdown')) { ?>
<form action="<?php echo JRoute::_('index.php');?>" method="post" name="poll" id="poll">
	<div class="contentpane<?php echo $this->params->get('pageclass_sfx') ?>">
		<label for="id">
			<?php echo JText::_('COM_ACEPOLLS_VIEW_RESULTS'); ?>
			<?php echo $this->lists['polls']; ?>
		</label>
	</div>
</form>
<?php } ?>
<br />

<b><?php echo JText::_('COM_ACEPOLLS_STATISTICS'); ?>:</b>
<br />
<br />
<?php     
    // set the correct view
    if ($this->params->get('show_what', '0') == '1') {
        echo $this->loadTemplate('pie');
    } else {
        echo $this->loadTemplate('chart');
    }
?>
<br />

<?php if ($this->params->get('show_voters') || $this->params->get('show_times')) { ?>
<table cellspacing="0" cellpadding="0" border="0">
	<tbody>
		<?php if ($this->params->get('show_voters')) { ?>
		<tr>
			<td class="smalldark">
				<?php echo JText::_('COM_ACEPOLLS_NUM_OF_VOTERS'); ?>
			</td>
			<td class="smalldark">
				&nbsp;
				<?php 
					if (isset($this->options[0])) {
						echo $this->options[0]->voters;
					}
				?>
		   </td>
		</tr>
		<?php } ?>
		
		<?php if ($this->params->get('show_times')) { ?>
		<tr>
			<td class="smalldark">
				<?php echo JText::_('COM_ACEPOLLS_START'); ?>
			</td>
			<td class="smalldark">
				&nbsp;
				<?php echo $this->poll->publish_up; ?>
			</td>
		</tr>
		<tr>
			<td class="smalldark">
				<?php echo JText::_('COM_ACEPOLLS_END'); ?>
			</td>
			<td class="smalldark">
				&nbsp;
				<?php echo $this->poll->publish_down; ?>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<?php }


$comments = $this->params->get('show_comments', '0');

if ($comments != 0) {
	$jcomments = JPATH_SITE . '/components/com_jcomments/jcomments.php';
	$jomcomment = JPATH_SITE . '/plugins/content/jom_comment_bot.php';
	$jxcomments = JPATH_SITE . '/components/com_comments/comments.php';
	$jacomments1 = JPATH_SITE . '/components/com_jacomment/jacomment.php';
	$jacomments2 = JPATH_SITE . '/plugins/system/jacomment.php';
	
	if ($comments == 1 && file_exists($jcomments)) {
		require_once($jcomments);
		echo JComments::showComments($this->poll->id, 'com_acepolls', $this->poll->title);
	}
	
	if ($comments == 2 && file_exists($jomcomment)) {
		require_once($jomcomment);
		echo jomcomment($this->poll->id, "com_acepolls");
	}
	
	if ($comments == 3 && file_exists($jxcomments)) {
		$url = 'index.php?option=com_acepolls&view=poll&id='.(int) $this->poll->id;
		$route = $url.':'.$this->poll->alias.'&Itemid='.JRequest::getInt('Itemid');
		
		JHTML::addIncludePath(JPATH_SITE.'/components/com_comments/helpers/html');
		JHTML::_('comments.comments', 'acepolls', $this->poll->id, $url, $route, $this->poll->title);
	}
	
	if ($comments == 4 && file_exists($jacomments1) && file_exists($jacomments2) && !JRequest::getInt('print')) {
		$_jacCode = "#{jacomment(.*?) contentid=(.*?) option=(.*?) contenttitle=(.*?)}#i";
		$_jacCodeDisableid = "#{jacomment(\s)off.*}#i";
		$_jacCodeDisable = "#{jacomment(\s)off}#i";
		if (!preg_match($_jacCode, $this->poll->title) && !preg_match($_jacCodeDisable, $this->poll->title) && !preg_match($_jacCodeDisableid, $this->poll->title)) {
			echo '{jacomment contentid='.$this->poll->id.' option=com_acepolls contenttitle='.$this->poll->title.'}';
		}
	}
}
?>

<br style="clear:both" />
</div>