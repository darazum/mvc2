<?php
namespace Base\Model;

use Base\Context as Context;
use Base\Exception;

class Factory
{
    const MODEL_USER = 1;
    const MODEL_POST = 2;

    private static function getModelStorage()
    {
        return [
            self::MODEL_USER => [
                'class_name' => '\\App\\User\\Model\\Base',
            ],
            self::MODEL_POST => [
                'class_name' => '\\App\\Main\\Model\\Post',
            ]
        ];
    }

    /**
     * @param int $modelClassName \Base\Model\Factory::MODEL_*
     * @param int $id
     *
     * @return ModelAbstract|null
     * @throws Exception
     */
    public static function getById(int $modelTypeId, $_method, int $id)
    {
        $db = Context::getInstance()->getDbConnection();

        $modelClassName = self::_getModelClassName($modelTypeId);

        /** @var ModelAbstract $model */
        $model = new $modelClassName();
        $table = $model->getTable();
        $query = "SELECT * FROM $table WHERE id = :id";
        $data = $db->fetchOne($query, $_method, [':id' => $id]);
        if (!$data) {
            return null;
        }
        $model->initByDbData($data);
        return $model;
    }

    /**
     * @param $modelTypeId
     * @param array $ids
     * @return ModelAbstract[]
     * @throws Exception
     */
    public static function getByIds($modelTypeId, $_method, array $ids)
    {
        $db = Context::getInstance()->getDbConnection();

        $return = [];
        $modelClassName = self::_getModelClassName($modelTypeId);
        array_walk($ids, function(&$id) {
            $id = (int)$id;
        });
        $ids = array_unique($ids);
        $idsStr = implode(',', $ids);
        $model = new $modelClassName();
        $table = $model->getTable();
        $select = "SELECT * FROM $table WHERE id IN($idsStr)";
        $data = $db->fetchAll($select, $_method);

        if ($data) {
            foreach ($data as $elem) {
                /** @var ModelAbstract $model */
                $model = new $modelClassName();
                $model->initByDbData($elem);
                $return[$elem['id']] = $model;
            }
        }

        return $return;
    }

    /**
     * @param $modelTypeId
     * @param $_method
     * @param int $limit
     * @param array $filter
     * @param string $order
     * @return ModelAbstract[]
     * @throws Exception
     */
    public static function getList($modelTypeId, $_method, int $limit, array $filter = [], string $order = '')
    {
        $db = Context::getInstance()->getDbConnection();

        $return = [];
        $modelClassName = self::_getModelClassName($modelTypeId);

        /** @var ModelAbstract $model */
        $model = new $modelClassName();
        $table = $model->getTable();

        $filterStr = '';
        if ($filter) {
            $filterStr = 'WHERE';
            // todo implement
        }

        $orderStr = '';
        if ($order) {
            $orderStr = "ORDER BY $order";
        }

        $select = "SELECT * FROM $table {$filterStr} {$orderStr} LIMIT $limit";

        $data = $db->fetchAll($select, $_method);

        if ($data) {
            foreach ($data as $elem) {
                $model = new $modelClassName();
                $model->initByDbData($elem);
                $return[$elem['id']] = $model;
            }
        }

        return $return;
    }


    /**
     * @param int $modelTypeId
     * @return string
     * @throws Exception
     */
    private static function _getModelClassName(int $modelTypeId): string
    {
        $config = self::getModelStorage();
        if (!isset($config[$modelTypeId])) {
            throw new Exception('No model type#' . $modelTypeId . ' in model storage');
        }
        $modelConfig = $config[$modelTypeId];
        $modelClassName = $modelConfig['class_name'];

        return $modelClassName;
    }
}