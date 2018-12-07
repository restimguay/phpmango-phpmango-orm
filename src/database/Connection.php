<?php

namespace MGO\database;

class Connection extends \PDO
{
    /**
     * @var DBConfig
     */
    private $_config;
    /**
     * @var Connection
     */
    private static $_instance;
    /**
     * @var \PDO
     */
    private static $_connection;

    public function __construct(DBConfig $config)
    {
        $this->setConfig($config);
        $this->connect();
    }

    /**
     * Sets the database connection details
     * @param DBConfig
     */
    private function setConfig(DBConfig $config){
        $this->_config = $config;
    }
    /**
     * Make a database connection
     */
    private function connect(){
        if(self::$_connection == null){
            parent::__construct($this->_config->getDSN(),$this->_config->getUsername(),$this->_config->getPassword());
            self::$_connection = $this;
        }
        return $this;
    }

    /**
     * Get the value of _connection
     *
     * @return  \PDO
     */ 
    public function db()
    {
        return self::$_connection;
    }

}