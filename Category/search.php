<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
// require related files
require '../objects/Category.php';
require '../config/Database.php';
// get the instance
$database = new Database();
$conn = $database->getConnection();
$category = new Category($conn);
// get the post data
$data = json_decode(file_get_contents('php://input'));
if (!isset($data->keywords)) {
    http_response_code(500);
    echo json_encode(['message' => 'miss keywords']);
    exit;
}
$category->keywords = $data->keywords;
$res = $category->search();
if (isset($res)) {
    http_response_code(201);
    echo json_encode(['message' => 'success', 'categories' => $res]);
} else {
    http_response_code(504);
    echo json_encode(['message' => 'fails']);
}