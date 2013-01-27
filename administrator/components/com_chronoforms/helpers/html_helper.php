<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
class HtmlHelper {
	var $data;
	var $advanced_data = false;
	var $containers = array();
	var $containers_endings = array();
	var $divs_containers_ids = array();
	var $divs_containers_ids_count = array();
	
	function __construct(){
		
	}
	
	function url($link = array()){
		$output = '';
		if(is_array($link) && !empty($link)){
			$params = array();
			foreach($link as $param => $value){
				if($param == '#'){
					$params[] = $param.''.$value;
				}else{
					$params[] = $param.'='.$value;
				}
			}
			$output = implode("&amp;", $params);
			$output = "index.php?".$output;			
		}
		return $output;
	}
	
	function link($text = '', $link = array(), $html = array()){
		$output = '';
		if(is_array($link) && !empty($link)){
			$params = array();
			foreach($link as $param => $value){
				if($param == '#'){
					$params[] = $param.''.$value;
				}else{
					$params[] = $param.'='.$value;
				}
			}
			$output = implode("&amp;", $params);
			$output = "index.php?".$output;
			$output = '<a href="'.$output.'" ';
			foreach($html as $tag => $val){
				if($tag == '#'){
					$output .= $tag.''.$val.' ';
				}else{
					$output .= $tag.'="'.$val.'" ';
				}
			}
			$output .= '>'.$text.'</a>';
		}
		return $output;
	}
	
	function image($path = '', $html = array('border' => 0), $link = array()){
		$output = '';
		$output = '<img src="'.$path.'" ';
		if(!isset($html['border'])){
			$html['border'] = 0;
		}
		foreach($html as $tag => $val){
			$output .= $tag.'="'.$val.'" ';
		}
		$output .= ' />';
		if(is_array($link) && !empty($link)){
			$output = $this->link($output, $link);
		}
		return $output;
	}
	
	function input($fieldname = '', $fieldoptions = array(), $do_replacements = false){
		$output = '';
		$tag = array();
		if(!isset($fieldoptions['type'])){
			$fieldoptions['type'] = 'text';
			$fieldoptions['tag'] = 'input';			
		}
		$tag['type'] = $fieldoptions['type'];
		$tag['name'] = $fieldname;
		//fix the quotes in the values
		if(isset($fieldoptions['value'])){
			$fieldoptions['value'] = str_replace('"', "'", $fieldoptions['value']);
		}
		
		//prepare validation classes
    	if(isset($fieldoptions['validations']) && !empty($fieldoptions['validations'])){
    		$validation_classes = explode(",", $fieldoptions['validations']);
    		$validation_classes = array_map('trim', $validation_classes);
    		if($fieldoptions['type'] == 'checkbox_group'){
    			//$validation_classes = array('%checkSelection');
    		}
    		$field_class = array();
    		if(isset($fieldoptions['class'])){
    			$field_class = array($fieldoptions['class']);
    		}
    		$field_class[] = "validate['".implode("','", $validation_classes)."']";
			$fieldoptions['class'] = implode(' ', $field_class);
			unset($fieldoptions['validations']);
		}else{
			unset($fieldoptions['validations']);
		}
		//set id if not set
		if(!isset($fieldoptions['id'])){
			$fieldoptions['id'] = $this->slug($fieldname);
		}
		//reset id if empty
		if(isset($fieldoptions['id']) && !strlen(trim($fieldoptions['id']))){
			unset($fieldoptions['id']);
		}
		//apply the label class
		if(isset($fieldoptions['label']) && !is_array($fieldoptions['label']) && ($fieldoptions['label'] !== false)){
			$fieldoptions['label'] = array('text' => $fieldoptions['label']);
		}
		if(!isset($fieldoptions['label'])){
			$fieldoptions['label'] = false;
		}
		//check if radio to add the div
    	/*if(isset($fieldoptions['type']) && ($fieldoptions['type'] == 'radio') && isset($fieldoptions['label']['text']) && strlen($fieldoptions['label']['text'])){
			$fieldoptions['before'] = '<lable class="ccms_form_label">'.$fieldoptions['label']['text'].'</label>';
    		if(!isset($fieldoptions['legend'])){
    			$fieldoptions['legend'] = false;
    		}
		}*/
		//prepare the tooltips
    	if(isset($fieldoptions['tooltip'])){
    		if(strlen(trim($fieldoptions['tooltip']))){
	    		if(!isset($fieldoptions['after'])){
	    			$fieldoptions['after'] = '';
	    		}
	    		//$tiplink = '<a href="#" title="'.$fieldoptions['label']['text'].'::'.$fieldoptions['tooltip'].'">?</a>';
				$tiplink = '<a href="#">?</a>';
				$fieldoptions['after'] .= '<div title="'.$fieldoptions['label']['text'].'" rel="'.nl2br($fieldoptions['tooltip']).'" class="tooltipimg">'.$tiplink.'</div>';
    		}
			unset($fieldoptions['tooltip']);
		}
    	//prepare the descriptions
    	if(isset($fieldoptions['smalldesc'])){
    		if(strlen(trim($fieldoptions['smalldesc']))){
	    		if(!isset($fieldoptions['after'])){
	    			$fieldoptions['after'] = '';
	    		}
				$fieldoptions['after'] .= '<div class="small-message">'.nl2br($fieldoptions['smalldesc']).'</div>';
    		}
			unset($fieldoptions['smalldesc']);
		}
		//add clear div
		if(!isset($fieldoptions['after'])){
    		$fieldoptions['after'] = '';
    	}
		$fieldoptions['after'] .= '<div class="clear"></div>';
		$fieldoptions['after'] .= '<div id="error-message-'.str_replace('[]', '', $fieldname).'"></div>';
		//set container div options
    	if(!isset($fieldoptions['type'])){
			$fieldoptions['type'] = 'text';
		}
		$div_class = array();
		$div_class[] = 'ccms_form_element';
		$etype = $fieldoptions['type'];
		if(isset($fieldoptions['multiple']) && $fieldoptions['multiple'] == 'checkbox'){
			$etype = 'checkboxgroup';
		}
		$div_class[] = 'cfdiv_'.$etype;
		
		if(isset($fieldoptions['label_over']) && $fieldoptions['label_over'] == true){
			$div_class[] = 'label_over';
			unset($fieldoptions['label_over']);
		}
		if(isset($fieldoptions['label_over'])){
			unset($fieldoptions['label_over']);
		}
		
		$hide_label = false;
		if(isset($fieldoptions['hide_label'])){
			$hide_label = (bool)$fieldoptions['hide_label'];
			unset($fieldoptions['hide_label']);
		}
		
    	if(isset($fieldoptions['radios_over'])){
			if($fieldoptions['radios_over'] == true){
				$div_class[] = 'radios_over';
			}
			unset($fieldoptions['radios_over']);
		}
		
		if(isset($fieldoptions['multiline_start'])){
			if($fieldoptions['multiline_start'] == true){
				$div_class[] = 'multiline_start';
			}
			unset($fieldoptions['multiline_start']);
		}
		if(isset($fieldoptions['multiline_add'])){
			if($fieldoptions['multiline_add'] == true){
				$div_class[] = 'multiline_add';
			}
			unset($fieldoptions['multiline_add']);
		}
		
		if(!isset($fieldoptions['div'])){
			$fieldoptions['div'] = array();
		}
		//$div_prefix = (isset($fieldoptions['id']) && strlen(trim($fieldoptions['id'])) > 0) ? $this->slug($fieldoptions['id']) : 'autoID-'.md5($fieldname.'-'.rand(11111, 99999));
		if(isset($fieldoptions['id']) && strlen(trim($fieldoptions['id'])) > 0){
			$div_prefix = $this->slug($fieldoptions['id']);
		}else{
			$temp_prefix = $this->slug($fieldname);
			if(!in_array($temp_prefix, $this->divs_containers_ids)){
				$div_prefix = $temp_prefix.'1';
				$this->divs_containers_ids[] = $temp_prefix;
				$this->divs_containers_ids_count[$temp_prefix] = array(1);
			}else{
				//get last count
				$last_count = $this->divs_containers_ids_count[$temp_prefix][count($this->divs_containers_ids_count[$temp_prefix]) - 1];
				$div_prefix = $temp_prefix.''.($last_count + 1);
				$this->divs_containers_ids_count[$temp_prefix][] = $last_count + 1;
			}
		}
		$divContainer = array_merge($fieldoptions['div'], array('id' => $div_prefix.'_container_div', 'class' => implode(' ', $div_class), 'style' => array()));
		unset($fieldoptions['div']);
		//add before prefix
		$beforeOutput = '';
		if(isset($fieldoptions['before'])){
    		$beforeOutput = $fieldoptions['before'];
			unset($fieldoptions['before']);
    	}
		//add after prefix
		$afterOutput = '';
		if(isset($fieldoptions['after'])){
    		$afterOutput = $fieldoptions['after'];
			unset($fieldoptions['after']);
    	}
		
		if(isset($fieldoptions['default']) && (!isset($fieldoptions['value']) || (is_string($fieldoptions['value']) && strlen($fieldoptions['value']) == 0))){
			$field_value = $fieldoptions['default'];
			unset($fieldoptions['default']);
		}
		
		//check form data
		if(isset($this->data) && !empty($this->data)){
			if($this->advanced_data === true){
				$no_field_preset_value_exists = (!isset($fieldoptions['value']) || (is_string($fieldoptions['value']) && strlen($fieldoptions['value']) == 0));
				if(is_array($this->data)){
					$data_value = $this->fieldValue($fieldname, $this->data);
					if(!is_null($data_value) && $no_field_preset_value_exists){
						$field_value = $data_value;
					}
				}else if(is_object($this->data)){
					$data_value = $this->fieldValue($fieldname, (array)$this->data);
					if(!is_null($data_value) && $no_field_preset_value_exists){
						$field_value = $data_value;
					}
				}
			}else{
				if(is_array($this->data)){
					if(isset($this->data[$fieldname])){
						$field_value = htmlspecialchars($this->data[$fieldname]);
					}
				}else if(is_object($this->data)){
					if(isset($this->data->$fieldname)){
						$field_value = htmlspecialchars($this->data->$fieldname);
					}
				}
			}
		}
		
		if(isset($field_value)){
			$fieldoptions['value'] = $field_value;
		}
		
		//merge tag with fieldoptions
		$tag = array_merge($fieldoptions, $tag);
		if(isset($tag['label'])){
			unset($tag['label']);
		}
		//close containers
		$old_output = '';
		if(!empty($this->containers) && isset($tag['container_id']) && $tag['container_id'] != $this->containers[count($this->containers) - 1]){
			//we are out of the last container, close it
			$containers = array_reverse($this->containers);
			$containers_endings = array_reverse($this->containers_endings);
			foreach($containers as $k => $container){
				if($container != $tag['container_id']){
					$old_output .= $containers_endings[$k];
					array_pop($this->containers);
					array_pop($this->containers_endings);
				}else{
					break;
				}
			}
		}
		if(isset($tag['container_id'])){
			unset($tag['container_id']);
		}
		//print_r2($fieldoptions);
		switch($fieldoptions['type']){			
			case 'submit':
				unset($fieldoptions['value']);
				$output .= '<input';
				if(isset($tag['button_type'])){
					$tag['type'] = $tag['button_type'];
					unset($tag['button_type']);
				}
				if(isset($tag['button_align'])){
					$divContainer['style'][] = 'text-align:'.$tag['button_align'];
					unset($tag['button_align']);
				}
				foreach($tag as $k => $v){
					if(in_array($k, array('reset_button', 'reset_button_value', 'back_button', 'back_button_value'))){
						continue;
					}
					$output .= ' '.$k.'="'.$v.'"';
				}
				$output .= ' />'."\n";
				if(isset($tag['reset_button']) && (bool)$tag['reset_button'] === true){
					$output .= "&nbsp;<input type='reset' name='reset' value='".$tag['reset_button_value']."' />";
				}
				if(isset($tag['back_button']) && (bool)$tag['back_button'] === true){
					$output .= "&nbsp;<input type='button' name='back' value='".$tag['back_button_value']."' onclick='history.back()' />";
				}				
				unset($fieldoptions['label']);
				break;
			case 'textarea':
				$value = '';
				if(isset($tag['value'])){
					$value = $tag['value'];
					unset($tag['value']);
				}
				if(isset($tag['type'])){
					unset($tag['type']);
				}
				if(isset($tag['wysiwyg_editor']) && $tag['wysiwyg_editor'] == 1){					
					$output .= "<?php \$editor = JFactory::getEditor(); echo \$editor->display('".$fieldname."', '".$value."', ".$fieldoptions['editor_width'].", ".$fieldoptions['editor_height'].", ".$fieldoptions['rows'].", ".$fieldoptions['cols'].", ".$fieldoptions['editor_buttons']."); ?>";
				} else {
					$output .= '<textarea';
					foreach($tag as $k => $v){
						if(in_array($k, array('wysiwyg_editor', 'editor_width', 'editor_height', 'editor_buttons'))){
							continue;
						}
						$output .= ' '.$k.'="'.$v.'"';
					}
					$output .= '>'.$value.'</textarea>'."\n";
				}
				break;
			case 'select':
				if(isset($fieldoptions['value'])){
					if(!empty($fieldoptions['value'])){
						$tag['selected'] = $fieldoptions['value'];
					}
					unset($fieldoptions['value']);
				}
				$output .= '<select';
				$options = array();
				if(isset($tag['options']) && is_array($tag['options'])){
					$options = $tag['options'];
					unset($tag['options']);
					$selected = '';
					if(isset($tag['selected'])){
						$selected = $tag['selected'];
						unset($tag['selected']);
					}
					if(!is_array($selected)){
						$selected = array($selected);
					}
				}
				$empty = false;
				if(isset($tag['empty'])){
					if(!empty($tag['empty']))
					$empty = '<option value="">'.$tag['empty'].'</option>'."\n";
					unset($tag['empty']);
				}
				if(isset($tag['multiple']) && $tag['multiple'] == 1){
					$tag['multiple'] = 'multiple';
					if(strpos($tag['name'], '[]') === false){
						$tag['name'] = $tag['name'].'[]';
					}
				}
				if(isset($tag['type'])){
					unset($tag['type']);
				}
				foreach($tag as $k => $v){
					if(!in_array($k, array('enable_dynamic_data', 'data_path', 'value_key', 'text_key'))){
						$output .= ' '.$k.'="'.$v.'"';
					}
				}
				$output .= '>'."\n";
				if($empty){
					$output .= $empty;
				}
				if(isset($tag['enable_dynamic_data']) && ($tag['enable_dynamic_data'] == 1)){
					if(!empty($tag['data_path']) && !empty($tag['value_key']) && !empty($tag['text_key'])){
						$output .= '
						<?php
						$options_data = $form->get_array_value($form->data, explode(".", "'.$tag["data_path"].'"));
						if(!is_null($options_data) && is_array($options_data)){
							foreach($options_data as $option_data){
								if(isset($option_data["'.$tag["value_key"].'"]) && isset($option_data["'.$tag["text_key"].'"])){
									echo \'<option value="\'.$option_data["'.$tag["value_key"].'"].\'"\'.(in_array($option_data["'.$tag["value_key"].'"], '.var_export($selected, true).') ? \' selected="selected"\' : "").">".$option_data["'.$tag["text_key"].'"]."</option>\n";
								}
							}
						}
						?>
						';
					}
				}else{
					foreach($options as $k => $option){
						$output .= '<option value="'.$k.'"'.(in_array($k, $selected) ? ' selected="selected"' : '').'>'.$option.'</option>'."\n";
					}
				}
				$output .= '</select>'."\n";
				break;
			case 'radio':
				$checked_value = null;
				if(isset($fieldoptions['value'])){
					$checked_value = $fieldoptions['value'];
				}
				unset($fieldoptions['value']);
				$options = array();
				if(isset($tag['options']) && is_array($tag['options'])){
					$options = $tag['options'];
					unset($tag['options']);
				}
				if(isset($tag['ghost'])){
					if((bool)$tag['ghost'] === true){
						$output .= '<input type="hidden" name="'.$fieldname.'" value="'.$tag['ghost_value'].'" alt="ghost" />'."\n";
					}
					unset($tag['ghost']);
					unset($tag['ghost_value']);
				}
				$output .= '<div style="float:left; clear:none;">';
				if(isset($tag['enable_dynamic_data']) && ($tag['enable_dynamic_data'] == 1)){
					if(!empty($tag['data_path']) && !empty($tag['value_key']) && !empty($tag['text_key'])){
						$output .= '
						<?php
						$options_data = $form->get_array_value($form->data, explode(".", "'.$tag["data_path"].'"));
						if(!is_null($options_data) && is_array($options_data)){
							$f_id = 0;
							foreach($options_data as $option_data){
								if(isset($option_data["'.$tag["value_key"].'"]) && isset($option_data["'.$tag["text_key"].'"])){
									echo \'<input type="'.$fieldoptions['type'].'" name="'.$fieldname.'" id="'.$this->slug($fieldname.'-').'\'.$f_id.\'" title="'.$tag['title'].'" value="\'.$option_data["'.$tag["value_key"].'"].\'"\'.(($option_data["'.$tag["value_key"].'"] == '.var_export($checked_value, true).') ? \' checked="checked"\' : \'\').\' class="'.addcslashes($tag['class'], "'").'" />\'."\n";
									echo \'<label for="'.$this->slug($fieldname.'-').'\'.$f_id.\'">\'.$option_data["'.$tag["text_key"].'"].\'</label>\'."\n";
									$f_id++;
								}
							}
						}
						?>
						';
					}
				}else{
					$f_id = 0;
					foreach($options as $k => $option){
						$output .= '<input type="'.$fieldoptions['type'].'" name="'.$fieldname.'" id="'.$this->slug($fieldname.'-'.$f_id).'" title="'.$tag['title'].'" value="'.$k.'"'.(($k == $checked_value) ? ' checked="checked"' : '').' class="'.$tag['class'].'" />'."\n";
						$output .= '<label for="'.$this->slug($fieldname.'-'.$f_id).'">'.$option.'</label>'."\n";
						$f_id++;
					}
				}
				$output .= '</div>';
				break;
			case 'checkbox_group':
				unset($fieldoptions['value']);
				$fieldoptions['type'] = 'checkbox';
				$options = array();
				if(isset($tag['options']) && is_array($tag['options'])){
					$options = $tag['options'];
					unset($tag['options']);
					$checked = false;
					if(isset($tag['selected'])){
						$checked = explode(",", $tag['selected']);
						unset($tag['selected']);
					}
				}
				if(isset($tag['ghost'])){
					if((bool)$tag['ghost'] === true){
						$output .= '<input type="hidden" name="'.$fieldname.'" value="'.$tag['ghost_value'].'" alt="ghost" />'."\n";
					}
					unset($tag['ghost']);
					unset($tag['ghost_value']);
				}
				$output .= '<div style="float:left; clear:none;">';
				if(isset($tag['enable_dynamic_data']) && ($tag['enable_dynamic_data'] == 1)){
					if(!empty($tag['data_path']) && !empty($tag['value_key']) && !empty($tag['text_key'])){
						$output .= '
						<?php
						$options_data = $form->get_array_value($form->data, explode(".", "'.$tag["data_path"].'"));
						if(!is_null($options_data) && is_array($options_data)){
							$f_id = 0;
							foreach($options_data as $option_data){
								if(isset($option_data["'.$tag["value_key"].'"]) && isset($option_data["'.$tag["text_key"].'"])){
									echo \'<input type="'.$fieldoptions['type'].'" name="'.$fieldname.'[]" id="'.$this->slug($fieldname.'-').'\'.$f_id.\'" title="'.$tag['title'].'" value="\'.$option_data["'.$tag["value_key"].'"].\'"\'.(in_array($option_data["'.$tag["value_key"].'"], '.var_export($checked, true).') ? \' checked="checked"\' : \'\').\' class="'.addcslashes($tag['class'], "'").'" />\'."\n";
									echo \'<label for="'.$this->slug($fieldname.'-').'\'.$f_id.\'">\'.$option_data["'.$tag["text_key"].'"].\'</label>\'."\n";
									$f_id++;
								}
							}
						}
						?>
						';
					}
				}else{
					$f_id = 0;
					foreach($options as $k => $option){
						$output .= '<input type="'.$fieldoptions['type'].'" name="'.$fieldname.'[]" id="'.$this->slug($fieldname.'-'.$f_id).'" title="'.$tag['title'].'" value="'.$k.'"'.(in_array($k, $checked) ? ' checked="checked"' : '').' class="'.$tag['class'].'" />'."\n";
						$output .= '<label for="'.$this->slug($fieldname.'-'.$f_id).'">'.$option.'</label>'."\n";
						$f_id++;
					}
				}
				$output .= '</div>';
				break;
			case 'checkbox':
				if(isset($tag['checked'])){
					if((bool)$tag['checked'] === true){
						$tag['checked'] = 'checked';
					}else{
						unset($tag['checked']);
					}
				}
				if(!isset($fieldoptions['id']) || empty($fieldoptions['id'])){
					$tag['id'] = $fieldoptions['id'] = $this->slug($fieldname);
				}
				if(isset($tag['ghost'])){
					if((bool)$tag['ghost'] === true){
						$output .= '<input type="hidden" name="'.$fieldname.'" value="'.$tag['ghost_value'].'" alt="ghost" />'."\n";
					}
					unset($tag['ghost']);
					unset($tag['ghost_value']);
				}
				$full_label = false;
				if(isset($tag['label_position']) && !empty($tag['label_position'])){
					if($tag['label_position'] == 'right'){
						$full_label = true;
					}
					$tag['class'] = strlen($tag['class']) ? $tag['class'].' label_'.$tag['label_position'] : 'label_'.$tag['label_position'];
				}
				unset($tag['label_position']);
				
				$output .= '<input';
				foreach($tag as $k => $v){
					$output .= ' '.$k.'="'.$v.'"';
				}
				$output .= ' />'."\n";
				if(isset($fieldoptions['label']) && $fieldoptions['label'] !== false){
					$class = '';
					if($full_label){
						$class = ' class="full_label"';
					}
					$afterOutput = '<label'.(isset($fieldoptions['id']) ? ' for="'.$fieldoptions['id'].'"' : '').$class.'>'.$fieldoptions['label']['text'].'</label>'.$afterOutput;
					unset($fieldoptions['label']);
				}
				break;
			case 'hidden':
				if(isset($tag['container_id']))unset($tag['container_id']);
				$output .= '<input';
				foreach($tag as $k => $v){
					$output .= ' '.$k.'="'.$v.'"';
				}
				$output .= ' />'."\n";
				$divContainer = '';
				break;
			case 'datetime':
				if(isset($tag['timeonly']) && (int)$tag['timeonly'] == 1){
					$tag['class'] = empty($tag['class']) ? 'cf_time_picker' : $tag['class'].' cf_time_picker';
				}
				if(isset($tag['addtime']) && (int)$tag['addtime'] == 1 && (int)$tag['timeonly'] == 0){
					$tag['class'] = empty($tag['class']) ? 'cf_datetime_picker' : $tag['class'].' cf_datetime_picker';
				}
				if((int)$tag['timeonly'] == 0 && (int)$tag['addtime'] == 0){
					$tag['class'] = empty($tag['class']) ? 'cf_date_picker' : $tag['class'].' cf_date_picker';
				}
				if(isset($tag['timeonly']))unset($tag['timeonly']);
				if(isset($tag['addtime']))unset($tag['addtime']);
				unset($tag['tag']);
				unset($tag['wf_key']);
				$tag['type'] = 'text';
				$output .= '<input';
				foreach($tag as $k => $v){
					$output .= ' '.$k.'="'.$v.'"';
				}
				$output .= ' />'."\n";
				break;
			case 'custom':
				$output = $tag['code'];
				if((int)$tag['clean'] == 1){
					$divContainer = '';
					$beforeOutput = '';
					$afterOutput = '';
				}
				break;
			case 'header':
				$output = $tag['code'];
				//$divContainer['id'] = md5($this->slug($tag['code'])).$divContainer['id'];
				$afterOutput = '<div class="clear"></div>';
				if((int)$tag['clean'] == 1){
					$divContainer = '';
					$beforeOutput = '';
					$afterOutput = '';
				}
				break;
			case 'container':
				$container_open = '';
				$container_close = '';
				if(isset($tag['container_type'])){
					if($tag['container_type'] == 'div'){
						$container_open = '<div class="cf_container ccms_form_element '.$tag['container_class'].'" id="cf_container_'.$tag['wf_key'].'">';
						$container_close = '<div class="clear"></div></div>';
					}else if($tag['container_type'] == 'fieldset'){
						$container_open = '<fieldset class="cf_container ccms_form_element '.$tag['container_class'].'" id="cf_container_'.$tag['wf_key'].'"><legend>'.$tag['area_label'].'</legend>';
						$container_close = '</fieldset>';
					}else if($tag['container_type'] == 'custom'){
						$container_open = $tag['start_code'];
						$container_close = $tag['end_code'];
					}else if($tag['container_type'] == 'tabs_area'){
						$container_open = "\n".'<div class="ccms_form_element">
							<?php
								jimport("joomla.html.pane");
								$pane = JPane::getInstance("tabs");
								echo $pane->startPane("cf_container_'.$tag['wf_key'].'");
							?>'."\n";
						$container_close = "\n".'<?php if(isset($pane)){echo $pane->endPane();} ?>
							<div class="clear"></div>
						</div>'."\n";
					}else if($tag['container_type'] == 'tab'){
						$container_open = "\n".'<?php if(isset($pane)){echo $pane->startPanel("'.$tag['area_label'].'", "cf_container_'.$tag['wf_key'].'");} ?>'."\n";
						$container_close = "\n".'<?php if(isset($pane)){echo $pane->endPanel();} ?>'."\n";
					}else if($tag['container_type'] == 'sliders_area'){
						$container_open = "\n".'<div class="ccms_form_element">
							<?php
								jimport("joomla.html.pane");
								$pane = JPane::getInstance("sliders");
								echo $pane->startPane("cf_container_'.$tag['wf_key'].'");
							?>'."\n";
						$container_close = "\n".'<?php if(isset($pane)){echo $pane->endPane();} ?>
							<div class="clear"></div>
						</div>'."\n";
					}else if($tag['container_type'] == 'slider'){
						$container_open = "\n".'<?php if(isset($pane)){echo $pane->startPanel("'.$tag['area_label'].'", "cf_container_'.$tag['wf_key'].'");} ?>'."\n";
						$container_close = "\n".'<?php if(isset($pane)){echo $pane->endPanel();} ?>'."\n";
					}
				}
				$output = $container_open;
				array_push($this->containers, $tag['wf_key']);
				array_push($this->containers_endings, $container_close);
				$divContainer = '';
				$beforeOutput = '';
				$afterOutput = '';
				break;
			case 'page_break':
				$output = "<!--_CHRONOFORMS_PAGE_BREAK_-->";
				$divContainer = '';
				$beforeOutput = '';
				$afterOutput = '';
				break;
			case 'pane_start':
				if($tag['pane_type'] == 'sliders'){
					$start_s = "startTransition";
				}else{
					$start_s = "startOffset";
				}
				$output = "\n".'<div class="ccms_form_element">
							<?php
								jimport("joomla.html.pane");
								$pane = JPane::getInstance("'.$tag['pane_type'].'", array("'.$start_s.'" => '.$tag['pane_start'].'));
								echo $pane->startPane("'.$tag['pane_id'].'");
							?>'."\n";
				$divContainer = '';
				$beforeOutput = '';
				$afterOutput = '';
				break;
			case 'panel_start':
				$output = "\n".'<?php if(isset($pane)){echo $pane->startPanel("'.$tag['panel_label'].'", "'.$tag['panel_id'].'");} ?>'."\n";
				$divContainer = '';
				$beforeOutput = '';
				$afterOutput = '';
				break;
			case 'panel_end':
				$output = "\n".'<?php if(isset($pane)){echo $pane->endPanel();} ?>'."\n";
				$divContainer = '';
				$beforeOutput = '';
				$afterOutput = '';
				break;
			case 'pane_end':
				$output = "\n".'<?php if(isset($pane)){echo $pane->endPane();} ?>
							<div class="clear"></div>
						</div>'."\n";
				$divContainer = '';
				$beforeOutput = '';
				$afterOutput = '';
				break;
			case 'widget':
				$params = array();
				$field_header = $tag['tag'].'_'.$tag['widget'].'_'.$tag['wf_key'];
				foreach($tag as $k => $v){
					$params[str_replace($field_header.'_', '', $k)] = $v;
				}
				if(isset($params['label_text'])){
					$fieldoptions['label']['text'] = $params['label_text'];
				}
				$output = "\n".'<?php echo HTML_ChronoForms::loadWidget($form, \''.$tag['widget'].'\', '.var_export($params, true).'); ?>'."\n";
				break;
			case 'file':
				if(isset($tag['ghost'])){
					if((bool)$tag['ghost'] === true){
						$output .= '<input type="hidden" name="'.$fieldname.'" value="'.$tag['ghost_value'].'" alt="ghost" />'."\n";
					}
					unset($tag['ghost']);
					unset($tag['ghost_value']);
				}
				$output .= '<input';
				foreach($tag as $k => $v){
					$output .= ' '.$k.'="'.$v.'"';
				}
				$output .= ' />'."\n";
				break;
			case 'text':
			case 'password':
			default:
				if(isset($tag['tag']))unset($tag['tag']);
				if(isset($tag['wf_key']))unset($tag['wf_key']);
				$output .= '<input';
				foreach($tag as $k => $v){
					$output .= ' '.$k.'="'.$v.'"';
				}
				$output .= ' />'."\n";
				break;
		}
		
		if(isset($fieldoptions['label']) && $fieldoptions['label'] !== false){
			$beforeOutput .= '<label'.(isset($fieldoptions['id']) ? ' for="'.$fieldoptions['id'].'"' : '').(($hide_label) ? ' style="display:none;"' : '').'>'.$fieldoptions['label']['text'].'</label>';
			unset($fieldoptions['label']);
		}		
		
		if(!empty($divContainer)){
			$output = '<div class="'.$divContainer['class'].'" id="'.$divContainer['id'].'" style="'.implode(';', $divContainer['style']).'">'.$beforeOutput.$output.$afterOutput.'</div>';
		}
		
		if($do_replacements){
			//do replacements
			$output = str_replace('%field_name%', $tag['name'], $output);
		}
		$output = $old_output.$output;		
		return $output;
	}
	
	function slug($str, $replacer = "_"){
		$str = strtolower(trim($str));
		$str = preg_replace('/[^a-z0-9{}'.$replacer.']/', $replacer, $str);
		$str = preg_replace('/'.$replacer.'+/', $replacer, $str);
		return $str;
	}
	
	function fieldValue($field_name, $data = array()){
		$field_name = str_replace('[]', '', $field_name);
		$value = null;
		if(!isset($data[$field_name])){
			//check if its an array
			if(strpos($field_name, '[') !== false){
				$value = $this->_processArrayField($field_name, $data);
			}
		}else{
			$value = $data[$field_name];
		}
		return $value;
	}
	
	function _processArrayField($field_name, $data = array()){
		$pieces = explode('[', $field_name);
		$value = null;
		if(isset($data[str_replace(']', '', $pieces[0])])){			
			$path = $data[str_replace(']', '', $pieces[0])];
			//remove the main field name
			unset($pieces[0]);
			foreach($pieces as $piece){
				if(isset($path[str_replace(']', '', $piece)])){
					$value = $path = $path[str_replace(']', '', $piece)];
				}else{
					$value = null;
				}
			}
		}
		return $value;
	}
}