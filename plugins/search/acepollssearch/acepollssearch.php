<?php 
/**
* @version		1.0.0
* @package		AcePolls
* @subpackage	AcePolls Search
* @copyright	2011 JoomAce LLC, www.joomace.net
* @license		GNU GPL
*/

//No Permision
defined( '_JEXEC' ) or die( 'Restricted access' );

class plgSearchAcepollssearch extends JPlugin {

	function __construct(& $subject, $config) {
		parent::__construct($subject, $config);
	}
	
	function onContentSearchAreas() {
		static $areas = array('acepolls' => 'Polls');
		return $areas;
	}
	
	function onContentSearch($text, $phrase = '', $ordering = '', $areas = null) {
		$db	=& JFactory::getDBO();

		if (is_array( $areas )) {
			if (!array_intersect($areas, array_keys(self::onContentSearchAreas()))) {
				return array();
			}
		}

		$limit = $this->params->get('search_limit', 50);

		$text = trim($text);
		if ($text == '') {
			return array();
		}

		$text = $db->Quote('%'.$db->getEscaped($text, true).'%', false);
		
		$query	= 'SELECT id, title, alias, publish_up AS created'
		. ' FROM #__acepolls_polls'
		. ' WHERE (title LIKE '.$text.' OR alias LIKE '.$text.') AND published = 1'
		. ' GROUP BY id'
		. ' ORDER BY title'
		;
		$db->setQuery($query, 0, $limit);
		$rows = $db->loadObjectList();
		
		if (empty($rows)) {
			return array();
		}

		foreach($rows as $key => $row) {
			$rows[$key]->href = 'index.php?option=com_acepolls&amp;view=poll&amp;id='. $row->id . ":" . $row->alias . self::getItemid($row->id);
		}

		return $rows;
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