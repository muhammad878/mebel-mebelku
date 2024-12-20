<?php
require 'db.php'; // Koneksi MongoDB

// Koneksi ke MongoDB dan koleksi 'orders'
$db = getMongoDBConnection();
$ordersCollection = $db->orders; // Menghubungkan ke koleksi orders

// Menambahkan pesanan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $order_id = $_POST['order_id']; // Mengambil Order ID
    $customer_name = $_SESSION['username']; // Mengambil Customer Name dari sesi
    $status = $_POST['status']; // Mengambil Status
    $gambar_produk = $_FILES['gambar_produk']['name']; // Mengambil Gambar Produk

    // Simpan gambar ke folder
    if ($gambar_produk) {
        move_uploaded_file($_FILES['gambar_produk']['tmp_name'], 'gambar/' . $gambar_produk);
    }

    // Menyimpan data ke database
    try {
        $ordersCollection->insertOne([
            'order_id' => $order_id, // Simpan Order ID
            'customer_name' => $customer_name, // Simpan Customer Name dari sesi
            'status' => $status, // Simpan Status
            'gambar_produk' => $gambar_produk // Simpan Gambar Produk
        ]);
        header("Location: halaman_admin.php"); // Redirect setelah berhasil
        exit;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Menghapus pesanan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $orderId = $_POST['orderId'];

    // Menghapus data dari database
    try {
        $ordersCollection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($orderId)]);
        header("Location: halaman_admin.php"); // Redirect setelah berhasil
        exit;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Mengupdate pesanan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $order_id = $_POST['order_id'];
    $customer_name = $_POST['customer_name'];
    $status = $_POST['status'];
    $gambar_produk = $_FILES['gambar_produk']['name'];

    // Jika ada gambar baru, simpan gambar ke folder
    if ($gambar_produk) {
        move_uploaded_file($_FILES['gambar_produk']['tmp_name'], 'gambar/' . $gambar_produk);
    }

    // Menyimpan data ke database
    try {
        $updateData = [
            'customer_name' => $customer_name,
            'status' => $status,
        ];

        // Jika ada gambar baru, tambahkan ke data yang akan diupdate
        if ($gambar_produk) {
            $updateData['gambar_produk'] = $gambar_produk;
        }

        $ordersCollection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($order_id)],
            ['$set' => $updateData]
        );
        header("Location: halaman_admin.php"); // Redirect setelah berhasil
        exit;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Mengambil semua pesanan
$ordersData = $ordersCollection->find()->toArray();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Orders</title>
    <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Manage Orders</h1>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addOrderModal">Add New Order</button>

        <!-- Modal for Adding Order -->
        <div class="modal fade" id="addOrderModal" tabindex="-1" aria-labelledby="addOrderModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addOrderModalLabel">Add New Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addOrderForm" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="add">
                            <div class="mb-3">
                                <label for="order_id" class="form-label">Order ID</label>
                                <input type="text" class="form-control" id="order_id" name="order_id" required>
                            </div>
                            <div class="mb-3">
                                <label for="customer_name" class="form-label">Customer Name</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <input type="text" class="form-control" id="status" name="status" required>
                            </div>
                            <div class="mb-3">
                                <label for="gambar_produk" class="form-label">Product Image</label>
                                <input type="file" class="form-control" id="gambar_produk" name="gambar_produk" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Order</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel untuk Menampilkan Pesanan -->
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Status</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($ordersData as $row) {
                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>";
                    echo "<td>" . htmlspecialchars($row['order_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['customer_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "<td><img src='gambar/" . htmlspecialchars($row['gambar_produk']) . "' style='width: 120px;'></td>";
                    echo "<td>
                            <form method='POST' action='halaman_admin.php' style='display:inline;'>
                                <input type='hidden' name='action' value='delete'>
                                <input type='hidden' name='orderId' value='" . $row['_id'] . "'>
                                <button type='submit' class='btn btn-danger' onclick=\"return confirm('Are you sure you want to delete this order?')\">Delete</button>
                            </form>
                            <button class='btn btn-warning' data-bs-toggle='modal' data-bs-target='#editOrderModal' onclick='populateEditForm(\"" . $row['_id'] . "\", \"" . htmlspecialchars($row['customer_name']) . "\", \"" . htmlspecialchars($row['status']) . "\", \"" . htmlspecialchars($row['gambar_produk']) . "\")'>Edit</button>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Modal for Editing Order -->
        <div class="modal fade" id="editOrderModal" tabindex="-1" aria-labelledby="editOrderModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editOrderModalLabel">Edit Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editOrderForm" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="edit">
                            <input type="hidden" id="edit_order_id" name="order_id">
                            <div class="mb-3">
                                <label for="edit_customer_name" class="form-label">Customer Name</label>
                                <input type="text" class="form-control" id="edit_customer_name" name="customer_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_status" class="form-label">Status</label>
                                <input type="text" class="form-control" id="edit_status" name="status" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_gambar_produk" class="form-label">Product Image</label>
                                <input type="file" class="form-control" id="edit_gambar_produk" name="gambar_produk">
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function populateEditForm(orderId, customerName, status, gambarProduk) {
            document.getElementById('edit_order_id').value = orderId;
            document.getElementById('edit_customer_name').value = customerName;
            document.getElementById('edit_status').value = status;
            document.getElementById('edit_gambar_produk').value = ''; // Reset input file
        }
    </script>
</body>
</html>