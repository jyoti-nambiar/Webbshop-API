<?php
class Product
{
    //DB param
    private $conn;
    private $table = 'products';

    //Product properties
    public $Id;
    public $Name;
    public $Description;
    public $Model;
    public $Price;

    //constructor with DB
    function __construct($db)
    {

        $this->conn = $db;
    }

    public function getProducts()
    {
        //create query
        $query = "SELECT * FROM $this->table";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    //Get individual product
    public function singleProduct()
    {
        $query = "SELECT * FROM $this->table WHERE Id=:id_IN LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_IN', $this->Id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        //set properties
        $this->Name = $row['Name'];
        $this->Description = $row['Description'];
        $this->Model = $row['Model'];
        $this->Price = $row['Price'];
    }

    //create new Product
    public function createProduct()
    {
        $query = "Insert INTO '.$this->table.' SET Name=:name_IN, Description=:description_IN, Model=:model_IN, Price=:price_IN";
        $stmt = $this->conn->prepare($query);

        //security functions
        $this->Name = htmlspecialchars(strip_tags($this->Name));
        $this->Description = htmlspecialchars(strip_tags($this->Description));
        $this->Model = htmlspecialchars(strip_tags($this->Model));
        $this->Price = htmlspecialchars(strip_tags($this->Price));
        $stmt->bindParam(':name_IN', $this->Name);
        $stmt->bindParam(':description_IN', $this->Description);
        $stmt->bindParam(':model_IN', $this->Model);
        $stmt->bindParam(':price_IN', $this->Price);
        if ($stmt->execute()) {
            return true;
        }
        printf("Error:%s.\n", $stmt->error);
        return false;
    }
}
