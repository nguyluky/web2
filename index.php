<?php
// Error reporting for debugging (remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Set headers
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}

// Include necessary files
require_once 'config.php';
require_once 'Router.php';
require_once 'Database.php';
require_once 'utils/Response.php';
require_once 'controllers/BaseController.php';
require_once 'controllers/ItemController.php';
require_once 'models/Item.php';

// Get the request URI and HTTP method
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Parse URL to get path
$uri = parse_url($requestUri, PHP_URL_PATH);
$uri = str_replace('/api/', '', $uri);
$uri = rtrim($uri, '/');

// Get request data
$data = json_decode(file_get_contents("php://input"), true);
if (!$data) $data = [];
$data = array_merge($_GET, $_POST, $data);

// Initialize router
$router = new Router();

// Define routes
$router->addRoute('GET', 'items', ['ItemController', 'getAll']);
$router->addRoute('GET', 'items/([0-9]+)', ['ItemController', 'getById']);
$router->addRoute('POST', 'items', ['ItemController', 'create']);
$router->addRoute('PUT', 'items/([0-9]+)', ['ItemController', 'update']);
$router->addRoute('DELETE', 'items/([0-9]+)', ['ItemController', 'delete']);

// Route the request
$router->route($requestMethod, $uri, $data);