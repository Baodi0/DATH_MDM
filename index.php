<?php
session_start();

spl_autoload_register(function ($class) {
    $paths = [
        '../controllers/',
        '../models/',
        '../config/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

$action = $_GET['action'] ?? 'home';
$controller = $_GET['controller'] ?? 'product';

try {
    switch ($controller) {
        case 'product':
            $productController = new ProductController();
            switch ($action) {
                case 'list':
                    $productController->list();
                    break;
                case 'detail':
                    $productController->detail();
                    break;
                case 'search':
                    $productController->search();
                    break;
                default:
                    $productController->home();
            }
            break;
            
        case 'cart':
            $cartController = new CartController();
            switch ($action) {
                case 'add':
                    $cartController->add();
                    break;
                case 'remove':
                    $cartController->remove();
                    break;
                case 'update':
                    $cartController->update();
                    break;
                case 'view':
                    $cartController->view();
                    break;
                default:
                    $cartController->view();
            }
            break;
            
        case 'order':
            $orderController = new OrderController();
            switch ($action) {
                case 'create':
                    $orderController->create();
                    break;
                case 'track':
                    $orderController->track();
                    break;
                case 'history':
                    $orderController->history();
                    break;
                default:
                    $orderController->history();
            }
            break;
            
        case 'review':
            $reviewController = new ReviewController();
            switch ($action) {
                case 'add':
                    $reviewController->add();
                    break;
                case 'list':
                    $reviewController->list();
                    break;
            }
            break;
            
        default:
            $productController = new ProductController();
            $productController->home();
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    include '../views/error.php';
}