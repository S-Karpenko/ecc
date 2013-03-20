<?php
 /**
 * @package mod_jlcackle
 * @author Vadim Kunicin (vadim@joomline.net)
 * @version 1.0
 * @copyright (C) 2011 by Vadim Kunicin(http://www.joomline.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html 
 *
*/
// No direct access
defined('_JEXEC') or die('Restricted access');

$id_site 	= $params->get('id_site');
$coments 		= $params->get('coments');
$textsize 	= $params->get('textsize');
$avatarsize 	= $params->get('avatarsize');
$doc = JFactory::getDocument();

?>
<div id="mc-last"></div>
<script type="text/javascript">
var mcSite = '<?=$id_site?>';
var mcSize = '<?=$coments?>';
var mcAvatarSize = '<?=$avatarsize?>';
var mcTextSize = '<?=$textsize?>';
(function() {
    var mc = document.createElement('script');
    mc.type = 'text/javascript';
    mc.async = true;
    mc.src = 'http://cackle.ru/mc.last-min.js';
    (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(mc);
})();
</script>
	<div style="text-align: right;">
			<a style="text-decoration:none; color: #c0c0c0; font-family: arial,helvetica,sans-serif; font-size: 5pt; " target="_blank" href="http://joomline.ru/rasshirenija/plugin/jlcackle.html">Расширения для Joomla</a>
	</div>