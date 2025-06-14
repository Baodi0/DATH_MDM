<?php
// views/product/list.php
$title = 'Danh sách sản phẩm - Shop Online';
include '../views/layout/header.php';
?>

<section class="product-list">
    <div class="container">
        <div class="page-header">
            <h1>
                <?php 
                if (!empty($category)) {
                    $categoryNames = [
                        'electronics' => 'Điện tử',
                        'fashion' => 'Thời trang',
                        'home' => 'Gia dụng',
                        'books' => 'Sách'
                    ];
                    echo $categoryNames[$category] ?? ucfirst($category);
                } else {
                    echo 'Tất cả sản phẩm';
                }
                ?>
            </h1>
            <div class="breadcrumb">
                <a href="index.php">Trang chủ</a>
                <span>/</span>
                <span>Sản phẩm</span>
                <?php if (!empty($category)): ?>
                    <span>/</span>
                    <span><?= $categoryNames[$category] ?? ucfirst($category) ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="product-list-content">
            <div class="filters-sidebar">
                <div class="filter-section">
                    <h3>Danh mục</h3>
                    <ul class="category-filter">
                        <li><a href="index.php?controller=product&action=list" class="<?= empty($category) ? 'active' : '' ?>">Tất cả</a></li>
                        <li><a href="index.php?controller=product&action=list&category=electronics" class="<?= $category === 'electronics' ? 'active' : '' ?>">Điện tử</a></li>
                        <li><a href="index.php?controller=product&action=list&category=fashion" class="<?= $category === 'fashion' ? 'active' : '' ?>">Thời trang</a></li>
                        <li><a href="index.php?controller=product&action=list&category=home" class="<?= $category === 'home' ? 'active' : '' ?>">Gia dụng</a></li>
                        <li><a href="index.php?controller=product&action=list&category=books" class="<?= $category === 'books' ? 'active' : '' ?>">Sách</a></li>
                    </ul>
                </div>

                <div class="filter-section">
                    <h3>Khoảng giá</h3>
                    <div class="price-filter">
                        <div class="price-input">
                            <input type="number" id="minPrice" placeholder="Từ" min="0">
                            <span>-</span>
                            <input type="number" id="maxPrice" placeholder="Đến" min="0">
                        </div>
                        <button onclick="filterByPrice()" class="btn btn-secondary">Lọc</button>
                    </div>
                </div>

                <div class="filter-section">
                    <h3>Đánh giá</h3>
                    <div class="rating-filter">
                        <div class="rating-option">
                            <input type="checkbox" id="rating5" value="5">
                            <label for="rating5">
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                5 sao
                            </label>
                        </div>
                        <div class="rating-option">
                            <input type="checkbox" id="rating4" value="4">
                            <label for="rating4">
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                </div>
                                4 sao trở lên
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="products-container">
                <div class="products-header">
                    <div class="products-count">
                        Hiển thị <?= count($products) ?> trong tổng số <?= $totalProducts ?> sản phẩm
                    </div>
                    <div class="sort-options">
                        <select id="sortSelect" onchange="sortProducts(this.value)">
                            <option value="newest">Mới nhất</option>
                            <option value="price_asc">Giá thấp đến cao</option>
                            <option value="price_desc">Giá cao đến thấp</option>
                            <option value="name_asc">Tên A-Z</option>
                            <option value="name_desc">Tên Z-A</option>
                        </select>
                    </div>
                </div>

                <?php if (empty($products)): ?>
                    <div class="no-products">
                        <div class="no-products-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3>Không tìm thấy sản phẩm nào</h3>
                        <p>Hãy thử tìm kiếm với từ khóa khác hoặc xem tất cả sản phẩm.</p>
                        <a href="index.php?controller=product&action=list" class="btn btn-primary">Xem tất cả sản phẩm</a>
                    </div>
                <?php else: ?>
                    <div class="products-grid" id="productsGrid">
                        <?php foreach ($products as $product): ?>
                            <div class="product-card" data-price="<?= $product['price'] ?>" data-name="<?= htmlspecialchars($product['name']) ?>">
                                <div class="product-image">
                                    <img src="<?= $product['image'] ?? 'images/no-image.jpg' ?>" alt="<?= htmlspecialchars($product['name']) ?>" loading="lazy">
                                    <?php if (!empty($product['original_price']) && $product['original_price'] > $product['price']): ?>
                                        <div class="product-badge sale">
                                            -<?= round((($product['original_price'] - $product['price']) / $product['original_price']) * 100) ?>%
                                        </div>
                                    <?php endif; ?>
                                    <?php if (($product['stock'] ?? 0) <= 0): ?>
                                        <div class="product-badge out-of-stock">Hết hàng</div>
                                    <?php endif; ?>
                                    <div class="product-overlay">
                                        <button class="btn btn-cart" onclick="addToCart('<?= $product['_id'] ?>')" <?= ($product['stock'] ?? 0) <= 0 ? 'disabled' : '' ?>>
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                        <a href="index.php?controller=product&action=detail&id=<?= $product['_id'] ?>" class="btn btn-view">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <h3 class="product-name">
                                        <a href="index.php?controller=product&action=detail&id=<?= $product['_id'] ?>">
                                            <?= htmlspecialchars($product['name']) ?>
                                        </a>
                                    </h3>
                                    <div class="product-price">
                                        <span class="current-price"><?= number_format($product['price']) ?>đ</span>
                                        <?php if (!empty($product['original_price']) && $product['original_price'] > $product['price']): ?>
                                            <span class="original-price"><?= number_format($product['original_price']) ?>đ</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="product-rating">
                                        <?php
                                        $rating = $product['average_rating'] ?? 0;
                                        for ($i = 1; $i <= 5; $i++):
                                        ?>
                                            <i class="fas fa-star <?= $i <= $rating ? 'active' : '' ?>"></i>
                                        <?php endfor; ?>
                                        <span class="rating-count">(<?= $product['review_count'] ?? 0 ?>)</span>
                                    </div>
                                    <div class="product-stock">
                                        <?php if (($product['stock'] ?? 0) > 0): ?>
                                            <span class="in-stock">Còn <?= $product['stock'] ?> sản phẩm</span>
                                        <?php else: ?>
                                            <span class="out-of-stock">Hết hàng</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="?controller=product&action=list<?= !empty($category) ? '&category=' . $category : '' ?>&page=<?= $page - 1 ?>" class="page-link prev">
                                    <i class="fas fa-chevron-left"></i> Trước
                                </a>
                            <?php endif; ?>

                            <?php
                            $startPage = max(1, $page - 2);
                            $endPage = min($totalPages, $page + 2);
                            
                            if ($startPage > 1): ?>
                                <a href="?controller=product&action=list<?= !empty($category) ? '&category=' . $category : '' ?>&page=1" class="page-link">1</a>
                                <?php if ($startPage > 2): ?>
                                    <span class="page-dots">...</span>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                <a href="?controller=product&action=list<?= !empty($category) ? '&category=' . $category : '' ?>&page=<?= $i ?>" 
                                   class="page-link <?= $i == $page ? 'active' : '' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>

                            <?php if ($endPage < $totalPages): ?>
                                <?php if ($endPage < $totalPages - 1): ?>
                                    <span class="page-dots">...</span>
                                <?php endif; ?>
                                <a href="?controller=product&action=list<?= !empty($category) ? '&category=' . $category : '' ?>&page=<?= $totalPages ?>" class="page-link"><?= $totalPages ?></a>
                            <?php endif; ?>

                            <?php if ($page < $totalPages): ?>
                                <a href="?controller=product&action=list<?= !empty($category) ? '&category=' . $category : '' ?>&page=<?= $page + 1 ?>" class="page-link next">
                                    Sau <i class="fas fa-chevron-right"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
function filterByPrice() {
    const minPrice = document.getElementById('minPrice').value;
    const maxPrice = document.getElementById('maxPrice').value;
    const products = document.querySelectorAll('.product-card');
    
    products.forEach(product => {
        const price = parseInt(product.dataset.price);
        const min = minPrice ? parseInt(minPrice) : 0;
        const max = maxPrice ? parseInt(maxPrice) : Infinity;
        
        if (price >= min && price <= max) {
            product.style.display = 'block';
        } else {
            product.style.display = 'none';
        }
    });
}

function sortProducts(sortBy) {
    const grid = document.getElementById('productsGrid');
    const products = Array.from(grid.querySelectorAll('.product-card'));
    
    products.sort((a, b) => {
        switch(sortBy) {
            case 'price_asc':
                return parseInt(a.dataset.price) - parseInt(b.dataset.price);
            case 'price_desc':
                return parseInt(b.dataset.price) - parseInt(a.dataset.price);
            case 'name_asc':
                return a.dataset.name.localeCompare(b.dataset.name);
            case 'name_desc':
                return b.dataset.name.localeCompare(a.dataset.name);
            default:
                return 0;
        }
    });
    
    products.forEach(product => grid.appendChild(product));
}
</script>

<?php include '../views/layout/footer.php'; ?>