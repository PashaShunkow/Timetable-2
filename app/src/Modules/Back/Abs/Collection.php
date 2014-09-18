<?php
/**
 * Abstract collection class
 *
 * @category  TimetableTool
 * @package   TimetableTool_Entities\Template
 * @author    Paul Shunkow
 * @copyright 2014 Paul Shunkow
 */
namespace Modules\Back\Abs;

use \System\Abs\Object as Object;

abstract class Collection extends Object implements \Iterator
{

    /**
     * Current cursor position
     *
     * @var int
     */
    protected $_pointer = 0;

    /**
     * Collection items
     *
     * @var array
     */
    protected $_items   = array();

    /**
     * Name of collection entity
     *
     * @var string
     */
    protected $_entity;

    /**
     * Array of conditions
     *
     * @var array
     */
    protected $_queryConditions = array();

    /**
     * Array of icnluded fields
     *
     * @var array
     */
    protected $_includedFields = array();

    /**
     * Related model
     *
     * @var Model
     */
    protected $_relatedModel;

    /**
     * Collection status flag
     *
     * @var bool
     */
    protected $_loaded;

    /**
     * Class constructor
     *
     * @param Model $model Related model
     */
    public function __construct(Model $model){
        $this->_relatedModel = $model;
        $this->_pointer = 0;
    }

    /**
     * Execute prepared query, create collection items based on fetched data
     *
     * @return $this
     */
    public function load()
    {
        $this->_items = array();
        $docs = $this->getDbAdapter()->findManyInCollection(
            $this->getQueryConditions(),
            $this->getIncludedFields(),
            $this->getEntityName()
        );
        foreach ($docs as $doc) {
            $item = clone $this->getRelatedModel();
            $this->_items[] = $item->initModelData($doc);
        }
        $this->_loaded(true);
        return $this;
    }

    public function setQueryConditions($conditions)
    {
        if (is_array($conditions)) {
            $this->_queryConditions = $conditions;
        } else {
            throw new \Exception('Wrong conditions format!');
        }
    }

    public function setIncludedFields($fields){
        if (is_array($fields)) {
            $this->_includedFields = $fields;
        } else {
            throw new \Exception('Wrong included fields format!');
        }
    }

    /**
     * Returns object of related model
     *
     * @return Model
     */
    public function getRelatedModel(){
        return $this->_relatedModel;
    }


    /**
     * Return count of loaded items
     *
     * @return int
     */
    public function count()
    {
        if (!$this->_isLoaded()) {
            $this->load();
        }
        return count($this->_items);
    }

    /**
     * Return array of loaded items
     *
     * @return array
     */
    public function getItems()
    {
        if (!$this->_isLoaded()) {
            $this->load();
        }
        return $this->_items;
    }

    /**
     * Check is collection is already loaded
     *
     * @return bool
     */
    protected function _isLoaded()
    {
        return $this->_loaded;
    }

    /**
     * Set collection load status true/false
     *
     * @param bool $value Collection status
     */
    protected function _loaded($value){
        $this->_loaded = $value;
    }

    /**
     * Return name of models entity
     *
     * @return string
     */
    public function getEntityName()
    {
        return $this->_entity;
    }

    /**
     * Return model bd adapter
     *
     * @return \System\Services\DbAdapter
     */
    public function getDbAdapter()
    {
        return $this->getRelatedModel()->getDbAdapter();
    }

    /**
     * Return array of query conditions
     *
     * @return array
     */
    public function getQueryConditions(){
        return $this->_queryConditions;
    }

    /**
     * Return array of included fields
     *
     * @return array
     */
    public function getIncludedFields(){
        return $this->_includedFields;
    }

    /**
     * Reset inner cursor to 0
     */
    public function rewind() {
        if (!$this->_isLoaded()) {
            $this->load();
        }
        $this->_pointer = 0;
    }

    /**
     * Return current item
     *
     * @return mixed
     */
    public function current() {
        return $this->_items[$this->_pointer];
    }

    /**
     * Return current cursor position
     *
     * @return int|mixed
     */
    public function key() {
        return $this->_pointer;
    }

    /**
     * Iterate cursor
     */
    public function next() {
        ++$this->_pointer;
    }

    /**
     * Check if exist item
     *
     * @return bool
     */
    public function valid() {
        return isset($this->_items[$this->_pointer]);
    }
}