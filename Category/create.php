<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// require related files
require '../config/Database.php';
require '../objects/Category.php';

$database = new Database();
$db = $database->getConnection();
 
$category = new Category($db);

// get the post data
$data = json_decode(file_get_contents("php://input"));
if (
    !empty($data->name) && !empty($data->description)
) {
    // assign the value to category properties
    $category->name = $data->name;
    $category->description = $data->description;
    $category->created = date('Y-m-d H:i:s');
    // create the category
    $res = $category->create();
    if ($res === true) {
        http_response_code(201);
        echo json_encode(array("message" => "Category was created."));
    } else {
        http_response_code(503);
        echo json_encode(["message" => "Unable to create category. Maybe there are some error in database.<br /> {$res}"]);
    }
}