<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// require related files
require '../config/Database.php';
require '../objects/Product.php';
 
// instantiate an database object and create the database connection
$database = new Database();
$db = $database->getConnection();
 
// initialize the product object
$product = new Product($db);
 
// get products
$stmt = $product->read();
$num = $stmt->rowCount();
 
if($num>0){
 
    // products array
    $products_arr = [];
    $products_arr["records"] = [];
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $product_item=[
            "id" => $id,
            "name" => $name,
            "description" => html_entity_decode($description),
            "price" => $price,
            "category_id" => $category_id,
            "category_name" => $category_name
        ];
 
        array_push($products_arr["records"], $product_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
    // show the results in json format
    echo json_encode($products_arr);
} else {
    // set response code - 404 Not found
    http_response_code(404);
    // tell the user no products found
    echo json_encode(
        ["message" => "No products found."]
    );
}
 
