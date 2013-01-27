<?php
/**
* @version		1.0.0
* @package		AcePolls
* @subpackage	AcePolls
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*
* Based on Apoll Component
* @copyright (C) 2009 - 2011 Hristo Genev All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @link http://www.afactory.org
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class modAcepollsHelper {

	function getPollOptions($poll_id) {
		$db	=& JFactory::getDBO();

		$query = "SELECT o.id, o.text, o.ordering" .
			" FROM ".$db->nameQuote('#__acepolls_options') . " AS o " .
			" WHERE o.poll_id = ".(int)$poll_id.
			" AND o.text <> ''" .
			" ORDER BY o.ordering"
			;
		
		$db->setQuery($query);

		if (!($options = $db->loadObjectList())) {
			echo "helper ".$db->stderr();
			return;
		}

		return $options;
	}
	
	// checks if user has voted (if cookie is set)
	function alreadyVoted($id) {
		$mainframe = JFactory::getApplication();
		
		$cookieName	= JUtility::getHash($mainframe->getName().'poll'.$id);
		$voted 		= JRequest::getVar($cookieName, '0', 'COOKIE', 'INT');
		
		return $voted;
	}
	
	function userVoted($user_id, $poll_id) {
		$db	=& JFactory::getDBO();
		$query = "SELECT date FROM ".$db->nameQuote('#__acepolls_votes')
		." WHERE poll_id=".(int) $poll_id
		." AND user_id=".(int)$user_id
		; 
		$db->setQuery($query);

		return $userVoted=($db->loadResult()) ? 1 : 0;	
	}
	
	function ipVoted($poll_id) {
		$db	=& JFactory::getDBO();
		$ip = ip2long($_SERVER['REMOTE_ADDR']);
		$query = "SELECT ip FROM ".$db->nameQuote('#__acepolls_votes')
		."WHERE poll_id=".(int) $poll_id." 
		AND ip = '".$ip."'"
		;
		$db->setQuery($query); 
		
		return $ipVoted=($db->loadResult()) ? 1 : 0;	
	}
	
	function getResults($poll_id) {
		
        $db	=& JFactory::getDBO();
		$query = "SELECT o.*, COUNT(v.id) AS hits, 
		(SELECT COUNT(id) FROM ".$db->nameQuote('#__acepolls_votes')." WHERE poll_id=".$poll_id.") AS votes 
		FROM ".$db->nameQuote('#__acepolls_options')." AS o 
		LEFT JOIN  ".$db->nameQuote('#__acepolls_votes')." AS v 
		ON (o.id = v.option_id AND v.poll_id = ".(int)$poll_id . ")
		WHERE o.poll_id=".(int)$poll_id ." 
		AND o.text <> '' 
		GROUP BY o.id 
		ORDER BY o.ordering";
		
		$db->setQuery($query);
		
		return $db->loadObjectList();
	}
    
    function getActivePolls() {
        $db    =& JFactory::getDBO();
        $query = "SELECT id FROM ".$db->nameQuote('#__acepolls_polls') .
        " WHERE published = 1";
        $db->setQuery($query);
        if ($ids = $db->loadResultArray()) {
            return $ids;
        } else {
            return false;
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