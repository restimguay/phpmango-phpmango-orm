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
    private $_sql = '';

    /**
     *@var string
     */
    private $_filter = '';

    /**
     *@var integer
     */
    private $_limit = '';

    /**
     *@var integer
     */
    private $_offset = '';

    /**
     *@var array
     */
    private $_parameters = [];

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
    public function executeQuery(){
        $this->buildFilters();
        $this->_sql.= $this->getFilter();
        $this->_sql.= $this->getLimitBy();
        $this->_sql.= $this->getOffsetBy();
        $this->_stmt = $this->db()->prepare($this->_sql);
        $this->bindFilterValues();
        $this->_stmt->execute();
        return $this;
    }

    /**
     * @return string ex. LIMIT <limiNumber>
     */
    private function getFilter(){        
        if($this->filter!==''){
            $this->filter = ' WHERE '.$this->filter;
        }
        return $this->_filter;
    }
    /**
     * @return BaseModel
     * @param array $parameters
     */
    public function where($parameters){
        $this->buildFilters($parameters);
        return $this;
    }
    /**
     * @return BaseModel
     */
    public function limitBy(int $limit){
        $this->_limit = ' LIMIT '.$limit;
        return $this;
    }
    /**
     * @return string ex. LIMIT <limiNumber>
     */
    private function getLimitBy(){
        return $this->_limit;
    }
    /**
     * @return string ex. OFFSET <limiNumber>
     */
    private function getOffsetBy(){
        return $this->_offset;
    }
    /**
     * @return BaseModel
     */
    public function offsetBy($offset){
        $this->_offset = ' OFFSET '.$offset;
        return $this;
    }
    /**
     * @return BaseModel
     */
    private function bindFilterValues(){
        foreach($this->_parameters as $field=>&$value){
            $this->_stmt->bindValue('v_'.$field,$value);
        }
        return $this;
    }

    /**
     * @return BaseModel
     */
    private function buildFilters(){
        $this->filter = '';
        foreach($this->_parameters as $field=>$value){
            if($this->filter==''){
                $this->filter.=$field.'=:v_'.$field;
            }else{
                $this->filter.=' and :f_'.$field.'=:v_'.$field;
            }
        }
        return $this;
    }
    /**
     * @return array
     */
    public function fetchAssoc(){
        $this->executeQuery();
        return $this->_stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * @return array
     */
    public function fetchObject(){
        $this->executeQuery();
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