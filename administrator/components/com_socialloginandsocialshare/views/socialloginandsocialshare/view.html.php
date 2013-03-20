<?php
defined ('_JEXEC') or die ('Restricted access');
jimport ('joomla.application.component.view');

/**
 * Class generate view.
 */
class SocialLoginAndSocialShareViewSocialLoginAndSocialShare extends JView
{
	public $settings;
	
	/**
	 * SocialLogin - Display administration area
	 */
	public function display ($tpl = null)
	{
		$document = &JFactory::getDocument ();
		$document->addStyleSheet ('components/com_socialloginandsocialshare/assets/css/socialloginandsocialshare.css');
		
		$model = &$this->getModel ();
		$this->settings = $model->getSettings ();
     	$this->form = $this->get ('Form');
		$this->addToolbar ();
        parent::display ($tpl);
	}

	
	/**
	 * SocialLogin - Add admin option on toolbar
	 */
	protected function addToolbar ()
	{
		JRequest::setVar ('hidemainmenu', false);
		JToolBarHelper::title (JText::_ ('Social Login And Social Share Configuration'), 'configuration.gif');
		JToolBarHelper::apply ('apply');
		JToolBarHelper::save($task = 'save', $alt = 'JTOOLBAR_SAVE');
		JToolBarHelper::cancel ('cancel');
	}
}