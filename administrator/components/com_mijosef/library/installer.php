<?php
/**
* @package		MijoSEF
* @copyright	2009-2012 Mijosoft LLC, www.mijosoft.com
* @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No Permission
defined('_JEXEC') or die('Restricted access');

// Imports
jimport('joomla.installer.helper');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.archive');
jimport('joomla.filesystem.path');

class MijosefInstaller {

    public function __construct() {
		$this->MijosefConfig = Mijosef::getConfig();
	}

    public function getPackageFromUpload($userfile) {
		// Make sure that file uploads are enabled in php
		if (!(bool) ini_get('file_uploads')) {
			JError::raiseWarning(100, JText::_('WARNINSTALLFILE'));
			return false;
		}

		// Make sure that zlib is loaded so that the package can be unpacked
		if (!extension_loaded('zlib')) {
			JError::raiseWarning(100, JText::_('WARNINSTALLZLIB'));
			return false;
		}

		// If there is no uploaded file, we have a problem...
		if (!is_array($userfile) ) {
			JError::raiseWarning(100, JText::_('No file selected'));
			return false;
		}

		// Check if there was a problem uploading the file.
		if ( $userfile['error'] || $userfile['size'] < 1 ) {
			JError::raiseWarning(100, JText::_('WARNINSTALLUPLOADERROR'));
			return false;
		}

		// Build the appropriate paths
		$JoomlaConfig =& JFactory::getConfig();
		$tmp_dest = $JoomlaConfig->get('tmp_path').'/'.$userfile['name'];
		$tmp_src  = $userfile['tmp_name'];

		// Move uploaded file
		jimport('joomla.filesystem.file');
		$uploaded = JFile::upload($tmp_src, $tmp_dest);
		
		if (!$uploaded) {
			JError::raiseWarning('SOME_ERROR_CODE', '<br /><br />' . JText::_('File not uploaded, please, make sure that your MijoSEF=>Configuraiton=>Personal ID and/or the "Global Configuration=>Server=>Path to Temp-folder" field has a valid value.') . '<br /><br /><br />');
			return false;
		}

		// Unpack the downloaded package file
		$package = self::unpack($tmp_dest);

		// Delete the package file
		JFile::delete($tmp_dest);

		return $package;
    }

    public function getPackageFromServer($url) {
		// Make sure that file uploads are enabled in php
		if (!(bool) ini_get('file_uploads')) {
			JError::raiseWarning('1001', JText::_('COM_MIJOSEF_EXTENSIONS_VIEW_INSTALL_PHP_SETTINGS'));
			return false;
		}

		// Make sure that zlib is loaded so that the package can be unpacked
		if (!extension_loaded('zlib')) {
			JError::raiseWarning('1001', JText::_('COM_MIJOSEF_EXTENSIONS_VIEW_INSTALL_PHP_ZLIB'));
			return false;
		}
		
		// Get temp path
		$JoomlaConfig =& JFactory::getConfig();
		$tmp_dest = $JoomlaConfig->get('tmp_path');

		$url = str_replace('http://www.mijosoft.com/', '', $url);
		$url = str_replace('https://www.mijosoft.com/', '', $url);
		$url = 'http://mijosoft.com/'.$url;
		
		// Grab the package
		$data = Mijosef::get('utility')->getRemoteData($url);
		
		$target = $tmp_dest.'/mijosef_upgrade.zip';
		
		// Write buffer to file
		$written = JFile::write($target, $data);
		
		if (!$written) {
			JError::raiseWarning('SOME_ERROR_CODE', '<br /><br />' . JText::_('File not uploaded, please, make sure that your "MijoSEF => Configuration => Personal ID" and/or the "Global Configuration=>Server=>Path to Temp-folder" field has a valid value.') . '<br /><br /><br />');
			return false;
		}
		
		$p_file = basename($target);
		
		// Was the package downloaded?
		if (!$p_file) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('Invalid Personal ID'));
			return false;
		}

		// Unpack the downloaded package file
		$package = self::unpack($tmp_dest.'/'.$p_file);
		
		if (!$package) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('An error occured, please, make sure that your "MijoSEF => Configuration => Personal ID" and/or the "Global Configuration=>Server=>Path to Temp-folder" field has a valid value.'));
			return false;
		}
		
		// Delete the package file
		JFile::delete($tmp_dest.DS.$p_file);
		
		return $package;
	}

    public function unpack($p_filename) {
        // Path to the archive
        $archivename = $p_filename;

        // Temporary folder to extract the archive into
        $tmpdir = uniqid('install_');

        // Clean the paths to use for archive extraction
        $extractdir = JPath::clean(dirname($p_filename).'/'.$tmpdir);
        $archivename = JPath::clean($archivename);

        $package = array();
        $package['dir'] = $extractdir;

        // do the unpacking of the archive
        $package['res'] = JArchive::extract($archivename, $extractdir);

        return $package;
    }
}