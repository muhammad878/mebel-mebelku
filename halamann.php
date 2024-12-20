<?php
session_start(); // Memulai sesi
require 'db.php'; // Mengimpor koneksi MongoDB

// Koneksi ke MongoDB dan koleksi 'orders'
$db = getMongoDBConnection();
$ordersCollection = $db->orders; // Menghubungkan ke koleksi orders

// Ambil username dari sesi
$username = $_SESSION['username'] ?? ''; // Ambil username dari sesi

// Mengambil semua pesanan untuk pengguna yang sedang login
// Mengambil semua pesanan untuk pengguna yang sedang login
$username = $_SESSION['username'] ?? ''; // Ambil username dari sesi
$ordersData = $ordersCollection->find(['customer_name' => $username])->toArray();
echo '<div class="card" style="padding: 20px; border-radius: 10px; background-color: white; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); margin: 20px 0;">';
echo '<h2 style="font-size: 1.5rem; font-weight: bold; color: #4e73df;">Current Username:</h2>';
echo '<p style="font-size: 1.2rem; color: #333;">' . htmlspecialchars($username) . '</p>';
echo '</div>';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Furniture Design</title>

    <!-- Bootstrap CSS -->
    <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" rel="stylesheet" />

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />

    <!-- Custom CSS -->
    <style>
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #3e4a41;
        color: #fff;
    }

    .navbar {
        background-color: #2b2b2b;
    }

    .navbar-nav .nav-link {
        color: #d4af37;
    }

    .hero-section {
        background-color: #2b2b2b;
        text-align: center;
        padding: 60px 0;
    }

    .hero-section h1 {
        font-size: 2.5rem;
        font-weight: 700;
        color: #d4af37;
    }

    .hero-section .btn {
        background-color: #d4af37;
        color: #2b2b2b;
        border: none;
        padding: 8px 16px;
        font-size: 1rem;
    }

    .products-section {
        background-color: #f5f5dc;
        color: #2b2b2b;
        padding: 30px 0;
    }

    .products-section h2 {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 20px;
        text-align: left;
    }

    .product-item {
        margin-bottom: 20px;
        text-align: center;
    }

    .product-item img {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
    }

    .product-item p {
        margin-top: 10px;
        font-size: 1rem;
    }

    .order-status {
        background-color: #fff;
        color: #2b2b2b;
        padding: 20px;
        margin: 30px 0;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .order-status h3 {
        font-size: 1.5rem;
        margin-bottom: 20px;
    }

    .status-item {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .status-item .status-icon {
        background-color: #d4af37;
        color: #2b2b2b;
        width: 50px;
        height: 50px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
        margin-right: 15px;
    }

    .status-item p {
        margin: 0;
        font-size: 1rem;
    }

    @media (max-width: 768px) {
        .products-section h2 {
            font-size: 1.5rem;
        }

        .product-item p {
            font-size: 0.9rem;
        }
    }
    </style>
</head>

<body>
    <!-- Navbar -->
    <section class="order-status">
       <div class="container">
           <h3>Order Status</h3>
           <?php if (empty($ordersData)): ?>
               <p>No orders found for your account.</p>
           <?php else: ?>
               <?php foreach ($ordersData as $order): ?>
                   <div class="status-item">
                       <div class="status-icon"><i class="fas fa-box"></i></div>
                       <p><?= htmlspecialchars($order['status'] ?? ''); ?></p>
                       <?php if (!empty($order['gambar_produk'])): ?>
                           <img src="gambar/<?= htmlspecialchars($order['gambar_produk']); ?>" alt="Product Image" style="width: 500px; height: 400px; margin-left: 10px;">
                       <?php endif; ?>
                       
                   </div>
               <?php endforeach; ?>
           <?php endif; ?>
       </div>
   </section>


    <!-- Products Section -->
    <section class="products-section">
        <div class="container">
            <h2>Featured Products</h2>
            <div class="row">
                <!-- Product 1 -->
                <div class="col-md-4 product-item">
                    <img src="gambar/tampilan1.jpg" alt="Stylish Chair">
                    <p>Stylish Chair<br><small>Elegant design for modern living spaces</small></p>
                </div>
                <!-- Product 2 -->
                <div class="col-md-4 product-item">
                    <img src="gambar/tampilanke2.jpg" alt="Comfortable Sofa">
                    <p>Comfortable Sofa<br><small>Perfect for relaxation and gatherings</small></p>
                </div>
                <!-- Product 3 -->
                <div class="col-md-4 product-item">
                    <img src="gambar/tampilanke3.jpg" alt="Modern Table">
                    <p>Modern Table<br><small>Versatile table for dining and work</small></p>
                </div>
                <!-- Product 4 -->
                <div class="col-md-4 product-item">
                    <img src="gambar/tampilanke4.jpg" alt="Cozy Armchair">
                    <p>Cozy Armchair<br><small>Ideal for reading and lounging</small></p>
                </div>
                <!-- Product 5 -->
                <div class="col-md-4 product-item">
                    <img src="gambar/tampilanke5.jpg" alt="Elegant Desk">
                    <p>Elegant Desk<br><small>Stylish workspace for productivity</small></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Order Status Section -->
   
</body>

</html>