<?php
class User
{
    //DB param
    private $conn;
    private $table = 'users';

    //users properties
    public $Id;
    public $Username;
    public $Email;
    public $Password;

    function __construct($db)
    {
        $this->conn = $db;
    }

    function createUser()
    {
        $query = "SELECT * FROM $this->table WHERE Username=:username_IN OR Email=:email_IN";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username_IN', $this->Username);
        $stmt->bindParam(':email_IN', $this->Email);
        $stmt->execute();
        if (!($stmt->execute())) {
            echo "The query could not be executed";
            die();
        }

        $count = $stmt->rowCount();
        if ($count > 0) {
            echo "The user already exist";
            die();
        }

        $query = "Insert INTO $this->table SET Username=:username_IN, Email=:email_IN, Password=:password_IN";
        $stmt = $this->conn->prepare($query);

        //security functions
        $this->Username = htmlspecialchars(strip_tags($this->Username));
        $this->Email = htmlspecialchars(strip_tags($this->Email));
        $salt = 'thisISForPassword$$Protection';
        $this->Password = md5(($this->Password . $salt));
        $stmt->bindParam(':username_IN', $this->Username);
        $stmt->bindParam(':email_IN', $this->Email);
        $stmt->bindParam(':password_IN',  $this->Password);

        if (!$stmt->execute()) {
            echo "User not created";
        }

        echo "Username: $this->Username Email:$this->Email Password:$this->Password";
    }
}
