<?php

namespace MGO\database;

use MGO\obj\BaseObject;


class BaseDatabase extends BaseObject
{
    public $_dsn;
    public $_driver;
    private static $_connection;
    public function connect(){
        if(self::$_connection==null){
            self::$_connection = new $this->_driver($this->_dsn);
        }
    }
}