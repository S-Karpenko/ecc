<?php
defined ('_JEXEC') or die ('Direct Access to this location is not allowed.');
JHtml::_('behavior.tooltip');
jimport ('joomla.plugin.helper');
?>
<form action="<?php echo JRoute::_('index.php?option=com_socialloginandsocialshare&view=socialloginandsocialshare&layout=default'); ?>" method="post" name="adminForm">

<div>
<div style="float:left; width:70%;">
<div>
	<fieldset class="sociallogin_form sociallogin_form_main">
				<div class="row row_title">
						<?php echo JText::_('COM_SOCIALLOGIN_THANK'); ?>
					</div>
					<div class="row">
						<?php echo JText::_('COM_SOCIALLOGIN_THANK_BLOCK'); ?> <strong><?php echo JText::_('COM_SOCIALLOGIN_THANK_BLOCK_ONE'); ?></strong> <a href="http://www.LoginRadius.com" target="_blank">www.LoginRadius.com.</a>
						
					</div>
					<div class="row">
						<?php echo JText::_('COM_SOCIALLOGIN_THANK_BLOCK_TWO'); ?> <strong>LoginRadius</strong> <?php echo JText::_('COM_SOCIALLOGIN_THANK_BLOCK_THREE'); ?> <strong>hello@loginradius.com.</strong>
					</div>
					<div class="row row_description">
						<?php echo JText::_('COM_SOCIALLOGIN_THANK_BLOCK_FOUR'); ?>
					</div>
					<div class="row row_button">
						<div class="button2-left">
							<div class="blank">
		<a class="modal" href="http://www.loginradius.com/" target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_THANK_BLOCK_FIVE'); ?></a>
							</div>
						</div>
					</div>
	</fieldset>
	</div>
	
	<!-- Form Box -->
	 <div>
<table class="form-table sociallogin_table">
	<tr>
	<th class="head" colspan="2"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_API'); ?></small></th>
	</tr>
	<tr >
	<th scope="row">LoginRadius<br /><small><?php echo JText::_('COM_SOCIALLOGIN_SETTING_API_KEY'); ?></small></th>
	<td><?php echo JText::_('COM_SOCIALLOGIN_SETTING_API_KEY_DESC'); ?>
<a href='http://www.LoginRadius.com/' target='_blank'> LoginRadius.</a><br/>
<input size="60" type="text" name="settings[apikey]" id="settings[apikey]" value="<?php echo (isset ($this->settings ['apikey']) ? htmlspecialchars ($this->settings ['apikey']) : ''); ?>" /></td>
	</tr>
	<tr >
	<th scope="row">LoginRadius<br /><small><?php echo JText::_('COM_SOCIALLOGIN_SETTING_API_SECRET'); ?></small></th>
	<td><?php echo JText::_('COM_SOCIALLOGIN_SETTING_API_SECRET_DESC'); ?> <a href='http://www.LoginRadius.com/' target='_blank'>LoginRadius.</a><br/>
		<input size="60" type="text" name="settings[apisecret]" id="settings[apisecret]" value="<?php echo (isset ($this->settings ['apisecret']) ? htmlspecialchars ($this->settings ['apisecret']) : ''); ?>" /></td>
	</tr>
	<tr class="row_white">
	<th scope="row"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_USEAPI'); ?></th>
	<td><?php echo JText::_('COM_SOCIALLOGIN_SETTING_USEAPI_DESC'); ?>
	<br />
	<?php   $useapi_curl = "";

			$useapi_fopen = "";
			$useapi = (isset($this->settings['useapi']) ? $this->settings['useapi'] : "");

			if ($useapi == '1' ) $useapi_curl = "checked='checked'";

			else if ($useapi == '0') $useapi_fopen = "checked='checked'";

			else $useapi_curl = "checked='checked'";?>
	
	<?php echo JText::_('COM_SOCIALLOGIN_SETTING_USEAPI_CURL'); ?> <input name="settings[useapi]" type="radio"  <?php echo $useapi_curl;?>value="1" />&nbsp;&nbsp;&nbsp;&nbsp;
<?php echo JText::_('COM_SOCIALLOGIN_SETTING_USEAPI_FOPEN'); ?> <input name="settings[useapi]" type="radio" <?php echo $useapi_fopen;?>value="0" />
	</td>
	</tr>
	</table>
	<table class="form-table sociallogin_table">
	<tr>
	<th class="head" colspan="2"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_BASIC'); ?></small></th>
	</tr>
	<tr>
  <th scope="row"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_BASIC_REDIRECT'); ?><br /><small><?php echo JText::_('COM_SOCIALLOGIN_SETTING_BASIC_REDIRECT_SMALL'); ?></small></th>
  <td><?php echo JText::_('COM_SOCIALLOGIN_SETTING_BASIC_REDIRECT_DESC'); ?><br />
<?php 
$db = &JFactory::getDBO();
$query = "SELECT m.id, m.title,m.level,mt.menutype FROM #__menu AS m
     INNER JOIN #__menu_types AS mt ON mt.menutype = m.menutype
     WHERE mt.menutype = m.menutype AND m.published = '1' ORDER BY mt.menutype,m.level";
$db->setQuery($query);
$rows = $db->loadObjectList();
?>
<?php $setredirct = (isset($this->settings['setredirct']) ? $this->settings['setredirct'] : "");?>
<select id="setredirct" name="settings[setredirct]">
<option value="default" selected="selected">---Default---</option>
<?php foreach ($rows as $row) {?>
<option <?php if ($row->id == $setredirct) { echo " selected=\"selected\""; } ?>value="<?php echo $row->id;?>" >
<?php 
  echo '<b>'.$row->menutype.'</b>';
  if($row->level == 1) { echo '-';}
  if($row->level == 2) { echo '--';}
  if($row->level == 3) { echo '---';}
  if($row->level == 4) { echo '----';}
  if($row->level == 5) { echo '-----';}
  echo $row->title;?>
  </option>
<?php }?>
</select>
  </td>
 </tr>	
 <tr class="row_white">
	<th scope="row"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_LINK'); ?><br /><small><?php echo JText::_('COM_SOCIALLOGIN_SETTING_LINK_SMALL'); ?></small></th>
	<td><?php echo JText::_('COM_SOCIALLOGIN_SETTING_LINK_DESC'); ?>
	<br />
	<?php   $yeslink = "";
            $notlink = "";
            $linkaccount = (isset($this->settings['linkaccount'])  ? $this->settings['linkaccount'] : "");

			if ($linkaccount == '1') $yeslink = "checked='checked'";

			else if ($linkaccount == '0') $notlink = "checked='checked'";

			else $yeslink = "checked='checked'";?>
	
    <?php echo JText::_('COM_SOCIALLOGIN_YES'); ?> <input name="settings[linkaccount]" type="radio" <?php echo $yeslink;?> value="1"  />&nbsp;&nbsp;&nbsp;&nbsp;
    <?php echo JText::_('COM_SOCIALLOGIN_NO'); ?> <input name="settings[linkaccount]" type="radio" <?php echo $notlink;?>value="0"   />
</td>
	</tr>
 
 
	<?php if (JPluginHelper::isEnabled('system', 'k2')) {?>
	<tr>
	<th scope="row"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_K2'); ?><br /><small><?php echo JText::_('COM_SOCIALLOGIN_SETTING_K2_SMALL'); ?></small></th>
	<td><?php echo JText::_('COM_SOCIALLOGIN_SETTING_K2_DESC'); ?> <strong>(default Registered)</strong>.
	<br />
<input type="text"  name="settings[k2group]" size="2" value="<?php echo (isset ($this->settings ['k2group']) ? htmlspecialchars ($this->settings ['k2group']) : '2'); ?>" />
</td>
	</tr>
	<?php }?>
	<tr class="row_white">
	<th scope="row"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_EMAIL'); ?><br /><small><?php echo JText::_('COM_SOCIALLOGIN_SETTING_EMAIL_SMALL'); ?></small></th>
	<td><?php echo JText::_('COM_SOCIALLOGIN_SETTING_EMAIL_DESC'); ?>
	<br />
	<?php    $yessendemail = "";

			$notsendemail = "";
$sendemail = (isset($this->settings['sendemail'])  ? $this->settings['sendemail'] : "");

			if ($sendemail == '1') $yessendemail = "checked='checked'";

			else if ($sendemail == '0') $notsendemail = "checked='checked'";

			else $yessendemail = "checked='checked'";?>
	
    <?php echo JText::_('COM_SOCIALLOGIN_YES'); ?> <input name="settings[sendemail]" type="radio" <?php echo $yessendemail;?> value="1"  />&nbsp;&nbsp;&nbsp;&nbsp;
    <?php echo JText::_('COM_SOCIALLOGIN_NO'); ?> <input name="settings[sendemail]" type="radio" <?php echo $notsendemail;?>value="0"   />
</td>
	</tr>
	<tr>
	<th scope="row"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_EMAIL_REQUIRED'); ?></th>
	<td><?php echo JText::_('COM_SOCIALLOGIN_SETTING_EMAIL_REQUIRED_DESC'); ?>
	</td>
	</tr>
	<tr>
	<th></th>
	<td>
	<?php   $yesdummyemail = "";

			$notdummyemail = "";
			
			$dummyemail = (isset($this->settings['dummyemail'])  ? $this->settings['dummyemail'] : "");


			if ($dummyemail == '0') $yesdummyemail = "checked='checked'";

			else if ($dummyemail == '1') $notdummyemail = "checked='checked'";

			else $notdummyemail = "checked='checked'";?>
	
	<?php echo JText::_('COM_SOCIALLOGIN_YES'); ?> <input name="settings[dummyemail]" type="radio"  <?php echo $notdummyemail;?>value="1"  />&nbsp;&nbsp;&nbsp;&nbsp;
<?php echo JText::_('COM_SOCIALLOGIN_NO'); ?> <input name="settings[dummyemail]" type="radio" <?php echo $yesdummyemail;?>value="0"  />
	</td>
	</tr>
	
	
</table>
<table class="form-table sociallogin_table">
	<tr>
	<th class="head" colspan="2"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_FRONT'); ?></small></th>
	</tr>
	<tr>
	<th scope="row"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_NAME'); ?></th>
	<td><?php echo JText::_('COM_SOCIALLOGIN_SETTING_NAME_DESC'); ?>
	<br />
	<?php    $showonlyname = "";

			$showusername = "";
	$showname = (isset($this->settings['showname'])  ? $this->settings['showname'] : "");

			if ($showname == '0') $showonlyname = "selected='selected'";

			else if ($showname == '1') $showusername = "selected='selected'";

			else $showonlyname = "selected='selected'";?>
	
<select id="showname" name="settings[showname]">
  <option <?php echo $showonlyname;?>value="0"  ><?php echo JText::_('COM_SOCIALLOGIN_NAME'); ?></option>
  <option <?php echo $showusername;?>value="1"><?php echo JText::_('COM_SOCIALLOGIN_USERNAME'); ?></option>
</select>
</td>
	</tr>
	<tr class="row_white">
	<th scope="row"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_FORM'); ?><br /><small><?php echo JText::_('COM_SOCIALLOGIN_SETTING_FORM_SMALL'); ?></small></th>
	<td><?php echo JText::_('COM_SOCIALLOGIN_SETTING_FORM_DESC'); ?>
	<br />
	<?php   $yesshowwithicons = "";

			$notshowwithicons = "";
			$showwithicons = (isset($this->settings['showwithicons'])  ? $this->settings['showwithicons'] : "");

			if ($showwithicons == '1') $yesshowwithicons = "checked='checked'";

			else if ($showwithicons == '0') $notshowwithicons = "checked='checked'";

			else $yesshowwithicons = "checked='checked'";?>
	
    <?php echo JText::_('COM_SOCIALLOGIN_YES'); ?> <input name="settings[showwithicons]" type="radio"  <?php echo $yesshowwithicons;?>value="1"  />&nbsp;&nbsp;&nbsp;&nbsp;
    <?php echo JText::_('COM_SOCIALLOGIN_NO'); ?> <input name="settings[showwithicons]" type="radio" <?php echo $notshowwithicons;?>value="0"  />
</td>
	</tr>
	<tr>
	<th scope="row"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_GREETING'); ?></th>
	<td><?php echo JText::_('COM_SOCIALLOGIN_SETTING_GREETING_DESC'); ?>
	<br />
	<?php   $yesshowlogout = "";

			$notshowlogout = "";
$showlogout = (isset($this->settings['showlogout'])  ? $this->settings['showlogout'] : "");
			if ($showlogout == '1') $yesshowlogout = "checked='checked'";

			else if ($showlogout == '0') $notshowlogout = "checked='checked'";

			else $yesshowlogout = "checked='checked'";?>
	
	<?php echo JText::_('COM_SOCIALLOGIN_YES'); ?> <input name="settings[showlogout]" type="radio" <?php echo $yesshowlogout;?> value="1" />&nbsp;&nbsp;&nbsp;&nbsp;
    <?php echo JText::_('COM_SOCIALLOGIN_NO'); ?> <input name="settings[showlogout]" type="radio" <?php echo $notshowlogout;?>value="0"  />
	</td>
	</tr>
<tr>
	<th scope="row"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_ICONS'); ?><br /><small><?php echo JText::_('COM_SOCIALLOGIN_SETTING_ICONS_SMALL'); ?></small></th>
	<td><?php echo JText::_('COM_SOCIALLOGIN_SETTING_ICONS_DESC'); ?>
	<br />
	<?php    $topshowicons = "";

			$botshowicons = "";
	$showicons = (isset($this->settings['showicons']) ? $this->settings['showicons'] : "");

			if ($showicons == '0') $topshowicons = "selected='selected'";

			else if ($showicons == '1') $botshowicons = "selected='selected'";

			else $topshowicons = "selected='selected'";?>
	
<select id="showicons" name="settings[showicons]">
  <option <?php echo $topshowicons;?>value="0" ><?php echo JText::_('COM_SOCIALLOGIN_SETTING_ICONS_TOP'); ?></option>
  <option <?php echo $botshowicons;?>value="1" ><?php echo JText::_('COM_SOCIALLOGIN_SETTING_ICONS_BOT'); ?></option>
</select>
</td>
	</tr>	
	
</table>

<!-- social share -->
<table class="form-table sociallogin_table">
	<tr>
	<th class="head" colspan="2"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE'); ?></small></th>
	</tr>
	<tr>
	<th><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_ENABLE'); ?></th>
	<td>
	<?php   $enableshare = "";
            $enableshare = (isset($this->settings['enableshare']) == 'on'  ? 'on' : 'off');
			if ($enableshare == 'on') $enableshare = "checked='checked'";
    ?>
<input name="settings[enableshare]" type="checkbox"  <?php echo $enableshare;?>value="on"  /><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_ENABLE_TITLE'); ?>&nbsp;&nbsp;&nbsp;&nbsp;
	</td>
	</tr>
	<tr >
	<th scope="row">LoginRadius<br /><small><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_SCRIPT'); ?></small></th>
	<td><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_SCRIPT_DESC'); ?>
<a href='http://www.LoginRadius.com/' target='_blank'> LoginRadius.</a><br/>
<input type="text" name="settings[sharescript]" id="settings[sharescript]" value="<?php echo (isset ($this->settings ['sharescript']) ?  htmlspecialchars($this->settings ['sharescript']) : ''); ?>"  style="width:320px; height:40px;"/></td>
	</tr>
	
<tr>
	<th><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_POSITION'); ?><br /><small><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_POSITION_DESC'); ?></small></th>
	<td>
	<?php   $sharetop = "";
            $sharebottom = "";
			$sharepos = (isset($this->settings['sharepos'])  ? $this->settings['sharepos'] : "");
            if ($sharepos == '1') $sharebottom = "checked='checked'";
            else if ($sharepos == '0') $sharetop = "checked='checked'";
            else $sharetop = "checked='checked'";?>
	
	<?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_POSITION_TOP'); ?> <input name="settings[sharepos]" type="radio"  <?php echo $sharetop;?>value="0"  />&nbsp;&nbsp;&nbsp;&nbsp;
<?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_POSITION_BOTTOM'); ?> <input name="settings[sharepos]" type="radio" <?php echo $sharebottom;?>value="1"  />
	</td>
	</tr>
	<tr>
	<th><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_ALIGN'); ?><br /><small><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_POSITION_DESC'); ?></small></th>
	<td>
	<?php   $shareleft = "";
            $sharecenter = "";
			$shareright = "";
			$sharealign = (isset($this->settings['sharealign'])  ? $this->settings['sharealign'] : "");
            if ($sharealign == "center") $sharecenter = "checked='checked'";
            else if ($sharealign == "left") $shareleft = "checked='checked'";
            else if ($sharealign == "right") $shareright = "checked='checked'";
			else $sharecenter = "checked='checked'";?>
	
	<?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_POSITION_LEFT'); ?> <input name="settings[sharealign]" type="radio"  <?php echo $shareleft;?>value="left"  />&nbsp;&nbsp;&nbsp;&nbsp;
<?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_POSITION_CENTER'); ?> <input name="settings[sharealign]" type="radio" <?php echo $sharecenter;?>value="center"  />&nbsp;&nbsp;&nbsp;&nbsp;
<?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_POSITION_RIGHT'); ?> <input name="settings[sharealign]" type="radio" <?php echo $shareright;?>value="right"  />
	</td>
	</tr>
	<tr>
	<th><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_ARTICLES');?><br /><small><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_ARTICLES_DESC');?></small></th>
	<td>
<?php 
$db = &JFactory::getDBO();
$query = "SELECT id, title FROM #__content WHERE state = '1' ORDER BY ordering";
$db->setQuery($query);
$rows = $db->loadObjectList();
?>
<?php $share_articles = (isset($this->settings['s_articles']) ? $this->settings['s_articles'] : "");
$share_articles = unserialize($share_articles);?>
<select id="s_articles[]" name="s_articles[]" multiple="multiple">
<?php foreach ($rows as $row) {?>
<option <?php if (!empty($share_articles)) {
                foreach ($share_articles as $key=>$value) {
				  if ($row->id == $value) { 
				    echo " selected=\"selected\""; 
				  } 
				}
			  }?>value="<?php echo $row->id;?>" >
<?php echo $row->title;?>
  </option>
<?php }?>
</select>	</td>
	</tr>
	<tr>

	</table>
	
<!-- social counter -->
<table class="form-table sociallogin_table">
	<tr>
	<th class="head" colspan="2"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_COUNTER');?></small></th>
	</tr>
	<tr>
	<th><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_COUNTER_ENABLE');?></th>
	<td>
	<?php   $enablecounter = "";
			$enablecounter = (isset($this->settings['enablecounter'])  == 'on'  ? 'on' : 'off');
            if ($enablecounter == 'on') $enablecounter = "checked='checked'";
    ?>
<input name="settings[enablecounter]" type="checkbox" <?php echo $enablecounter;?> value="on"  /><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_COUNTER_ENABLE_TITLE');?>
	</td>
	</tr>
	
	<tr >
	<th scope="row">LoginRadius<br /><small><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_COUNTER_SCRIPT');?></small></th>
	<td><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_COUNTER_SCRIPT_DESC');?>
<a href='http://www.LoginRadius.com/' target='_blank'> LoginRadius.</a><br/>
<input type="text"  name="settings[counterscript]" id="settings[counterscript]" value="<?php echo (isset ($this->settings ['counterscript']) ?  htmlspecialchars($this->settings ['counterscript']) : ''); ?>" style="width:320px; height:40px;" /></td>
	</tr>
<tr>
	<th><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_COUNTER_POSITION');?><br /><small><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_COUNTER_POSITION_DESC');?></small></th>
	<td>
	<?php   $countertop = "";
            $counterbottom = "";
			$counterpos = (isset($this->settings['counterpos'])  ? $this->settings['counterpos'] : "");
            if ($counterpos == '1') $counterbottom = "checked='checked'";
            else if ($counterpos == '0') $countertop = "checked='checked'";
            else $countertop = "checked='checked'";?>
	
	<?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_COUNTER_POSITION_TOP');?> <input name="settings[counterpos]" type="radio"  <?php echo $countertop;?>value="0"  />&nbsp;&nbsp;&nbsp;&nbsp;
<?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_COUNTER_POSITION_BOTTOM');?> <input name="settings[counterpos]" type="radio" <?php echo $counterbottom;?>value="1"  />
	</td>
	</tr>
<tr>
	<th><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_COUNTER_ALIGN');?><br /><small><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_COUNTER_POSITION_DESC');?></small></th>
	<td>
	<?php   $counterleft = "";
            $countercenter = "";
			$counterright = "";
			
			$counteralign = (isset($this->settings['counteralign'])  ? $this->settings['counteralign'] : "");
            if ($counteralign == "center") $countercenter = "checked='checked'";
            else if ($counteralign == "left") $counterleft = "checked='checked'";
            else if ($counteralign == "right") $counterright = "checked='checked'";
			else $countercenter = "checked='checked'";?>
	
	<?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_COUNTER_POSITION_LEFT');?> <input name="settings[counteralign]" type="radio"  <?php echo $counterleft;?>value="left"  />&nbsp;&nbsp;&nbsp;&nbsp;
<?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_COUNTER_POSITION_CENTER');?> <input name="settings[counteralign]" type="radio" <?php echo $countercenter;?>value="center"  />&nbsp;&nbsp;&nbsp;&nbsp;
<?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_COUNTER_POSITION_RIGHT');?> <input name="settings[counteralign]" type="radio" <?php echo $counterright;?>value="right"  />
	</td>
	</tr>
<tr>
	<th><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_COUNTER_ARTICLES');?><br /><small><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_COUNTER_ARTICLES_DESC');?></small></th>
	<td>

<?php 
$db = &JFactory::getDBO();
$query = "SELECT id, title FROM #__content WHERE state = '1' ORDER BY ordering";
$db->setQuery($query);
$rows = $db->loadObjectList();
?>
<?php $counter_articles = (isset($this->settings['c_articles']) ? $this->settings['c_articles'] : "");
$counter_articles = unserialize($counter_articles);?>
<select id="c_articles[]" name="c_articles[]" multiple="multiple">
<?php foreach ($rows as $row) {?>
<option <?php if (!empty($counter_articles)) { 
                foreach ($counter_articles as $key=>$value) {
				  if ($row->id == $value) { 
				    echo " selected=\"selected\""; 
				  } 
				}
			  }?>value="<?php echo $row->id;?>" >
<?php echo $row->title;?>
  </option>
<?php }?>
</select>	</td>
	</tr>
	
	</table>
</div>
</div>	
	
<div style="float:right; width:29%;">
<!-- Help Box -->
<div class="sociallogin_helpbox sociallogin_helpboxaside" >
<h3 style="margin: 10px 5px;"><?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_HELP'); ?></h3>
<p align="justify" style="line-height: 19px;">
<ul>
		<li><a href="http://support.loginradius.com/customer/portal/topics/272883-joomla-extension/articles" target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_HELP_LINK_ONE'); ?></a></li>
		<li><a href="http://extensions.joomla.org/extensions/access-a-security/site-access/authentication-cloud-based/19293" target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_HELP_LINK_TWO'); ?></a></li>
		<li><a href="http://joomla.loginradius.com/" target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_HELP_LINK_THREE'); ?></a></li>
		<li><a href="http://jcommunity.loginradius.com/" target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_HELP_LINK_FOUR'); ?></a></li>
		<li><a href="https://www.loginradius.com/Loginradius/About" target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_HELP_LINK_FIVE'); ?></a></li>
		<li><a href="http://blog.loginradius.com/" target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_HELP_LINK_SIX'); ?></a></li>
		<li><a href="https://www.loginradius.com/AddOns/Drupal" target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_HELP_LINK_SEVEN'); ?></a></li>
		<li><a href="https://www.loginradius.com/LoginRadius/Contact" target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_HELP_LINK_EIGHT'); ?></a></li>
		<br /><br />
		</ul></p>

</div>
<!-- More help Box -->
  <div class="sociallogin_morehelpbox sociallogin_morehelpboxaside" >
<h3 style="margin: 10px 5px;"><?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_GETTING'); ?></h3><p align="justify" style="line-height: 19px;">
<?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_GETTING_TEXT'); ?> <a href ="http://support.loginradius.com/customer/portal/articles/594022-how-do-i-implement-social-login-on-joomla-v1-6-and-higher" target="_blank">Plugin User Guide</a> <?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_GETTING_TEXT_ONE'); ?> <?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_GETTING_TEXT_TWO'); ?> <a href ="<?php echo JURI::root();?>administrator/index.php?option=com_admin&view=sysinfo" target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_GETTING_TEXT_THREE'); ?></a> <?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_GETTING_TEXT_FOUR'); ?> </p>
</div>

<!-- Support Box -->
  <div class="sociallogin_supportbox sociallogin_supportboxaside" >
<h3 style="margin: 10px 5px;"><?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_TECH_SUPPORT'); ?></h3><p align="justify" style="line-height: 19px;">
<?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_TECH_SUPPORT_TEXT_ONE'); ?> <a href ="http://support.loginradius.com/" target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_TECH_SUPPORT_TEXT_TWO'); ?></a> <?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_TECH_SUPPORT_TEXT_THREE'); ?> <a href="mailto:hello@loginradius.com">hello@loginradius.com</a></p>
</div>

	<!-- Upgrade Box -->
<div class="sociallogin_upgradebox sociallogin_upgradeboxaside" >
<h3 style="margin: 10px 5px;"><?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_SUPPORT'); ?></h3><p align="justify" style="line-height: 19px;">
<?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_SUPPORT_TEXT'); ?> <a href ="http://extensions.joomla.org/extensions/access-a-security/site-access/authentication-cloud-based/19293" target="_blank">social login</a> <?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_SUPPORT_TEXT_ONE'); ?> <a href="https://www.loginradius.com/AddOns/Drupal" target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_SUPPORT_TEXT_TWO'); ?></a>.</p>
</div>
 </div>
	</div>
	<input type="hidden" name="task" value="" />
</form>