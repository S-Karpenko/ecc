<?php
defined ('_JEXEC') or die ('Direct Access to this location is not allowed.');
jimport ('joomla.application.component.modellist');

/**
 * SocialLoginAndSocialShare Model.
 */
 
class SocialLoginAndSocialShareModelSocialLoginAndSocialShare extends JModelList
{
	/**
	 * Save Settings.
	 */
	public function saveSettings ()
	{
		//Get database handle
		$db = $this->getDbo ();
		$api_settings = array();
        $mainframe = JFactory::getApplication();
		//Read Settings
		$settings = JRequest::getVar ('settings');
		$s_articles = JRequest::getVar ('s_articles');
		$c_articles = JRequest::getVar ('c_articles');
		$settings['apikey'] = trim($settings['apikey']);
		$settings['apisecret'] = trim($settings['apisecret']);
        //print_r($settings);
		$apikey = trim($settings['apikey']);
		$apisecret = trim($settings['apisecret']);
		$apicred = $settings['useapi'];
		$settings['s_articles'] = (sizeof($s_articles) > 0 ? serialize($s_articles) : "");
		$settings['c_articles'] = (sizeof($c_articles) > 0 ? serialize($c_articles) : "");
		$api_settings = $this->check_api_settings($apikey, $apisecret, $apicred);
		if (! $this->isValidApiSettings($apikey)) {
		  JError::raiseWarning ('', JText::_ ('COM_SOCIALLOGIN_APIKEY_ERROR'));
		  $mainframe->redirect (JRoute::_ ('index.php?option=com_socialloginandsocialshare&view=socialloginandsocialshare&layout=default', false));
		}
		else if (! $this->isValidApiSettings($apisecret)) {
		  JError::raiseWarning ('', JText::_ ('COM_SOCIALLOGIN_APIKEY_ERROR'));
		  $mainframe->redirect (JRoute::_ ('index.php?option=com_socialloginandsocialshare&view=socialloginandsocialshare&layout=default', false));
		}
		else if ($api_settings == false) {
		  JError::raiseWarning ('', JText::_ ('COM_SOCIALLOGIN_CONNECTION_ERROR'));
		  $mainframe->redirect (JRoute::_ ('index.php?option=com_socialloginandsocialshare&view=socialloginandsocialshare&layout=default', false));
		}
		else {
		  $sql = "DELETE FROM #__LoginRadius_settings";
		  $db->setQuery ($sql);
		  $db->query ();
          // Inserting iframe settings.
		  $settings['ishttps'] = (!empty($api_settings->IsHttps) ? $api_settings->IsHttps : "");
          $settings['ifheight'] = (!empty($api_settings->height) ? $api_settings->height : 50);
          $settings['ifwidth'] = (!empty($api_settings->width) ? $api_settings->width : 169);
		  
          //Insert new settings
		  foreach ($settings as $k => $v)
		  {
		     
			 $sql = "INSERT INTO #__LoginRadius_settings ( setting, value )" . " VALUES ( " . $db->Quote ($k) . ", " . $db->Quote ($v) . " )";
			$db->setQuery ($sql);
			$db->query ();
		  }
		}
	 }

	/**
	 * Read Settings
	 */
	public function getSettings ()
	{
		$settings = array ();
        $db = $this->getDbo ();
        $sql = "SELECT * FROM #__LoginRadius_settings";
		$db->setQuery ($sql);
		$rows = $db->LoadAssocList ();

		if (is_array ($rows))
		{
			foreach ($rows AS $key => $data)
			{
				$settings [$data['setting']] = $data ['value'];
				
			}
		}

		return $settings;
	}
	
	/**
	 * Check apikey and secret is valid.
	 */
	public function isValidApiSettings($apikey) {
      return !empty($apikey) && preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/i', $apikey);
    }
	
	/**
	 * Check api credential settings.
	 */
	public function check_api_settings($apikey, $apisecret, $apicred){
      if (isset($apikey)){
        $ValidateUrl = "https://hub.loginradius.com/getappinfo/$apikey/$apisecret";
        if ($apicred == 1) {
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
         $UserAuth = json_decode($JsonResponse);
       }
       else {
         $JsonResponse = file_get_contents($ValidateUrl);
         $UserAuth = json_decode($JsonResponse);
       }
       if (isset( $UserAuth->IsValid)) { 
         return $UserAuth;
       }
	   else {
	     return false;
	   }
     }
   }
 }