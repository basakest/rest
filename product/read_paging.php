<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
// include database and object files
require '../config/core.php';
require '../shared/Utilities.php';
require '../config/Database.php';
require '../objects/Product.php';
  
$utilities = new Utilities();
  
// get the database instance and get connection
$database = new Database();
$db = $database->getConnection();
  
$product = new Product($db);
  
//$from_record_num, $records_per_page comes from core.php
$stmt = $product->readPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();
  
if($num > 0){
    $products_arr = [];
    $products_arr["records"] = [];
    $products_arr["paging"] = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $product_item = [
            "id" => $id,
            "name" => $name,
            "description" => html_entity_decode($description),
            "price" => $price,
            "category_id" => $category_id,
            "category_name" => $category_name
        ];
        array_push($products_arr["records"], $product_item);
    }
    $total_rows = $product->count();
    $page_url = "{$home_url}product/read_paging.php?";
    $paging = $utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    $products_arr["paging"] = $paging;
    http_response_code(200);
    echo json_encode($products_arr);
} else {
    http_response_code(404);
    echo json_encode(
        ["message" => "No products found."]
    );
}
?>