<?php
class User
{
    //DB param
    private $conn;
    private $table = 'users';

    //users properties
    public $id;
    public $username;
    public $email;
    public $password;

    function __construct($db)
    {
        $this->conn = $db;
    }

    function createUser($username, $email, $password)
    {
    }
}
