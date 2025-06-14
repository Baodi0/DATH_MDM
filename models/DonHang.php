<?php
class DonHang extends Database
{
    public function create($userId, $cartItems, $customerInfo)
    {
        $orderId = uniqid('ORDER_');
        $total = 0;
        
        // Calculate total
        foreach ($cartItems as $item) {
            $total += $item['quantity'] * $item['product']['price'];
        }
        
        // Simulate Cassandra insert for order
        $orderData = [
            'id' => $orderId,
            'user_id' => $userId,
            'customer_name' => $customerInfo['name'],
            'customer_email' => $customerInfo['email'],
            'customer_phone' => $customerInfo['phone'],
            'customer_address' => $customerInfo['address'],
            'total' => $total,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
            'estimated_delivery' => date('Y-m-d', strtotime('+5 days'))
        ];
        
        // Store in session for simulation (in real app, would be Cassandra)
        if (!isset($_SESSION['orders'])) {
            $_SESSION['orders'] = [];
        }
        $_SESSION['orders'][$orderId] = $orderData;
        
        // Store order details
        if (!isset($_SESSION['order_details'])) {
            $_SESSION['order_details'] = [];
        }
        
        foreach ($cartItems as $item) {
            $_SESSION['order_details'][] = [
                'order_id' => $orderId,
                'product_id' => $item['product_id'],
                'product_name' => $item['product']['name'],
                'quantity' => $item['quantity'],
                'price' => $item['product']['price']
            ];
        }
        
        return $orderId;
    }
    
    public function getById($orderId)
    {
        return $_SESSION['orders'][$orderId] ?? null;
    }
    
    public function getByUserId($userId)
    {
        $userOrders = [];
        if (isset($_SESSION['orders'])) {
            foreach ($_SESSION['orders'] as $order) {
                if ($order['user_id'] === $userId) {
                    $userOrders[] = $order;
                }
            }
        }
        return $userOrders;
    }
    
    public function hasUserPurchased($userId, $productId)
    {
        if (!isset($_SESSION['order_details'])) {
            return false;
        }
        
        foreach ($_SESSION['order_details'] as $detail) {
            if (isset($_SESSION['orders'][$detail['order_id']])) {
                $order = $_SESSION['orders'][$detail['order_id']];
                if ($order['user_id'] === $userId && $detail['product_id'] === $productId) {
                    return true;
                }
            }
        }
        
        return false;
    }
}