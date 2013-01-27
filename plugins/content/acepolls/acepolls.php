<?php 
/**
* @version		1.0.0
* @package		AcePolls
* @subpackage	AcePolls
* @copyright	2011 JoomAce LLC, www.joomace.net
* @license		GNU GPL
*/

//No Permision
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.html.parameter');

class plgContentAcepolls extends JPlugin {
	
	function onContentPrepare($context, &$row, &$params, $limitstart){
		$regex = '/{acepolls\s*.*?}/i';
		
		// find all instances of plugin and put in $matches
		preg_match_all($regex, $row->text, $matches);

		// Number of plugins
		$count = count($matches[0]);

		// plugin only processes if there are any instances of the plugin in the text
		if ($count) {
			self::_processMatches($row, $matches, $count, $regex);
		}
		
		return true;
	}
	
	function _processMatches(&$row, &$matches, $count, $regex) {
		for ($i=0; $i < $count; $i++) {
			$id = str_replace('acepolls', '', $matches[0][$i]);
			$id = str_replace('{', '', $id);
			$id = str_replace('}', '', $id);
			$id = trim($id);

			$module = JModuleHelper::getModule('mod_acepolls');
			$content = self::_renderModule($module, array(), $id);
			
			$row->text 	= str_replace($matches[0][$i], $content, $row->text );
		}

		// removes tags without matching module positions
		$row->text = preg_replace($regex, '', $row->text);
	}
	
	function _renderModule($module, $attribs = array(), $id)	{
		static $chrome;
		$mainframe = JFactory::getApplication();
		$option = JRequest::getCmd('option');

		$scope = $mainframe->scope; //record the scope
		$mainframe->scope = $module->module;  //set scope to component name

		// Handle legacy globals if enabled
		if ($mainframe->getCfg('legacy'))
		{
			// Include legacy globals
			global $my, $database, $acl, $mosConfig_absolute_path;

			// Get the task variable for local scope
			$task = JRequest::getString('task');

			// For backwards compatibility extract the config vars as globals
			$registry =& JFactory::getConfig();
			foreach (get_object_vars($registry->toObject()) as $k => $v) {
				$name = 'mosConfig_'.$k;
				$$name = $v;
			}
			$contentConfig = &JComponentHelper::getParams( 'com_content' );
			foreach (get_object_vars($contentConfig->toObject()) as $k => $v)
			{
				$name = 'mosConfig_'.$k;
				$$name = $v;
			}
			$usersConfig = &JComponentHelper::getParams( 'com_users' );
			foreach (get_object_vars($usersConfig->toObject()) as $k => $v)
			{
				$name = 'mosConfig_'.$k;
				$$name = $v;
			}
		}

		// Get module parameters
		$params = new JParameter($module->params);
		$params->set('id', $id);

		// Get module path
		$module->module = preg_replace('/[^A-Z0-9_\.-]/i', '', $module->module);
		$path = JPATH_BASE.DS.'modules'.DS.$module->module.DS.$module->module.'.php';

		// Load the module
		if (!$module->user && file_exists( $path ) && empty($module->content))
		{
			$lang =& JFactory::getLanguage();
			$lang->load($module->module);

			$content = '';
			ob_start();
			require $path;
			$module->content = ob_get_contents().$content;
			ob_end_clean();
		}

		// Load the module chrome functions
		if (!$chrome) {
			$chrome = array();
		}

		require_once (JPATH_BASE.DS.'templates'.DS.'system'.DS.'html'.DS.'modules.php');
		$chromePath = JPATH_BASE.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS.'modules.php';
		if (!isset( $chrome[$chromePath]))
		{
			if (file_exists($chromePath)) {
				require_once ($chromePath);
			}
			$chrome[$chromePath] = true;
		}

		//make sure a style is set
		if(!isset($attribs['style'])) {
			$attribs['style'] = 'none';
		}

		//dynamically add outline style
		if(JRequest::getBool('tp')) {
			$attribs['style'] .= ' outline';
		}

		foreach(explode(' ', $attribs['style']) as $style)
		{
			$chromeMethod = 'modChrome_'.$style;

			// Apply chrome and render module
			if (function_exists($chromeMethod))
			{
				$module->style = $attribs['style'];

				ob_start();
				$chromeMethod($module, $params, $attribs);
				$module->content = ob_get_contents();
				ob_end_clean();
			}
		}

		$mainframe->scope = $scope; //revert the scope

		return $module->content;
	}
}