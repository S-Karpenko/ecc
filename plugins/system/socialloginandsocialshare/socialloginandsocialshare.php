<?php

defined ('_JEXEC') or die ('Restricted access');

jimport ('joomla.plugin.plugin');

jimport ('joomla.filesystem.file');

jimport ('joomla.user.helper');

jimport ('joomla.mail.helper' );

jimport ('joomla.application.component.helper');

jimport ('joomla.application.component.modelform');

jimport ('joomla.application.component.controller' );

jimport ('joomla.event.dispatcher');

jimport ('joomla.plugin.helper');

jimport ('joomla.utilities.date');



// Check if plugin is correctly installed.

if (!JFile::exists (dirname (__FILE__) . DS . 'LoginRadius.php') && !JFile::exists (dirname (__FILE__) . DS . 'socialloginandsocialshare_helper.php')) {

  JError::raiseNotice ('sociallogin_plugin', JText::_ ('COM_SOCIALLOGIN_PLUGIN_INSTALL_FAILURE'));

  return;

}



// Includes plugins required files.

require_once(dirname (__FILE__) . DS . 'socialloginandsocialshare_helper.php');

require_once(dirname (__FILE__) . DS . 'LoginRadius.php');



/*

 * Class that indicates the plugin.

 */

class plgSystemSocialLoginAndSocialShare extends JPlugin {



/*

 * Class constructor.

 */

  function plgSystemSocialLoginAndSocialShare(&$subject, $config) {

    parent::__construct($subject,$config);

  }

  

/*

 * Plugin class function that calls on after plugin intialise.

 */

  function onAfterInitialise() {

    $lrdata = array(); $user_id = ''; $id = ''; $email = ''; $msg = ''; $defaultUserGroup = ''; $lr_settings = array();

    $lr_settings = plgSystemSocialLoginTools::sociallogin_getsettings ();

	// Get module configration option value

	$mainframe = JFactory::getApplication();

	$db =& JFactory::getDBO();
	
    $config = JFactory::getConfig();

    $language =& JFactory::getLanguage();

	$session =& JFactory::getSession();

	$language->load('com_users');
	
	$language->load('com_socialloginandsocialshare', JPATH_ADMINISTRATOR);
	
	$authorize = JFactory::getACL();

    	// Retrive data from LoginRadius.

	$obj = new LoginRadius();

	$lr_settings ['apisecret'] = (!empty($lr_settings ['apisecret']) ? $lr_settings ['apisecret'] : "");

    $userprofile = $obj->sociallogin_getapi($lr_settings ['apisecret']);

	

	// Checking user is logged in.

	if ($obj->IsAuthenticated == true && JFactory::getUser()->id) {

	  $lrdata = plgSystemSocialLoginTools::get_userprofile_data($userprofile);

	  

	  // Check lr id exist.

	  $query = "SELECT LoginRadius_id from #__LoginRadius_users WHERE LoginRadius_id=".$db->Quote ($lrdata['id'])." AND id = " . JFactory::getUser()->id;

      $db->setQuery($query);

      $check_id = $db->loadResult();

	  

	  // Try to map another account.

      if (empty($check_id)) {

	    $userImage = plgSystemSocialLoginTools::add_newid_image($lrdata);

		

		// Remove.

		$sql = "DELETE FROM #__LoginRadius_users WHERE LoginRadius_id = " . $db->Quote ($lrdata['id']);

		$db->setQuery ($sql);

		if ($db->query ()) {

		  

		  // Add new id to db.

		  $sql = "INSERT INTO #__LoginRadius_users SET id = " . JFactory::getUser()->id . ", LoginRadius_id = " . $db->Quote ($lrdata['id']).", provider = " . $db->Quote ($lrdata['Provider']) . ", lr_picture = " . $db->Quote ($userImage);

          $db->setQuery ($sql);

	      $db->query();

        }

		$mainframe->enqueueMessage (JText::_ ('COM_SOCIALLOGIN_ADD_ID'));

	    $mainframe->redirect('index.php?option=com_socialloginandsocialshare&view=profile');

	  }

      else {

        JError::raiseWarning ('', JText::_ ('COM_SOCIALLOGIN_EXIST_ID'));

        return false;

      }

    }

	

	// User is not logged in trying to make log in user.

	if ($obj->IsAuthenticated == true && !JFactory::getUser()->id) {

	  

	  // Remove the session if any.

	  if ($session->get('tmpuser')) {

	    $session->clear('tmpuser');

	  }

	  

	  // Getting all user data.

	  $lrdata = plgSystemSocialLoginTools::get_userprofile_data($userprofile);

	  if ($lr_settings ['dummyemail'] == 0 && $lrdata['email'] == "") {

	    // Random email if not true required email.

		$lrdata['email'] = plgSystemSocialLoginTools::get_random_email($lrdata);

      }

	  

	  // Find the not activate user.

	   $query = "SELECT u.id FROM #__users AS u

     INNER JOIN #__LoginRadius_users AS lu ON lu.id = u.id

     WHERE lu.LoginRadius_id = '".$lrdata['id']."' AND u.activation != '' AND u.activation != 0";


        $db->setQuery($query);

        $block_id = $db->loadResult();

		if (!empty($block_id) || $block_id) {

		  JError::raiseWarning ('', JText::_ ('COM_SOCIALLOGIN_USER_NOTACTIVATE'));

          return false;

		}

	  

	  // Find the block user.

	   $query = "SELECT u.id FROM #__users AS u

     INNER JOIN #__LoginRadius_users AS lu ON lu.id = u.id

     WHERE lu.LoginRadius_id = '".$lrdata['id']."' AND u.block = 1";

        $db->setQuery($query);

        $block_id = $db->loadResult();

		if (!empty($block_id) || $block_id) {

		  JError::raiseWarning ('', JText::_ ('COM_SOCIALLOGIN_USER_BLOCK'));

          return false;

		}

		

	  // Checking user admin mail setting.

	  if ($lr_settings ['dummyemail'] == 1 && $lrdata['email'] == '') {

	    $usersConfig = JComponentHelper::getParams( 'com_users' );

	    $useractivation = $usersConfig->get( 'useractivation' );

	    $query = "SELECT u.id FROM #__users AS u

     INNER JOIN #__LoginRadius_users AS lu ON lu.id = u.id

     WHERE lu.LoginRadius_id = '".$lrdata['id']."'";

        $db->setQuery($query);

        $user_id = $db->loadResult();

		$newuser = true;

        if (isset($user_id)) {

		  $user =& JFactory::getUser($user_id);

            if ($user->id == $user_id) {

              $newuser = false;

            }

        }

        else {
		  if ($useractivation == '0') {
		    $lrdata['email'] = plgSystemSocialLoginTools::get_random_email($lrdata);
		  }
          else {
		    $msg = JText::_ ('COM_SOCIALLOGIN_POPUP_MSG').' '. $lrdata['Provider'] . ' '. JText::_ ('COM_SOCIALLOGIN_POPUP_MSGONE'). JText::_ ('COM_SOCIALLOGIN_POPUP_MSGTWO');

		    $msgtype = 'msg';

		    // Register session variables.

            $session->set('tmpuser',$lrdata);

            plgSystemSocialLoginTools::enterEmailPopup($msg, $msgtype);
          }
        } 
      }

	}

	

	// Check user click on enter mail popup submit button.

	if (isset($_POST['sociallogin_emailclick'])) {

	  $lrdata = $session->get('tmpuser');

	  if (isset($_POST['session']) && $_POST['session'] == $lrdata['session'] && !empty($lrdata['session'])) {

        $email = urldecode($_POST['email']);

        if(!JMailHelper::isEmailAddress($email)) {

		  $msgtype = 'warning';

		  $msg = JText::_ ('COM_SOCIALLOGIN_EMAIL_INVALID');

          plgSystemSocialLoginTools::enterEmailPopup($msg, $msgtype);

		  return false;

        }

	    else {

		  $email = $db->getEscaped($email);

		  $query = "SELECT id FROM #__users WHERE email=".$db->Quote ($email);

		  $db->setQuery($query);

		  $user_exist = $db->loadResult();

	      if ($user_exist != 0 ) {

		    $msgtype = 'warning';

			$msg = JText::_ ('COM_SOCIALLOGIN_EMAIL_EXIST');

            plgSystemSocialLoginTools::enterEmailPopup($msg, $msgtype);

		    return false;

		  }

          else {

	        $lrdata = $session->get('tmpuser');

            $email = $db->getEscaped(urldecode($_POST['email']));

            $lrdata['email'] = $email;

	      }

	    }

	  }

	  else {

	    $session->clear('tmpuser');

	    JError::raiseWarning ('', JText::_ ('COM_SOCIALLOGIN_SESSION_EXPIRED'));

		return false;

	  }

    }

	

	// Checking user click on popup cancel button.

    else if (isset($_POST['cancel'])) {

	   // Redirect after Cancel click.

	    $session->clear('tmpuser');

	    $redirct = JURI::base();

	    $mainframe->redirect($redirct);

	}

	if (isset($lrdata['id']) && !empty($lrdata['id']) && !empty($lrdata['email'])) {

	  

	  // Filter username form data.

	  if (!empty($lrdata['fname']) && !empty($lrdata['lname'])) {

	    $username = $lrdata['fname'].$lrdata['lname'];

	    $name = $lrdata['fname'];

	  }

	  else {

	    $username = plgSystemSocialLoginTools::get_filter_username($lrdata);

	    $name = plgSystemSocialLoginTools::get_filter_username($lrdata);

	  }

	 $query="SELECT u.id FROM #__users AS u

     INNER JOIN #__LoginRadius_users AS lu ON lu.id = u.id

     WHERE lu.LoginRadius_id = '".$lrdata['id']."'";

      $db->setQuery($query);

      $user_id = $db->loadResult();

      

	  // If not then check for email exist.

	  if (empty($user_id)) {

        $query = "SELECT id FROM #__users WHERE email='".$lrdata['email']."'";

        $db->setQuery($query);

        $user_id = $db->loadResult(); 

		$query = "SELECT LoginRadius_id from #__LoginRadius_users WHERE LoginRadius_id=".$db->Quote ($lrdata['id'])." AND id = " . $user_id;

        $db->setQuery($query);

        $check_id = $db->loadResult();

	    if (empty($check_id) && $lr_settings ['linkaccount'] == '1') {

		  // Add new id to db.

		  $userImage = plgSystemSocialLoginTools::add_newid_image($lrdata);

		  $sql = "INSERT INTO #__LoginRadius_users SET id = " . $user_id . ", LoginRadius_id = " . $db->Quote ($lrdata['id']).", provider = " . $db->Quote ($lrdata['Provider']) . ", lr_picture = " . $db->Quote ($userImage);

          $db->setQuery ($sql);

	      $db->query();

		}

	  }

	  $newuser = true;

      if (isset($user_id)) {

	    $user =& JFactory::getUser($user_id);

        if ($user->id == $user_id) {

          $newuser = false;

        }

	  }

	  if ($newuser == true) {

         $user = new JUser;
	    $need_verification = false;

		// If user registration is not allowed, show 403 not authorized.

	    $usersConfig = JComponentHelper::getParams( 'com_users' );

        if ($usersConfig->get('allowUserRegistration') == '0') {

          JError::raiseWarning( '', JText::_( 'COM_SOCIALLOGIN_REGISTER_DISABLED'));

          return false;

        }

		

		// Default to Registered.

        $defaultUserGroup = $usersConfig->get('new_usertype');

	    if (empty($defaultUserGroups)) {

          $defaultUserGroups = 'Registered';

        }

        

		// if username already exists

        $username = plgSystemSocialLoginTools::get_exist_username($username);

		

		// Remove special char if have.

		$username = plgSystemSocialLoginTools::remove_unescapedChar($username);

	    $name = plgSystemSocialLoginTools::remove_unescapedChar($name);

	    

		//Insert data 

		jimport ('joomla.user.helper');

	    $userdata = array ();

	    $userdata ['name'] = $db->getEscaped($name);

        $userdata ['username'] = $db->getEscaped($username);

        $userdata ['email'] = $lrdata['email'];

        $userdata ['usertype'] = 'deprecated';

        $userdata ['groups'] = array($defaultUserGroup);

        $userdata ['registerDate'] = JFactory::getDate ()->toMySQL ();

        $userdata ['password'] = JUserHelper::genRandomPassword ();

        $userdata ['password2'] = $userdata ['password'];

		$useractivation = $usersConfig->get( 'useractivation' );
		if (isset($_POST['sociallogin_emailclick']) AND $useractivation != '2') {
            $need_verification = true;
		}

		if ($useractivation == '2' OR $need_verification == true) {

		  $userdata ['activation'] = JUtility::getHash(JUserHelper::genRandomPassword());

		  $userdata ['block'] = 1;

		}

		else {

		  $userdata ['activation'] = '';

		  $userdata ['block'] = 0;

		}
		

		if (!$user->bind ($userdata)) {

          JError::raiseWarning ('', JText::_ ('COM_USERS_REGISTRATION_BIND_FAILED'));

          return false;

        }

        

		//Save the user

        if (!$user->save()) {

             JError::raiseWarning ('', JText::_ ('COM_SOCIALLOGIN_REGISTER_FAILED'));

          return false;

        }

		

        $user_id = $user->get ('id');

	   

	    // Saving user extra profile.

	     //plgSystemSocialLoginTools::save_userprofile_data($user_id, $lrdata);

				

				// Trying to insert image.

				$profile_Image = $lrdata['thumbnail'];

				if (empty($profile_Image)) {

				  $profile_Image = JURI::root().'media' . DS . 'com_socialloginandsocialshare' . DS .'images' . DS . 'noimage.png';

				}

		        $userImage = $username . $user_id . '.jpg';

				$sociallogin_savepath = JPATH_ROOT.DS.'images'.DS.'sociallogin'.DS;

				plgSystemSocialLoginTools::insert_user_picture($sociallogin_savepath, $profile_Image, $userImage);

				

				// Remove.

		        $sql = "DELETE FROM #__LoginRadius_users WHERE LoginRadius_id = " . $db->Quote ($lrdata['id']);

		        $db->setQuery ($sql);

		        if ($db->query ()) {

				  

				  //Add new id to db

		          $sql = "INSERT INTO #__LoginRadius_users SET id = " . $db->quote ($user_id) . ",  LoginRadius_id = " . $db->Quote ($lrdata['id']).", provider = " . $db->Quote ($lrdata['Provider']).", lr_picture = " . $db->Quote ($userImage);

                  $db->setQuery ($sql);

	              $db->query();

			    }

         

		  // check for the community builder works.

          $query = "SHOW TABLES LIKE '%__comprofiler'";

          $db->setQuery($query);

          $cbtableexists = $db->loadResult();

          if (isset($cbtableexists)) {

		    plgSystemSocialLoginTools::make_cb_user($user, $profile_Image, $userImage, $lrdata);

          }

		  

		  // check for the k2 works.

          if (JPluginHelper::isEnabled('system', 'k2')) {

		    plgSystemSocialLoginTools::check_exist_comk2($user_id, $username, $profile_Image, $userImage, $lrdata);

		  }

		  

		  // check for the jom social works.

          $query = "SHOW TABLES LIKE '%__community_users'";

          $db->setQuery($query);

          $jomtableexists = $db->loadResult();

          if (isset($jomtableexists)) {

		    plgSystemSocialLoginTools::make_jomsocial_user($user, $profile_Image, $userImage);

          }

	    

		 // Handle account activation/confirmation emails.

		 if ($useractivation == '2' OR $need_verification == true) {
           if($need_verification == true) {
		     $usermessgae = 3;
             $this->_sendMail($user, $usermessgae);
             $mainframe->enqueueMessage(JText::_ ('COM_USERS_REGISTRATION_COMPLETE_ACTIVATE'));
			 $session->clear('tmpuser');
             return false;
		   }
           else {

		   $usermessgae = 1;

		   $this->_sendMail($user, $usermessgae);

		   $mainframe->enqueueMessage(JText::_ ('COM_USERS_REGISTRATION_COMPLETE_VERIFY'));
		   
           $session->clear('tmpuser');
		   return false;

		 }
        }
		 else {

		   $usermessgae = 2;

		   $this->_sendMail($user, $usermessgae);

		 }

	   }

	 } 

	  if ($user_id) {

	    $user =& JUser::getInstance((int)$user_id);

        

		// Register session variables

		$session =& JFactory::getSession();

		$query = "SELECT lr_picture from #__LoginRadius_users WHERE LoginRadius_id=".$db->Quote ($lrdata['id'])." AND id = " . $user->get('id');

        $db->setQuery($query);

        $check_picture = $db->loadResult();

		$session->set('user_picture',$check_picture);

		$session->set('user_lrid',$lrdata['id']);

		$session->set('user',$user);

        

		// Getting the session object

        $table = & JTable::getInstance('session');

        $table->load( $session->getId());

        $table->guest = '0';

        $table->username = $user->get('username');

        $table->userid = intval($user->get('id'));

        $table->usertype = $user->get('usertype');

        $table->gid  = $user->get('gid');

        $table->update();

        $user->setLastVisit();

	    $user =& JFactory::getUser();

		

		//Redirect after Login

	    $redirct = plgSystemSocialLoginTools::getReturnURL();

	    $mainframe->redirect($redirct);
		
		$session->clear('tmpuser');

	  }

    }



/*

 * Function that sends a verification link to exist user.

 */

   function _sendMail(&$user, $usermessgae) {

	 // Compile the notification mail values.

	        $lr_settings = plgSystemSocialLoginTools::sociallogin_getsettings ();

	        $config = JFactory::getConfig();

		    $data = $user->getProperties();

		    $data['fromname'] = $config->get('fromname');

		    $data['mailfrom'] = $config->get('mailfrom');

		    $data['sitename'] = $config->get('sitename');

		    $data['siteurl'] = JUri::base();

			$uri = JURI::getInstance();

			$base = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));

			$data['activate'] = $base.JRoute::_('index.php?option=com_users&task=registration.activate&token='.$data['activation'], false);



			$emailSubject	= JText::sprintf(

				'COM_USERS_EMAIL_ACCOUNT_DETAILS',

				$data['name'],

				$data['sitename']

			);



			if($usermessgae == 1) {

				$emailBody = JText::sprintf('COM_SOCIALLOGIN_EMAIL_REGISTERED_WITH_ADMIN_ACTIVATION_BODY',

				$data['name'],

				$data['sitename'],

				$data['siteurl'].'index.php?option=com_users&task=registration.activate&token='.$data['activation'],

				$data['siteurl'],

				$data['username'],

				$data['password_clear']

			   );

			}

			else if ($usermessgae == 2) {

			   $emailBody = JText::sprintf('COM_SOCIALLOGIN_SEND_MSG',

				$data['name'],

				$data['sitename'],

				$data['siteurl'].'index.php',

				$data['siteurl'],

				$data['username'],

				$data['password_clear']

			    );

			}
			else if ($usermessgae == 3) {
              $emailBody = JText::sprintf('COM_USERS_EMAIL_REGISTERED_WITH_ACTIVATION_BODY',
              $data['name'],
              $data['sitename'],
              $data['siteurl'].'index.php?option=com_users&task=registration.activate&token='.$data['activation'],
              $data['siteurl'],
              $data['username'],
              $data['password_clear']
              );
           }


			// Send the registration email.

			$return = JUtility::sendMail($data['mailfrom'], $data['fromname'], $data['email'], $emailSubject, $emailBody);

			// Check for an error.

		if ($return !== true) {

			$this->setError(JText::_('COM_USERS_REGISTRATION_SEND_MAIL_FAILED'));



			// Send a system message to administrators receiving system mails

			$db = JFactory::getDBO();

			$q = "SELECT id

				FROM #__users

				WHERE block = 0

				AND sendEmail = 1";

			$db->setQuery($q);

			$sendEmail = $db->loadResultArray();

			if (count($sendEmail) > 0) {

				$jdate = new JDate();

				// Build the query to add the messages

				$q = "INSERT INTO `#__messages` (`user_id_from`, `user_id_to`, `date_time`, `subject`, `message`)

					VALUES ";

				$messages = array();

				foreach ($sendEmail as $userid) {

					$messages[] = "(".$userid.", ".$userid.", '".$jdate->toMySQL()."', '".JText::_('COM_USERS_MAIL_SEND_FAILURE_SUBJECT')."', '".JText::sprintf('COM_USERS_MAIL_SEND_FAILURE_BODY', $return, $data['username'])."')";

				}

				$q .= implode(',', $messages);

				$db->setQuery($q);

				$db->query();

			}

			return false;

		  }

		  if ($lr_settings['sendemail'] == 1  && $usermessgae == 2) {

			 $db = JFactory::getDBO();

			 // get all admin users

             $query = 'SELECT name, email, sendEmail' . ' FROM #__users' . ' WHERE sendEmail=1';

             $db->setQuery( $query );

             $rows = $db->loadObjectList();

             // Send mail to all superadministrators id

             foreach( $rows as $row ) {

               JUtility::sendMail($data['mailfrom'], $data['fromname'], $row->email, $emailSubject, JText::sprintf('COM_SOCIALLOGIN_SEND_MSG_ADMIN',

               $row->name,

               $data['sitename'],

               $data['siteurl'],

               $data['email'],

               $data['username'],

               $data['password_clear']

               ));

             }

           }

     }

 }