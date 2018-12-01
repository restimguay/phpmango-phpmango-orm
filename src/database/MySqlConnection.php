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
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $this->_driver = \PDO::class;
        $this->_dsn = "mysql:host=$host;dbname=$db;charset=$charset;";
        $this->connect($user,$pass,$options);
    }

    public function setDSN(string $dsn)
    {
        $this->_dsn = $dsn;
    }

    public function setDriverClass($driverClass){
        $this->_driver = $driverClass;
    }
}