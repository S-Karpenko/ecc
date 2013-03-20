<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
$mainframe = JFactory::getApplication();
$uri = JFactory::getURI();
$i = 14;
?>
<?php echo $pane->startPane('thanks_message'); ?>
<?php echo $pane->startPanel('Your thanks message', 'thanks_message_'.$i); ?>
	<?php echo $HtmlHelper->input('chronoaction['.$i.'][action_show_thanks_message_'.$i.'_content1]', array('type' => 'textarea', 'label' => "Message body", 'class' => 'mce_editor', 'rows' => 20, 'cols' => 85, 'style' => 'width: 600px; height: 300px;', 'smalldesc' => 'You may use the curly brackets formula to get fields data from the form data array, e.g: {field_name}.')); ?>
	<?php echo $HtmlHelper->input('chronoaction['.$i.'][action_show_thanks_message_'.$i.'_enabled]', array('type' => 'hidden', 'value' => 1)); ?>
	<?php echo $HtmlHelper->input('chronoaction_id['.$i.']', array('type' => 'hidden', 'value' => $i)); ?>
	<?php echo $HtmlHelper->input('chronoaction['.$i.'][type]', array('type' => 'hidden', 'value' => 'show_thanks_message')); ?>
<?php echo $pane->endPanel(); ?>
<?php echo $pane->endPane(); ?>