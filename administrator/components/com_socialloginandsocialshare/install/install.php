<?php
defined ('_JEXEC') or die ('Direct Access to this location is not allowed.');
jimport('joomla.filesystem.folder');
jimport('joomla.installer.installer');

$db = JFactory::getDBO ();
$manifest = $this->manifest;
$src = $this->parent->getPath('source');
$status = new JObject();
$status->modules = array();
$status->plugins = array();
$isUpdate = JFile::exists(JPATH_SITE.DS.'modules'.DS.'mod_socialloginandsocialshare'.DS.'mod_socialloginandsocialshare.php');
// create a folder inside your images folder
JFolder::create(JPATH_ROOT.DS.'images'.DS.'sociallogin');

// Load sociallogin language file
$lang = &JFactory::getLanguage();
$lang->load('com_socialloginandsocialshare', JPATH_SITE);

  if ($manifest instanceof JXMLElement AND property_exists ($manifest, 'modules')) {
    if ($manifest->modules instanceof JXMLElement) {
      foreach ($manifest->modules->children () AS $module) {
	    $mod_data = array ();
		  foreach ($module->attributes () as $key => $value) {
            $mod_data [$key] = strval ($value);
		  }
          $mod_data ['client'] = JApplicationHelper::getClientInfo ($mod_data ['client'], true);
          if(is_null($mod_data ['client']->name)) $client = 'site';
		  $path = $src.DS.$mod_data ['module'];
		  $installer = new JInstaller;
		  $result = $installer->install($path);
		  $status->modules[] = array('name'=>$mod_data ['module'],'client'=>$mod_data ['client']->name, 'result'=>$result);
	  }
	}
	$mod_data ['manifest_cache'] = json_encode (JApplicationHelper::parseXMLInstallFile ((string) $mod_data ['client']->path . DS . 'modules' . DS . $mod_data ['module'] . DS . $mod_data ['module'] . '.xml'));
	if(!$isUpdate) {
	    $query = "UPDATE #__modules SET position='".$mod_data ['position']."', ordering='".$mod_data ['order']."', published=1, access=1 WHERE module='".$mod_data ['module']."'";
		$db->setQuery($query);
		$db->query();
    }
	$query = 'SELECT `id` FROM `#__modules` WHERE module = ' . $db->Quote ($mod_data ['module']);
    $db->setQuery ($query);
    if (!$db->Query ()) {
      $this->parent->abort (JText::_ ('Module') . ' ' . JText::_ ('Install') . ': ' . $db->stderr (true));
      return false;
    }
    $mod_id = $db->loadResult ();
    if ((int) $mod_data ['client']->id == 0) {
      $query = 'REPLACE INTO `#__modules_menu` (moduleid,menuid) values (' . $db->Quote ($mod_id) . ',0)';
      $db->setQuery ($query);
      if (!$db->query ()) {
        // Install failed, roll back changes
        $this->parent->abort (JText::_ ('Module') . ' ' . JText::_ ('Install') . ': ' . $db->stderr (true));
        return false;
      }
	}
  }
  
// Install plugin.
  if ($manifest instanceof JXMLElement AND property_exists ($manifest, 'plugins')) {
    if ($manifest->plugins instanceof JXMLElement) {
      foreach ($manifest->plugins->children () AS $plugin) {
        $plg_data = array ();
        foreach ($plugin->attributes () as $key => $value) {
          $plg_data [$key] = strval ($value);
        }
        $this->parent->setPath ('extension_root', JPATH_ROOT . DS . 'plugins' . DS . $plg_data ['group'] . DS . $plg_data ['plugin']);
        $created = false;
        if (!file_exists ($this->parent->getPath ('extension_root'))) {
          if (!$created = JFolder::create ($this->parent->getPath ('extension_root'))) {
            $this->parent->abort (JText::_ ('Plugin') . ' ' . JText::_ ('Install') . ': ' . JText::_ ('COM_SOCIALLOGIN_INSTALLATION_MODULE_PLG_ERROR') . ': "' . $this->parent->getPath ('extension_root') . '"');
            return false;
          }
        }
        if ($created) {
          $this->parent->pushStep (array (
					'type' => 'folder',
					'path' => $this->parent->getPath ('extension_root')
				));
        }
        if ($this->parent->parseFiles ($plugin->files, -1) === false) {
          $this->parent->abort ();
          return false;
        }
        $plg_data ['manifest_cache'] = json_encode (JApplicationHelper::parseXMLInstallFile (JPATH_ROOT . DS . 'plugins' . DS . $plg_data ['group'] . DS . $plg_data ['plugin'] . DS . $plg_data ['plugin'] . '.xml'));
        $query = 'SELECT `extension_id` FROM `#__extensions` WHERE folder = ' . $db->Quote ($plg_data ['group']) . ' AND type=\'plugin\' AND element = ' . $db->Quote ($plg_data ['plugin']);
        $db->setQuery ($query);
        if (!$db->Query ()) {
          $this->parent->abort (JText::_ ('Plugin') . ' ' . JText::_ ('Install') . ': ' . $db->stderr (true));
          return false;
        }
        $plugin_id = $db->loadResult ();
        if (empty ($plugin_id)) {
          $plugin_data = array ();
          $plugin_data ['name'] = $plg_data ['title'];
          $plugin_data ['type'] = 'plugin';
          $plugin_data ['element'] = $plg_data ['plugin'];
          $plugin_data ['folder'] = $plg_data ['group'];
          $plugin_data ['client_id'] = 0;
          $plugin_data ['enabled'] = 1;
          $plugin_data ['access'] = 1;
          $plugin_data ['protected'] = 0;
          $plugin_data ['manifest_cache'] = $plg_data ['manifest_cache'];
          $plugin_data ['params'] = '{}';
          $table = JTable::getInstance ('extension');
          if (!$table->bind ($plugin_data)) {
            $this->parent->abort (JText::_ ('Plugin') . ' ' . JText::_ ('Install') . ': ' . JText::_ ('table->bind throws error'));
            return false;
          }
          if (!$table->check ($plugin_data)) {
           $this->parent->abort (JText::_ ('Plugin') . ' ' . JText::_ ('Install') . ': ' . JText::_ ('table->check throws error'));
			return false;
		  }
          if (!$table->store ($plugin_data)) {
            $this->parent->abort (JText::_ ('Plugin') . ' ' . JText::_ ('Install') . ': ' . JText::_ ('table->store throws error'));
            return false;
          }
          $this->parent->pushStep (array (
					'type' => 'extension',
					'extension_id' => $table->extension_id
				));
        }
         // Plugin Installed
			$status->plugins[] = array('name'=>$plg_data ['plugin'],'group'=>$plg_data ['group']);      
	  }
    }
  }

// Install jomfish language filter.

if (JFolder::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'contentelements')){
     $elements = &$this->manifest->xpath('joomfish/file');
	 foreach ($elements as $element) {
		JFile::copy($src.DS.'admin'. DS . 'com_joomfish'.DS.'contentelements'.DS.$element->data(),JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomfish'.DS.'contentelements'.DS.$element->data());
	 }
} 
else {
	$mainframe = &JFactory::getApplication();
	$mainframe->enqueueMessage(JText::_('COM_SOCIALLOGIN_JOMFISH_ERROR'));
}

// Installation completed.
if (count($status->modules) AND count($status->plugins)) {?>
  <h2>Social Login Installation</h2>
<table class="adminlist">
	<thead>
		<tr>
			<th class="title" colspan="2"><?php echo JText::_('Extension'); ?></th>
			<th><?php echo JText::_('Status'); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="3"></td>
		</tr>
	</tfoot>
	<tbody>
	    <tr>
			<th><?php echo JText::_('Component'); ?></th>
			<th></th>
			<th></th>
		</tr>
		<tr class="row0">
			<td class="key" colspan="2"><?php echo 'Social Login And Social Share '.JText::_('Component'); ?></td>
			<td style="color:#6c9c31;"><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>

<?php if (count($status->modules)) : ?>
		<tr>
			<th><?php echo JText::_('Module'); ?></th>
			<th><?php echo JText::_('Client'); ?></th>
			<th></th>
		</tr>
	<?php foreach ($status->modules as $module) : ?>
		<tr class="row<?php echo (++ $rows % 2); ?>">
			<td class="key"><?php echo $module['name']; ?></td>
			<td class="key"><?php echo ucfirst($module['client']); ?></td>
			<td style="color:#6c9c31;"><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
	<?php endforeach;
	endif;
if (count($status->plugins)) : ?>
		<tr>
			<th><?php echo JText::_('Plugin'); ?></th>
			<th><?php echo JText::_('Group'); ?></th>
			<th></th>
		</tr>
	<?php foreach ($status->plugins as $plugin) : ?>
		<tr class="row<?php echo (++ $rows % 2); ?>">
			<td class="key"><?php echo ucfirst($plugin['name']); ?></td>
			<td class="key"><?php echo ucfirst($plugin['group']); ?></td>
			<td style="color:#6c9c31;"><strong><?php echo JText::_('Installed'); ?></strong></td>
		</tr>
	<?php endforeach;
endif; ?>
	</tbody>
</table>

	<h2><?php echo JText::_ ('COM_SOCIALLOGIN_INSTALLATION_STATUS'); ?></h2>
	<p class="nowarning">
		<?php echo JText::_ ('COM_SOCIALLOGIN_INSTALLATION_THANK'); ?> <strong>Social Login</strong>!
		<?php echo JText::_ ('COM_SOCIALLOGIN_INSTALLATION_CONFIG'); ?> <a href="index.php?option=com_socialloginandsocialshare">social login <?php echo JText::_ ('COM_SOCIALLOGIN_INSTALLATION_COM'); ?></a>
		<?php echo JText::_ ('COM_SOCIALLOGIN_INSTALLATION_FREE'); ?> <a href="https://www.loginradius.com/loginradius/contact" target="_blank"><?php echo JText::_ ('COM_SOCIALLOGIN_INSTALLATION_CONTACT'); ?></a> <?php echo JText::_ ('COM_SOCIALLOGIN_INSTALLATION_ASSIST'); ?>
		<strong><?php echo JText::_ ('COM_SOCIALLOGIN_INSTALLATION_THANKYOU'); ?></strong>
		</p>
<?php }