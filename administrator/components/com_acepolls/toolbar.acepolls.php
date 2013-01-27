<?php
/**
* @version		1.0.0
* @package		AcePolls
* @subpackage	AcePolls
* @copyright	2009-2011 JoomAce LLC, www.joomace.net
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

$controller	= JRequest::getCmd('controller', 'polls');

JHTML::_('behavior.switcher');

// Load submenus
$controllers = array('&controller=polls' => JText::_('COM_ACEPOLLS_POLLS'),'&controller=votes' => JText::_('COM_ACEPOLLS_VOTES'));	

foreach ($controllers as $key => $val) {
	$active	= ($controller == $key);
	JSubMenuHelper::addEntry($val, 'index.php?option=com_acepolls'.$key, $active);
}
?>