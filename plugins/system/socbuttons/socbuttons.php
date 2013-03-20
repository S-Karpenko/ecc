<?php


// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );
jimport( 'joomla.html.parameter' );

class  plgSystemSocButtons extends JPlugin
{

	var $_cache = null;

	/**
	 * Constructor
	 *
	 * For php4 compatability we must not use the __constructor as a constructor for plugins
	 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
	 * This causes problems with cross-referencing necessary for the observer design pattern.
	 *
	 * @access	protected
	 * @param	object	$subject The object to observe
	 * @param 	array   $config  An array that holds the plugin configuration
	 * @since	1.0
	 */
	function plgSystemSocButtons(& $subject, $config)
	{
		parent::__construct($subject, $config);

		//Set the language in the class
		$config =& JFactory::getConfig();
		if (JPluginHelper::isEnabled('system','cache'))
		{
			$plugin = JPluginHelper::getPlugin('system','cache');
			$params = new JParameter($plugin->params);
			$cachetime = $params->get('cachetime', 15);
			$browsercache = $params->get('browsercache', false);
			
		} else {
			$cachetime = 15;
			$browsercache = false;
			
		}
		
		$options = array(
			'cachebase' 	=> JPATH_BASE.DS.'cache',
			'defaultgroup' 	=> 'page',
			'lifetime' 		=> $cachetime * 60,
			'browsercache'	=> $browsercache,
			'caching'		=> false,
			'language'		=> $config->getValue('config.language', 'en-GB')
		);

		jimport('joomla.cache.cache');
		$this->_cache =& JCache::getInstance( 'page', $options );
		
	}

	/**
	* Converting the site URL to fit to the HTTP request
	*
	*/
	function onAfterRender() {
			global $_PROFILER;
			$app = & JFactory::getApplication();
			$dispatcher = & JDispatcher::getInstance();
			if($app->isAdmin() || JDEBUG) {
				return;
			}	
			$option = JRequest::getVar('option' , '');
			$view = JRequest::getVar('view' , '');
			if (!JPluginHelper::isEnabled('content','socbuttons')) return true;
			$search = $this->params->get('needle','{socbuttons}');
			$imgwidth = $this->params->get('width' , 150);
			if ($search == '') return true;
			$data = JResponse::toString($app->getCfg('gzip'));
			if (strpos($data, $search) === false) return false;
			$row = new stdClass();
			$row->text = '';
			$match = array();
			preg_match('/<title>([^<]*)<\/title>/si',$data,$match);
			$row->title = $match[1];
			JRequest::setVar('view' , 'row');
			$temp = JPluginHelper::getPlugin('content','socbuttons');
			require_once (JPATH_SITE . DS . 'plugins' . DS . 'content'. DS . 'socbuttons' . DS . 'socbuttons.php');
			$plgSocButtons = new plgContentSocButtons($dispatcher , array('params' => new JParameter($temp->params)));
			$plgSocButtons->onContentAfterDisplay('com_content.article', $row , $par10 = null , $par2 = null);
			
			
if ($plgSocButtons->params->get('Vk') == 1) {
				//$Vk_id = $plgSocButtons->params->get('vk_id', '');
				//$artical->text  = str_replace('<script type="text/javascript" src="http://userapi.com/js/api/openapi.js"></script>', '', $artical->text );
				$row->text  = str_replace('"vk_like"', '"vk_like' . (int) rand(0 , 1000) . '"', $row->text );
			}
			
			$data = str_replace($search,  $row->text , $data);
			if (($option <> 'com_content') or ($view <> 'artical')) {
				if($plgSocButtons->params->get('fblike')==1) {
					$current=JFactory::getURI();
					$link = $current->toString();
					
					$config =& JFactory::getConfig();
					preg_match('/<title>([^<]*)<\/title>/si',$data,$match);
					$title = $match[1];
					$pos = strpos($data,'</head>');
					$start = substr($data,0,$pos);
					$end = substr($data,$pos+7);
					
					$meta = '<meta property="og:title" content="'.$title.'"/>'."\n";
					
					if ($option == 'com_content')
						$meta .= '<meta property="og:type" content="article"/>'."\n";
						
					$meta .= '<meta property="og:url" content="'.$link.'"/>'."\n";
					$meta .= '<meta property="og:site_name" content="'.$config->getValue('sitename').'"/>'."\n";
				
					$pattern = "/<img[^>]*src\=['\"]?(([^>]*)(jpg|gif|png|jpeg))['\"]?[^>]*>/i";
					$matches = array();
					if(preg_match_all($pattern, $data, $matches)){
						foreach ($matches[0] as $key => $val) {
							$width = array();
							if ((strpos($matches[1][$key], '/templates/') === FALSE) AND preg_match("/width\=\"([\d]+)\"/i", $val , $width) AND ($width[1] > $imgwidth)) { 
								$meta .= '<meta property="og:image" content="'.$matches[1][$key].'"/>'."\n";
								break;
							}
						}
					}
					$data = $start.$meta."</head>".$end;
				}
				
				if ($plgSocButtons->params->get('Google')==1) {
						$data  = str_replace('<script type="text/javascript" src="https://apis.google.com/js/plusone.js">{lang: \''.$plgSocButtons->params->get('plusLang').'\'}</script>', '', $data );					
						$pos = strpos($data,'</head>');
						$start = substr($data,0,$pos);
						$end = substr($data,$pos+7);
						$data = $start . "\n"  
						. '<script type="text/javascript" src="https://apis.google.com/js/plusone.js">{lang: \''.$plgSocButtons->params->get('plusLang').'\'}</script>'
						. '</head>'
						.$end;
				}
				if ($plgSocButtons->params->get('Vk') == 1) {
					$Vk_id = $plgSocButtons->params->get('vk_id', '');
					$data  = str_replace('<script type="text/javascript" src="http://userapi.com/js/api/openapi.js"></script>', '', $data );
					$pos = strpos($data,'</head>');
					$start = substr($data,0,$pos);
					$end = substr($data,$pos+7);
					$data = $start . "\n"
					. '<script type="text/javascript" src="http://userapi.com/js/api/openapi.js"></script>'
					. '</head>'
					.$end;
				}							
			}
			if(JDEBUG)
			{
				$_PROFILER->mark('arterSystemSocButtons');
				echo implode( '', $_PROFILER->getBuffer());
			}			
			JResponse::setBody($data);
	}
}
