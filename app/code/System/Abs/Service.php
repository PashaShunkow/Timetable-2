<?php
/**
 * System abstract service
 *
 * @category  TimetableTool
 * @package   TimetableTool
 * @author    Paul Shunkow
 * @copyright 2014 Paul Shunkow
 */

namespace System\Abs;

use System\Helper as Helper;

abstract class Service {

    /**
     * System helper
     *
     * @var Helper
     */
     protected $_helper;

    /**
     * Class constructor
     *
     * @param Helper $helper  System helper
     * @param array  $configs Configs for current service
     */
    public function __construct(Helper $helper, $configs = array())
    {
        $this->_helper = $helper;
        $this->_init();
    }

    /**
     * Return system helper object
     *
     * @return Helper
     */
    public function SH(){
        return $this->_helper;
    }

    /**
     * Init the config object
     */
    protected function _init()
    {

    }
}