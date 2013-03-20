<?php
/**
 * @version		$Id: default.php 20196 2011-01-09 02:40:25Z ian $
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.6
 */

defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
?>
<div class="profile<?php echo $this->pageclass_sfx?>">
<?php if ($this->params->get('show_page_heading')) : ?>
<h1>
	<?php echo $this->escape($this->params->get('page_heading')); ?>
</h1>
<?php endif; ?>
<?php $session =& JFactory::getSession();
      $lr_settings = array ();
      $db = JFactory::getDBO ();
	  $sql = "SELECT * FROM #__LoginRadius_settings";
      $db->setQuery ($sql);
      $rows = $db->LoadAssocList ();
      if (is_array ($rows)) {
        foreach ($rows AS $key => $data) {
          $lr_settings [$data ['setting']] = $data ['value'];
        }
      }
	  $sql = "SELECT * FROM #__LoginRadius_users WHERE id =".JFactory::getUser()->id;
      $db->setQuery ($sql);
      $acmaprows = $db->loadObjectList();
	  
	  ?>
	  <style>
	  .buttondelete {
	     background: -moz-linear-gradient(center top , #FCFCFC 0%, #E0E0E0 100%) repeat scroll 0 0 transparent;
         border: 1px solid #CCCCCC;
         border-radius: 5px 5px 5px 5px;
         color: #666666;
         padding: 1px;
         text-shadow: 0 1px 0 #FFFFFF;
		 cursor:pointer;
		 margin-left:5px;
      }
	  .AccountSetting-addprovider {
         list-style: none outside none !important;
         margin: 0 !important;
         padding: 0 !important;
         text-decoration: none;
		 line-height:normal!important;
      }
	  .AccountSetting-addprovider li {
         float: left !important;
         list-style: none outside none!important;
         min-width: 30px!important;
         word-wrap: break-word!important;
		 margin-bottom:5px !important;
       }
	  </style>
<fieldset id="users-profile-core" style=" background: none repeat scroll 0 0 #F7FAFE;border: 1px solid #DDDDDD;">
	<legend>
		<?php echo JText::_('COM_SOCIALLOGIN_LINK_ACCOUNT_HEAD'); ?>
	</legend>
	    <div >
	       <div style="float:right;">
	         <?php if (!empty($lr_settings['apikey'])) {
             $http = ($lr_settings['ishttps'] == 1 ? "https://" : "http://");
	         $loc = (isset($_SERVER['REQUEST_URI']) ? urlencode($http.$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI']) : urlencode($http.$_SERVER["HTTP_HOST"].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']));?><b><?php echo JText::_('COM_SOCIALLOGIN_LINK_ACCOUNT_TEXT'); ?></b><br />
             <iframe src="<?php echo $http;?>hub.loginradius.com/Control/PluginSlider.aspx?apikey=<?php echo $lr_settings['apikey']?>&callback=<?php echo $loc;?>" width="<?php echo $lr_settings['ifwidth']?>" height="<?php echo $lr_settings['ifheight']?>" frameborder="0" scrolling="no" allowtransparency="true"></iframe>
            <?php }?></div>
			<div style="float:left; width:270px;">
			   <div style="float:left; padding:5px;">
			   <?php $user_picture = $session->get('user_picture');?>
			   <img src="<?php if (!empty($user_picture)) { echo JURI::root().'images'.DS.'sociallogin'.DS. $session->get('user_picture');} else {echo JURI::root().'media' . DS . 'com_socialloginandsocialshare' . DS .'images' . DS . 'noimage.png';}?>" alt="<?php echo JFactory::getUser()->name?>" style="width:80px; height:auto;background: none repeat scroll 0 0 #FFFFFF; border: 1px solid #CCCCCC; display: block; margin: 2px 4px 4px 0; padding: 2px;">
			   </div>
			   <div style="float:right;padding:5px;font-size: 20px;margin: 5px;">
			   <b><?php echo JFactory::getUser()->name?></b>
			   </div>
			</div>
	      </div>
		  <div style="clear:both;"></div><br />
	  <?php echo JText::_('COM_SOCIALLOGIN_LINK_ACCOUNT_DESC'); ?><br /><br />
	  
	  <div style="width:350px;">
	  <ul class="AccountSetting-addprovider">
	  <?php $msg = JText::_('COM_SOCIALLOGIN_LINK_ACCOUNT_MSG'); ?>
	  <?php foreach ($acmaprows as $row) {?>
	  
	<li>
	<form id="member-profile" action="<?php echo JRoute::_('index.php?option=com_socialloginandsocialshare&task=profile.delmap'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
	<?php if ($row->LoginRadius_id == $session->get('user_lrid')) {
	        $msg = '<span style="color:red;">'.JText::_('COM_SOCIALLOGIN_LINK_ACCOUNT_MSGONE').'</span>';   
	      }
		  else {
		    $msg = JText::_('COM_SOCIALLOGIN_LINK_ACCOUNT_MSG');
		  }?>
		 
	        <span style="margin-right:5px;"> <img src="<?php echo 'administrator/components/com_socialloginandsocialshare/assets/img/'.$row->provider.'.png'; ?>" /></span>
			
			<?php echo $msg;?>
			<b><?php echo $row->provider; ?></b>
			<button type="submit" class="buttondelete"><span><?php echo JText::_('COM_SOCIALLOGIN_LINK_ACCOUNT_REMOVE'); ?></span></button><input type="hidden" name="option" value="com_socialloginandsocialshare" />
			<input type="hidden" name="task" value="profile.delmap" />
			<input type="hidden" name="mapid" value="<?php echo $row->provider; ?>" />
			<input type="hidden" name="lruser_id" value="<?php echo $row->LoginRadius_id; ?>" />
			</form>
			<?php echo JHtml::_('form.token'); ?></li><br />
	<?php }?>
	</ul>
	</div>
	
</fieldset>
<?php echo $this->loadTemplate('core'); ?>

<?php echo $this->loadTemplate('params'); ?>

<?php echo $this->loadTemplate('custom'); ?>

<?php if (JFactory::getUser()->id == $this->data->id) : ?>
<a href="<?php echo JRoute::_('index.php?option=com_users&task=profile.edit&user_id='.(int) $this->data->id);?>">
	<?php echo JText::_('COM_USERS_Edit_Profile'); ?></a>
<?php endif; ?>
</div>
