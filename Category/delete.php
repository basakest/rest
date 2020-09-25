<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//require related files
require '../config/Database.php';
require '../objects/Category.php';

//create object instance
$database = new Database();
$conn = $database->getConnection();
$category = new Category($conn);

//get the post data
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->id)) {
    http_response_code(500);
    echo json_encode(['message' => 'id is missing']);
    exit;
}

//attach the data to category object
$category->id = $data->id;

$res = $category->delete();
if ($res === true) {
    http_response_code(201);
    echo json_encode(['message' => 'delete successfully']);
} else {
    http_response_code(503);
    echo json_encode(['message' => 'delete fails.Maybe there are something went wrong about databases' . $res]);
}