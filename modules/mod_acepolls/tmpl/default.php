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
defined('_JEXEC') or die('Restricted access');
 
$document =& JFactory::getDocument();	 
$document->addStyleDeclaration("div#poll_loading_".$poll->id." {
	background: url(media/system/images/mootree_loader.gif) 0% 50% no-repeat;
	width:100%;
	height:20px; 
	padding: 4px 0 0 20px; 
}
");
?>

<div class="poll<?php echo $params->get('moduleclass_sfx'); ?>" style="border:none; padding:1px;">

<?php if ($params->get('show_poll_title')) : ?>
    <h4><?php echo $poll->title; ?></h4>
<?php endif; ?>

<div id="polldiv_<?php echo $poll->id;?>">

<?php if ($display_poll) { ?>
<form action="<?php echo JRoute::_('index.php');?>" method="post" name="poll_vote_<?php echo $poll->id;?>" id="poll_vote_<?php echo $poll->id;?>">
<?php for ($i = 0, $n = count($options); $i < $n; $i ++) { ?>
	<label for="mod_voteid<?php echo $options[$i]->id;?>" class="<?php echo $tabclass_arr[$tabcnt].$params->get('moduleclass_sfx'); ?>" style="display:block; padding:2px;">
		<input type="radio" name="voteid" id="mod_voteid<?php echo $options[$i]->id;?>" value="<?php echo $options[$i]->id;?>" alt="<?php echo $options[$i]->id;?>" <?php echo $disabled; ?> />
			<?php echo $options[$i]->text; ?>
	</label>
	<?php $tabcnt = 1 - $tabcnt; } 
			
			//show messages box
			if($params->get('show_msg')) : 
				echo '<div id="mod_poll_messages_'.$poll->id.'" style="margin:5px;">'.JText::_($msg);
				if($params->get('show_detailed_msg')) echo " ".$details;
				echo '</div>';
			endif;
	?>
	<div style="padding:2px;" id="poll_buttons_<?php echo $poll->id;?>" >	
	<input type="submit" id="submit_vote_<?php echo $poll->id; ?>" name="task_button" class="button" value="<?php echo JText::_('MOD_ACEPOLLS_VOTE'); ?>" <?php echo $disabled; ?> />
	</div>	
	<div id="poll_loading_<?php echo $poll->id;?>" style="display:none;"><?php echo JText::_('MOD_ACEPOLLS_PROCESSING'); ?>
	</div>		

	<input type="hidden" name="option" value="com_acepolls" />
	<input type="hidden" name="id" value="<?php echo $poll->id;?>" />
	<?php if ($params->get('ajax')) { ?>
    <input type="hidden" name="format" value="raw" />
    <input type="hidden" name="view" value="poll" />
	<?php } else { ?>
	<input type="hidden" name="task" value="vote" />
	<?php }; 
	echo "<div>".JHTML::_('form.token')."</div>";  ?>
</form>

<?php if($params->get('ajax')) {
// add mootools
JHTML::_('behavior.mootools');

$updateValue = '';
$poll_bars_color = $params->get('poll_bars_color');

for ($i = 0; $i < count($results); $i++) {
	if ($params->get('only_one_color')) {
		$background_color = $poll_bars_color;
	}
	else {
		$background_color = "' + options.item($i).attributes[2].nodeValue + '";	
	}

	$updateValue .= "<div style=\"width:100%\"><div style=\"padding: 3px;\">' + text.item($i).firstChild.nodeValue + ' - ' + options.item($i).attributes[1].nodeValue + '%</div><div class=\"poll_module_bar_holder\" id=\"poll_module_bar_holder".$i."\" style=\"width: 98%; height: 10px; padding:1px; border:1px solid #".$params->get('poll_bars_border_color').";\"><div id=\"poll_module_bar'+options.item($i).attributes[0].nodeValue+'\" style=\"background:#$background_color; width:' + options.item($i).attributes[1].nodeValue + '%; height:10px;\"></div></div></div>";
}

if ($params->get('show_total')) {
	$updateValue .= "<br /><b>".JText::_('MOD_ACEPOLLS_TOTAL_VOTES')."</b>: ' + voters.item(0).firstChild.nodeValue + '";
}

$js = "
/* <![CDATA[ */
window.addEvent('load', function()
    {
		$('poll_vote_".$poll->id."').addEvent('submit', function(e) {
				// Prevent the submit event
			    new Event(e).stop();

				var options = $('poll_vote_".$poll->id."').getElements('input[name=voteid]');

				var nothing_selected = 1;
				
				options.each(function(item, index){
					if(item.checked==1) {
						nothing_selected = 0;
					}
				});
				
				if (nothing_selected) {
					alert('".JText::_('Please select an option')."');
					return false;
				}
				else {
					$('submit_vote_".$poll->id."').disabled = 1;
					$('poll_loading_".$poll->id."').setStyle('display', '');
					
					// Update the page
					this.set('send', {
					onComplete: function(response, responseXML)
					{						
						// get the XML nodes
						var root    = responseXML.documentElement;
						var options = root.getElementsByTagName('option');
						var text    = root.getElementsByTagName('text');
						var voters  = root.getElementsByTagName('voters');
						
						// update the page element
						var slide = new Fx.Slide('polldiv_".$poll->id."').hide();
						$('polldiv_".$poll->id."').innerHTML = '".$updateValue."';
						slide.slideIn();
					}
					}).send();
				}
        });  
    });/* ]]> */";

$document->addScriptDeclaration($js);

}
//If user has voted 
	} else { 
	
		foreach ($results as $row) :
			$percent = ($row->votes)? round((100*$row->hits)/$row->votes, 1):0;
			$width = ($percent)? $percent:2; 
			if($params->get('only_one_color')) 
				$background_color = $params->get('poll_bars_color');
			else 
				$background_color = $row->color; ?>
			
			<div>
				<div style="padding:3px;"><?php echo $row->text." - ".$percent; ?>%</div>
				<div style="height:10px; padding:1px; border:1px solid #<?php echo $params->get('poll_bars_border_color'); ?>;">
					<div style="width: <?php echo $width; ?>%; height:10px;background:#<?php echo $background_color; ?>;"></div>
				</div>
			</div>
<?php  endforeach;
			if($params->get('show_total')) 
				echo "<br /><b>".JText::_('MOD_ACEPOLLS_TOTAL_VOTES')."</b>: ".$row->votes;
			
			if($params->get('show_msg')) : 
				echo '<div id="mod_poll_messages_'.$poll->id.'" style="margin:5px;">'.JText::_($msg);
				if($params->get('show_detailed_msg')) { 
					echo " ".$details;
				}
				echo '</div>';
			endif;
 } ?>

<!-- End of #polldiv -->
</div>
<?php if (($params->get('show_view_details')) || ($params->get('rel_article_window'))) { ?>
<div id="poll_links" style="padding-top:5px; ">

	<?php if ($params->get('show_view_details')) : ?>
	<a class="poll_result_link" href="<?php echo JRoute::_('index.php?option=com_acepolls&view=poll&id='.$slug.$itemid); ?>"><?php echo JText::_('MOD_ACEPOLLS_VIEW_DETAILS'); ?></a><br />
	<?php endif; ?>
	
	<?php if ($params->get('show_rel_article')) : ?>
	<a class="poll_result_link" target="<?php echo $params->get('rel_article_window'); ?>" href="<?php echo JRoute::_($params->get('rel_article')); ?>">
		<?php echo JText::_('MOD_ACEPOLLS_READ_RELATED_ARTICLE'); ?> >></a>
	<?php endif; ?>
<?php } ?>

</div>
</div>