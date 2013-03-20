<?php
/**
* @package		MijoSEF
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.form.formfield');

class JFormFieldCategoryList extends JFormField {

	protected $type = 'CategoryList';

	function getInput() {
		$attribs = 'class="inputbox" size="10" multiple="multiple"';

		// Get rows
		static $tree;
		if (!isset($tree)) {
			$extension = Mijosef::get('utility')->getExtensionFromRequest();
			$mijosef_ext = Mijosef::getExtension($extension);
			
			$db_query = "";
			if ($this->element['db_query']) {
				$db_query = $this->element['db_query'];
			}
			
			$rows = $mijosef_ext->getCategoryList($db_query);

			// Collect childrens
			$children = array();
			if (!empty($rows)) {
				foreach ($rows as $row) {
					// Not subcategories
					if (empty($row->parent)) {
						$row->parent = 0;
					}
					
					$pt = $row->parent;
					$list = @$children[$pt] ? $children[$pt] : array();
					array_push($list, $row);
					$children[$pt] = $list;
				}
			}

			// Not subcategories
			if (empty($rows[0]->parent)) {
				$rows[0]->parent = 0;
			}

			// Build Tree
			$tree = self::_buildTree(intval($rows[0]->parent), '', array(), $children);
		}

		$options = array();
		$options[] = array('id' => 'all', 'name' => JText::_('COM_MIJOSEF_PARAMS_ALL_CATS'));

		/*foreach ($node->children() as $option) {
			$options[] = array('id' => $option->attributes('value'), 'name' => $option->data());
		}*/
		
		foreach ($tree as $item){
			$options[] = array('id' => $item->id, 'name' => $item->name);
		}

		return JHTML::_('select.genericlist', $options, $this->name, $attribs, 'id', 'name', $this->value, $this->name);
	}
	
	function _buildTree($id, $indent, $list, &$children) {
		if (@$children[$id]) {
			foreach ($children[$id] as $ch) {
				$id = $ch->id;

				$pre 	= '<sup>|_</sup>&nbsp;';
				$spacer = '.&nbsp;&nbsp;&nbsp;';

				if ($ch->parent == 0) {
					$txt = $ch->name;
				} else {
					$txt = $pre . $ch->name;
				}
				
				$list[$id] = $ch;
				$list[$id]->name = "$indent$txt";
				$list[$id]->children = count(@$children[$id]);
				$list = self::_buildTree($id, $indent . $spacer, $list, $children);
			}
		}
		return $list;
	}
}