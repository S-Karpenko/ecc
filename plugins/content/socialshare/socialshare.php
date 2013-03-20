<?php
/* no direct access*/

defined( '_JEXEC' ) or die( 'Restricted access' );
if(!class_exists('ContentHelperRoute')) require_once (JPATH_SITE . '/components/com_content/helpers/route.php'); 

jimport('joomla.plugin.plugin');
jimport('joomla.html.parameter');

/**
 * plgContentSocialShare
 */  
class plgContentSocialShare  extends JPlugin {
  
   /**
    * Constructor
    * Loads the plugin settings and assigns them to class variables
    */
    public function __construct(&$subject)
    {
        parent::__construct($subject);
  
        // Loading plugin parameters
		$lr_settings = $this->sociallogin_getsettings();

        $this->_plugin = JPluginHelper::getPlugin('content', 'socialshare');
        $this->_params = new JParameter($this->_plugin->params);
        
		//Properties holding plugin settings
		$this->share_articles = (!empty($lr_settings['s_articles']) ? unserialize($lr_settings['s_articles']) : "");
		$this->counter_articles = (!empty($lr_settings['c_articles']) ? unserialize($lr_settings['c_articles']) : "");
        $this->share_alignment = (!empty($lr_settings['sharealign']) ? $lr_settings['sharealign'] : "");
        $this->share_widget_position = (!empty($lr_settings['sharepos']) ? $lr_settings['sharepos'] : "");
		$this->counter_alignment = (!empty($lr_settings['counteralign']) ? $lr_settings['counteralign'] : "");
        $this->counter_widget_position = (!empty($lr_settings['counterpos']) ? $lr_settings['counterpos'] : "");
    }
     
	/**
	 * Before display content method
	 */
	public function onContentBeforeDisplay($context, &$article, &$params, $limitstart=0) {
		$beforediv ='';
		if(!($this->share_widget_position)) {
			$app = JFactory::getApplication();
			if (!empty($this->share_articles)) {
            foreach ($this->share_articles as $key=>$value) {
			  if ($article->id == $value) {
		       
			    $beforediv = "<DIV align='".$this->share_alignment."' style='padding-bottom:10px;padding-top:10px;'>".$this->showShare()."</DIV>";
			  }
			}
		  }
		}
		if(!($this->counter_widget_position)) {
			$app = JFactory::getApplication();
			if (!empty($this->counter_articles)) {
            foreach ($this->counter_articles as $key=>$value) {
			  if ($article->id == $value) {
		   
			$beforediv.= "<DIV align='".$this->counter_alignment."' style='padding-bottom:10px;padding-top:10px;'>".$this->showCounter()."</DIV>";
			}
		  }
		}
	  }
	  return $beforediv;
	} 
	
	  
	/**
	 * After display content method
	 */
	public function onContentAfterDisplay($context, &$article, &$params, $limitstart=0) {
		$afterdiv = '';
		if(($this->share_widget_position)) {
			$app = JFactory::getApplication();
			if (!empty($this->share_articles)) {
			foreach ($this->share_articles as $key=>$value) {
			  if ($article->id == $value) {
            
			$afterdiv = "<DIV align='".$this->share_alignment."' style='padding-bottom:10px;padding-top:10px;'>".$this->showShare()." </DIV>";
			  }
		    }
		  }
		}
		if(($this->counter_widget_position)) {
			$app = JFactory::getApplication();
			if (!empty($this->counter_articles)) {
			foreach ($this->counter_articles as $key=>$value) {
			  if ($article->id == $value) {
            
			$afterdiv .= "<DIV align='".$this->counter_alignment."' style='padding-bottom:10px;padding-top:10px;'>".$this->showCounter()." </DIV>";
			}
		  }
		}
      }
	  return $afterdiv;
	} 
	private function showShare()
    {
		$lr_settings = $this->sociallogin_getsettings();
		if (!empty($lr_settings['enableshare']) AND $lr_settings['enableshare'] == 'on') {
		  $sharescript = explode(';', $lr_settings['sharescript']);
		  @$sharescript[0] = str_replace('false','true', @$sharescript[0]);
		  @$sharescript[1] = str_replace('false','true', @$sharescript[1]);
$sharescript = '<script>'.@$sharescript[0].';'.@$sharescript[1].';</script><script type="text/javascript" src="//share.loginradius.com/Content/js/LoginRadius.js" id="lrsharescript"></script> <script type="text/javascript">'.@$sharescript[2].';'.@$sharescript[3].';'.@$sharescript[4].';'.@$sharescript[5].';'.@$sharescript[6].';'.@$sharescript[7].';'.@$sharescript[8].';'.@$sharescript[9].@$sharescript[10].';</script>';

        }
		else {
		  $sharescript = "";
		}
		
        $includeButtonScript = $sharescript.'<div class="lrsharecontainer"></div>';
		return $includeButtonScript;
    }
	
	private function showCounter()
    {
		$lr_settings = $this->sociallogin_getsettings();
		if (!empty($lr_settings['enablecounter']) AND $lr_settings['enablecounter'] == 'on') {
          $counterscript = explode(';', $lr_settings['counterscript']);
		  @$counterscript[0] = str_replace('false','true', @$counterscript[0]);
		  @$counterscript[1] = str_replace('false','true', @$counterscript[1]);
@$counterscript = '<script>'.@$counterscript[0].';'.@$counterscript[1].';</script><script type="text/javascript" src="//share.loginradius.com/Content/js/LoginRadius.js"></script> <script type="text/javascript">'.@$counterscript[2].';'.@$counterscript[3].';'.@$counterscript[4].';'.@$counterscript[5].';'.@$counterscript[6].';'.@$counterscript[7].';'.@$counterscript[8].';'.@$counterscript[9].';</script>';
        }
        else {
		  $counterscript = "";
		}


		$includeButtonScript = $counterscript.'<div class="lrcounter_simplebox"></div> ';
		return $includeButtonScript;
    }
/**
 * Get the databse settings.
 */
	private function sociallogin_getsettings () {
      $lr_settings = array ();
      $db = JFactory::getDBO ();
	  $sql = "SELECT * FROM #__LoginRadius_settings";
      $db->setQuery ($sql);
      $rows = $db->LoadAssocList ();
      if (is_array ($rows)) {
        foreach ($rows AS $key => $data) {
          $lr_settings [$data ['setting']] = $data ['value'];
        }
      }
      return $lr_settings;
    }
	
}
  