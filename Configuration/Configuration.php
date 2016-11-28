<?php
namespace lolita\LolitaFramework\Configuration;

use \lolita\LolitaFramework\Core\Loc;
use \lolita\LolitaFramework\Core\Cls;
use \lolita\LolitaFramework;
use \Exception;

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

    /**
     * Configureation class constructor
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     */
    public function __construct()
    {
        $this->settings_path = $this->getDefaultSettingsPath();
        $this->prepareModules()->load();
    }

    /**
     * Prepare our configuration modules
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return [Configuration] $this object.
     */
    public function prepareModules()
    {
        $modules = $this->getAllConfigurationModules();
        foreach ($modules as $module) {
            $class_name  = $this->getClassName($module);
            $config_path = $this->getConfigPath($module);
            $data        = $this->getConfigData($config_path);

            if (false !== $data) {
                if (Cls::isImplements($class_name, __NAMESPACE__ . NS . 'IModule')) {
                    $this->prepared_modules[$class_name::getPriority()][] = array(
                        'priority'    => $class_name::getPriority(),
                        'class_name'  => $class_name,
                        'config_path' => $config_path,
                        'data'        => $data,
                    );
                }
            }
        }
        ksort($this->prepared_modules);
        return $this;
    }

    /**
     * Load all modules with config files
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
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
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  string $module file path.
     * @return class name.
     */
    private function getClassName($module)
    {
        return __NAMESPACE__ . NS . 'Modules' . NS . str_replace('.php', '', basename($module));
    }

    /**
     * Get config path
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  string $class name.
     * @return config path
     */
    private function getConfigPath($module)
    {
        $module = strtolower($module);
        $path   = apply_filters(
            sprintf('lf_config_%s_path', str_replace('.php', '', basename($module))),
            $this->settings_path . str_replace('.php', self::CONFIG_EXTENSION, basename($module))
        );
        return $path;
    }

    /**
     * Get config data from file
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @param  string $config_path config file path.
     * @return config file data.
     */
    private function getConfigData($config_path)
    {
        $filesystem  = Loc::wpFilesystem();
        $data        = null;
        if (is_file($config_path)) {
            $data = $filesystem->get_contents($config_path);
            $data = json_decode($data, true);
            if (null === $data || false === $data) {
                throw new Exception('JSON can be converted to Array:' . $config_path, 1);
            }
        } else {
            $data = false;
        }
        return $data;
    }

    /**
     * Set new settings path
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
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
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return default settings path.
     */
    private function getDefaultSettingsPath()
    {
        return apply_filters(
            'lf_configuration_settings_path',
            Loc::lolita()->baseDir() . '/app' . DS . 'config' . DS
        );
    }

    /**
     * Get all configuration modules
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
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
     *
     * @author Guriev Eugen <gurievcreative@gmail.com>
     * @return [string] folder.
     */
    public static function getFolder()
    {
        return dirname(__FILE__);
    }
}
