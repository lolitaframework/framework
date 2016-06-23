<?php
namespace franken\LolitaFramework\Configuration;

use \franken\LolitaFramework\Core\GlobalLocator as GlobalLocator;
use \franken\LolitaFramework\Core\HelperClass as HelperClass;

class Configuration
{
    /**
     * Configuration file extension
     */
    const CONFIG_EXTENSION = '.json';

    /**
     * Default module priority
     */
    const DEFAULT_PRIORITY = 100;

    /**
     * Settings path
     *
     * @var null
     */
    private $settings_path = null;

    /**
     * All loaded configuration modules
     *
     * @var null
     */
    private $loaded_modules = null;

    /**
     * All prepared modules
     * @var null
     */
    private $prepared_modules = array();

    public function __construct()
    {
        $this->settings_path = $this->getDefaultSettingsPath();
        $this->prepareModules()->load();
    }

    /**
     * Prepare our configuration modules
     * @return [Configuration] $this object.
     */
    public function prepareModules()
    {
        $modules = $this->getAllConfigurationModules();
        foreach ($modules as $module) {
            $class_name  = $this->getClassName($module);
            $config_path = $this->getConfigPath($module);
            $data        = $this->getConfigData($config_path);

            if (HelperClass::isImplements($class_name, __NAMESPACE__ . NS . 'IModule')) {
                $this->prepared_modules[$class_name::getPriority()][] = array(
                    'priority'    => $class_name::getPriority(),
                    'class_name'  => $class_name,
                    'config_path' => $config_path,
                    'data'        => $data,
                );
            }
        }
        ksort($this->prepared_modules);
        return $this;
    }

    /**
     * Load all modules with config files
     *
     * @return [Configuration] $this object.
     */
    public function load()
    {
        foreach ($this->prepared_modules as $priority => $modules) {
            if (is_array($modules)) {
                foreach ($modules as $module) {
                    $class_name  = $module['class_name'];
                    $data        = $module['data'];

                    $this->loaded_modules[$class_name] = new $class_name($data);
                }
            }
        }
        return $this;
    }

    /**
     * Get class name from module path
     *
     * @param  [type] $module file path.
     * @return class name.
     */
    private function getClassName($module)
    {
        return __NAMESPACE__ . NS . 'Modules' . NS . str_replace('.php', '', basename($module));
    }

    /**
     * Get config path
     *
     * @param  [type] $class name.
     * @return config path
     */
    private function getConfigPath($module)
    {
        $module = strtolower($module);
        return $this->settings_path . str_replace('.php', self::CONFIG_EXTENSION, basename($module));
    }

    /**
     * Get config data from file
     *
     * @param  [type] $config_path config file path.
     * @return config file data.
     */
    private function getConfigData($config_path)
    {
        $filesystem  = GlobalLocator::wpFilesystem();
        $data        = null;
        if (is_file($config_path)) {
            $data = $filesystem->get_contents($config_path);
            $data = json_decode($data, true);
        } else {
            $data = false;
        }
        return $data;
    }

    /**
     * Set new settings path
     *
     * @param [string] $path settings.
     * @return [Configuration] instance.
     */
    public function setSettingsPath($path)
    {
        $this->settings_path = $path;
        return $this;
    }

    /**
     * Get default settings path
     *
     * @return default settings path.
     */
    private function getDefaultSettingsPath()
    {
        return dirname(LF_DIR).'/app'.DS.'config'.DS;
    }

    /**
     * Get all configuration modules
     *
     * @return array configuration modules.
     */
    private function getAllConfigurationModules()
    {
        $modules_path = dirname(__FILE__) . DS . 'Modules';
        $pattern      = $modules_path.DS.'*.php';
        return (array) glob($pattern);
    }

    /**
     * Get configuration folder
     * @return [string] folder.
     */
    public static function getFolder()
    {
        return dirname(__FILE__);
    }
}
