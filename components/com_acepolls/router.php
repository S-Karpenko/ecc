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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

function AcepollsBuildRoute(&$query) {
	static $items;

	$segments	= array();
	$itemid		= null;

	// Break up the poll id into numeric and alias values.
	if (isset($query['id']) && strpos($query['id'], ':')) {
		list($query['id'], $query['alias']) = explode(':', $query['id'], 2);
	}

	// Get the menu items for this component.
	if (!$items) {
		$component	= &JComponentHelper::getComponent('com_acepolls');
		$menu		= &JSite::getMenu();
		$items		= $menu->getItems('component_id', $component->id);
	}

	// Search for an appropriate menu item.
	// First we will search for menu item linking to this poll, 
	// if we can't find we will look for a menu item linking to the polls view.
	if (is_array($items)) {
		// If only the option and itemid are specified in the query, return that item.
		if (!isset($query['view']) && !isset($query['id']) && isset($query['Itemid'])) {
			$itemid = (int) $query['Itemid'];
		}
 
		// Search for a menu that links to the same view and id
		if (!$itemid) {
			foreach ($items as $item) {
				// Check if this menu item links to this view.
				// menu-view must be equal to the query-view
				// also menu-id must be equal to the query-id
				if (isset($item->query['view']) && isset($query['view'])
					&& $item->query['view'] == $query['view']
					&& isset($item->query['id']) 
					&& $item->query['id'] == $query['id'])
				{
					$itemid	= $item->id;
				}
			}
		}

		// If no specific link has been found, search for a general one.
		// Search for a menu linking to polls view
		if (!$itemid) {
			foreach ($items as $item) {
				if (isset($query['view']) && $query['view'] == 'poll' && isset($item->query['view']) && $item->query['view'] == 'polls') {
					// Check for an undealt with poll id.
					if (isset($query['id'])) {
						// This menu item links to the apolls view but we need to append the poll id to it.
						$itemid	= $item->id;
						$segments[]	= isset($query['alias']) ? $query['id'].':'.$query['alias'] : $query['id'];
						break;
					}
				}
			}
		}
	}

	// Check if the router found an appropriate itemid.
	if (!$itemid) {
		//if item id was not found that means that there is no link to either polls view or poll view
		// Check if a id was specified.
		if (isset($query['id'])) {
			if (isset($query['alias'])) {
				$query['id'] .= ':'.$query['alias'];
			}

			// Push the id onto the stack.
			$segments[] = 'poll';
			$segments[] = $query['id'];
			
			unset($query['id']);
			unset($query['alias']);
		}
		
		unset($query['view']);
	}
	else {
		$query['Itemid'] = $itemid;

		// Remove the unnecessary URL segments.
		unset($query['view']);
		unset($query['id']);
		unset($query['alias']);
	}
	 
	return $segments;
}

function AcepollsParseRoute($segments) {
	$vars = array();

	//Get the active menu item if there is a menu link to polls view
	$menu	=& JSite::getMenu();
	$item	=& $menu->getActive();
	
	// Count route segments
	$count	= count($segments);
	
	//if item is not set, there is no menu linking to this view - poll
	if (!isset($item)) {
		$vars['view']	= 'poll';
		$vars['id']    = $segments[$count - 1];
		return $vars;
	}
	
	//if there is a menu to polls view ->
	$vars['view']	= 'poll';
	$vars['id']		= $segments[$count-1];

	return $vars;
}