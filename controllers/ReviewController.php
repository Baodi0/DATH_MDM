<?php
class ReviewController
{
    private $reviewModel;
    private $orderModel;
    
    public function __construct()
    {
        $this->reviewModel = new DanhGia();
        $this->orderModel = new DonHang();
    }
    
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'] ?? '';
            $userId = $_SESSION['user_id'] ?? session_id();
            $rating = (int)($_POST['rating'] ?? 5);
            $comment = $_POST['comment'] ?? '';
            
            // Kiểm tra user đã mua sản phẩm chưa
            $hasPurchased = $this->orderModel->hasUserPurchased($userId, $productId);
            
            if ($hasPurchased) {
                $this->reviewModel->add($productId, $userId, $rating, $comment);
                $response = ['success' => true, 'message' => 'Đánh giá đã được thêm'];
            } else {
                $response = ['success' => false, 'message' => 'Bạn cần mua sản phẩm trước khi đánh giá'];
            }
            
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }
    
    public function list()
    {
        $productId = $_GET['product_id'] ?? '';
        $reviews = $this->reviewModel->getByProductId($productId);
        
        header('Content-Type: application/json');
        echo json_encode($reviews);
    }
}