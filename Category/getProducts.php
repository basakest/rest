<?php
// required header
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//require related file
require '../objects/Category.php';
require '../config/Database.php';

//get instance
$database = new Database();
$conn = $database->getConnection();
$category = new Category($conn);

//get post data
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->id)) {
    http_response_code(500);
    echo json_encode(['message' => 'id is not found']);
    exit();
}
$category->id = $data->id;
$res = $category->getProducts();

if ($res) {
    http_response_code(201);
    echo json_encode(['message' => 'successful', 'products' => $res]);
} else {
    http_response_code(503);
    echo json_encode(['message' => 'something went worng' . $res]);
}
