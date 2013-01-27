<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
class CfactionXlsExport{
	var $formname;
	var $formid;
	var $group = array('id' => 'data_export', 'title' => 'Data Export');
	var $details = array('title' => 'XLS Export', 'tooltip' => 'Exports the data to XLS sheet (Actually HTML) which is supported by MS Excel.');
	
	function run($form, $actiondata){
		$mainframe = JFactory::getApplication();
		$session = JFactory::getSession();
		$params = new JParameter($actiondata->params);
		
		$data_path = trim($params->get('data_path', ''));
		$data = $form->get_array_value($form->data, explode('.', $data_path));		
		
		
		if(!empty($data) && is_array($data)){
			$data = array_values($data);
			$first_data_record = $data[0];
			$list_fields = strlen(trim($params->get('list_fields', ''))) ? explode(',', trim($params->get('list_fields', ''))) : array_keys($first_data_record);
			$list_headers = strlen(trim($params->get('list_headers', ''))) ? explode(',', trim($params->get('list_headers', ''))) : array_keys($first_data_record);
			
			$table_rows = '';
			//add headers
			$table_rows .= '<tr bgcolor="#999999">'."\n";
			foreach($list_headers as $k => $v){
				$table_rows .= '<td style="color:white">'.$v.'</td>'."\n";
			}
			$table_rows .= '</tr>'."\n";		
			//add data rows
			foreach($data as $record){
				$table_rows .= '<tr>'."\n";
				foreach($record as $k => $v){
					if(!in_array($k, $list_fields)){
						continue;
					}
					$table_rows .= '<td valign="top" style="mso-number-format:\@">'.$v.'</td>'."\n";
				}
				$table_rows .= '</tr>'."\n";
			}
			//finalize table
			$excel_table = "<table border='1'>".$table_rows."</table>";
			if($params->get('save_file', 0) == 1){
				$save_path = JPATH_SITE.DS.'components'.DS.'com_chronoforms'.DS.'exports'.DS.$form->form_details->name.DS;
				jimport('joomla.filesystem.file');
				if (!JFile::exists($save_path.'index.html')){
					if(!JFolder::create($save_path)){
						$form->debug['XLS Export'][] = "Couldn't create save folder: {$save_path}";
						JError::raiseWarning(100, "Couldn't create save folder: {$save_path}");
						return;
					}	
				}
				if((bool)$params->get('add_bom', 0) === true){
					$excel_table = "\xEF\xBB\xBF".$excel_table;
				}
				$file_name = $params->get('file_name', 'cf_export.xls');
				$saved = file_put_contents($save_path.$file_name, $excel_table);
				if(empty($saved)){
					$form->debug['XLS Export'][] = "Couldn't create XLS file";
					JError::raiseWarning(100, "Couldn't create XLS file");
					return;
				}
				if(strlen($params->get('post_file_name', '')) > 0){
					$post_file_name = $params->get('post_file_name', '');
					$form->data[$post_file_name] = $file_name;
					$form->files[$post_file_name] = array('name' => $file_name, 'path' => $save_path.$file_name, 'size' => filesize($save_path.$file_name));
					//$form->files[$post_file_name]['link'] = $save_url.$file_name;
				}
			}else{
				//set headers
				header("Pragma: public");  
				header("Expires: 0");  
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");  
				header("Content-Type: application/force-download");  
				header("Content-Type: application/octet-stream");  
				header("Content-Type: application/download");;  
				header("Content-Disposition: attachment;filename=".$params->get('file_name', 'cf_export.xls'));  
				header("Content-Transfer-Encoding: binary");
				header('Content-Encoding: UTF-8');
				//output data
				if((bool)$params->get('add_bom', 0) === true){
					echo "\xEF\xBB\xBF";
				}
				echo $excel_table;
				$mainframe = JFactory::getApplication();
				$mainframe->close();
			}			
			
		}
	}
		
	function load($clear){
		if($clear){
			$action_params = array(
				'content1' => '',
				'enabled' => 1,
				'data_path' => '',
				'list_fields' => '',
				'list_headers' => '',
				'add_bom' => 0,
				'save_file' => 0,
				'post_file_name' => '',
				'file_name' => 'cf_export.xls',
			);
		}
		return array('action_params' => $action_params);
	}
}
?>