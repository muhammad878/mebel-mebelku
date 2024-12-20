<?php
// Mengaktifkan output buffering dan session
ob_start();
session_start();
require 'db.php'; // Mengimpor koneksi MongoDB

// Fungsi base_url untuk menghasilkan URL dasar
function base_url($path = '')
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    return $protocol . $host . $baseDir . '/' . $path;
}

// Fungsi untuk menampilkan pesan flash sederhana
function flash_message()
{
    if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-info">' . $_SESSION['message'] . '</div>';
        unset($_SESSION['message']);
    }
}

// Fungsi sederhana untuk menampilkan kesalahan input
function display_error($error)
{
    if (!empty($error)) {
        echo '<small class="text-danger">' . htmlspecialchars($error) . '</small>';
    }
}

$usernameError = $passwordError = ''; // Variabel untuk menyimpan pesan kesalahan
$usernameValue = ''; // Menyimpan nilai username yang diinput

// Form handling dan autentikasi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = getMongoDBConnection(); // Koneksi ke MongoDB
    $usersCollection = $db->admin; // Menghubungkan ke koleksi 'admin'

    $username = $_POST['username'] ?? ''; // Ambil input username
    $password = $_POST['password'] ?? ''; // Ambil input password

    // Validasi input
    if (empty($username)) {
        $usernameError = 'Username tidak boleh kosong.';
    } else {
        $usernameValue = htmlspecialchars($username);
    }

    if (empty($password)) {
        $passwordError = 'Password tidak boleh kosong.';
    }

    // Jika tidak ada error pada input
    if (empty($usernameError) && empty($passwordError)) {
        // Cari pengguna berdasarkan username
        $user = $usersCollection->findOne(['username' => $username]);

        // Verifikasi password jika user ditemukan
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username; // Simpan username di sesi
            $_SESSION['message'] = "Login berhasil!";
            header('Location: ' . base_url('halaman_admin.php')); // Redirect ke halaman admin
            exit;
        } else {
            $_SESSION['message'] = "Login gagal. Username atau password salah.";
            header('Location: ' . base_url('index.php')); // Redirect kembali ke halaman login
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>StayHub</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
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
                <h1>Welcome Back!</h1>
                <p>Login to continue your journey with us.</p>
            </div>
        </div>
        <div class="right">
            <h2>Login Admin</h2>
            <?php flash_message(); ?>
            <form method="post" action="">
                <div class="form-group">
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username..."
                        value="<?= htmlspecialchars($usernameValue); ?>" required>
                    <?php display_error($usernameError); ?>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                        required>
                    <?php display_error($passwordError); ?>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
            <div class="help">
                <a href="<?= base_url('lupaadmin.php'); ?>">Forgot Password?</a>
            </div>
            <div class="help">
                <a href="<?= base_url('regisadmin.php'); ?>">Create an Account!</a>
            </div>
        </div>
    </div>
</body>
</html>