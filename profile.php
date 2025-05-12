<?php
// Koneksi ke database
$host = 'localhost';
$db = 'my_saas_db';
$user = 'root'; // Ganti jika perlu
$pass = ''; // Ganti jika perlu
$port = 3307;

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verifikasi admin yang login
if (!isset($_SESSION['admin_id'])) {
    die("Anda harus login untuk mengakses halaman ini.");
}

// Ambil ID admin dari sesi
$admin_id = $_SESSION['admin_id'];

// Ambil data admin
$sql = "SELECT * FROM admins WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <title>Profil Admin - My SaaS App</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1f1c2c, #928dab);
            min-height: 100vh;
            color: white;
        }
        .sidebar {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            min-height: 100vh;
            padding: 20px;
            border-right: 1px solid rgba(255, 255, 255, 0.2);
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            margin: 10px 0;
            padding: 10px;
            border-radius: 10px;
        }
        .sidebar a.active, .sidebar a:hover {
            background: linear-gradient(to right, #6a11cb, #2575fc);
        }
        .content {
            padding: 30px;
        }
        footer {
            text-align: center;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 sidebar">
            <h4>PrintingApp</h4>
            <a href="dashboard.php">Dashboard</a>
            <a href="product_management.php">Manajemen Produk</a>
            <a href="profile.php" class="active">Profil Admin</a>
            <a href="login.php">Logout</a>
        </div>

        <!-- Content -->
        <div class="col-md-10 content">
            <h2>Profil Admin</h2>
            <p><strong>Nama:</strong> <?php echo htmlspecialchars($admin['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($admin['email']); ?></p>
            <p><strong>Nama Toko:</strong> <?php echo htmlspecialchars($admin['subdomain']); ?></p>
            <!-- Tambahkan informasi lainnya sesuai kebutuhan -->
        </div>
    </div>
</div>

<footer>
    <div class="container">
        <p>&copy; 2025 My SaaS App. All rights reserved.</p>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>