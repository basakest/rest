<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// require related files
require '../config/Database.php';
require '../objects/Product.php';

$database = new Database();
$db = $database->getConnection();
 
$product = new Product($db);
 
// get the post data
$data = json_decode(file_get_contents("php://input"));
 
// make the items in data is not empty
if (
    !empty($data->name) &&
    !empty($data->price) &&
    !empty($data->description) &&
    !empty($data->category_id)
) {
    // assign them to product properties
    $product->name = $data->name;
    $product->price = $data->price;
    $product->description = $data->description;
    $product->category_id = $data->category_id;
    $product->created = date('Y-m-d H:i:s');
 
    // create the product
    $res = $product->create();
    if ($res === true) {
        // set response code - 201 created
        http_response_code(201);
        // tell the user
        echo json_encode(array("message" => "Product was created."));
    } else {
        // set response code - 503 service unavailable
        http_response_code(503);
        // tell the user
        echo json_encode(["message" => "Unable to create product. Maybe there are some error in database.<br /> {$res}"]);
    }
}
 
// tell the user data is incomplete
else {
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
    echo json_encode(["message" => "Unable to create product. Data is incomplete."]);
}
