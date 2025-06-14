<?php
// views/order/checkout.php
$title = 'Thanh toán - Shop Online';
include '../views/layout/header.php';
?>

<section class="checkout-page">
    <div class="container">
        <div class="page-header">
            <h1>Thanh toán</h1>
            <div class="breadcrumb">
                <a href="index.php">Trang chủ</a>
                <span>/</span>
                <a href="index.php?controller=cart&action=view">Giỏ hàng</a>
                <span>/</span>
                <span>Thanh toán</span>
            </div>
        </div>

        <form method="POST" class="checkout-form">
            <div class="checkout-content">
                <div class="customer-info">
                    <h2>Thông tin giao hàng</h2>
                    <div class="form-group">
                        <label for="name">Họ và tên *</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Số điện thoại *</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Địa chỉ giao hàng *</label>
                        <textarea id="address" name="address" rows="3" required></textarea>
                    </div>

                    <h2>Phương thức thanh toán</h2>
                    <div class="payment-methods">
                        <div class="payment-method">
                            <input type="radio" id="cod" name="payment_method" value="cod" checked>
                            <label for="cod">
                                <i class="fas fa-money-bill-wave"></i>
                                Thanh toán khi nhận hàng (COD)
                            </label>
                        </div>
                        <div class="payment-method">
                            <input type="radio" id="bank" name="payment_method" value="bank">
                            <label for="bank">
                                <i class="fas fa-credit-card"></i>
                                Chuyển khoản ngân hàng
                            </label>
                        </div>
                    </div>
                </div>

                <div class="order-summary">
                    <h2>Đơn hàng của bạn</h2>
                    <div class="order-items">
                        <?php foreach ($cartItems as $item): ?>
                            <div class="order-item">
                                <img src="<?= $item['product']['image'] ?? 'images/no-image.jpg' ?>" alt="<?= htmlspecialchars($item['product']['name']) ?>">
                                <div class="item-details">
                                    <h4><?= htmlspecialchars($item['product']['name']) ?></h4>
                                    <p>Số lượng: <?= $item['quantity'] ?></p>
                                </div>
                                <div class="item-price">
                                    <?= number_format($item['quantity'] * $item['product']['price']) ?>đ
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="order-total">
                        <div class="total-row">
                            <span>Tạm tính:</span>
                            <span><?= number_format($total) ?>đ</span>
                        </div>
                        <div class="total-row">
                            <span>Phí vận chuyển:</span>
                            <span>Miễn phí</span>
                        </div>
                        <div class="total-row final">
                            <span>Tổng cộng:</span>
                            <span><?= number_format($total) ?>đ</span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-place-order">
                        Đặt hàng
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

<?php include '../views/layout/footer.php'; ?>