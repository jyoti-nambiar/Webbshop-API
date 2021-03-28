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
    //Get all products
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
        if (!($stmt->execute()) || $stmt->rowCount() < 1) {
            $error = new stdClass();
            $error->message = "No product with the id provided";
            $error->code = "0003";
            print_r(json_encode($error));
            die();
        }

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
        $query = "SELECT * FROM $this->table WHERE Name=:name_IN AND Description=:description_IN";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name_IN', $this->Name);
        $stmt->bindParam(':description_IN', $this->Description);
        $stmt->execute();
        if (!($stmt->execute())) {
            echo "The query could not be executed";
            die();
        }

        $count = $stmt->rowCount();
        if ($count > 0) {
            echo "The product already exist";
            die();
        }

        $query = "Insert INTO $this->table SET Name=:name_IN, Description=:description_IN, Model=:model_IN, Price=:price_IN";
        $stmt = $this->conn->prepare($query);

        //security functions
        $this->Name = htmlspecialchars(strip_tags($this->Name));
        $this->Description = htmlspecialchars(strip_tags($this->Description));
        $this->Model = htmlspecialchars(strip_tags($this->Model));
        $this->Price = htmlspecialchars(strip_tags($this->Price));
        $stmt->bindParam(':name_IN', $this->Name);
        $stmt->bindParam(':description_IN', $this->Description);
        $stmt->bindParam(':model_IN',  $this->Model);
        $stmt->bindParam(':price_IN', $this->Price);
        if (!$stmt->execute()) {
            echo "Product not created";
        }

        echo "Name: $this->Name Description:$this->Description Model:$this->Model Price:$this->Price";
    }


    //update product

    public function updateProduct()
    {
        $query = "UPDATE $this->table SET Name=:name_IN, Description=:description_IN, Model=:model_IN, Price=:price_IN WHERE ID=:id_IN";
        $stmt = $this->conn->prepare($query);

        //security functions
        $this->Name = htmlspecialchars(strip_tags($this->Name));
        $this->Description = htmlspecialchars(strip_tags($this->Description));
        $this->Model = htmlspecialchars(strip_tags($this->Model));
        $this->Price = htmlspecialchars(strip_tags($this->Price));
        $this->Id = htmlspecialchars(strip_tags($this->Id));
        //bind data
        $stmt->bindParam(':name_IN', $this->Name);
        $stmt->bindParam(':description_IN', $this->Description);
        $stmt->bindParam(':model_IN',  $this->Model);
        $stmt->bindParam(':price_IN', $this->Price);
        $stmt->bindParam(':id_IN', $this->Id);
        if (!$stmt->execute()) {
            echo "Product not created";
        }
        $stmt->execute();
        echo "Name: $this->Name Description:$this->Description Model:$this->Model Price:$this->Price";
    }

    public function deleteProduct()
    {
        $query = "DELETE FROM $this->table WHERE Id=:id_IN ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_IN', $this->Id);
        if (!($stmt->execute())) {
            $error = new stdClass();
            $error->message = "No product with the id provided exist";
            $error->code = "0003";
            print_r(json_encode($error));
            die();
        }

        $stmt->execute();
        echo "Product deleted";
    }
}
