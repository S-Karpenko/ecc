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
?>
<?php $i = 9; ?>
<?php echo $pane->startPane('db_save'); ?>
<?php echo $pane->startPanel('DB Connection', 'db_save_'.$i); ?>
	<?php echo $HtmlHelper->input('chronoaction['.$i.'][action_db_save_'.$i.'_enabled]', array('type' => 'select', 'label' => 'Enabled', 'options' => array(0 => 'No', 1 => 'Yes'))); ?>			
	<?php
		$database = JFactory::getDBO();
		$tables = $database->getTableList();
		$options = array();
		foreach($tables as $table){
			$options[$table] = $table;
		}
	?>
	<?php echo $HtmlHelper->input('chronoaction['.$i.'][action_db_save_'.$i.'_table_name]', array('type' => 'select', 'label' => 'Table', 'options' => $options, 'empty' => " - ", 'class' => 'medium_input', 'smalldesc' => "The database table where the form data will be stored.<br />if you didn't create a table for your form yet then you can do this in the Forms Manager.")); ?>
	<?php echo $HtmlHelper->input('chronoaction['.$i.'][action_db_save_'.$i.'_save_under_modelid]', array('type' => 'hidden', 'value' => 0)); ?>

	<?php echo $HtmlHelper->input('chronoaction_id['.$i.']', array('type' => 'hidden', 'value' => $i)); ?>
	<?php echo $HtmlHelper->input('chronoaction['.$i.'][type]', array('type' => 'hidden', 'value' => 'db_save')); ?>
<?php echo $pane->endPanel(); ?>
<?php echo $pane->endPane(); ?>

