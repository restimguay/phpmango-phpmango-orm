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
    public function connect($dsn,$user,$pass,$options){
        if(self::$_connection==null){
            self::$_dsn = $dsn;
            self::$_user = $user;
            self::$_pass = $pass;
            self::$_options = $options;
            $this->makeConnection();
        }
    }

    private function makeConnection(){
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