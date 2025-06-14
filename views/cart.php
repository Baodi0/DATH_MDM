<?php
// views/cart/view.php
$title = 'Giỏ hàng - Shop Online';
include '../views/layout/header.php';
?>

<section class="cart-page">
    <div class="container">
        <div class="page-header">
            <h1>Giỏ hàng của bạn</h1>
            <div class="breadcrumb">
                <a href="index.php">Trang chủ</a>
                <span>/</span>
                <span>Giỏ hàng</span>
            </div>
        </div>

        <?php if (empty($cartItems)): ?>
            <div class="empty-cart">
                <div class="empty-cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h2>Giỏ hàng của bạn đang trống</h2>
                <p>Hãy thêm một số sản phẩm vào giỏ hàng để tiếp tục mua sắm.</p>
                <a href="index.php?controller=product&action=list" class="btn btn-primary">Tiếp tục mua sắm</a>
            </div>
        <?php else: ?>
            <div class="cart-content">
                <div class="cart-items">
                    <div class="cart-header">
                        <div class="item-info">Sản phẩm</div>
                        <div class="item-price">Đơn giá</div>
                        <div class="item-quantity">Số lượng</div>
                        <div class="item-total">Thành tiền</div>
                        <div class="item-actions">Thao tác</div>
                    </div>

                    <?php foreach ($cartItems as $item): ?>
                        <div class="cart-item" data-product-id="<?= $item['product_id'] ?>">
                            <div class="item-info">
                                <img src="<?= $item['product']['image'] ?? 'images/no-image.jpg' ?>" alt="<?= htmlspecialchars($item['product']['name']) ?>">
                                <div class="item-details">
                                    <h3><?= htmlspecialchars($item['product']['name']) ?></h3>
                                    <p class="item-stock">Còn lại: <?= $item['product']['stock'] ?></p>
                                </div>
                            </div>
                            <div class="item-price">
                                <?= number_format($item['product']['price']) ?>đ
                            </div>
                            <div class="item-quantity">
                                <div class="quantity-controls">
                                    <button type="button" onclick="updateQuantity('<?= $item['product_id'] ?>', <?= $item['quantity'] - 1 ?>)">-</button>
                                    <input type="number" value="<?= $item['quantity'] ?>" min="1" max="<?= $item['product']['stock'] ?>" 
                                           onchange="updateQuantity('<?= $item['product_id'] ?>', this.value)">
                                    <button type="button" onclick="updateQuantity('<?= $item['product_id'] ?>', <?= $item['quantity'] + 1 ?>)">+</button>
                                </div>
                            </div>
                            <div class="item-total">
                                <?= number_format($item['quantity'] * $item['product']['price']) ?>đ
                            </div>
                            <div class="item-actions">
                                <button class="btn-remove" onclick="removeFromCart('<?= $item['product_id'] ?>')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="cart-summary">
                    <div class="summary-card">
                        <h3>Tổng đơn hàng</h3>
                        <div class="summary-row">
                            <span>Tạm tính:</span>
                            <span id="subtotal"><?= number_format($total) ?>đ</span>
                        </div>
                        <div class="summary-row">
                            <span>Phí vận chuyển:</span>
                            <span>Miễn phí</span>
                        </div>
                        <div class="summary-row total">
                            <span>Tổng cộng:</span>
                            <span id="total"><?= number_format($total) ?>đ</span>
                        </div>
                        <div class="summary-actions">
                            <a href="index.php?controller=product&action=list" class="btn btn-secondary">Tiếp tục mua sắm</a>
                            <a href="index.php?controller=order&action=create" class="btn btn-primary">Thanh toán</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include '../views/layout/footer.php'; ?>