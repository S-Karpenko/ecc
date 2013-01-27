<?php
/**
* @version		1.0.0
* @package		AcePolls
* @subpackage	Mighty Touch
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgAcepollsMightytouch extends JPlugin {

	function onAfterVote($poll, $option_id) {
		$user =& JFactory::getUser();
		$api = JPATH_ROOT.'/components/com_community/api.php';
		
		if ($this->params->get('points', '0') == '1' && file_exists($api)) {
			require_once($api);
			JSCommunityApi::increaseKarma($user->id, $this->params->get('points_value', '0'));
		}
		
		if ($this->params->get('activity', '0') == '1' && file_exists($api)) {
			require_once($api);
			
			$icon = 'media/acepolls/images/acepolls.png';
			$link = JRoute::_('index.php?option=com_acepolls&amp;view=poll&amp;id='. $poll->id . ":" . $poll->alias . self::getItemid($poll->id));
			$text = JText::_('COM_ACEPOLLS_ACTIVITY_TEXT') . ' <a href="'.$link.'">' . $poll->title . '</a>';
			
			$capi = new JSCommunityApi();
			$capi->registerActivity(0, $text, $user->id, $icon, 'user', null, 'com_acepolls', '', 'Polls');
		}
	}
	
	function getItemid($poll_id) {
        $component 	=& JComponentHelper::getComponent('com_acepolls');
		$menus		=& JApplication::getMenu('site', array());
		$items		= $menus->getItems('component_id', $component->id);
		$match 		= false;
		$item_id	= '';
		
		if (isset($items)) {
			foreach ($items as $item) {
				if ((@$item->query['view'] == 'poll') && (@$item->query['id'] == $poll_id)) {
					$itemid = $item->id;
					$match = true;
					break;
				}			
			}
		}
		
		if ($match) {
			$item_id = '&Itemid='.$itemid;
		}
		
		return $item_id;
	}
}
?>