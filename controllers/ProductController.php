<?php

class CartController
{
    private $cartModel;
    private $productModel;
    
    public function __construct()
    {
        $this->cartModel = new GioHang();
        $this->productModel = new SanPham();
    }
    
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'] ?? '';
            $quantity = (int)($_POST['quantity'] ?? 1);
            $userId = $_SESSION['user_id'] ?? session_id();
            
            $product = $this->productModel->getById($productId);
            if ($product && $product['stock'] >= $quantity) {
                $this->cartModel->addItem($userId, $productId, $quantity);
                $response = ['success' => true, 'message' => 'Đã thêm vào giỏ hàng'];
            } else {
                $response = ['success' => false, 'message' => 'Sản phẩm không có sẵn'];
            }
            
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }
    
    public function remove()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'] ?? '';
            $userId = $_SESSION['user_id'] ?? session_id();
            
            $this->cartModel->removeItem($userId, $productId);
            $response = ['success' => true, 'message' => 'Đã xóa khỏi giỏ hàng'];
            
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }
    
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'] ?? '';
            $quantity = (int)($_POST['quantity'] ?? 1);
            $userId = $_SESSION['user_id'] ?? session_id();
            
            $this->cartModel->updateQuantity($userId, $productId, $quantity);
            $response = ['success' => true, 'message' => 'Đã cập nhật giỏ hàng'];
            
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }
    
    public function view()
    {
        $userId = $_SESSION['user_id'] ?? session_id();
        $cartItems = $this->cartModel->getItems($userId);
        $total = $this->cartModel->getTotal($userId);
        
        include '../views/cart/view.php';
    }
}