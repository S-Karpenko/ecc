<?php
/**
* @version		1.0.0
* @package		MijoSEF
* @subpackage	MijoSEF
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
* @license		GNU/GPL based on AceSEF www.joomace.net
*/

// No permission
defined('_JEXEC') or die('Restricted Access');

// Controller Class
class MijosefControllerSupport extends MijosefController {

	// Main constructer
    function __construct() {
        parent::__construct('support');
    }
	
	// Support page
    function support() {
        $view = $this->getView(ucfirst($this->_context), 'html');
        $view->setLayout('support');
        $view->display();
    }
    
	// Translators page
    function translators() {
        $view = $this->getView(ucfirst($this->_context), 'html');
        $view->setLayout('translators');
        $view->display();
    }
}