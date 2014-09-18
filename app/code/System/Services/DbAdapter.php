<?php
/**
 * System Db Adapter
 *
 * @category  TimetableTool
 * @package   TimetableTool
 * @author    Paul Shunkow
 * @copyright 2014 Paul Shunkow
 */
namespace System\Services;

class DbAdapter extends \MongoClient
{
    const DEFAULT_DOC_ID = '_id';

    protected $_dbName = 'table';

    /**
     * Insert new one doc to specified collection
     *
     * @param array  $doc            Data array
     * @param string $collectionName Collection name
     * @param array  $options        Additional options
     *
     * @return bool
     */
    public function insertOneIntoCollection($doc, $collectionName, $options = array()){
        $collection = $this->selectCollection($this->getDbName(), $collectionName);
        try {
            $collection->insert($doc, $options);
            return $doc;
        } catch (\Exception $e) {
            var_dump($e);
            die;
        }
    }

    /**
     * Find single document in DB by id
     *
     * @param string $id             Model id
     * @param string $collectionName Collection name
     * @return array|null
     */
    public function findOneInCollectionById($id, $collectionName)
    {
        $collection = $this->selectCollection($this->getDbName(), $collectionName);
        $doc =  $collection->findOne(array(static::DEFAULT_DOC_ID => $this->makeRealMongoId($id)));
        return $doc;
    }

    /**
     * Update existed document in specified collection
     *
     * @param array  $doc            Data array
     * @param string $collectionName Collection name
     *
     * @return void
     */
    public function updateOneInCollection($doc, $collectionName){
        $collection = $this->selectCollection($this->getDbName(), $collectionName);
        $id = $doc[static::DEFAULT_DOC_ID];
        unset($doc[static::DEFAULT_DOC_ID]);
        $result = $collection->update(array(static::DEFAULT_DOC_ID => $this->makeRealMongoId($id)), $doc);
        $this->_checkQueryResult($result);
    }

    /**
     * Delete document from db by id
     *
     * @param string $id             Document id
     * @param string $collectionName collection name
     * @return array
     */
    public function deleteOneFromCollection($id, $collectionName)
    {
        $collection = $this->selectCollection($this->getDbName(), $collectionName);
        $result = $collection->remove(array(static::DEFAULT_DOC_ID => $this->makeRealMongoId($id)));
        $this->_checkQueryResult($result);
    }

    /**
     * Find docs in collection according to query
     *
     * @param array  $query          Query conditions
     * @param array  $fields         Included fields
     * @param string $collectionName Collection name
     * @return \MongoCursor
     */
    public function findManyInCollection($query, $fields, $collectionName)
    {
        $collection = $this->selectCollection($this->getDbName(), $collectionName);
        return $collection->find($query, $fields);
    }

    /**
     * Return name of current db name
     *
     * @return string
     */
    public function getDbName(){
        return $this->_dbName;
    }

    /**
     * Convert string to MongoId object
     *
     * @param string $id Model id
     * @return \MongoId
     */
    public function makeRealMongoId($id)
    {
        if (!$id instanceof \MongoId) {
            $id = new \MongoId($id);
        }
        return $id;
    }

    protected function _checkQueryResult($result){
        if ($result['ok'] != 1) {
            var_dump($result);
            die;
        }
        return true;
    }
}