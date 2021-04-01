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

    public function createUser()
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

    //get all users
    public function getUsers()
    {
        //create query
        $query = "SELECT * FROM $this->table";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }



    //login function
    public function login()
    {
        $query = "SELECT Id, Username, Password FROM users WHERE Username=:username_IN AND Password=:password_IN";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username_IN', $this->Username);
        $stmt->bindParam(':password_IN',  $this->Password);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch();
            return $this->validateToken($row['Id'], $row['Username']);
        }
    }


    function validateToken($id, $username)
    {
        $this->Id = $id;
        $this->Username = $username;
        $checkToken = $this->checkToken($this->Id);

        if ($checkToken != false) {

            return $checkToken;
        }

        $token = md5(time() . $this->Id . $this->Username);
        $query = "INSERT INTO sessions (User_Id, Token, Last_used) VALUES (:userid_IN, :token_IN, :lastused_IN)";
        $stmt = $this->conn->prepare($query);
        $time = time();

        $stmt->bindParam(':userid_IN', $this->Id);
        $stmt->bindParam(':token_IN', $token);
        $stmt->bindParam(':lastused_IN', $time);
        $stmt->execute();
        return $token;
    }



    function checkToken($id)
    {
        $query = "SELECT Token, Last_used FROM sessions WHERE User_Id=:userid_IN AND Last_Used > :activeTime_IN LIMIT 1";
        $this->Id = $id;
        $activeTime = time() - (60 * 60);

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userid_IN', $this->Id);
        $stmt->bindParam(':activeTime_IN', $activeTime);
        $stmt->execute();
        $return = $stmt->fetch();
        if (isset($return['Token'])) {

            return $return['Token'];
        } else {
            return false;
        }
    }

    function isTokenValid($token)
    {
        $query = "SELECT Token, Last_used FROM sessions WHERE Token=:token_IN AND Last_used > :activeTime_IN LIMIT 1";
        $activeTime = time() - (60 * 60);
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token_IN', $token);
        $stmt->bindParam(':activeTime_IN', $activeTime);
        $stmt->execute();
        $return = $stmt->fetch();
        if (isset($return['Token'])) {

            $this->UpdateToken($return['Token']);
            return true;
        } else {

            return false;
        }
    }
    function UpdateToken($token)
    {
        $sql = "UPDATE sessions SET Last_used=:last_used_IN WHERE Token=:token_IN";
        $statement = $this->conn->prepare($sql);
        $time = time();
        $statement->bindParam(":last_used_IN", $time);
        $statement->bindParam(":token_IN", $token);
        $statement->execute();
    }
}
