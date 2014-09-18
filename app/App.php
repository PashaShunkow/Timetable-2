<?php

class App {

    const S_CONFIG = 'Config';
    const S_VMCONNECTOR = 'VMConnector';
    const S_ROUTER = 'Router';

    /**
     * Application instance
     *
     * @var App
     */
    static private $_app;

    /**
     * Request data array
     *
     * @var array
     */
    protected $_requestData;

    /**
     * System helper
     *
     * @var System\Helper
     */
    protected $_systemHelper;

    /**
     * Array of system services
     *
     * @var array
     */
    protected $_services = array(
        self::S_CONFIG => null,
        self::S_ROUTER => null
        //'VMConnector' => null,
    );


    /**
     * Class constructor
     */
    protected function __construct(){}

    /**
     * Runs the application
     */
    static public function run(){
        if (!self::$_app) {
            self::$_app = new self();
        }
        self::$_app->_init();
        $conf = self::$_app->getService(self::S_CONFIG);
        $r=0;
        //self::$_app->getService('Router')->encodeRequest(self::$_app);
//        self::$_app->getService('VMConnector')->processRequest();
//        self::$_app->getService('VMConnector')->renderHtml();
    }

    /**
     * Init the application
     */
    protected function _init(){
        $this->_registerAutoloader();
        $this->_initSystemHelper();
        $this->_initConfigs();
        $this->_initServices();
    }

    /**
     * Register system autoloader
     */
    protected function _registerAutoloader(){
        require_once 'code' . DIRECTORY_SEPARATOR . 'System' . DIRECTORY_SEPARATOR . 'Libs' . DIRECTORY_SEPARATOR . 'Autoloader.php';
        spl_autoload_register('Autoloader::autoload');
    }

    /**
     * init system helper object
     */
    protected function _initSystemHelper()
    {
        if (!$this->_systemHelper) {
            $this->_systemHelper = new System\Helper();
        }
    }

    /**
     * Return system helper
     *
     * @return \System\Helper
     */
    protected function _getSystemHelper(){
        return $this->_systemHelper;
    }

    /**
     * Init config service
     */
    protected function _initConfigs()
    {
        $this->_services[self::S_CONFIG] = new System\Services\Config($this->_systemHelper, array());
    }

    /**
     * Init system services
     */
    protected function _initServices(){
        foreach ($this->_services as $name => $service) {
            if ($service == null) {
                $class = 'System\\Services\\' . $name;
                $service = new $class($this->_getSystemHelper(), $this->getService('Config')->getConfig($name));
                $this->_services[$name] = $service;
            }
        }
    }

    /**
     * Return system service
     *
     * @param string $serviceName Service name
     * @return mixed
     */
    public function getService($serviceName){
        if(isset($this->_services[$serviceName]) && $this->_services[$serviceName]){
            return $this->_services[$serviceName];
        }else{
            die('Cant provide service ' . $serviceName);
        }
    }
}