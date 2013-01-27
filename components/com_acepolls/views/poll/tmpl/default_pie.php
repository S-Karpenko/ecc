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

$document =& JFactory::getDocument();
$document->addScriptDeclaration($this->js);
$document->addScript(JURI::root().'media/acepolls/js/json.js');   
?>
<div id="my_poll">         
	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" id="graph-2" width="<?php echo $this->params->get('pieX'); ?>" height="<?php echo $this->params->get('pieY'); ?>">
	    <param name="wmode" value="transparent" /> 
	    <param name="movie" value="<?php echo JURI::root().'media/acepolls/swf/open-flash-chart.swf'; ?>" />
	    
	    <!--[if !IE]>-->
	    <object type="application/x-shockwave-flash" data="<?php echo JURI::root().'media/acepolls/swf/open-flash-chart.swf'; ?>" name="open-flash-chart" width="<?php echo $this->params->get('pieX'); ?>" height="<?php echo $this->params->get('pieY'); ?>">
	    <!--<![endif]-->
	        <p>No Flash Player Installed</p>
	    <!--[if !IE]>-->   
	    </object>
	    <!--<![endif]-->
	</object>
</div>