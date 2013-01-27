<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
/* ensure that this file is not called from another file */
defined('_JEXEC') or die('Restricted access');
class ChronoFormsDbUpdates extends JObject{
	
	function __construct(){
		
	}
			
	function updateDB(){
		$mainframe = JFactory::getApplication();
		$database = JFactory::getDBO();
		jimport('joomla.utilities.error');
		jimport('joomla.filesystem.file');
		$updates = 0;
		//get existing columns list
		$table_name = '#__chronoforms';
		$results = $database->getTableFields(array($table_name), false);
		$table_fields = $results[$table_name];
		$table_fields = array_keys($table_fields);
		//add new columns
		$columns = array(
			'app' => "VARCHAR( 100 ) NOT NULL DEFAULT ''"
		);
		foreach($columns as $f => $data){
			if(!in_array($f, $table_fields)){
				$sql = "ALTER TABLE `#__chronoforms` ADD `".$f."` ".$data.";";
				$database->setQuery($sql);
				if (!$database->query()) {
					JError::raiseWarning(100, $database->getErrorMsg());
				}else{
					$updates++;
				}
			}
		}
		$mainframe->enqueueMessage("Applied '".$updates."' Database updates successfully.");
	}	
}
?>