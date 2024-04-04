<?php
require('../src/Controller/SegnaliController.php');
require('../src/Controller/CategorieController.php');
require('../src/System/DatabaseConnector.php');

$dbConnection = (new DatabaseConnector())->getConnection();


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );


if ($uri[1] == 'segnali') {
    $idSegnale = null;
    if (isset($uri[2])) {
        $idSegnale = (int) $uri[2];
    }

    $requestMethod = $_SERVER["REQUEST_METHOD"];

    $controller = new SegnaliController($dbConnection, $requestMethod, $idSegnale);
    $controller->processRequest();
}else if ($uri[1] == 'categorie'){
    $idCategoria = null;
    if (isset($uri[2])) {
        $idCategoria = (int) $uri[2];
    }

    $requestMethod = $_SERVER["REQUEST_METHOD"];

    $controller = new CategorieController($dbConnection, $requestMethod, $idCategoria);
    $controller->processRequest();
}else{
    header("HTTP/1.1 404 Not Found");
    exit();
}



