<?php

namespace MGO\database;

class DBConfig
{    
    private $_config = [];
    
    public function __construct(array $config, string $environment){
        $this->_config = $config[$environment];

        foreach($this->_config as $key=>$conf){

            preg_match_all('/{([^}]*)}/', $this->_config[$key]['dsn'], $matches);

            foreach($matches[0] as $match){
                $ma = $this->getField($match);
                $this->_config[$key]['dsn'] = preg_replace('/'.$match.'/', $this->_config[$key][$ma], $this->_config[$key]['dsn']);
            }
        }
        return $this;
    }

    public function getConfigList(){
        return $this->_config;
    }

    /**
     * 
     */
    private function getField($match){
        return substr($match, 1, strlen($match)-2);
    }

    /**
     * Get the value of _dsn
     */ 
    public function getDSN()
    {
        return $this->_dsn;
    }

    /**
     * Set the value of _dsn
     *
     * @return  self
     */ 
    public function setDSN($_dsn)
    {
        $this->_dsn = $_dsn;

        return $this;
    }

    /**
     * Get the value of _username
     */ 
    public function getUsername()
    {
        return $this->_username;
    }

    /**
     * Set the value of _username
     *
     * @return  self
     */ 
    public function setUsername($_username)
    {
        $this->_username = $_username;

        return $this;
    }

    /**
     * Get the value of _password
     */ 
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * Set the value of _password
     *
     * @return  self
     */ 
    public function setPassword($_password)
    {
        $this->_password = $_password;

        return $this;
    }

    /**
     * Get the value of _auto_generate
     */ 
    public function getAutoGenerate()
    {
        return $this->_auto_generate;
    }
}