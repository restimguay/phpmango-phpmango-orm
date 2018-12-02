<?php

namespace MGO\database;

class MySqlConnection extends BaseDatabase implements IDatabaseConnection 
{
    private $_stmt;
    private $_sql;
    private $_parameters;
    public function init()
    {
        $host = '127.0.0.1';
        $db   = 'project_social';
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';
        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        parent::$_driver = \PDO::class;       
        $this->connect("mysql:host=$host;dbname=$db;charset=$charset;",$user,$pass,$options);
    }

    public function setDSN(string $dsn)
    {
        $this->_dsn = $dsn;
    }

    public function setDriverClass($driverClass){
        $this->_driver = $driverClass;
    }
    /**
     * @return MySqlConnection
     */
    public function findIn($tableName){
        $this->_sql ="SELECT * FROM $tableName";
        return $this;
    }
    /**
     * @return MySqlConnection
     */
    public function by($parameters){
        $this->_parameters =$parameters;
        return $this;
    }
    public function execute(){
        $filter = '';
        foreach($this->_parameters as $field=>$value){
            if($filter==''){
                $filter.=$field.'=:v_'.$field;
            }else{
                $filter.=' and :f_'.$field.'=:v_'.$field;
            }
        }
        if($filter!==''){
            $filter = ' WHERE '.$filter;
        }
        $this->_sql.=$filter;
        $conn = $this->getConnection();
        $this->_stmt = $conn->prepare($this->_sql);

        foreach($this->_parameters as $field=>&$value){
            $this->_stmt->bindValue('v_'.$field,$value);
        }
        $result = $this->_stmt->execute();
        return $this;
    }

    public function fetchAssoc(){
        return $this->_stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}