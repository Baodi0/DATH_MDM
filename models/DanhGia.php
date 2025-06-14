<?php
class DanhGia extends Database
{
    private $collection;
    
    public function __construct()
    {
        parent::__construct();
        $this->collection = $this->mongodb->ecommerce->danhgia;
    }
    
    public function add($productId, $userId, $rating, $comment)
    {
        $this->collection->insertOne([
            'product_id' => $productId,
            'user_id' => $userId,
            'rating' => $rating,
            'comment' => $comment,
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ]);
    }
    
    public function getByProductId($productId, $limit = 10)
    {
        return $this->collection->find(['product_id' => $productId], [
            'limit' => $limit,
            'sort' => ['created_at' => -1]
        ])->toArray();
    }
    
    public function getAverageRating($productId)
    {
        $pipeline = [
            ['$match' => ['product_id' => $productId]],
            ['$group' => [
                '_id' => null,
                'average' => ['$avg' => '$rating'],
                'count' => ['$sum' => 1]
            ]]
        ];
        
        $result = $this->collection->aggregate($pipeline)->toArray();
        return $result[0] ?? ['average' => 0, 'count' => 0];
    }
}
