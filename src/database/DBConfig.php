<?php

namespace MGO\database;

class DBConfig
{
    public $_dsn;
    public $_username;
    public $_password;

    public function __construct(){        
        return $this;
    }
    
    public function setConfig($config,$environment){
        $config = $config[$environment];
        $this->_dsn = $config['dsn'];

        
        preg_match_all('/{([^}]*)}/', $this->_dsn, $matches);

        foreach($matches[0] as $match){
            $ma = $this->getField($match);
            $this->_dsn = preg_replace('/'.$match.'/', $config[$ma], $this->_dsn);
        }
        $this->_username =$config['username'];
        $this->_password = $config['password'];
        return $this;
    }
    private function getField($match){
        return substr($match,1,strlen($match)-2);
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
}