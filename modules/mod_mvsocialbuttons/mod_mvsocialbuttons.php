<?php
// no direct access
defined( '_JEXEC') or die;
JLoader::register('MvSocialButtonsHelper', dirname(__FILE__).DIRECTORY_SEPARATOR.'helper.php');
$urlPath        = JURI::base() . "modules/mod_mvsocialbuttons/";
$moduleClassSfx = htmlspecialchars($params->get('moduleclass_sfx'));
$doc = JFactory::getDocument();
/** $doc JDocumentHTML **/
// Loading style.css
if($params->get("loadCss")) {
    $doc->addStyleSheet($urlPath . "style.css");
}
$link  	 	= JURI::getInstance()->toString();
$title  	= $doc->getTitle();
$title      = rawurlencode($title);
$link       = rawurlencode($link);
// Short URL service
if($params->get("shortUrlService")) {
    $link = MvSocialButtonsHelper::getShortUrl($link, $params);
}
$stylePath = $urlPath."images/".$params->get("icons_package");
require JModuleHelper::getLayoutPath('mod_mvsocialbuttons', $params->get('layout', 'default'));