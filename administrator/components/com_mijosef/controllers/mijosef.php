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

// Control Panel Controller Class
class MijosefControllerMijosef extends MijosefController {
	
	// Main constructer
    function __construct() {
        parent::__construct('mijosef');
    }
	
	function sefStatus() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		$model = $this->getModel('Mijosef');
		$msg = $model->sefStatus();
        
        $this->setRedirect('index.php?option=com_mijosef', $msg);
    }
	
	function saveDownloadID() {
		// Check token
		JRequest::checkToken() or jexit('Invalid Token');
		
		$model = $this->getModel('Mijosef');
		$msg = $model->saveDownloadID();
        
        $this->setRedirect('index.php?option=com_mijosef', $msg);
    }
}
?>