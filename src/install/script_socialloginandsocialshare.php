<?php
/**
 * @package     SocialLoginandSocialShare.Plugin
 * @subpackage  com_socialloginandsocialshare
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
jimport('joomla.filesystem.folder');
jimport('joomla.installer.installer');
if (!defined('DS'))
{
    define('DS', DIRECTORY_SEPARATOR);
}

/**
 * Script file of socialloginandsocialshare component
 */
class Com_SocialLoginAndSocialShareInstallerScript
{

    /**
     * Postflight is executed after the Joomla install, update or discover_update actions have completed
     * 
     * @param $type
     * @param $parent
     */
    public function postflight($type, $parent)
    {
        
    }

    /**
     * Install is executed after the Joomla install database scripts have completed.
     * 
     * @param $parent
     * @return bool
     */
    function install($parent)
    {
        $status = $this->installationScript($parent, 'install');
        $this->installationResults($status);
    }

    /**
     * Install is executed into the Joomla install function
     * 
     * @param $parent
     * @param $action
     * @return bool|stdClass
     */
    function installationScript($parent, $action)
    {
        $status = new stdClass;
        $status->modules = array();
        $status->plugins = array();
        $db = JFactory::getDBO();
        $src = $parent->getParent()->getPath('source');
        $manifest = $parent->getParent()->manifest;
        $isUpdate = JFile::exists(JPATH_SITE . DS . 'modules' . DS . 'mod_socialloginandsocialshare' . DS . 'mod_socialloginandsocialshare.php');
        // create a folder inside your images folder
        JFolder::create(JPATH_ROOT . DS . 'images' . DS . 'sociallogin');

        // Load sociallogin language file
        $lang = JFactory::getLanguage();
        $lang->load('com_socialloginandsocialshare', JPATH_SITE);

        // Installing modules.
        $modules = $manifest->xpath('modules/module');

        foreach ($modules AS $module)
        {
            $moduleData = array();

            foreach ($module->attributes() as $key => $value)
            {
                $moduleData [$key] = strval($value);
            }

            $moduleData ['client'] = JApplicationHelper::getClientInfo($moduleData ['client'], true);

            $path = $src . DS . $moduleData ['module'];
            $installer = new JInstaller;
            $result = $installer->$action($path);

            if ($result)
            {
                $status->modules[] = array('name' => $moduleData ['module'], 'client' => $moduleData ['client']->name, 'result' => $result);
            }
        }

        if (!$isUpdate)
        {
            $query = "UPDATE #__modules SET title = " . $db->Quote($moduleData ['title']) . ", position=" . $db->Quote($moduleData ['position']) . ", ordering=" . $db->Quote($moduleData ['order']) . ", published = 1, access=1 WHERE module=" . $db->Quote($moduleData ['module']);
            $db->setQuery($query);
            $db->execute();
        }

        $query = 'SELECT `id` FROM `#__modules` WHERE module = ' . $db->Quote($moduleData ['module']);
        $db->setQuery($query);

        if (!$db->execute())
        {
            $parent->getParent()->abort(JText::_('Module') . ' ' . JText::_('Install') . ': ' . $db->stderr(true));
            return false;
        }

        $moduleId = $db->loadResult();

        if ((int) $moduleData ['client']->id == 0)
        {
            $query = 'REPLACE INTO `#__modules_menu` (moduleid,menuid) values (' . $db->Quote($moduleId) . ',0)';
            $db->setQuery($query);

            if (!$db->execute())
            {
                // Install failed, roll back changes
                $parent->getParent()->abort(JText::_('Module') . ' ' . JText::_('Install') . ': ' . $db->stderr(true));
                return false;
            }
        }

        // Installing plugins.
        $plugins = $manifest->xpath('plugins/plugin');

        foreach ($plugins AS $plugin)
        {
            $pluginData = array();

            foreach ($plugin->attributes() as $key => $value)
            {
                $pluginData [$key] = strval($value);
            }

            $path = $src . DS . 'plg_' . $pluginData['plugin'];
            $installer = new JInstaller;
            $result = $installer->$action($path);

            if ($result)
            {
                $query = "UPDATE #__extensions SET enabled=1 WHERE type='plugin' AND element=" . $db->Quote($pluginData ['plugin']) . " AND folder=" . $db->Quote($pluginData ['group']);
                $db->setQuery($query);
                $db->execute();
            }

            // Plugin Installed
            $status->plugins[] = array('name' => $pluginData ['plugin'], 'group' => $pluginData ['group']);
            if ($action == 'update')
            {
                $this->updatePlugin($parent, $pluginData);
            }
        }
        return $status;
    }

    /**
     * Install is executed update the Joomla plugin.
     * 
     * @param $parent
     * @param $pluginData
     * @return bool
     */
    function updatePlugin($parent, $pluginData)
    {
        $db = JFactory::getDBO();

        $query = "SELECT `extension_id` FROM `#__extensions` WHERE type='plugin' AND element=" . $db->Quote($pluginData ['plugin']) . " AND folder=" . $db->Quote($pluginData ['group']);
        $db->setQuery($query);

        if (!$db->execute())
        {
            $parent->getParent()->abort(JText::_('Plugin') . ' ' . JText::_('Update') . ': ' . $db->stderr(true));
            return false;
        }
    }

    /**
     * Display HTML after successfully installation
     * 
     * @param $status
     */
    private function installationResults($status)
    {
        $rows = 0;
        if (count($status->modules) AND count($status->plugins))
        {
            ?>
            <h2>Social Login and Social Share Installation</h2>
            <table class="adminlist table table-striped">
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
                        <td class="key"
                            colspan="2"><?php echo 'Social Login and Social Share ' . JText::_('Component'); ?></td>
                        <td style="color:#6c9c31;"><strong><?php echo JText::_('Installed'); ?></strong></td>
                    </tr>

                    <?php if (count($status->modules))
                    { ?>
                        <tr>
                            <th><?php echo JText::_('Module'); ?></th>
                            <th><?php echo JText::_('Client'); ?></th>
                            <th></th>
                        </tr>
                        <?php foreach ($status->modules as $module)
                        { ?>
                            <tr class="row<?php echo( ++$rows % 2); ?>">
                                <td class="key"><?php echo $module['name']; ?></td>
                                <td class="key"><?php echo ucfirst($module['client']); ?></td>
                                <td style="color:#6c9c31;"><strong><?php echo JText::_('Installed'); ?></strong></td>
                            </tr>
                            <?php
                        }
                    }
                    if (count($status->plugins))
                    {
                        ?>
                        <tr>
                            <th><?php echo JText::_('Plugin'); ?></th>
                            <th><?php echo JText::_('Group'); ?></th>
                            <th></th>
                        </tr>
                <?php foreach ($status->plugins as $plugin)
                { ?>
                            <tr class="row<?php echo( ++$rows % 2); ?>">
                                <td class="key"><?php echo ucfirst($plugin['name']); ?></td>
                                <td class="key"><?php echo ucfirst($plugin['group']); ?></td>
                                <td style="color:#6c9c31;"><strong><?php echo JText::_('Installed'); ?></strong></td>
                            </tr>
                    <?php
                }
            }
            ?>
                </tbody>
            </table>

            <h2><?php echo JText::_('COM_SOCIALLOGIN_INSTALLATION_STATUS'); ?></h2>
            <p class="nowarning">
            <?php echo JText::_('COM_SOCIALLOGIN_INSTALLATION_THANK'); ?> <strong>Social Login</strong>!
            <?php echo JText::_('COM_SOCIALLOGIN_INSTALLATION_CONFIG'); ?>
                <a href="index.php?option=com_socialloginandsocialshare">
                    social login <?php echo JText::_('COM_SOCIALLOGIN_INSTALLATION_COM'); ?>
                </a>
            <?php echo JText::_('COM_SOCIALLOGIN_INSTALLATION_FREE'); ?>
                <a href="http://ish.re/CBXX" target="_blank">
            <?php echo JText::_('COM_SOCIALLOGIN_INSTALLATION_CONTACT'); ?>
                </a> <?php echo JText::_('COM_SOCIALLOGIN_INSTALLATION_ASSIST'); ?>
                <strong><?php echo JText::_('COM_SOCIALLOGIN_INSTALLATION_THANKYOU'); ?></strong>
            </p>
            <?php
        }
    }

    /**
     * The uninstall method is executed before any Joomla uninstall action, such as file removal or database changes. Uninstall cannot cause an abort of the Joomla uninstall action, so returning false would be a waste of time.
     * 
     * @param $parent
     */
    public function uninstall($parent)
    {
        $db = JFactory::getDBO();
        $status = new stdClass;
        $status->modules = array();
        $status->plugins = array();
        $manifest = $parent->getParent()->manifest;
        $plugins = $manifest->xpath('plugins/plugin');

        foreach ($plugins as $plugin)
        {
            $name = (string) $plugin->attributes()->plugin;
            $group = (string) $plugin->attributes()->group;
            $query = "SELECT `extension_id` FROM #__extensions WHERE `type`='plugin' AND element = " . $db->Quote($name) . " AND folder = " . $db->Quote($group);
            $db->setQuery($query);
            $extensions = $db->loadColumn();

            if (count($extensions))
            {
                foreach ($extensions as $id)
                {
                    $installer = new JInstaller;
                    $result = $installer->uninstall('plugin', $id);
                }
                $status->plugins[] = array('name' => $name, 'group' => $group, 'result' => $result);
            }
        }

        $modules = $manifest->xpath('modules/module');

        foreach ($modules as $module)
        {
            $name = (string) $module->attributes()->module;
            $client = (string) $module->attributes()->client;
            $db = JFactory::getDBO();
            $query = "SELECT `extension_id` FROM `#__extensions` WHERE `type`='module' AND element = " . $db->Quote($name);
            $db->setQuery($query);
            $extensions = $db->loadColumn();

            if (count($extensions))
            {

                foreach ($extensions as $id)
                {
                    $installer = new JInstaller;
                    $result = $installer->uninstall('module', $id);
                }

                $status->modules[] = array('name' => $name, 'client' => $client, 'result' => $result);
            }
        }
    }

    /**
     * update is executed update the Joomla Extension.
     * 
     * @param $parent
     * @return bool
     */
    public function update($parent)
    {
        $status = $this->installationScript($parent, 'update');
        $this->installationResults($status);
    }

}
