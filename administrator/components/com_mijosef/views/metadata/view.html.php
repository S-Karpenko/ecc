<?php
/**
* @version		1.0.0
* @package		MijoSEF
* @subpackage	MijoSEF
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license		GNU/GPL based on AceSEF www.joomace.net
*/

// No Permission
defined('_JEXEC') or die('Restricted Access');

// View Class
class MijosefViewMetadata extends MijosefView {

	// View URLs
	function view($tpl = null) {
		$toolbar = $this->get('ToolbarSelections');
		
	    $this->type = JFactory::getApplication()->getUserStateFromRequest('com_mijosef.metadata.type', 'type', 'all');
		
		// Toolbar
		JToolBarHelper::title(JText::_('COM_MIJOSEF_COMMON_METADATA'), 'mijosef');
		$this->toolbar->appendButton('Popup', 'new1', JText::_('New'), 'index.php?option=com_mijosef&controller=metadata&task=add&tmpl=component', 700, 490);
		JToolBarHelper::custom('edit', 'edit1.png', 'edit1.png', JText::_('Edit'), true, true);
		JToolBarHelper::divider();
		JToolBarHelper::custom('apply', 'apply1.png', 'apply1.png', JText::_('Apply'), false);
		JToolBarHelper::custom('generateMetadata', 'generatemetadata.png', 'generatemetadata.png', JText::_('COM_MIJOSEF_TOOLBAR_GENERATE_METADATA'), false);
		JToolBarHelper::divider();
		JToolBarHelper::spacer();
		$this->toolbar->appendButton('Custom', $toolbar->action);
		$this->toolbar->appendButton('Custom', $toolbar->fields);
		$this->toolbar->appendButton('Custom', $toolbar->selection);
		$this->toolbar->appendButton('Custom', $toolbar->button);
		JToolBarHelper::divider();
		$this->toolbar->appendButton('Popup', 'cache', JText::_('COM_MIJOSEF_CACHE_CLEAN'), 'index.php?option=com_mijosef&amp;controller=purgeupdate&amp;task=cache&amp;tmpl=component', 300, 380);
		JToolBarHelper::divider();
		$this->toolbar->appendButton('Popup', 'help1', JText::_('Help'), 'http://www.mijosoft.com/support/docs/mijosef/user-manual/metadata?tmpl=component', 650, 500);
		
		// Get behaviors
		JHTML::_('behavior.modal', 'a.modal', array('onClose'=>'\function(){location.reload(true);}'));
		
		// Get Items
		$items = $this->get('Items');
	
		// Footer colspan
		$colspan = 5;
		if ($this->MijosefConfig->ui_metadata_keys == 1) {
			$colspan = $colspan + 1;
		}
		if ($this->MijosefConfig->ui_metadata_published == 1) {
			$colspan = $colspan + 1;
		}
		if ($this->MijosefConfig->ui_metadata_cached == 1) {
			$colspan = $colspan + 1;
			$this->assignRef('cache', $this->get('Cache'));
		}
		if ($this->MijosefConfig->ui_metadata_id == 1) {
			$colspan = $colspan + 1;
		}
		
		// Get data from the model
		$this->assignRef('lists',		$this->get('Lists'));
		$this->assignRef('urls',		self::getNewURLs($items, $this->get('URLs')));
		$this->assignRef('items',		$items);
		$this->assignRef('pagination',	$this->get('Pagination'));
		$this->assignRef('colspan',		$colspan);

		parent::display($tpl);
	}
	
	function getNewURLs($metadata, $urls) {
		static $new_urls;
		
		if (!isset($new_urls)) {
			$new_urls = array();
			$skip = array();
			
			foreach ($metadata as $index => $meta) {
				foreach ($urls as $index => $url) {
					if ((strcasecmp($url->url_sef, $meta->url_sef) == 0)) {
						if(!isset($new_urls[$meta->url_sef])) {
							$new_urls[$meta->url_sef]['id'] = $url->id;
							$new_urls[$meta->url_sef]['count'] = 1;
							$new_urls[$meta->url_sef]['url_real'] = $url->url_real;
							
							if ($url->used == '0') {
								$skip[$url->url_sef] = 0;
							}
						} else {
							if ($url->used != '0') {
								$new_urls[$meta->url_sef]['id'] = $url->id;
								$new_urls[$meta->url_sef]['url_real'] = $url->url_real;
								$skip[$url->url_sef] = 1;
							}
							
							if ($skip[$url->url_sef] == 0) {
								$new_urls[$meta->url_sef]['id'] = $url->id;
								$new_urls[$meta->url_sef]['count'] = $new_urls[$meta->url_sef]['count'] + 1;
							}
						}
					}
				}
			}
		}
		
		return $new_urls;
	}
}
?>