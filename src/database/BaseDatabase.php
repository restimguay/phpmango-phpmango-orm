<?php

namespace MGO\database;

use MGO\obj\BaseObject;


class BaseDatabase extends BaseObject
{
    public static $_dsn;
    public static $_driver;
    private static $_user;
    private static $_pass;
    private static $_options;
    private static $_connection;
    /**
     * @property DBConfig
     */
    private static $_dbconfig;
    /**
     * @var BaseDatabase $_instance
     */
    private static $_instance=null;
    public static function setConfig(DBConfig $dbConfig){
        self::$_dbconfig = $dbConfig;
    }

    public static function connect(){
        if(self::$_instance == null){
            self::$_instance = new BaseDatabase();
        }
    }

    private static function makeConnection(){
        if(self::$_connection==null){
            self::$_connection = new self::$_driver(self::$_dsn,self::$_user,self::$_pass,self::$_options);
        }
    }
    /**
     * @return \PDO::Connection
     */
    public function getConnection(){
        $this->makeConnection();
        return self::$_connection;
    }

    public function buildFilter(string &$sqlStatement, array $parameter){
        $filter = ' WHERE ';
        foreach($parameter as $field => $value){
            if($filter=='WHERE '){
                $filter.=$field.'=:'.$field;
            }else{
                $filter.=' and '.$field.'=:'.$field;
            }
        }
        $sqlStatement.= $filter;
        return $sqlStatement;
    }
}