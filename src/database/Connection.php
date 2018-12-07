<?php

namespace MGO\database;

class Connection
{
    /**
     * @var DBConfig
     */
    private static $_config;
    /**
     * @var \PDO[]
     */
    private static $_connection=[];

    public function __construct(DBConfig $config)
    {
        self::$_config = $config;
        $this->connect();
    }

    /**
     * Sets the database connection details
     * @param DBConfig
     */
    private function setConfig(DBConfig $config){
        self::$_config = $config;
    }
    /**
     * Make a database connection
     */
    private function connect(){
        $config = self::$_config->getConfigList();
        foreach($config as $key=>$conf){
            self::$_connection[$key] = new \PDO($conf['dsn'], $conf['username'], $conf['password']);
        }
        return $this;
    }

    /**
     * Get the value of _connection
     *
     * @return  \PDO
     * @var string $verb
     */ 
    public function db(string $verb='default')
    {
        if(isset(self::$_connection[$verb])){
            return self::$_connection[$verb];
        }else{
            return self::$_connection['default'];
        }        
    }
    /**
     * @return boolean true if auto-generate is set to true, otherwise false
     */
    public function autoGenerateEnable(){
        $config = self::$_config->getConfigList();
        return $config['default']['auto-generate'];
    }
}