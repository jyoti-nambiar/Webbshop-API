<?php
include('../../config/Database_conn.php');
include('../../objects/Product.php');

//call database object
$database = new Database();
$db = $database->connect();

//call product object
$product = new Product($db);
