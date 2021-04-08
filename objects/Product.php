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

    function updateProduct($id, $name = "", $description = "", $model = "", $price = "")
    {
        $error = new stdClass();

        if (!empty($name)) {
            $error->message = $this->Updatename($id, $name);
        }
        if (!empty($description)) {
            $error->message = $this->UpdateDescription($id, $description);
        }
        if (!empty($model)) {
            $error->message = $this->UpdateModel($id, $model);
        }
        if (!empty($price)) {
            $error->message = $this->UpdatePrice($id, $price);
        }

        return $error;
    }

    function Updatename($id, $name)
    {
        $sql = "UPDATE $this->table SET Name=:name_IN WHERE id=:user_id_IN";
        $statement = $this->conn->prepare($sql);
        $name = htmlspecialchars(strip_tags($name)); //prevents code inject
        $statement->bindParam(":name_IN", $name);
        $statement->bindParam(":user_id_IN", $id);
        $statement->execute();


        if ($statement->rowCount() < 1) {
            return "No product with id=$id was found!";
        } else {
            return "Product .$id name changed";
        }
    }
    function UpdateDescription($id, $description)
    {
        $sql = "UPDATE $this->table SET Description=:description_IN WHERE id=:user_id_IN";
        $statement = $this->conn->prepare($sql);
        $description = htmlspecialchars(strip_tags($description)); //prevents code inject
        $statement->bindParam(":description_IN", $description);
        $statement->bindParam(":user_id_IN", $id);
        $statement->execute();

        if ($statement->rowCount() < 1) {
            return "No product with id=$id was found!";
        } else {
            return "Product .$id description changed";
        }
    }

    function UpdateModel($id, $model)
    {
        $sql = "UPDATE $this->table SET Model=:model_IN WHERE id=:user_id_IN";
        $statement = $this->conn->prepare($sql);
        $model = htmlspecialchars(strip_tags($model)); //prevents code inject
        $statement->bindParam(":model_IN", $model);
        $statement->bindParam(":user_id_IN", $id);
        $statement->execute();

        if ($statement->rowCount() < 1) {
            return "No product with id=$id was found!";
        } else {
            return "Product .$id model changed";
        }
    }

    function UpdatePrice($id, $price)
    {
        $sql = "UPDATE $this->table SET Price=:price_IN WHERE id=:user_id_IN";
        $statement = $this->conn->prepare($sql);
        $price = htmlspecialchars(strip_tags($price));
        $statement->bindParam(":price_IN", $price);
        $statement->bindParam(":user_id_IN", $id);
        $statement->execute();

        if ($statement->rowCount() < 1) {
            return "No product with id=$id was found!";
        } else {
            return "Product .$id price changed";
        }
    }


    //Delete product
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
    //product by category
    public function getByCategory()
    {
        //create query
        $query = "SELECT * FROM $this->table WHERE Model LIKE :model_IN";
        $stmt = $this->conn->prepare($query);
        $this->Model = "%$this->Model%";
        $stmt->bindParam(':model_IN', $this->Model);
        if (!($stmt->execute()) || $stmt->rowCount() < 1) {
            $error = new stdClass();
            $error->message = "No product exist in the category provided";
            $error->code = "0013";
            print_r(json_encode($error));
            die();
        }

        $stmt->execute();
        return $stmt;
    }
}
