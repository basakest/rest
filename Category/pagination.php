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

$pages = [];
// 现在是第几页
$now_page = isset($_GET['page'])?$_GET['page']:1;
// 总共有多少条数据
$total = ($category->getTotalNum())['total'];
$min_page = 1;
//总共有多少页数据，向上取整
$max_page = ceil($total / $category->num_per_page);
//下方第一个页码与当前页的间隔，向下取整
$scope1 = $now_page - floor($category->num_per_page / 2);
//下方最后一个页码与当前页的间隔
$scope2 = $category->num_per_page - $scope1;
// 下方页码第一个显示的数
$start_page = $scope1 >= 1 ? $scope : $min_page;
// 下方页码最后一个显示的数
$end_page = $now_page + $scope2;
// 如果超过末尾值则取末尾值
$end_page = $max_page >= $end_page ? $end_page : $max_page;
$pages['first'] = $start_page;
$pages['last'] = $end_page;
$category->offset = ($now_page - 1) * $category->num_per_page;
$res = $category->pagination();
if ($res) {
    http_response_code(200);
    echo json_encode(['pages' => $pages, 'res' => $res]);
}
