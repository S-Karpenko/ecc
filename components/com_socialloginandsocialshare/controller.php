<?php
/**
 * @package   	SocialLogin
 * @copyright 	Copyright 2012 http://www.loginradius.com - All rights reserved.
 * @license   	GNU/GPL 2 or later
 */
defined ('_JEXEC') or die ('Direct Access to this location is not allowed.');
jimport ('joomla.application.component.controller');

/**
 * General Controller of SocialLoginAndSocialShare component
 */
 
class SocialLoginAndSocialShareController extends JController
{
	public function display($cachable = false, $urlparams = false)
	{
		// Get the document object.
		$document	= JFactory::getDocument();

		// Set the default view name and format from the Request.
		$vName	 = JRequest::getCmd('view', 'profile');
		$vFormat = $document->getType();
		$lName	 = JRequest::getCmd('layout', 'default');

		if ($view = $this->getView($vName, $vFormat)) {
			// Do any specific processing by view.
			switch ($vName) {
				

				// Handle view specific models.
				case 'profile':

					// If the user is a guest, redirect to the login page.
					$user = JFactory::getUser();
					if ($user->get('guest') == 1) {
						// Redirect to login page.
						$this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));
						return;
					}
					$model = $this->getModel($vName);
					break;

				
			}

			// Push the model into the view (as default).
			$view->setModel($model, true);
			$view->setLayout($lName);

			// Push document object into the view.
			$view->assignRef('document', $document);

			$view->display();
		}
	}
	

}
