<?php 
/**
 * @version		$Id: default.php 1.4 16 Team LoginRadius
 * @copyright	Copyright (C) 2011 - till Open Source Matters. All rights reserved.
 * @license		GNU/GPL
 */
//no direct access
defined( '_JEXEC' ) or die('Restricted access');
JHtml::_('behavior.keepalive');?>

<?php 
// Check for plugin enabled.
  jimport('joomla.plugin.helper');
  if(!JPluginHelper::isEnabled('system','socialloginandsocialshare')) :
    JError::raiseNotice ('sociallogin_plugin', JText::_ ('MOD_LOGINRADIUS_PLUGIN_ERROR')); 
   endif; ?>

<?php if ($type == 'logout') : ?>
<?php $session =& JFactory::getSession();
  if ($lr_settings['showlogout'] == 1) :?>
  <form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form">
  <div>
  <?php $db = JFactory::getDBO();
	   $user_lrid = $session->get('user_lrid');
	   $query = "SELECT * FROM ".$db->nameQuote('#__LoginRadius_users')." WHERE id = '".$user->get('id')."' AND LoginRadius_id=".$db->Quote ($user_lrid);
       $db->setQuery($query);
       $find_id = $db->loadResult();
       $query = "SELECT COUNT(*) FROM ".$db->nameQuote('#__LoginRadius_users')." WHERE id = ".$user->get('id');
       $db->setQuery($query);
       $count = $db->loadResult();
	   if (empty($find_id)) {
	     $count = $count;
	   }
	   else {
	     $count = ($count == 0 ? $count : $count -1 );
	   }?>
	   <?php $user_picture = $session->get('user_picture');?>
     <div style="float:left;"><a href="<?php echo 'index.php?option=com_socialloginandsocialshare&view=profile';?>" title="My Profile">
<img src="<?php if (!empty($user_picture)) { echo JURI::root().'images'.DS.'sociallogin'.DS. $session->get('user_picture');} else {echo JURI::root().'media' . DS . 'com_socialloginandsocialshare' . DS .'images' . DS . 'noimage.png';}?>" alt="<?php echo $user->get('name');?>" style="width:50px; height:auto;background: none repeat scroll 0 0 #FFFFFF; border: 1px solid #CCCCCC; display: block; margin: 2px 4px 4px 0; padding: 2px;"></a>
     </div>
     <div>
       <div class="login-greeting" >
	   <div style=" font-weight:bold;">
	    <?php if($lr_settings['showname'] == 0) : {
		      echo JText::sprintf('MOD_LOGINRADIUS_HINAME', $user->get('name'));
	        } else : {
		      echo JText::sprintf('MOD_LOGINRADIUS_HINAME', $user->get('username'));
	        } endif; ?></div>
			<?php echo JText::_('MOD_LOGINRADIUS_VALUE_MAP'); ?> <b><?php echo $count;?></b><br /> <?php echo JText::_('MOD_LOGINRADIUS_VALUE_MAPONE'); ?>
       </div><br />
       <a href="<?php echo 'index.php?option=com_socialloginandsocialshare&view=profile';?>"><?php echo JText::_('MOD_LOGINRADIUS_VALUE_ACCOUNT'); ?></a>
	   <div class="logout-button">
		<input type="submit" name="Submit" class="button" value="<?php echo JText::_('JLOGOUT'); ?>" />
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.logout" />
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php echo JHtml::_('form.token');?>		
	   </div>
	</div>
  </div>
   </form>
<?php endif; ?>
<?php else : ?>

<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form" >
	<?php if ($lr_settings['showicons'] == 0) {
	        echo $params->get('pretext');
	        if (!empty($lr_settings['apikey'])) {
              $http = ($lr_settings['ishttps'] == 1 ? "https://" : "http://");
	          $loc = (isset($_SERVER['REQUEST_URI']) ? urlencode($http.$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI']) : urlencode($http.$_SERVER["HTTP_HOST"].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']));?><br />
              <iframe src="<?php echo $http;?>hub.loginradius.com/Control/PluginSlider.aspx?apikey=<?php echo $lr_settings['apikey']?>&callback=<?php echo $loc;?>" width="<?php echo $lr_settings['ifwidth']?>" height="<?php echo $lr_settings['ifheight']?>" frameborder="0" scrolling="no" allowtransparency="true"></iframe>
      <?php }
		  }
          if ($lr_settings['showwithicons'] == 1): ?>
            <div id='usetrad' name='usetrad'>
    <?php	if ($params->get('pretext')): ?>
		      <div class="pretext"></div>
	<?php endif; ?>
	      <fieldset class="userdata">
	      <p id="form-login-username">
		  <label for="modlgn-username"><?php echo JText::_('MOD_LOGINRADIUS_VALUE_USERNAME') ?></label>
		  <input id="modlgn-username" type="text" name="username" class="inputbox"  size="18" />
	      </p>
	      <p id="form-login-password">
		 <label for="modlgn-passwd"><?php echo JText::_('JGLOBAL_PASSWORD') ?></label>
		 <input id="modlgn-passwd" type="password" name="password" class="inputbox" size="18"  />
	     </p>
   <?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
	       <p id="form-login-remember">
		   <label for="modlgn-remember"><?php echo JText::_('MOD_LOGINRADIUS_REMEMBER_ME') ?></label>
		   <input id="modlgn-remember" type="checkbox" name="remember" class="inputbox" value="yes"/>
	       </p>
   <?php endif; ?>
	     <input type="submit" name="Submit" class="button" value="<?php echo JText::_('JLOGIN') ?>" />
	     <input type="hidden" name="option" value="com_users" />
	     <input type="hidden" name="task" value="user.login" />
	     <input type="hidden" name="return" value="<?php echo $return; ?>" />
	     <?php echo JHtml::_('form.token'); ?>
	     </fieldset></div><?php endif; ?>
	<?php if ($lr_settings['showicons'] == 1) {
	        echo $params->get('pretext');
	        if (!empty($lr_settings['apikey'])) {
              $http = ($lr_settings['ishttps'] == 1 ? "https://" : "http://");
	          $loc = (isset($_SERVER['REQUEST_URI']) ? urlencode($http.$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI']) : urlencode($http.$_SERVER["HTTP_HOST"].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']));?><br />
              <iframe src="<?php echo $http;?>hub.loginradius.com/Control/PluginSlider.aspx?apikey=<?php echo $lr_settings['apikey']?>&callback=<?php echo $loc;?>" width="<?php echo $lr_settings['ifwidth']?>" height="<?php echo $lr_settings['ifheight']?>" frameborder="0" scrolling="no" allowtransparency="true"></iframe>
      <?php }
		 }
         if ($lr_settings['showwithicons'] == 1): ?>
           <div id='usetrad1' name = 'usetrad1'>
           <ul>
		    <li>
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
			<?php echo JText::_('MOD_LOGINRADIUS_FORGOT_YOUR_PASSWORD'); ?></a>
		   </li>
		   <li>
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
			<?php echo JText::_('MOD_LOGINRADIUS_FORGOT_YOUR_USERNAME'); ?></a>
		   </li>
		   <?php
		   $usersConfig = JComponentHelper::getParams('com_users');
		   if ($usersConfig->get('allowUserRegistration')) : ?>
		     <li>
			  <a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">
				<?php echo JText::_('MOD_LOGINRADIUS_REGISTER'); ?></a>
		     </li>
		  <?php endif; ?>
	      </ul></div><?php endif; ?>
	      <?php if ($params->get('posttext')): ?>
		    <div class="posttext">
		    <p><?php echo $params->get('posttext'); ?></p>
		    </div>
	      <?php endif; ?>
	</form>
	<?php 
	// Adding column if uses old version.
	$provider_exists = false;$pic_exists = false;
	$db =& JFactory::getDBO();
    $columns = "show columns from #__LoginRadius_users";
    $db->setQuery( $columns );
    if ($rows = $db->loadObjectList())  {
      foreach ($rows as $row)  {
         if ($row->Field == 'provider') {
            $provider_exists = true;
            break;
          }
		  if ($row->Field == 'lr_picture') {
            $pic_exists = true;
            break;
          }
      }
    }     
    if (!$provider_exists) {
       $query = "ALTER TABLE #__LoginRadius_users ADD provider varchar(255) NULL";
       $db->setQuery( $query );
       $db->query();
    }
	if (!$pic_exists) {
       $query = "ALTER TABLE #__LoginRadius_users ADD lr_picture varchar(255) NULL";
       $db->setQuery( $query );
       $db->query();
    }
  endif;?>