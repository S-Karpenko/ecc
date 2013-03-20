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
$i = 6;
?>
<?php echo $pane->startPane('upload_files'); ?>
<?php echo $pane->startPanel('Uploads Settings', 'upload_files_'.$i); ?>
	<?php echo $HtmlHelper->input('chronoaction['.$i.'][action_upload_files_'.$i.'_enabled]', array('type' => 'select', 'label' => 'Enabled', 'options' => array(0 => 'No', 1 => 'Yes'))); ?>
	<?php echo $HtmlHelper->input('chronoaction['.$i.'][action_upload_files_'.$i.'_files]', array('type' => 'text', 'label' => "Files", 'class' => 'big_input', 'value' => '', 'smalldesc' => 'Config string, e.g: field1:jpg-png-gif,field2:zip-rar,field3:doc-docx-pdf')); ?>
	<?php echo $HtmlHelper->input('chronoaction['.$i.'][action_upload_files_'.$i.'_max_size]', array('type' => 'text', 'label' => "Max Size in KB", 'class' => 'medium_input', 'value' => '')); ?>
	<?php echo $HtmlHelper->input('chronoaction['.$i.'][action_upload_files_'.$i.'_min_size]', array('type' => 'text', 'label' => "Min Size in KB", 'class' => 'medium_input', 'value' => '')); ?>
	<?php echo $HtmlHelper->input('chronoaction['.$i.'][action_upload_files_'.$i.'_max_error]', array('type' => 'text', 'label' => "Max Size Error", 'class' => 'medium_input', 'value' => '')); ?>
	<?php echo $HtmlHelper->input('chronoaction['.$i.'][action_upload_files_'.$i.'_min_error]', array('type' => 'text', 'label' => "Min Size Error", 'class' => 'medium_input', 'value' => '')); ?>
	<?php echo $HtmlHelper->input('chronoaction['.$i.'][action_upload_files_'.$i.'_type_error]', array('type' => 'text', 'label' => "File type Error", 'class' => 'medium_input', 'value' => '')); ?>

	<?php echo $HtmlHelper->input('chronoaction_id['.$i.']', array('type' => 'hidden', 'value' => $i)); ?>
	<?php echo $HtmlHelper->input('chronoaction['.$i.'][type]', array('type' => 'hidden', 'value' => 'upload_files')); ?>
<?php echo $pane->endPanel(); ?>
<?php echo $pane->endPane(); ?>