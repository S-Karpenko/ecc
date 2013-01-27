<?php
/**
* @version		1.0.0
* @package		AcePolls
* @subpackage	JomSocial
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgAcepollsJomsocial extends JPlugin {
	
	function onAfterVote($poll, $option_id) {
		$user =& JFactory::getUser();
		$points = JPATH_ROOT.'/components/com_community/libraries/core.php';
		$activity = JPATH_ROOT.'/components/com_community/libraries/core.php';
		
		if ($this->params->get('points', '0') == '1' && file_exists($points)) {
			require_once($points);
			CUserPoints::assignPoint('com_acepolls.vote');
		}
		
		if ($this->params->get('activity', '0') == '1' && file_exists($activity)) {
			require_once($activity);
			
			$text = JText::_('COM_ACEPOLLS_ACTIVITY_TEXT');
			$link = JRoute::_('index.php?option=com_acepolls&amp;view=poll&amp;id='. $poll->id . ":" . $poll->alias . self::getItemid($poll->id));
			
			$act = new stdClass();
			$act->cmd       = 'wall.write';
			$act->actor 	= $user->id;
			$act->target 	= 0;
			$act->title 	= JText::_('{actor} ' . $text . ' <a href="'.$link.'">'.$poll->title.'</a>');
			$act->content 	= '';
			$act->app       = 'wall';
			$act->cid       = 0;

			CFactory::load('libraries', 'activities');
			CActivityStream::add($act);
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