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

class BFConnector extends Service
{
    const MODEL_CLASS_TEMPLATE = 'Modules\\Back\\{entity}\\Model';
    const COLLECTION_CLASS_TEMPLATE = 'Modules\\Back\\{entity}\\Collection';
    const ENTITY_PLACEHOLDER = '{entity}';
    
    const E_FORM = 'form';
    const E_LIST = 'list';

    const DC_MODEL = 'model';
    const DC_COLLECTION = 'collection';

    protected $_requestData;

    protected $_dataContainers = array();

    protected $_elements = array();

    /**
     * Run request processing
     *
     * @param array $requestData Request data array
     */
    public function processRequest($requestData){
        $this->_requestData = $requestData;
        $this->_initDataContainers($requestData['elements'], $requestData['data']);
        $this->_createElements($requestData['elements']);
    }

    public function output(){
        var_dump($this->_elements);
    }

    /**
     * Request part of request data array if key exist
     *
     * @param string $key Key in request array
     * @return mixed
     */
    protected function _getRequestData($key = null)
    {
        if ($key && isset($this->_requestData[$key])) {
            return $this->_requestData[$key];
        }
        return $this->_requestData;
    }

    /**
     * Init requested data container\s model or\and collection
     *
     * @throws \Exception
     */
    protected function _initDataContainers()
    {
        $elements = $this->_getRequestData('elements');
        foreach ($elements as $name => $container) {
            $method = '_init' . ucfirst($container);
            $this->_tryCallMethod($method);
        }
    }

    /**
     * Init model of requested entity
     */
    protected function _initModel()
    {
        $entity = ucfirst($this->_getRequestData('entity'));
        $className = str_replace(self::ENTITY_PLACEHOLDER, $entity, self::MODEL_CLASS_TEMPLATE);
        $model = new $className($this->SH()->getDbAdapter());
        $this->_addDataContainer(self::DC_MODEL, $model);
    }

    /**
     * Init collection of requested entity
     */
    protected function _initCollection()
    {
        $entity = ucfirst($this->_getRequestData('entity'));
        $className = str_replace(self::ENTITY_PLACEHOLDER, $entity, self::COLLECTION_CLASS_TEMPLATE);
        $collection = new $className($this->_getDataContainer(self::DC_MODEL));
        $this->_addDataContainer(self::DC_COLLECTION, $collection);
    }

    /**
     * Return data container by name, try to create it if not exist
     *
     * @param string $name Data container name
     * @return mixed
     * @throws \Exception
     */
    protected function _getDataContainer($name)
    {
        if (!isset($this->_dataContainers[$name])) {
            $method = '_init' . ucfirst($name);
            $this->_tryCallMethod($method);
        }

        return $this->_dataContainers[$name];
    }

    /**
     * Add data container to common array
     *
     * @param string $name      Data container name
     * @param mixed  $container Data container
     */
    protected function _addDataContainer($name, $container){
        $this->_dataContainers[$name] = $container;
    }

    /**
     * Create requested elements
     *
     * @throws \Exception
     */
    protected function _createElements()
    {
        $elements = $this->_getRequestData('elements');
        foreach ($elements as $name => $dataContainer) {
            $method = '_create' . ucfirst($name);
            $this->_tryCallMethod($method);
        }
    }

    /**
     * Create from element
     * 
     * @throws \Exception
     */
    protected function _createForm()
    {
        $dataContainer = $this->_getDataContainer(self::DC_MODEL);
        $attributes = $this->_getAttributesForElement(self::E_FORM);
        $element = array(
            'element' => self::E_FORM,
            'entity' => ucfirst($this->_getRequestData('entity'))
        );
        foreach ($attributes as $attribute) {
            $element[$attribute] = $dataContainer->getData($attribute);
        }
        $this->_addElement(self::E_FORM, $element);
    }

    /**
     * Create list element
     *
     * @throws \Exception
     */
    protected function _createList()
    {
        $dataContainer = $this->_getDataContainer(self::DC_COLLECTION);
        $attributes = $this->_getAttributesForElement(self::E_LIST);
        $element = array(
            'element' => self::E_LIST,
            'entity' => ucfirst($this->_getRequestData('entity'))
        );

        $dataContainer->setIncludedFields($attributes);

        foreach ($dataContainer as $item) {
            $element['items'][] = $item->getData();
        }
        $this->_addElement(self::E_LIST, $element);
    }

    /**
     * Add element to common array
     *
     * @param string $name    Element name
     * @param array  $element Element
     */
    protected function _addElement($name, $element){
        $this->_elements[$name] = $element;
    }

    /**
     * Return array of attributes for current entity and specified element
     * 
     * @param $element
     * @throws \Exception
     * @return array
     */
    protected function _getAttributesForElement($element){
        $entity = ucfirst($this->_getRequestData('entity'));
        if (isset($this->_configs[$entity][$element])) {
            return $this->_configs[$entity][$element];
        } else {
            throw new \Exception('Cant find attributes for entity: ' . $entity . ' element: ' . $element);
        }
    }

    /**
     * Try to call dynamic method
     *
     * @param string $method Method name
     * @throws \Exception
     */
    protected function _tryCallMethod($method){
        if (is_callable(array($this, $method))) {
            $this->$method();
        } else {
            throw new \Exception('Cant call method: ' . $method);
        }
    }
}