<?php
class SanPham extends Database
{
    private $collection;
    
    public function __construct()
    {
        parent::__construct();
        $this->collection = $this->mongodb->ecommerce->sanpham;
    }
    
    public function getAll($page = 1, $limit = 12)
    {
        $skip = ($page - 1) * $limit;
        return $this->collection->find([], [
            'skip' => $skip,
            'limit' => $limit,
            'sort' => ['created_at' => -1]
        ])->toArray();
    }
    
    public function getById($id)
    {
        $cacheKey = "product_" . $id;
        
        // Try Redis cache first
        if ($this->redis) {
            $cached = $this->redis->get($cacheKey);
            if ($cached) {
                return json_decode($cached, true);
            }
        }
        
        $product = $this->collection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
        
        if ($product && $this->redis) {
            $this->redis->setex($cacheKey, 3600, json_encode($product->toArray()));
        }
        
        return $product ? $product->toArray() : null;
    }
    
    public function getByCategory($category, $page = 1, $limit = 12)
    {
        $skip = ($page - 1) * $limit;
        $filter = empty($category) ? [] : ['category' => $category];
        
        return $this->collection->find($filter, [
            'skip' => $skip,
            'limit' => $limit,
            'sort' => ['created_at' => -1]
        ])->toArray();
    }
    
    public function countByCategory($category)
    {
        $filter = empty($category) ? [] : ['category' => $category];
        return $this->collection->countDocuments($filter);
    }
    
    public function getFeatured($limit = 8)
    {
        return $this->collection->find(['featured' => true], [
            'limit' => $limit,
            'sort' => ['created_at' => -1]
        ])->toArray();
    }
    
    public function getCategories()
    {
        return $this->collection->distinct('category');
    }
    
    public function search($query, $limit = 10)
    {
        $regex = new MongoDB\BSON\Regex($query, 'i');
        return $this->collection->find([
            '$or' => [
                ['name' => $regex],
                ['description' => $regex]
            ]
        ], ['limit' => $limit])->toArray();
    }
    
    public function getRelated($category, $excludeId, $limit = 4)
    {
        return $this->collection->find([
            'category' => $category,
            '_id' => ['$ne' => new MongoDB\BSON\ObjectId($excludeId)]
        ], ['limit' => $limit])->toArray();
    }
}