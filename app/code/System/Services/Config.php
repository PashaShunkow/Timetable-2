<?php
/**
 * System config service
 *
 * @category  TimetableTool
 * @package   TimetableTool_System
 * @author    Paul Shunkow
 * @copyright 2014 Paul Shunkow
 */
namespace System\Services;

use System\Abs\Service as Service;

class Config extends Service
{
    protected $_configs = array();

    /**
     * Return part of configs array if exist
     *
     * @param string $area Key in configs array
     * @return array
     */
    public function getConfig($area)
    {
        $configs = array();
        if (isset($this->_configs[$area])) {
            $configs = $this->_configs[$area];
        }
        return $configs;
    }

    /**
     * Init the config object
     */
    protected function _init()
    {
        $this->_parseRouterConfigs();
        $this->_parseEntityConfig();
    }

    /**
     * Return entities configs
     *
     * @return void
     */
    protected function _parseEntityConfig()
    {
        $_entityConfigs = $this->_parseConfig('entities');
        $this->_configs['Entities'] = $_entityConfigs;
    }

    /**
     * Return routes configs
     *
     * @return void
     */
    protected function _parseRouterConfigs()
    {
        $_routesConfigs = $this->_parseConfig('routes');
        $this->_configs['Router'] = $_routesConfigs;
    }

    /**
     * Parse configs from specified file
     *
     * @param string $configFile Config file name
     * @return array
     * @throws \Exception
     */
    protected function _parseConfig($configFile)
    {
        $_configFile = $this->SH()->getDirAbsolutePath(array('app', 'etc')) . $configFile . '.json';
        if (!is_file($_configFile)) {
            throw new \Exception('Cant config file! Should be in : ' . $_configFile);
        }
        $_configs = $this->SH()->JSONDecode(file_get_contents($_configFile), true);
        if (empty($_configs)) {
            throw new \Exception('Config is empty or unreadable config file path: ' . $configFile);
        }
        return $_configs;
    }
}