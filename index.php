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
require_once './controllers/BaseController.php';
require_once './controllers/ItemController.php';
require_once './controllers/UserController.php';
require_once './controllers/CartController.php';
require_once './controllers/ImportController.php';
require_once './controllers/OrderController.php';
require_once './controllers/SupplierController.php';
require_once './controllers/WarrantyController.php';
require_once './models/Item.php';
require_once './models/User.php';
require_once './models/Cart.php';
require_once './models/Import.php';
require_once './models/Order.php';
require_once './models/Supplier.php';
require_once './models/Warranty.php';

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
// items
$router->addRoute('GET', 'item', ['ItemController', 'getAll']);
$router->addRoute('GET', 'item/([0-9]+)', ['ItemController', 'getById']);
$router->addRoute('POST', 'item', ['ItemController', 'create']);
$router->addRoute('PUT', 'item/([0-9]+)', ['ItemController', 'update']);
$router->addRoute('DELETE', 'item/([0-9]+)', ['ItemController', 'delete']);

// user
$router->addRoute('POST', 'user', ['UserController', 'create']);
$router->addRoute('GET', 'user', ['UserController', 'getAll']);
$router->addRoute('GET', 'user/([0-9]+)', ['UserController', 'getById']);
$router->addRoute('PUT', 'user/([0-9]+)', ['UserController', 'updateById']);
$router->addRoute('DELETE', 'user/([0-9]+)', ['UserController', 'deleteById']);

// supplier
$router->addRoute('GET', 'supplier', ['SupplierController', 'getAll']);
$router->addRoute('GET', 'supplier/([0-9]+)', ['SupplierController', 'getById']);
$router->addRoute('POST', 'supplier', ['SupplierController', 'create']);
$router->addRoute('PUT', 'supplier/([0-9]+)', ['SupplierController', 'updateById']);
$router->addRoute('DELETE', 'supplier/([0-9]+)', ['SupplierController', 'deleteById']);

// order
$router->addRoute('GET', 'order', ['OrderController', 'getAll']);
$router->addRoute('GET', 'order/([0-9]+)', ['OrderController', 'getById']);
$router->addRoute('POST', 'order', ['OrderController', 'create']);

// import
$router->addRoute('GET', 'import', ['ImportController', 'getAll']);
$router->addRoute('GET', 'import/([0-9]+)', ['ImportController', 'getById']);
$router->addRoute('POST', 'import', ['ImportController', 'create']);

// warranty
$router->addRoute('GET', 'warranty', ['WarrantyController', 'getAll']);
$router->addRoute('GET', 'warranty/([0-9]+)', ['WarrantyController', 'getById']);
$router->addRoute('GET', 'warranty/account/([0-9]+)', ['WarrantyController', 'getByAccountId']);
$router->addRoute('GET', 'warranty/product/([0-9]+)', ['WarrantyController', 'getByProductId']);
$router->addRoute('PUT', 'warranty/([0-9]+)', ['WarrantyController', 'update']);

// cart
$router->addRoute('POST', 'cart', ['CartController', 'create']);
$router->addRoute('GET', 'cart/([0-9]+)', ['CartController', 'getByAccountId']);
$router->addRoute('GET', 'cart/([0-9]+)/([0-9]+)', ['CartController', 'getByProductId']);
$router->addRoute('PUT', 'cart/([0-9]+)/([0-9]+)', ['CartController', 'update']);
$router->addRoute('DELETE', 'cart/([0-9]+)/([0-9]+)', ['CartController', 'delete']);
// Route the request
$router->route($requestMethod, $uri, $data);