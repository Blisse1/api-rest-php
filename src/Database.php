<?php

class Database
{
    // On php 4.1.30
    private $host;
    private $name;
    private $user;
    private $password;

    public function __construct($host, $name, $user, $password)
    {
        $this -> host = $host;
        $this -> name = $name;
        $this -> user = $user;
        $this -> password = $password;
    }

    public function getConnection(): PDO
    {
        $dsn = "mysql:host={$this -> host};dbname={$this -> name};charset=utf8";
        return new PDO($dsn, $this -> user, $this -> password, [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_STRINGIFY_FETCHES => false,
        ]);
    }
}