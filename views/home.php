<?php

// views/home.php
$title = 'Trang chủ - Shop Online';
include 'layout/header.php';
?>

<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>Chào mừng đến với ShopOnline</h1>
            <p>Khám phá hàng ngàn sản phẩm chất lượng với giá tốt nhất</p>
            <a href="index.php?controller=product&action=list" class="btn btn-primary">Mua sắm ngay</a>
        </div>
    </div>
</section>

<section class="featured-products">
    <div class="container">
        <h2 class="section-title">Sản phẩm nổi bật</h2>
        <div class="products-grid">
            <?php foreach ($featuredProducts as $product): ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="<?= $product['image'] ?? 'images/no-image.jpg' ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        <div class="product-overlay">
                            <button class="btn btn-cart" onclick="addToCart('<?= $product['_id'] ?>')">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">
                            <a href="index.php?controller=product&action=detail&id=<?= $product['_id'] ?>">
                                <?= htmlspecialchars($product['name']) ?>
                            </a>
                        </h3>
                        <p class="product-price"><?= number_format($product['price']) ?>đ</p>
                        <div class="product-rating">
                            <?php
                            $rating = $product['average_rating'] ?? 0;
                            for ($i = 1; $i <= 5; $i++):
                            ?>
                                <i class="fas fa-star <?= $i <= $rating ? 'active' : '' ?>"></i>
                            <?php endfor; ?>
                            <span>(<?= $product['review_count'] ?? 0 ?>)</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="categories">
    <div class="container">
        <h2 class="section-title">Danh mục sản phẩm</h2>
        <div class="categories-grid">
            <div class="category-card">
                <a href="index.php?controller=product&action=list&category=electronics">
                    <i class="fas fa-laptop"></i>
                    <h3>Điện tử</h3>
                </a>
            </div>
            <div class="category-card">
                <a href="index.php?controller=product&action=list&category=fashion">
                    <i class="fas fa-tshirt"></i>
                    <h3>Thời trang</h3>
                </a>
            </div>
            <div class="category-card">
                <a href="index.php?controller=product&action=list&category=home">
                    <i class="fas fa-home"></i>
                    <h3>Gia dụng</h3>
                </a>
            </div>
            <div class="category-card">
                <a href="index.php?controller=product&action=list&category=books">
                    <i class="fas fa-book"></i>
                    <h3>Sách</h3>
                </a>
            </div>
        </div>
    </div>
</section>

<?php include 'layout/footer.php'; ?>

<?php