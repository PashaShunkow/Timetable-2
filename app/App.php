<?php

class App {
    static private $_app;

    static private $_appRoot;

    protected $_requestData;

    protected $_services = array(
        'Router' => null,
        'VMConnector' => null,
    );

    static public function run(){
        if (!self::$_app) {
            self::$_app = new self();
        }
        self::$_app->_init();
        self::$_app->getService('Router')->encodeRequest(self::$_app);
//        self::$_app->getService('VMConnector')->processRequest();
//        self::$_app->getService('VMConnector')->renderHtml();
    }

    protected function __construct(){}

    protected function _init(){
        $this->_registerAutoloader();
//        $this->_initServices();
    }

    protected function _registerAutoloader(){
        require_once 'code' . DIRECTORY_SEPARATOR . 'System' . DIRECTORY_SEPARATOR . 'Autoloader.php';
        spl_autoload_register('Autoloader::autoload');
    }

    protected function _initServices(){
        foreach ($this->_services as $name => $service) {
            if ($service == null) {
                $class = 'System\\' . $name;
                $service = $class::init();
                $this->_services[$name] = $service;
            }
        }
    }

    public function getService($serviceName){
        if(isset($this->_services[$serviceName]) && $this->_services[$serviceName]){
            return $this->_services[$serviceName];
        }else{
            die('Cant provide service ' . $serviceName);
        }
    }

    /**
     * Return base app dir
     *
     * @return string
     */
    static public function getBaseDir()
    {
        if (self::$_appRoot) {
            return self::$_appRoot;
        }

        $appRoot = realpath(dirname(''));

        if (is_dir($appRoot) and is_readable($appRoot)) {
            self::$_appRoot = $appRoot;
            return self::$_appRoot;
        } else {
            die($appRoot . ' is not a directory or not readable by this user');
        }
    }
}