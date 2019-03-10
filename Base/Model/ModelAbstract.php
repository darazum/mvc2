<?php
namespace Base\Model;

abstract class ModelAbstract
{
    abstract function getTable();

    abstract function initByDbData(array $data);
}