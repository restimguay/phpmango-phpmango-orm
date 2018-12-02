<?php

namespace MGO\database;

interface IDatabaseConnection
{
    public function init();
    public function setDSN(string $dsn);
    public function setDriverClass(string $driverClass);

    /**
    * @param string $tableName the name of the table to query
    * @param array $parameter the record filter
    */
}