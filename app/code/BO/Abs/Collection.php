<?php
/**
 * Abstract collection class
 *
 * @category  TimetableTool
 * @package   TimetableTool_Entities\Template
 * @author    Paul Shunkow
 * @copyright 2014 Paul Shunkow
 */
namespace BO\Abs;

use \BO\Abs\Model as Model;
use \System\Object as Object;
use \System\DbAdapter as DbAdapter;

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
     * Class constructor
     *
     * @param DbAdapter $dbAdapter Db Adapter object
     */
    public function __construct(DbAdapter $dbAdapter){
        $this->_dbAdapter = $dbAdapter;
        $this->_pointer = 0;
    }

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
        $this->setIsLoaded(true);
        return $this;
    }

    public function setRelatedModel(Model $model){
        $this->_relatedModel = $model;
    }

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
        return $this->getIsLoaded();
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
     * @return DbAdapter
     */
    public function getDbAdapter()
    {
        return $this->_dbAdapter;
    }

    public function getQueryConditions(){
        return $this->_queryConditions;
    }

    public function getIncludedFields(){
        return $this->_includedFields;
    }

    public function rewind() {
        if (!$this->_isLoaded()) {
            $this->load();
        }
        $this->_pointer = 0;
    }

    public function current() {
        return $this->_items[$this->_pointer];
    }

    public function key() {
        return $this->_pointer;
    }

    public function next() {
        ++$this->_pointer;
    }

    public function valid() {
        return isset($this->_items[$this->_pointer]);
    }
}