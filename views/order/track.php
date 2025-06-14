<?php
// views/order/track.php
$title = 'Theo dõi đơn hàng - Shop Online';
include '../views/layout/header.php';
?>

<section class="order-tracking">
    <div class="container">
        <div class="page-header">
            <h1>Theo dõi đơn hàng</h1>
            <div class="breadcrumb">
                <a href="index.php">Trang chủ</a>
                <span>/</span>
                <span>Theo dõi đơn hàng</span>
            </div>
        </div>

        <div class="order-info">
            <div class="order-header">
                <h2>Đơn hàng #<?= $order['id'] ?></h2>
                <div class="order-status status-<?= $order['status'] ?>">
                    <?php
                    $statusLabels = [
                        'pending' => 'Chờ xử lý',
                        'confirmed' => 'Đã xác nhận',
                        'shipping' => 'Đang giao hàng',
                        'delivered' => 'Đã giao hàng',
                        'cancelled' => 'Đã hủy'
                    ];
                    echo $statusLabels[$order['status']] ?? 'Không xác định';
                    ?>
                </div>
            </div>

            <div class="order-details">
                <div class="detail-section">
                    <h3>Thông tin giao hàng</h3>
                    <p><strong>Người nhận:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($order['customer_email']) ?></p>
                    <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($order['customer_phone']) ?></p>
                    <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['customer_address']) ?></p>
                </div>

                <div class="detail-section">
                    <h3>Thông tin đơn hàng</h3>
                    <p><strong>Ngày đặt:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
                    <p><strong>Dự kiến giao hàng:</strong> <?= date('d/m/Y', strtotime($order['estimated_delivery'])) ?></p>
                    <p><strong>Tổng tiền:</strong> <?= number_format($order['total']) ?>đ</p>
                </div>
            </div>

            <div class="order-timeline">
                <h3>Trạng thái đơn hàng    }

