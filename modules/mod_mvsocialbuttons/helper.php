<?php
// no direct access
defined('_JEXEC') or die;
class MvSocialButtonsHelper{
    public static function getShortUrl($link, $params){    
        JLoader::register("MvSocialButtonsModuleShortUrl", dirname(__FILE__).DIRECTORY_SEPARATOR."shorturl.php");

        $options = array(
            "login"     => $params->get("login"),
            "apiKey"    => $params->get("apiKey"),
            "service"   => $params->get("shortUrlService"),
        );

        $shortUrl  = new MvSocialButtonsModuleShortUrl($link, $options);
        $shortLink = $shortUrl->getUrl();
        if(!$shortLink) {
            // Add logger
            JLog::addLogger(
                array(
                    'text_file' => 'error.php',
                 )
            );
            
            JLog::add($shortUrl->getError(), JLog::ERROR);
            
            // Get original link
            $shortLink = $link;
        } 
        
        return $shortLink;
            
    }
    
    /**
     * Generate a code for the extra buttons. 
     * Is also replace indicators {URL} and {TITLE} with that of the article.
     * 
     * @param string $title Article Title
     * @param string $url   Article URL
     * @param array $params Plugin parameters
     * 
     * @return string
     */
    public static function getExtraButtons($title, $url, &$params) {
        
        $html  = "";
        // Дополнительные кнопки
        for($i=1; $i < 6;$i++) {
            $btnName = "ebuttons" . $i;
            $extraButton = $params->get($btnName, "");
            if(!empty($extraButton)) {
                
                // Parse markup
                if(false !== strpos($extraButton, "<mv:email")) {
                    $matches     = array();
                    if(preg_match('/src="([^"]*)"/i', $extraButton, $matches)) {
                        $extraButton = self::sendToFriendIcon($matches[1], $url);
                    }
                }                
                $extraButton = str_replace("{URL}", $url,$extraButton);
                $extraButton = str_replace("{TITLE}", $title,$extraButton);
                $html  .= $extraButton;
            }
        }        
        return $html;
    }
    public static function sendToFriendIcon($imageSrc, $link) {        
        JLoader::register("MailToHelper", JPATH_SITE . '/components/com_mailto/helpers/mailto.php');        
        $link     = rawurldecode($link);        
		$template = JFactory::getApplication()->getTemplate();
		$url	  = 'index.php?option=com_mailto&tmpl=component&template='.$template.'&link='.MailToHelper::addLink($link);
		$status   = 'width=400,height=350,menubar=yes,resizable=yes';
		$attribs  = array(
		    'title'   => JText::_('JGLOBAL_EMAIL'),
			'onclick' => "window.open(this.href,'win2','".$status."'); return false;"
		);
		$text   = '<img src="'.$imageSrc.'" alt="'. JText::_('MOD_MVSOCIALBUTTONS_SHARE_WITH_FRIENDS').'" title="'. JText::_('MOD_MVSOCIALBUTTONS_SHARE_WITH_FRIENDS').'" />';		
		$output = JHtml::_('link', $url, $text, $attribs);
		return $output;
	} 
/** Кнопка закладки Delicious. **/  
    public static function getDeliciousButton($title, $link, $style){        
        $img_url = $style. "/delicious.png";        
        return '<a rel="noindex, nofollow" href="http://del.icio.us/post?url=' . $link . '&amp;title=' . $title . '" title="' . JText::sprintf("MOD_MVSOCIALBUTTONS_SUBMIT", "Delicious") . '" target="_blank" ><img src="' . $img_url . '" alt="' . JText::sprintf("MOD_MVSOCIALBUTTONS_SUBMIT", "Delicious") . '" /></a>';    
    }
/** Кнопка закладки Digg. **/    
    public static function getDiggButton($title, $link, $style){
        
        $img_url = $style . "/digg.png";
        
        return '<a rel="noindex, nofollow" href="http://digg.com/submit?url=' . $link . '&amp;title=' . $title . '" title="' . JText::sprintf("MOD_MVSOCIALBUTTONS_SUBMIT", "Digg") . '" target="_blank" ><img src="' . $img_url . '" alt="' . JText::sprintf("MOD_MVSOCIALBUTTONS_SUBMIT", "Digg") . '" /></a>';
    }
/** Кнопка закладки Facebook. **/ 
    public static function getFacebookButton($title, $link, $style){
        
        $img_url = $style . "/facebook.png";
        
        return '<a rel="noindex, nofollow" href="http://www.facebook.com/sharer.php?u=' . $link . '&amp;t=' . $title . '" title="' . JText::sprintf("MOD_MVSOCIALBUTTONS_SUBMIT", "Facebook") . '" target="_blank" ><img src="' . $img_url . '" alt="' . JText::sprintf("MOD_MVSOCIALBUTTONS_SUBMIT", "Facebook") . '" /></a>';
    }
/** Кнопка закладки Google. **/    
    public static function getGoogleButton($title, $link, $style){
        
        $img_url = $style . "/google.png";
        
        return '<a rel="noindex, nofollow" href="http://www.google.com/bookmarks/mark?op=edit&amp;bkmk=' . $link . '" title="' . JText::sprintf("MOD_MVSOCIALBUTTONS_SUBMIT", "Google Bookmarks") . '" target="_blank" ><img src="' . $img_url . '" alt="' . JText::sprintf("MOD_MVSOCIALBUTTONS_SUBMIT", "Google Bookmarks") . '" /></a>';
    }
/** Кнопка закладки в Stumbleupon. **/     
    public static function getStumbleuponButton($title, $link, $style){
        
        $img_url = $style . "/stumbleupon.png";
        
        return '<a rel="noindex, nofollow" href="http://www.stumbleupon.com/submit?url=' . $link . '&amp;title=' . $title . '" title="' . JText::sprintf("MOD_MVSOCIALBUTTONS_SUBMIT", "Stumbleupon") . '" target="_blank" ><img src="' . $img_url . '" alt="' . JText::sprintf("MOD_MVSOCIALBUTTONS_SUBMIT", "Stumbleupon") . '" /></a>';
    }
/** Кнопка закладки в Technorati. **/    
    public static function getTechnoratiButton($title, $link, $style){
        
        $img_url = $style . "/technorati.png";
        
        return '<a rel="noindex, nofollow" href="http://technorati.com/faves?add=' . $link . '" title="' . JText::sprintf("MOD_MVSOCIALBUTTONS_SUBMIT", "Technorati") . '" target="_blank" ><img src="' . $img_url . '" alt="' . JText::sprintf("MOD_MVSOCIALBUTTONS_SUBMIT", "Technorati") . '" /></a>';
    }
 /** Кнопка закладки в Twitter. **/    
    public static function getTwitterButton($title, $link, $style){
        
        $img_url = $style . "/twitter.png";
        
        return '<a rel="noindex, nofollow" href="http://twitter.com/share?text=' . $title . "&amp;url=" . $link . '" title="' . JText::sprintf("MOD_MVSOCIALBUTTONS_SUBMIT", "Twitter") . '" target="_blank" ><img src="' . $img_url . '" alt="' . JText::sprintf("MOD_MVSOCIALBUTTONS_SUBMIT", "Twitter") . '" /></a>';
    }
/** Кнопка закладки Linkedin. **/   
    public static function getLinkedInButton($title, $link, $style){
        
        $img_url = $style . "/linkedin.png";
        
        return '<a rel="noindex, nofollow" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=' . $link .'&amp;title=' . $title . '" title="' . JText::sprintf("MOD_MVSOCIALBUTTONS_SUBMIT", "LinkedIn") . '" target="_blank" ><img src="' . $img_url . '" alt="' . JText::sprintf("MOD_MVSOCIALBUTTONS_SUBMIT", "LinkedIn") . '" /></a>';
    }
/** Кнопка закладки БобрДобр. **/     
       public static function getBobrdobrButton($title, $link, $style){
        
        $img_url = $style . "/bobrdobr.png";
        
        return '<a rel="noindex, nofollow" href="http://bobrdobr.ru/add.html?url=' . $link .'&amp;title=' . $title . '" title="' . JText::sprintf("MOD_MVSOCIALBUTTONS_SUBMIT", "Bobrdobr") . '" target="_blank" ><img src="' . $img_url . '" alt="' . JText::sprintf("MOD_MVSOCIALBUTTONS_SUBMIT", "Bobrdobr") . '" /></a>';
    }
/** Кнопка закладки Liveinternet. **/      
        public static function getLiveinternetButton($title, $link, $style){
        
        $img_url = $style . "/liveinternet.png";
        
        return '<a rel="noindex, nofollow" href="http://www.liveinternet.ru/journal_post.php?action=n_add&amp;cnurl=' . $link .'&amp;title=' . $title . '" title="' . JText::sprintf("MOD_MVSOCIALBUTTONS_SUBMIT", "Liveinternet") . '" target="_blank" ><img src="' . $img_url . '" alt="' . JText::sprintf("MOD_MVSOCIALBUTTONS_SUBMIT", "Liveinternet") . '" /></a>';
    }
/** Кнопка закладки Живой Журнал. **/       
        public static function getLivejournalButton($title, $link, $style){
        
        $img_url = $style . "/livejournal.png";
        
        return '<a rel="noindex, nofollow" href="http://www.livejournal.com/update.bml?event=' . $link .'&amp;title=' . $title . '" title="' . JText::sprintf("MOD_MVSOCIALBUTTONS_SUBMIT", "Livejournal") . '" target="_blank" ><img src="' . $img_url . '" alt="' . JText::sprintf("MOD_MVSOCIALBUTTONS_SUBMIT", "Livejournal") . '" /></a>';
    }
/** Кнопка закладки Мой мир. **/   
        public static function getMoymirButton($title, $link, $style){
        
        $img_url = $style . "/moymir.png";
        
        return '<a rel="noindex, nofollow" href="http://connect.mail.ru/share?share_url='  . $link .'&amp;title=' . $title . '" title="' . JText::sprintf("MOD_MVSOCIALBUTTONS_SUBMIT", "Moymir") . '" target="_blank" ><img src="' . $img_url . '" alt="' . JText::sprintf("MOD_MVSOCIALBUTTONS_SUBMIT", "Moymir") . '" /></a>';
    }
/** Кнопка закладки Одноклассники. **/       
        public static function getOdnoklassnikiButton($title, $link, $style){
        
        $img_url = $style . "/odnoklassniki.png";
        
        return '<a rel="noindex, nofollow" href="http://www.odnoklassniki.ru/dk?st.cmd=addShare&amp;st._surl='  . $link .'&amp;title=' . $title . '" title="' . JText::sprintf("MOD_MVSOCIALBUTTONS_SUBMIT", "Odnoklassniki") . '" target="_blank" ><img src="' . $img_url . '" alt="' . JText::sprintf("MOD_MVSOCIALBUTTONS_SUBMIT", "Odnoklassniki") . '" /></a>';
    }
 /** Кнопка закладки ВКонтакте. **/    
        public static function getVkcomButton($title, $link, $style){
        
        $img_url = $style . "/vkcom.png";
        
        return '<a rel="noindex, nofollow" href="http://vk.com/share.php?url=' . $link .'&amp;title=' . $title . '" title="' . JText::sprintf("MOD_MVSOCIALBUTTONS_SUBMIT", "Vkcom") . '" target="_blank" ><img src="' . $img_url . '" alt="' . JText::sprintf("MOD_MVSOCIALBUTTONS_SUBMIT", "Vkcom") . '" /></a>';
    } 
/** Кнопка закладки Я.ру. **/       
        public static function getYaruButton($title, $link, $style){
        
        $img_url = $style . "/yaru.png";
        
        return '<a rel="noindex, nofollow" href="http://zakladki.yandex.ru/newlink.xml?url=' . $link .'&amp;title=' . $title . '" title="' . JText::sprintf("MOD_MVSOCIALBUTTONS_SUBMIT", "Yaru") . '" target="_blank" ><img src="' . $img_url . '" alt="' . JText::sprintf("MOD_MVSOCIALBUTTONS_SUBMIT", "Yaru") . '" /></a>';
    } 
    
}