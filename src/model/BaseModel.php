<?php

namespace MGO\model;

use MGO\database\Connection;


class BaseModel extends Connection
{
    /**
     *@var string
     */
    public $_tableName;

    /**
     *@var string
     */
    public $_primaryKey;

    /**
     *@var \PDOStatement
     */
    private $_stmt;
    
    /**
     *@var string
     */
    private $_sql;

    /**
     *@var array
     */
    private $_parameters;

    /**
     * @param string $tableName
     * @param string $primaryKey
     */
    public function __construct()
    {
    }
    /**
     * @return BaseModel
     */
    public function findIn($tableName){
        $this->_sql ="SELECT * FROM $tableName";
        return $this;
    }
    /**
     * @param array $parameters
     * @return BaseModel
     */
    public function by($parameters){
        $this->_parameters =$parameters;
        return $this;
    }

    /**
     * @return boolean
     */
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
        $conn = $this->db();
        $this->_stmt = $conn->prepare($this->_sql);

        foreach($this->_parameters as $field=>&$value){
            $this->_stmt->bindValue('v_'.$field,$value);
        }
        $this->_stmt->execute();
        return $this;
    }
    /**
     * @return array
     */
    public function fetchAssoc(){
        $this->execute();
        return $this->_stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * @return array
     */
    public function fetchObject(){
        $this->execute();
        return $this->_stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    

    /**
     * Get *@var string
     * @return string
     */ 
    public function getTableName()
    {
        return $this->_tableName;
    }

    /**
     * Set *@var string
     *
     * @return  self
     */ 
    public function setTableName($tableName)
    {
        $this->_tableName = $tableName;

        return $this;
    }

    /**
     * Get *@var string
     * @return string
     */ 
    public function getPrimaryKey()
    {
        return $this->_primaryKey;
    }

    /**
     * Set *@var string
     *
     * @return  self
     */ 
    public function setPrimaryKey($primaryKey)
    {
        $this->_primaryKey = $primaryKey;

        return $this;
    }

    /**
     * Get *@var \PDOStatement
     * @return \PDOStatement
     */ 
    public function getStatement()
    {
        return $this->_stmt;
    }

    /**
     * Set *@var \PDOStatement
     *
     * @return  self
     */ 
    public function setStatement($_stmt)
    {
        $this->_stmt = $_stmt;

        return $this;
    }
}