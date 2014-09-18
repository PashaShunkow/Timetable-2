<?php
/**
 * System request service
 *
 * @category  TimetableTool
 * @package   TimetableTool_System
 * @author    Paul Shunkow
 * @copyright 2014 Paul Shunkow
 */
namespace System\Services;

class Request
{
    protected $_getParams    = array();
    protected $_postParams   = array();
    protected $_serverParams = array();

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->_loadGlobalArrays();
    }

    /**
     * Return element of GET array by key
     *
     * @param string $key Key in GET array
     * @return mixed
     */
    public function getGET($key = null)
    {
        if ($key == null) {
            return $this->_getParams;
        }
        if (isset($this->_getParams[$key])) {
            return $this->_getParams[$key];
        }
        return null;
    }

    /**
     * Set element in GET array by key
     *
     * @param string $key   Key in GET array
     * @param mixed  $value Value
     * @return mixed
     */
    public function setGET($key, $value = null)
    {
        if (is_array($key)) {
            $this->_getParams = array_merge($this->_getParams, $key);
        } elseif ($value !== null) {
            $this->_getParams[$key] = $value;
        }

        return $this;
    }

    /**
     * Return element of POST array by key
     *
     * @param string $key Key in POST array
     * @return mixed
     */
    public function getPOST($key = null)
    {
        if ($key == null) {
            return $this->_postParams;
        }
        if (isset($this->_postParams[$key])) {
            return $this->_postParams[$key];
        }
        return null;
    }

    /**
     * Set element in POST array by key
     *
     * @param string $key   Key in POST array
     * @param mixed  $value Value
     * @return mixed
     */
    public function setPOST($key, $value = null)
    {
        if (is_array($key)) {
            $this->_postParams = array_merge($this->_postParams, $key);
        } elseif ($value !== null) {
            $this->_postParams[$key] = $value;
        }

        return $this;
    }

    /**
     * Return element of SERVER array by key
     *
     * @param string $key Key in SERVER array
     * @return mixed
     */
    public function getSERVER($key = null)
    {
        if ($key == null) {
            return $this->_serverParams;
        }
        if (isset($this->_serverParams[$key])) {
            return $this->_serverParams[$key];
        }
        return null;
    }

    /**
     * Load global array in inner class vars
     */
    protected function _loadGlobalArrays()
    {
        $this->_getParams    = $_GET;
        $this->_postParams   = $_POST;
        $this->_serverParams = $_SERVER;
    }
}