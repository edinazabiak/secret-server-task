<?php 

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../classes/database.php';
include_once '../../classes/secret.php';

$database = new Database();
$db = $database->connect();

$secret = new Secret($db);

$secret->hash = isset($_GET['hash']) ? $_GET['hash'] : die();

$row = $secret->getSecretByHash();

if ($row == null) {
    http_response_code(404);
    echo http_response_code();
}
else {
    $secret_array = array (
        'hash' => $secret->hash,
        'secretText' => $secret->text,
        'remainingViews' => $secret->expiresAfterViews,
        'expiresAt' => $secret->expiresAfter,
        'createdAt' => $secret->createdAt
    );
    
    echo json_encode($secret_array);
}
