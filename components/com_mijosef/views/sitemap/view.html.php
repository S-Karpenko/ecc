<?php
/**
* @version		1.0.0
* @package		MijoSEF
* @subpackage	MijoSEF
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license		GNU/GPL based on AceSEF www.joomace.net
*/

//No Permision
defined('_JEXEC') or die('Restricted access');

// Imports
jimport('joomla.application.component.view');

class MijosefViewSitemap extends JView {

	function display($tpl = null) {
		$mainframe =& JFactory::getApplication();
		$document =& JFactory::getDocument();
		$params = $mainframe->getParams();
		
		// Add page number to title
		$limit = $mainframe->getUserStateFromRequest('limit', 'limit', $params->get('display_num', $mainframe->getCfg('list_limit')), 'int');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
		if (!empty($limit) && !empty($limitstart)) {			
			$number = $limitstart / $limit; 
			$number++;

			$document->setTitle($params->get('page_title', '') . ' - ' . JText::_('PAGE') . ' ' . $number);
		}
		
		$this->assignRef('items', 		$this->get('Items'));
		$this->assignRef('params', 		$params);
		$this->assignRef('pagination',	$this->get('Pagination'));
		
		parent::display($tpl);
	}
}