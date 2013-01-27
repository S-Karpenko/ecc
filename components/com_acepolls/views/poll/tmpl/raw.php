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

defined('_JEXEC') or die('Restricted access'); 

$document =& JFactory::getDocument();
$document->setMimeEncoding('text/xml');

echo '<?xml version="1.0" encoding="UTF-8" ?>'."\n";
echo $this->xml;