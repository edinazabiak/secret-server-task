<?php 

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, 
Authorization, X-Requested-With');

include_once '../../classes/database.php';
include_once '../../classes/secret.php';

$database = new Database();
$db = $database->connect();

$secret = new Secret($db);

$data = json_decode(file_get_contents("php://input"));

$secret->text = $data->text;
$secret->expiresAfterViews = $data->expiresAfterViews;
$secret->expiresAfter = $data->expiresAfter;

if ($secret->setSecret()) {
    echo http_response_code();
} 
else {
    http_response_code(405);
    echo http_response_code();
}
