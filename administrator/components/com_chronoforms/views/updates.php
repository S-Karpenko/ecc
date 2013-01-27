<?php
/**
* CHRONOFORMS version 4.0
* Copyright (c) 2006 - 2011 Chrono_Man, ChronoEngine.com. All rights reserved.
* Author: Chrono_Man (ChronoEngine.com)
* @license		GNU/GPL
* Visit http://www.ChronoEngine.com for regular updates and information.
**/
defined('_JEXEC') or die('Restricted access');
$mainframe = JFactory::getApplication();
?>
<fieldset>
<legend>Database updates</legend>
	<form action="index.php" method="post" name="adminForm" enctype="multipart/form-data">
		<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminlist">
			<tr>
				<td>
					Apply all the latest Chronoforms Database updates:
				</td>
			</tr>
			<tr>
				<td>
					<input type="submit" value="Run">
				</td>
			</tr>
		</table>
		<input type="hidden" name="option" value="com_chronoforms" />
		<input type="hidden" name="task" value="updates" />
		<input type="hidden" name="fn" value="db_updates" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
	</form>
</fieldset>