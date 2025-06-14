<?php
class GioHang extends Database
{
    private $collection;
    
    public function __construct()
    {
        parent::__construct();
        $this->collection = $this->mongodb->ecommerce->giohang;
    }
    
    public function addItem($userId, $productId, $quantity)
    {
        $filter = ['user_id' => $userId, 'product_id' => $productId];
        $existingItem = $this->collection->findOne($filter);
        
        if ($existingItem) {
            $this->collection->updateOne($filter, [
                '$inc' => ['quantity' => $quantity],
                '$set' => ['updated_at' => new MongoDB\BSON\UTCDateTime()]
            ]);
        } else {
            $this->collection->insertOne([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'created_at' => new MongoDB\BSON\UTCDateTime(),
                'updated_at' => new MongoDB\BSON\UTCDateTime()
            ]);
        }
        
        // Clear cache
        if ($this->redis) {
            $this->redis->del("cart_" . $userId);
        }
    }
    
    public function removeItem($userId, $productId)
    {
        $this->collection->deleteOne([
            'user_id' => $userId,
            'product_id' => $productId
        ]);
        
        // Clear cache
        if ($this->redis) {
            $this->redis->del("cart_" . $userId);
        }
    }
    
    public function updateQuantity($userId, $productId, $quantity)
    {
        if ($quantity <= 0) {
            $this->removeItem($userId, $productId);
        } else {
            $this->collection->updateOne([
                'user_id' => $userId,
                'product_id' => $productId
            ], [
                '$set' => [
                    'quantity' => $quantity,
                    'updated_at' => new MongoDB\BSON\UTCDateTime()
                ]
            ]);
        }
        
        // Clear cache
        if ($this->redis) {
            $this->redis->del("cart_" . $userId);
        }
    }
    
    public function getItems($userId)
    {
        $cacheKey = "cart_" . $userId;
        
        // Try Redis cache first
        if ($this->redis) {
            $cached = $this->redis->get($cacheKey);
            if ($cached) {
                return json_decode($cached, true);
            }
        }
        
        $pipeline = [
            ['$match' => ['user_id' => $userId]],
            ['$lookup' => [
                'from' => 'sanpham',
                'localField' => 'product_id',
                'foreignField' => '_id',
                'as' => 'product'
            ]],
            ['$unwind' => '$product'],
            ['$project' => [
                'product_id' => 1,
                'quantity' => 1,
                'product.name' => 1,
                'product.price' => 1,
                'product.image' => 1,
                'product.stock' => 1
            ]]
        ];
        
        $items = $this->collection->aggregate($pipeline)->toArray();
        
        // Cache for 30 minutes
        if ($this->redis && !empty($items)) {
            $this->redis->setex($cacheKey, 1800, json_encode($items));
        }
        
        return $items;
    }
    
    public function getTotal($userId)
    {
        $items = $this->getItems($userId);
        $total = 0;
        
        foreach ($items as $item) {
            $total += $item['quantity'] * $item['product']['price'];
        }
        
        return $total;
    }
    
    public function clear($userId)
    {
        $this->collection->deleteMany(['user_id' => $userId]);
        
        // Clear cache
        if ($this->redis) {
            $this->redis->del("cart_" . $userId);
        }
    }
}