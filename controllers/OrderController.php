<?php

class OrderController
{
    private $orderModel;
    private $cartModel;
    
    public function __construct()
    {
        $this->orderModel = new DonHang();
        $this->cartModel = new GioHang();
    }
    
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'] ?? session_id();
            $customerInfo = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'address' => $_POST['address'] ?? ''
            ];
            
            $cartItems = $this->cartModel->getItems($userId);
            if (empty($cartItems)) {
                header('Location: index.php?controller=cart&action=view');
                return;
            }
            
            $orderId = $this->orderModel->create($userId, $cartItems, $customerInfo);
            $this->cartModel->clear($userId);
            
            header('Location: index.php?controller=order&action=track&id=' . $orderId);
        } else {
            $userId = $_SESSION['user_id'] ?? session_id();
            $cartItems = $this->cartModel->getItems($userId);
            $total = $this->cartModel->getTotal($userId);
            
            include '../views/order/checkout.php';
        }
    }
    
    public function track()
    {
        $orderId = $_GET['id'] ?? '';
        $order = $this->orderModel->getById($orderId);
        
        if (!$order) {
            header('Location: index.php');
            return;
        }
        
        include '../views/order/track.php';
    }
    
    public function history()
    {
        $userId = $_SESSION['user_id'] ?? session_id();
        $orders = $this->orderModel->getByUserId($userId);
        
        include '../views/order/history.php';
    }
}