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
    public function __construct($tableName = '',$primaryKey = '')
    {
        $this->_primaryKey = $primaryKey;
        $this->_tableName = $tableName;
        if($tableName!=''){
            $className = $this->parseClassname(get_class($this));
            if($className != 'BaseModel'){
                $this->checkTableConsistency($tableName);
            }
        }
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
        $this->_parameters = $parameters;
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
     * @return BaseModel
     */
    public function query($sql, $parameters){
        $this->_limit = '';
        $this->_offset = '';
        $this->_filter = '';
        $this->_sql = $sql;
        $this->_parameters = $parameters;     
        $this->_stmt = $this->db()->prepare($this->_sql);
        $this->bindFilterValues(false);
        $result = $this->_stmt->execute();
        return $this;
    }
    /**
     * @return string ex. LIMIT <limiNumber>
     */
    private function getFilter(){        
        if($this->_filter!==''){
            $this->_filter = ' WHERE '.$this->_filter;
        }
        return $this->_filter;
    }
    /**
     * @return BaseModel
     * @param array $parameters
     */
    public function where($parameters){
        $this->_parameters = $parameters;
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
    private function bindFilterValues(bool $useSuffix = true){
        if($useSuffix){
            foreach($this->_parameters as $field=>&$value){
                if(strpos($this->_sql,'v_'.$field)>0){
                    $this->_stmt->bindValue('v_'.$field,$value);
                }
            }
        }else{
            foreach($this->_parameters as $field=>&$value){
                if(strpos($this->_sql,$field)>0){
                    $this->_stmt->bindValue($field,$value);
                }
            }
        }
        return $this;
    }

    /**
     * @return BaseModel
     */
    private function buildFilters(){
        $this->_filter = '';
        foreach($this->_parameters as $field=>$value){
            if($this->_filter==''){
                $this->_filter.=$field.'=:v_'.$field;
            }else{
                $this->_filter.=' and :f_'.$field.'=:v_'.$field;
            }
        }
        return $this;
    }
    /**
     * @return array
     */
    public function fetchAssoc(){
        if($this->_stmt==null){
            $this->executeQuery();
        }
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
    /**
    * 'sex'=>[
    *               'type'=>'string',
    *               'min'=>4,
    *               'max'=>6,
    *               'label'=>'Sex',
    *               'required'=>true
    *           ],
    */
    public function definition(){
        return [

        ];
    }

    /**
     * This methods creates table and alter it as you add fields.
     * 
     */
    private function checkTableConsistency($tableName){
        $definition = $this->definition();
        try {
            $result = $this->db()->query("SELECT 1 FROM $tableName LIMIT 1");
            if(!$result){                
                $fieldInfo = isset($definition[$this->_primaryKey])?$definition[$this->_primaryKey]:[];
                //set the default field type to string
                $fieldType = isset($definition['type'])?$definition['type']:'string';
                //set the default lenght to 32
                $max = isset($fieldInfo['max'])?$fieldInfo['max']:32;
                //set the field to not required
                //id INT(6) UNSIGNED AUTO_INCREMENT 
                $field = $this->_primaryKey.' int('.$max.') AUTO_INCREMENT PRIMARY KEY';
                $required = isset($fieldInfo['required'])?$fieldInfo['required']:false;
                $this->db()->query("CREATE TABLE $tableName ($field)");
            }
        } catch (Exception $e) {
            $this->db()->query("CREATE TABLE $tableName");
        }
        $definition = $this->definition();
        $this->_stmt = $this->db()->query('DESCRIBE ' . $tableName);
        $missingFields = [];
        $extraFields = [];
        $result = $this->_stmt->fetchAll(\PDO::FETCH_ASSOC);
        echo "      //Missing properties for ".get_class($this)."\n\n";
        foreach($result as $column){
            $name = $column['Field'];
            $typ = $this->getPhpType($column['Type']);
            if(!property_exists($this,$name)){
                echo "      /**\n";
                echo "      *@var $typ $$name\n";
                echo "      */\n";
                echo "      public $$name;\n\n";
                $missingFields[] = [$column['Field'],$column['Type']];
            }
        }

        $objVars = get_object_vars($this);

        foreach($objVars as $var=>$value){
            if(strpos($var,'_') !== 0){
                if(!in_array($var,$result)){
                    $extraFields[]=[$var,gettype($var)];
                }
            }
        }

       
        foreach($extraFields as $field=>$type){
            $name = $type[0];
            $fieldInfo = isset($definition[$name])?$definition[$name]:[];
            //set the default field type to string
            $fieldType = isset($fieldInfo['type'])?$fieldInfo['type']:'string';
            //set the default lenght to 32
            $max = isset($fieldInfo['max'])?$fieldInfo['max']:32;
            //set the field to not required
            $required = isset($fieldInfo['required'])?$fieldInfo['required']:false;
            switch($fieldType){
                case 'string':
                    $sql = "ALTER TABLE $tableName ADD $name varchar($max)";
                    if($required){
                        $sql.=" NOT NULL";
                    }
                    $this->db()->exec($sql);
                    break;
                case 'integer':
                    $sql = "ALTER TABLE $tableName ADD $name int($max)";
                    if($required){
                        $sql.=" NOT NULL";
                    }
                    $this->db()->exec($sql);
                    break;
            }
        }
    }
    private function getPhpType($type){
        $pos = strpos('_'.$type,'int');
        $pos2 = strpos('_'.$type,'varchar');
        if(strpos('_'.$type,'int')==1){
            return 'integer';
        }elseif(strpos('_'.$type,'varchar')==1){
            return 'string';
        }
    }

    function parseClassname($name)
    {
        return join('', array_slice(explode('\\', $name), -1));
    }
}