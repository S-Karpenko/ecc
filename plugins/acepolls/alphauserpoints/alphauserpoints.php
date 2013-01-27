<?php
/**
* @version		1.0.0
* @package		AcePolls
* @subpackage	AlphaUserPoints
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgAcepollsAlphauserpoints extends JPlugin {
	
	function onAfterVote($poll, $option_id) {
		$user =& JFactory::getUser();
		$aup = JPATH_SITE.'/components/com_alphauserpoints/helper.php';
		
		if ($this->params->get('points', '0') == '1' && file_exists($aup)) {
			require_once($aup);
			
			$aup_id = AlphaUserPointsHelper::getAnyUserReferreID($user->id);
			if ($aup_id) {
				AlphaUserPointsHelper::newpoints('sysplgaup_votepoll', $aup_id, '', JText::_('COM_ACEPOLLS_CAST_VOTE_AUP') . ' ' . $poll->id, $this->params->get('points_value', '0'), true);
			}
		}
	}
}
?>