<?php

namespace MGO\database;

interface DatabaseConnection
{
    public function init();
    public function setDSN(string $dsn);
    public function setDriverClass(string $driverClass);
}