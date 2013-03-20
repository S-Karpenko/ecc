<?php
/**
* @package		MijoSEF
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license		GNU/GPL based on AceSEF www.joomace.net
*/

// No Permission
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.form.formfield');

class JFormFieldTags extends JFormField {

	protected $type = 'Tags';

	protected function getInput() {
        JHtml::_('behavior.framework');
		JHtml::_('behavior.modal', 'a.modal');

		$script = array();
		$script[] = '	function selectTag(title, object) {';
		$script[] = '		document.id("'.$this->id.'_id").value = title;';
		$script[] = '		document.id("'.$this->id.'_name").value = title;';
		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));
		
		JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_mijosef/tables');
		$tag = &JTable::getInstance('MijosefTags', 'Table');
		if ($this->value)	{
			$tag->loadByTitle($this->value);
		}
		else {
			$tag->title = JText::_('Select Tag');
		}

		$link = 'index.php?option=com_mijosef&amp;controller=tags&amp;task=modal&amp;tmpl=component&amp;tag='.$this->name;

		$html = "\n".'<div style="float: left;"><input style="background: #ffffff;" type="text" id="'.$this->id.'_name" value="'.htmlspecialchars($tag->title, ENT_QUOTES, 'UTF-8').'" disabled="disabled" /></div>';
		$html .= '<div class="button2-left"><div class="blank"><a class="modal" title="'.JText::_('Select Tag').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 650, y: 375}}">'.JText::_('Select').'</a></div></div>'."\n";
		$html .= "\n".'<input type="hidden" id="'.$this->id.'_id" name="'.$this->name.'" value="'.(int)$this->value.'" />';

		return $html;
	}
}