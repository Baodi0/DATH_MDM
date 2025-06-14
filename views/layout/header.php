?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Shop Online' ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="index.php">
                        <i class="fas fa-store"></i>
                        <span>ShopOnline</span>
                    </a>
                </div>
                
                <div class="search-bar">
                    <form class="search-form" action="index.php" method="GET">
                        <input type="hidden" name="controller" value="product">
                        <input type="hidden" name="action" value="list">
                        <div class="search-input-container">
                            <input type="text" name="q" id="searchInput" placeholder="Tìm kiếm sản phẩm..." autocomplete="off">
                            <div class="search-suggestions" id="searchSuggestions"></div>
                        </div>
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
                
                <div class="header-actions">
                    <a href="index.php?controller=cart&action=view" class="cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count" id="cartCount">0</span>
                    </a>
                    <a href="index.php?controller=order&action=history" class="user-icon">
                        <i class="fas fa-user"></i>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <nav class="main-nav">
        <div class="container">
            <ul class="nav-menu">
                <li><a href="index.php">Trang chủ</a></li>
                <li><a href="index.php?controller=product&action=list">Tất cả sản phẩm</a></li>
                <li><a href="index.php?controller=product&action=list&category=electronics">Điện tử</a></li>
                <li><a href="index.php?controller=product&action=list&category=fashion">Thời trang</a></li>
                <li><a href="index.php?controller=product&action=list&category=home">Gia dụng</a></li>
                <li><a href="index.php?controller=product&action=list&category=books">Sách</a></li>
            </ul>
        </div>
    </nav>

    <main class="main-content">
<?php