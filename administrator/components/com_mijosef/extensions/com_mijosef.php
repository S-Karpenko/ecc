<?php
/*
* @package		MijoSEF
* @subpackage	MijoSEF
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		http://www.mijosoft.com/company/license
*/

// No Permission
defined('_JEXEC') or die('Restricted access.');

class MijoSEF_com_mijosef extends MijosefExtension {

	public function beforeBuild(&$uri) {
		if ($uri->getVar('view') == 'tags' && !is_null($uri->getVar('limitstart')) && is_null($uri->getVar('tag'))) {
			$_tag = JRequest::getString('tag');
			
			if (!empty($_tag)) {
				$uri->setVar('tag', $_tag);
			}
		}
	}
	
	public function build(&$vars, &$segments, &$do_sef, &$metadata, &$item_limitstart) {
        extract($vars);
		
		$meta_vars = $vars;
		
		if (isset($view)){
			switch($view){
				case 'sitemap':
					if (isset($format) && $format == 'xml') {
						$do_sef = false;
					}
					break;
				case 'tags':
					if (isset($tag) && $tag == '0') {
						$segments[] = JText::_('COM_MIJOSEF_TAGS_ALL');
						unset($vars['tag']);
						break;
					} 
					
					if (!empty($tag)) {
                        $tag_prefix = $this->params->get('tag_prefix', '');
                        if (!empty($tag_prefix)) {
                            $segments[] = JText::_($tag_prefix);
                        }

						if ((($this->params->get('meta_desc', '2') == '2') || ($this->params->get('tagid_inc', '2') == '2')) || ($this->params->get('tag_part', 'global') == 'alias')) {
							$segments[] = self::_getTag($tag);
						}
                        else {
							$segments[] = str_replace('+', ' ', $tag);
						}

						unset($vars['tag']);
					}
					break;
				case 'url':
					if (!empty($id)) {
						$segments[] = 'mijosef_url';
						unset($vars['id']);
					}
					break;
				default:
					$segments[] = $view;
					break;
			}
			unset($vars['view']);
		}
		
		$metadata = parent::getMetaData($meta_vars, $item_limitstart);
		
		unset($vars['limit']);
		unset($vars['limitstart']);
    }

    public function _getTag($tag) {
		static $cache = array();
		
		if (!isset($cache[$tag])) {
			$joomfish = $this->MijosefConfig->joomfish_trans_url ? ', id' : '';
			
			$clean_tag = Mijosef::get('utility')->cleanText($tag);
			$row = MijoDatabase::loadRow("SELECT id, title, alias, description$joomfish FROM #__mijosef_tags WHERE title = '$clean_tag'");
			
			$name = (($this->params->get('tagid_inc', '1') != '1') ? $row[0].' ' : '');
			if (parent::urlPart($this->params->get('tag_part', 'global')) == 'title') {
				$name .= $row[1];
			} else {
				$name .= $row[2];
			}
			
			$cache[$tag]['name'] = $name;
			$cache[$tag]['meta_title'] = $row[1];
			
			if($this->params->get('meta_desc_mijosef', '2') == '2'){
				$cache[$tag]['meta_desc'] = $row[3];
			}
		}
		
		$this->meta_title[] = $cache[$tag]['meta_title'];
		if (!empty($cache[$tag]['meta_desc'])) {
			$this->meta_desc = $cache[$tag]['meta_desc'];
		}
		
		return $cache[$tag]['name'];
    }
}