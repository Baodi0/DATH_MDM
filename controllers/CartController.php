<?php

class ProductController
{
    private $productModel;
    private $reviewModel;
    
    public function __construct()
    {
        $this->productModel = new SanPham();
        $this->reviewModel = new DanhGia();
    }
    
    public function home()
    {
        $featuredProducts = $this->productModel->getFeatured();
        $categories = $this->productModel->getCategories();
        include '../views/home.php';
    }
    
    public function list()
    {
        $category = $_GET['category'] ?? '';
        $page = $_GET['page'] ?? 1;
        $limit = 12;
        
        $products = $this->productModel->getByCategory($category, $page, $limit);
        $totalProducts = $this->productModel->countByCategory($category);
        $totalPages = ceil($totalProducts / $limit);
        
        include '../views/product/list.php';
    }
    
    public function detail()
    {
        $id = $_GET['id'] ?? '';
        if (empty($id)) {
            header('Location: index.php');
            return;
        }
        
        $product = $this->productModel->getById($id);
        if (!$product) {
            header('Location: index.php');
            return;
        }
        
        $reviews = $this->reviewModel->getByProductId($id);
        $relatedProducts = $this->productModel->getRelated($product['category'], $id);
        
        include '../views/product/detail.php';
    }
    
    public function search()
    {
        $query = $_GET['q'] ?? '';
        $suggestions = [];
        
        if (strlen($query) >= 2) {
            $suggestions = $this->productModel->search($query);
        }
        
        header('Content-Type: application/json');
        echo json_encode($suggestions);
    }
}