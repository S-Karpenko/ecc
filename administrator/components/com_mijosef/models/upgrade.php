<?php
/**
* @package		MijoSEF
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die('Restricted Access');

// Imports
jimport('joomla.installer.installer');
jimport('joomla.installer.helper');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

// Model Class
class MijosefModelUpgrade extends MijosefModel {

	// Main constructer
	public function __construct() {
        parent::__construct('upgrade');
    }
    
	// Upgrade
    public function upgrade() {
        $installer = Mijosef::get('installer');

        // Get package
        $type = JRequest::getCmd('type');
        if ($type == 'upload') {
            $userfile = JRequest::getVar('install_package', null, 'files', 'array');
            $package = $installer->getPackageFromUpload($userfile);
        }
        elseif ($type == 'server') {
            $url = self::_getURL();
            $package = $installer->getPackageFromServer($url);
        }

        if (!$package || empty($package['dir'])) {
            $this->setState('message', 'Unable to find install package.');
            return false;
        }

        $file = '';
        if (JFile::exists($package['dir'].'/com_mijosef_j25_basic.zip')) {
            $file = $package['dir'].'/com_mijosef_j25_basic.zip';
        }
        else if (JFile::exists($package['dir'].'/com_mijosef_j25_plus.zip')) {
            $file = $package['dir'].'/com_mijosef_j25_plus.zip';
        }
        else if (JFile::exists($package['dir'].'/com_mijosef_j25_pro.zip')) {
            $file = $package['dir'].'/com_mijosef_j25_pro.zip';
        }
        else if (JFile::exists($package['dir'].'/com_mijosef_j25_vip.zip')) {
            $file = $package['dir'].'/com_mijosef_j25_vip.zip';
        }

        if (!empty($file)) {
            $p1 = $installer->unpack($file);
            $install = new JInstaller();
            $install->install($p1['dir']);
        }
        else {
            $install = new JInstaller();
            $install->install($package['dir']);
        }

        JFolder::delete($package['dir']);

        return true;
    }

    public function _getURL() {
		$pid = $this->MijosefConfig->pid;
		
		$f = 'index.php?option=com_mijoextensions&view=download&model=com_mijosef_basic&free=1';
		$c = 'index.php?option=com_mijoextensions&view=download&model=com_mijosef&pid='.$pid;
		
		if (!empty($pid)){
			$url = $c;
		}
		else {
			$url = $f;
		}
		
		return $url;
	}
}