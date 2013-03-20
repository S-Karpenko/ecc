<?php 
jimport ('joomla.plugin.helper');
require_once(dirname (__FILE__) . DS . 'socialloginandsocialshare_helper.php');
class LoginRadius {
  public $IsAuthenticated, $JsonResponse, $UserProfile; 
  public function sociallogin_getapi($ApiSecrete) {
    $IsAuthenticated = false;
	$lr_settings = plgSystemSocialLoginTools::sociallogin_getsettings ();
    if (isset($_REQUEST['token'])) {
      $ValidateUrl = "https://hub.loginradius.com/userprofile.ashx?token=".$_REQUEST['token']."&apisecrete=".$ApiSecrete."";
      if ($lr_settings['useapi'] == 1) {
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $ValidateUrl);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
        if (ini_get('open_basedir') == '' && (ini_get('safe_mode') == 'Off' or !ini_get('safe_mode'))) {
          curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, 1);
          curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
          $JsonResponse = curl_exec($curl_handle);
        }
        else {
          curl_setopt($curl_handle,CURLOPT_HEADER, 1);
          $url = curl_getinfo($curl_handle,CURLINFO_EFFECTIVE_URL);
          curl_close($curl_handle);
          $ch = curl_init();
          $url = str_replace('?','/?',$url);
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          $JsonResponse = curl_exec($ch);
          curl_close($ch);
       }
       $UserProfile = json_decode($JsonResponse);
    }
    else {
      $JsonResponse = file_get_contents($ValidateUrl);
      $UserProfile = json_decode($JsonResponse);
    }
    if (isset($UserProfile->ID) && $UserProfile->ID != '') { 
      $this->IsAuthenticated = true;
      return $UserProfile;
    }
   }
  }
}?>