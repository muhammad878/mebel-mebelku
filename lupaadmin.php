<?php
require 'db.php'; // Memanggil koneksi MongoDB

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = getMongoDBConnection(); // Koneksi ke MongoDB
    $users = $db->user; // Menghubungkan ke koleksi 'user'

    // Mengambil data username dan password lama dari input form
    $username = $_POST['username'];
    $password = $_POST['password'];  // Password lama yang dimasukkan pengguna
    $newPassword = $_POST['new_password']; // Password baru yang ingin diubah

    // Cari pengguna berdasarkan username
    $user = $users->findOne(['username' => $username]);

    if ($user && password_verify($password, $user['password'])) {
        // Jika password lama valid, maka update password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT); // Meng-hash password baru

        // Update password pengguna
        $users->updateOne(
            ['username' => $username],
            ['$set' => ['password' => $hashedPassword]]
        );

        $success_message = "Password berhasil diperbarui.";
    } else {
        $error_message = "Username atau password lama salah.";
    }
}

// Fungsi base_url untuk menghasilkan URL dasar
function base_url($path = '') {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    return $protocol . $host . $baseDir . '/' . $path;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>StayHub - Ubah Password</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&amp;display=swap" rel="stylesheet"/>
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            display: flex;
            background-color: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            width: 100%;
        }
        .left {
            position: relative;
            width: 60%;
        }
        .left img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .left .content {
            position: absolute;
            bottom: 20px;
            left: 20px;
            color: white;
        }
        .left .content h1 {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
        }
        .right {
            padding: 40px;
            width: 40%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .right h2 {
            font-size: 24px;
            font-weight: 600;
            margin: 0 0 20px;
        }
        .right .form-group {
            margin-bottom: 20px;
        }
        .right .form-control {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            width: 100%;
        }
        .right .btn {
            padding: 10px;
            background-color: #4e73df;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        .right .btn:hover {
            background-color: #3e5bbf;
        }
        .right .help {
            font-size: 12px;
            color: #666;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="left">
            <img alt="A woman speaking into a microphone" src="https://storage.googleapis.com/a1aa/image/9GNs88g6uOa3PdelFnqm3ejdQEOneVotQSOKKkef4aoGqsTfE.jpg"/>
            <div class="content">
                <h1>StayHub</h1>
                <p>Reset your password easily.</p>
            </div>
        </div>
        <div class="right">
            <h2>Ubah Password Anda</h2>
            <p>Masukkan username dan password lama Anda untuk mengubah password.</p>
            <?php 
            if (isset($error_message)) {
                echo '<div class="alert alert-danger">' . $error_message . '</div>';
            }
            if (isset($success_message)) {
                echo '<div class="alert alert-success">' . $success_message . '</div>';
            }
            ?>
            <form method="POST" action="">
                <div class="form-group">
                    <input type="text" class="form-control" id="username" name="username"
                        placeholder="Masukkan Username..." required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="Masukkan Password Lama..." required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" id="new_password"
                        name="new_password" placeholder="Masukkan Password Baru..." required>
                </div>
                <button type="submit" class="btn">Ubah Password</button>
            </form>
            <div class="help">
                <a class="small" href="<?= base_url('regisadmin.php'); ?>">Buat Akun Baru!</a>
            </div>
            <div class="help">
                <a class="small" href="<?= base_url('loginad.php'); ?>">Sudah Punya Akun? Login!</a>
            </div>
        </div>
    </div>
</body>

</html>