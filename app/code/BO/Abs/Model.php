<?php
/**
 * Abstract model
 *
 * @category  TimetableTool
 * @package   TimetableTool
 * @author    Paul Shunkow
 * @copyright 2014 Paul Shunkow
 */
namespace BO\Abs;

use \System\Libs\Object as Object;
use \System\Services\DbAdapter as DbAdapter;

abstract class Model extends Object
{
    /**
     * Db Adapter object
     *
     * @var \System\Services\DbAdapter
     */
    protected $_dbAdapter;

    /**
     * Name of models entity
     *
     * @var string
     */
    protected $_entity;

    /**
     * Id key name
     *
     * @var string
     */
    protected $_idFieldName = '_id';

    /**
     * Class constructor
     *
     * @param DbAdapter  $dbAdapter  Db Adapter object
     * @param Collection $collection Db Adapter object
     */
    public function __construct(DbAdapter $dbAdapter)
    {
        $this->_dbAdapter = $dbAdapter;
    }

    /**
     * Load model from DB by id
     *
     * @param  string $id Model id
     * @return $this
     */
    public function load($id)
    {
        $data = $this->getDbAdapter()->findOneInCollectionById($id, $this->getEntityName());
        $this->initModelData($data);
        return $this;
    }

    /**
     * Save model into DB
     *
     * @return $this;
     */
    public function save()
    {
        $dbAdapter = $this->getDbAdapter();
        if (!$this->getId()) {
            $data = $dbAdapter->insertOneIntoCollection($this->getData(), $this->getEntityName());
            $this->initModelData($data);
        } else {
            $dbAdapter->updateOneInCollection($this->getData(), $this->getEntityName());
        }
        return $this;
    }

    /**
     * Delete model from db
     */
    public function delete()
    {
        $dbAdapter = $this->getDbAdapter();
        if ($this->getId()) {
            $dbAdapter->deleteOneFromCollection($this->getId(), $this->getEntityName());
        }
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

    /**
     * Init model data array
     *
     * @param $data
     */
    public function initModelData($data)
    {
        $this->setData($data);
        $this->_normalizeId();
        return $this;
    }

    /**
     * Convert MongoId object to string
     */
    protected function _normalizeId()
    {
        if ($this->getId() && is_object($this->getId())) {
            $this->setId($this->getId()->__toString());
        }
    }

}