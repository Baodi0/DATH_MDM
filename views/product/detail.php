<?php
// views/product/detail.php
$title = htmlspecialchars($product['name']) . ' - Shop Online';
include '../views/layout/header.php';
?>

<section class="product-detail">
    <div class="container">
        <div class="breadcrumb">
            <a href="index.php">Trang chủ</a>
            <span>/</span>
            <a href="index.php?controller=product&action=list">Sản phẩm</a>
            <span>/</span>
            <span><?= htmlspecialchars($product['name']) ?></span>
        </div>

        <div class="product-detail-content">
            <div class="product-images">
                <div class="main-image">
                    <img id="mainImage" src="<?= $product['image'] ?? 'images/no-image.jpg' ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                </div>
                <?php if (!empty($product['gallery'])): ?>
                    <div class="thumbnail-images">
                        <?php foreach ($product['gallery'] as $image): ?>
                            <img src="<?= $image ?>" alt="<?= htmlspecialchars($product['name']) ?>" onclick="changeMainImage(this)">
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="product-info">
                <h1 class="product-title"><?= htmlspecialchars($product['name']) ?></h1>
                
                <div class="product-rating">
                    <?php
                    $averageRating = 0;
                    $reviewCount = 0;
                    if (!empty($reviews)) {
                        $totalRating = array_sum(array_column($reviews, 'rating'));
                        $reviewCount = count($reviews);
                        $averageRating = $totalRating / $reviewCount;
                    }
                    
                    for ($i = 1; $i <= 5; $i++):
                    ?>
                        <i class="fas fa-star <?= $i <= $averageRating ? 'active' : '' ?>"></i>
                    <?php endfor; ?>
                    <span class="rating-text">(<?= $reviewCount ?> đánh giá)</span>
                </div>

                <div class="product-price">
                    <span class="current-price"><?= number_format($product['price']) ?>đ</span>
                    <?php if (!empty($product['original_price']) && $product['original_price'] > $product['price']): ?>
                        <span class="original-price"><?= number_format($product['original_price']) ?>đ</span>
                        <span class="discount">-<?= round((($product['original_price'] - $product['price']) / $product['original_price']) * 100) ?>%</span>
                    <?php endif; ?>
                </div>

                <div class="product-stock">
                    <?php if (($product['stock'] ?? 0) > 0): ?>
                        <span class="in-stock"><i class="fas fa-check-circle"></i> Còn hàng (<?= $product['stock'] ?> sản phẩm)</span>
                    <?php else: ?>
                        <span class="out-of-stock"><i class="fas fa-times-circle"></i> Hết hàng</span>
                    <?php endif; ?>
                </div>

                <div class="product-description">
                    <h3>Mô tả sản phẩm</h3>
                    <p><?= nl2br(htmlspecialchars($product['description'] ?? '')) ?></p>
                </div>

                <div class="product-actions">
                    <div class="quantity-selector">
                        <label>Số lượng:</label>
                        <div class="quantity-controls">
                            <button type="button" onclick="decreaseQuantity()">-</button>
                            <input type="number" id="quantity" value="1" min="1" max="<?= $product['stock'] ?? 0 ?>">
                            <button type="button" onclick="increaseQuantity()">+</button>
                        </div>
                    </div>
                    
                    <div class="action-buttons">
                        <button class="btn btn-primary btn-add-cart" onclick="addToCart('<?= $product['_id'] ?>')" <?= ($product['stock'] ?? 0) <= 0 ? 'disabled' : '' ?>>
                            <i class="fas fa-shopping-cart"></i>
                            Thêm vào giỏ hàng
                        </button>
                        <button class="btn btn-secondary btn-buy-now" <?= ($product['stock'] ?? 0) <= 0 ? 'disabled' : '' ?>>
                            <i class="fas fa-bolt"></i>
                            Mua ngay
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Reviews Section -->
        <div class="product-reviews">
            <h2>Đánh giá sản phẩm</h2>
            
            <div class="review-summary">
                <div class="rating-overview">
                    <div class="average-rating">
                        <span class="rating-number"><?= number_format($averageRating, 1) ?></span>
                        <div class="rating-stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?= $i <= $averageRating ? 'active' : '' ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <span class="rating-count"><?= $reviewCount ?> đánh giá</span>
                    </div>
                </div>
            </div>

            <div class="add-review">
                <h3>Thêm đánh giá của bạn</h3>
                <form id="reviewForm" class="review-form">
                    <div class="rating-input">
                        <label>Đánh giá:</label>
                        <div class="star-rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star" data-rating="<?= $i ?>" onclick="setRating(<?= $i ?>)"></i>
                            <?php endfor; ?>
                        </div>
                        <input type="hidden" id="rating" name="rating" value="5">
                    </div>
                    <div class="comment-input">
                        <label for="comment">Bình luận:</label>
                        <textarea id="comment" name="comment" rows="4" placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                </form>
            </div>

            <div class="reviews-list">
                <?php foreach ($reviews as $review): ?>
                    <div class="review-item">
                        <div class="review-header">
                            <div class="reviewer-name">Người dùng</div>
                            <div class="review-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?= $i <= $review['rating'] ? 'active' : '' ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <div class="review-date"><?= date('d/m/Y', $review['created_at']->toDateTime()->getTimestamp()) ?></div>
                        </div>
                        <div class="review-content">
                            <p><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Related Products -->
        <?php if (!empty($relatedProducts)): ?>
            <div class="related-products">
                <h2>Sản phẩm liên quan</h2>
                <div class="products-grid">
                    <?php foreach ($relatedProducts as $relatedProduct): ?>
                        <div class="product-card">
                            <div class="product-image">
                                <img src="<?= $relatedProduct['image'] ?? 'images/no-image.jpg' ?>" alt="<?= htmlspecialchars($relatedProduct['name']) ?>">
                                <div class="product-overlay">
                                    <button class="btn btn-cart" onclick="addToCart('<?= $relatedProduct['_id'] ?>')">
                                        <i class="fas fa-shopping-cart"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="product-info">
                                <h3 class="product-name">
                                    <a href="index.php?controller=product&action=detail&id=<?= $relatedProduct['_id'] ?>">
                                        <?= htmlspecialchars($relatedProduct['name']) ?>
                                    </a>
                                </h3>
                                <p class="product-price"><?= number_format($relatedProduct['price']) ?>đ</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include '../views/layout/footer.php'; ?><?php