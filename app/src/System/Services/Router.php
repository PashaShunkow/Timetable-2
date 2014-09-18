<?php
/**
 * System router service
 *
 * @category  TimetableTool
 * @package   TimetableTool_System
 * @author    Paul Shunkow
 * @copyright 2014 Paul Shunkow
 */
namespace System\Services;

use System\Abs\Service as Service;

class Router extends Service
{
    /**
     * Default route data
     */
    protected $_elements = array();
    protected $_data = array();
    protected $_entity = '';

    /**
     * Route on related view
     *
     * @return array
     */
    public function decodeRequest()
    {
        $request = $this->SH()->getRequest();
        $requestUri = $request->getSERVER('REQUEST_URI');
        if(strpos($requestUri, '/') !== false)
        {
            if (strpos($requestUri, '/') === 0) {
                $requestUri = substr($requestUri, 1);
            }
            $parts = explode('/', $requestUri);
            if (!empty($parts[0])) {
                $this->_elements = $this->_getElementsForRoute($parts[0]);
            }
            if (!empty($parts[1])) {
                $this->_entity = $parts[1];
            }
            if (!empty($parts[2])) {
                $this->_data = $this->_parseDataFromString($parts[2]);
            }
        }

        return $this->_getRequestData();
    }

    /**
     * Returns elements from current route
     *
     * @param $route
     * @throws \Exception
     *
     * @return array
     */
    protected function _getElementsForRoute($route){
        if(isset($this->_configs[$route])){
            return $this->_configs[$route];
        }else{
            throw new \Exception('Cant find route : "' . $route . '" in decelerated routes!');
        }
    }

    /**
     * Return data array
     *
     * @param string $string String with encoded data
     * @return array
     */
    protected function _parseDataFromString($string){
        $params = array();
        $data   = array();

        if (strpos($string, '_') != false) {
            $params = explode('_', $string);
        }else{
            $params[] = $string;
        }

        foreach ($params as $param) {
            if (strpos($param, '=') != false) {
                $param = explode('=', $param, 2);
                $data[$param[0]] = $param[1];
            }
        }

        return $data;
    }

    /**
     * Return data for current action
     *
     * @throws \HttpRequestException
     * @return array
     */
    protected function _getRequestData()
    {
        $requestData = array(
            'elements' => $this->_elements,
            'entity' => $this->_entity,
            'data' => $this->_data
        );

        if ($this->_validateRequestData($requestData)) {
            return $requestData;
        } else {
            throw new \HttpRequestException('Request data is invalid ' . print_r($requestData));
        }
    }

    /**
     * Validate request data array
     *
     * @param array $data data array
     * @return bool
     */
    protected function _validateRequestData($data)
    {
        if (empty($data['elements']) || !is_array($data['elements']) || empty($data['entity'])) {
            return false;
        } else {
            return true;
        }
    }
}