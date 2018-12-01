<?php

namespace MGO\database;

class MySqlConnection extends BaseDatabase implements DatabaseConnection  
{
    public function init()
    {
        $host = '127.0.0.1';
        $db   = 'project_social';
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';
        $this->_driver = \PDO::class;
        $this->_dsn = "mysql:host=$host;dbname=$db;charset=$charset;";
        $this->connect();
    }

    public function setDSN(string $dsn)
    {
        $this->_dsn = $dsn;
    }

    public function setDriverClass($driverClass){
        $this->_driver = $driverClass;
    }
}