<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
* this action is a remake of the original image resizing plugin for Chronoforms V3.x by: Emmanuel Danan - www.vistamedia.fr - emmanuel AT vistamedia DOT fr
**/
defined('_JEXEC') or die('Restricted access');
class CfactionImageResize{
	var $formname;
	var $formid;
	var $group = array('id' => 'form_utilities', 'title' => 'Utilities');
	var $details = array('title' => 'Image Resize', 'tooltip' => 'Do some images resizing operations.');
	
	function run($form, $actiondata){
		$params = new JParameter($actiondata->params);
		$mainframe = JFactory::getApplication();
		
		//set the images path
		$upload_path = $params->get('upload_path', '');
		if(!empty($upload_path)){
			$upload_path = str_replace(array("/", "\\"), DS, $upload_path);
			if(substr($upload_path, -1) == DS){
				$upload_path = substr_replace($upload_path, '', -1);
			}
			$upload_path = str_replace("JOOMLA_PATH", JPATH_SITE, $upload_path).DS;
			$params->set('upload_path', $upload_path);
		}else{
			$upload_path = JPATH_SITE.DS.'components'.DS.'com_chronoforms'.DS.'uploads'.DS.$form->form_details->name.DS;
		}
		
		$image_file_name = $params->get('photo', '');
		if(strpos($image_file_name, ',') !== false){
			$image_file_names = explode(',', $image_file_name);
		}else{
			$image_file_names = array($image_file_name);
		}
		
		foreach($image_file_names as $image_file_name){
			//stop if the field name is not set or if the file data doesn't exist
			//if((strlen($image_file_name) == 0) || !isset($form->data[$image_file_name]) || !isset($form->files[$image_file_name]['path'])){
			if((strlen($image_file_name) == 0) || !isset($form->files[$image_file_name])){
				continue;
			}
			
			if($form->files[$image_file_name] === array_values($form->files[$image_file_name])){
				//array of files
				$reset = false;
			}else{
				$form->files[$image_file_name] = array($form->files[$image_file_name]);
				$reset = true;
			}
			foreach($form->files[$image_file_name] as $k => $image){
				// Common parameters		
				$photo = $image['name'];//$form->data[$image_file_name];
				$filein = $image['path'];			
				
				$file_info = pathinfo($filein);
				
				$form->debug['Image_Resize'][$actiondata->order]['thumb_big'] = $form->files[$image_file_name][$k]['thumb_big'] = $this->processSize('big', $form, $actiondata, $photo, $filein, $upload_path, $file_info);
				// treatment of the medium image
				$form->debug['Image_Resize'][$actiondata->order]['thumb_med'] = $form->files[$image_file_name][$k]['thumb_med'] = $this->processSize('med', $form, $actiondata, $photo, $filein, $upload_path, $file_info);

				// treatment of the small image
				$form->debug['Image_Resize'][$actiondata->order]['thumb_small'] = $form->files[$image_file_name][$k]['thumb_small'] = $this->processSize('small', $form, $actiondata, $photo, $filein, $upload_path, $file_info);

				if($params->get('delete_original')){
					unlink($filein);
				}
			}
			if($reset){
				$form->files[$image_file_name] = $form->files[$image_file_name][0];
			}
		}
	}
	
	function processSize($size = 'big', $form, $actiondata, $photo, $filein, $upload_path, $file_info){
		$params = new JParameter($actiondata->params);
		$quality = $params->get('quality', 90);
		$dir = '';
		if($params->get($size.'_directory', '')){
			$dir .= $params->get($size.'_directory', '');
		} else {
			$dir .= $upload_path;
		}
		// add a final slash if needed
		if(substr($dir, -1) != DS){
			$dir .= DS;
		}

		$fileout 			= $dir.$params->get($size.'_image_prefix', '').str_replace('.'.$file_info['extension'], '', $photo).$params->get($size.'_image_suffix', '_'.$size).'.'.$file_info['extension'];
		$crop 				= $params->get($size.'_image_method', 0);
		$imagethumbsize_w 	= $params->get($size.'_image_width', 400);
		$imagethumbsize_h 	= $params->get($size.'_image_height', 300);
		$red				= $params->get($size.'_image_r', 255);
		$green				= $params->get($size.'_image_g', 255);
		$blue				= $params->get($size.'_image_b', 255);
		$use				= $params->get($size.'_image_use', 0);
		
		if($size == 'big'){
			$use = true;
		}
		if($use){
			if($crop){
				$this->resizeThenCrop($filein, $fileout, $imagethumbsize_w, $imagethumbsize_h, $red, $green, $blue, $quality);
			}else{
				$this->resize($filein, $fileout, $imagethumbsize_w, $imagethumbsize_h, $red, $green, $blue, $quality);
			}
			return $params->get($size.'_image_prefix', '').str_replace('.'.$file_info['extension'], '', $photo).$params->get($size.'_image_suffix', '_'.$size).'.'.$file_info['extension'];
		}
		return null;
	}
	
	function resizeThenCrop( $filein, $fileout, $imagethumbsize_w, $imagethumbsize_h, $red, $green, $blue, $quality )
	{
        // Cacul des nouvelles dimensions
        list($width, $height) = getimagesize($filein);
        //$new_width  = $width * $percent;
        //$new_height = $height * $percent;

        if ( preg_match("/.jpg/i", "$filein") || preg_match("/.jpeg/i", "$filein") ) {
            $format = 'image/jpeg';
        } elseif ( preg_match("/.gif/i", "$filein") ) {
            $format = 'image/gif';
        } else if( preg_match("/.png/i", "$filein") ) {
            $format = 'image/png';
        }

        switch($format) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($filein);
                break;
            case 'image/gif';
                $image = imagecreatefromgif($filein);
                break;
            case 'image/png':
                $image = imagecreatefrompng($filein);
                break;
        }

        $width  = $imagethumbsize_w ;
        $height = $imagethumbsize_h ;
        list($width_orig, $height_orig) = getimagesize($filein);

        if ( $width_orig < $height_orig ) {
            $height = ($imagethumbsize_w / $width_orig) * $height_orig;
        } else {
            $width  = ($imagethumbsize_h / $height_orig) * $width_orig;
        }

        if ( $width < $imagethumbsize_w ) {
            // If the image width is less than the thumbnail
            $width  = $imagethumbsize_w;
            $height = ($imagethumbsize_w / $width_orig) * $height_orig;
        }

        if ( $height < $imagethumbsize_h ) {
            // If the image height is less than the thumbnail

            $height = $imagethumbsize_h;
            $width  = ($imagethumbsize_h / $height_orig) * $width_orig;
        }

        $thumb   = imagecreatetruecolor($width , $height);
        $bgcolor = imagecolorallocate($thumb, $red, $green, $blue);
        ImageFilledRectangle($thumb, 0, 0, $width, $height, $bgcolor);
        imagealphablending($thumb, true);

        imagecopyresampled($thumb, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
        $thumb2 = imagecreatetruecolor($imagethumbsize_w , $imagethumbsize_h);
        // true color for better quality
        $bgcolor = imagecolorallocate($thumb2, $red, $green, $blue);
        ImageFilledRectangle($thumb2, 0, 0, $imagethumbsize_w, $imagethumbsize_h, $bgcolor);
        imagealphablending($thumb2, true);

        $w1 = ($width  / 2) - ($imagethumbsize_w / 2);
        $h1 = ($height / 2) - ($imagethumbsize_h  / 2);

        imagecopyresampled($thumb2, $thumb, 0, 0, $w1, $h1,$imagethumbsize_w, $imagethumbsize_h, $imagethumbsize_w, $imagethumbsize_h);

        // create the file
        switch($format) {
            case 'image/jpeg':
                imagejpeg($thumb2, $fileout, $quality);
                break;

            case 'image/gif';
                imagegif($thumb2, $fileout);
                break;

            case 'image/png':
                imagepng($thumb2, $fileout);
                break;
        }
	}


    function resize( $filein, $fileout, $imagethumbsize_w, $imagethumbsize_h, $red, $green, $blue, $quality )
    {
        // Cacul des nouvelles dimensions
        list($width_orig, $height_orig) = getimagesize($filein);

        if ( preg_match("/.jpg/i", "$filein") || preg_match("/.jpeg/i", "$filein") ) {
            $format = 'image/jpeg';
        }
        if ( preg_match("/.gif/i", "$filein") ) {
            $format = 'image/gif';
        }
        if ( preg_match("/.png/i", "$filein") ) {
            $format = 'image/png';
        }

        switch ( $format ) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($filein);
                break;
            case 'image/gif';
                $image = imagecreatefromgif($filein);
                break;
            case 'image/png':
                $image = imagecreatefrompng($filein);
                break;
        }

        $ratio_orig = $width_orig/$height_orig;

        if ($imagethumbsize_w/$imagethumbsize_h > $ratio_orig) {
            $imagethumbsize_w = $imagethumbsize_h*$ratio_orig;
        } else {
            $imagethumbsize_h = $imagethumbsize_w/$ratio_orig;
        }

        // Redimensionnement
        $thumb3  = imagecreatetruecolor($imagethumbsize_w, $imagethumbsize_h);
        $bgcolor = imagecolorallocate($thumb3, $red, $green, $blue);
        ImageFilledRectangle($thumb3, 0 ,0 ,$imagethumbsize_w,
            $imagethumbsize_h, $bgcolor);
        imagealphablending($thumb3, true);

        imagecopyresampled($thumb3, $image, 0, 0, 0, 0, $imagethumbsize_w,
            $imagethumbsize_h, $width_orig, $height_orig);

        switch ( $format ) {
            case 'image/jpeg':
                imagejpeg($thumb3, $fileout, $quality); // on cree le fichier
                break;
            case 'image/gif';
                imagegif($thumb3, $fileout); // on cree le fichier
                break;
            case 'image/png':
                imagepng($thumb3, $fileout); // on cree le fichier
                break;
        }
    }
	
	function load($clear){
		if($clear){
			$action_params = array(
				'photo' => '',
				'delete_original' => 0,
				'quality' => 90,
				'big_directory' => '',
				'big_image_use' => '1',
				'big_image_prefix' => '',
				'big_image_suffix' => '_big',
				'big_image_height' => '300',
				'big_image_width' => '400',
				'big_image_r' => '255',
				'big_image_g' => '255',
				'big_image_b' => '255',
				'big_image_method' => '0',
				'med_directory' => '',
				'med_image_use' => '0',
				'med_image_prefix' => '',
				'med_image_suffix' => '_med',
				'med_image_height' => '300',
				'med_image_width' => '400',
				'med_image_r' => '255',
				'med_image_g' => '255',
				'med_image_b' => '255',
				'med_image_method' => '0',
				'small_image_use' => '0',
				'small_directory' => '',
				'small_image_prefix' => '',
				'small_image_suffix' => '_small',
				'small_image_height' => '300',
				'small_image_width' => '400',
				'small_image_r' => '255',
				'small_image_g' => '255',
				'small_image_b' => '255',
				'small_image_method' => '0'
			);
		}
		return array('action_params' => $action_params);
	}
}
?>