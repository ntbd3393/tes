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

if (!isset($_SESSION['admin_id'])) {
    die("Anda harus login untuk membuat toko.");
}

$admin_id = $_SESSION['admin_id']; // Ambil ID admin dari session

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_store'])) {
    $store_name = $_POST['store_name'];
    $address = $_POST['address'];
    $contact_person = $_POST['contact_person'];
    $email = $_POST['email'];
    $subdomain = strtolower(trim($_POST['subdomain']));
    $image = $_FILES['store_image']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image);

    // Validasi subdomain
    $checkSubdomain = $conn->query("SELECT * FROM stores WHERE subdomain = '$subdomain'");
    if ($checkSubdomain->num_rows > 0) {
        echo "<script>alert('Subdomain sudah digunakan. Silakan pilih yang lain.');</script>";
    } else {
        // Simpan gambar
        if (move_uploaded_file($_FILES['store_image']['tmp_name'], $target_file)) {
            // Simpan toko ke database
            $sql = "INSERT INTO stores (name, address, contact_person, email, subdomain, image, admin_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssi", $store_name, $address, $contact_person, $email, $subdomain, $target_file, $admin_id);

            if ($stmt->execute()) {
                // Ambil store_id yang baru saja dibuat
                $_SESSION['store_id'] = $conn->insert_id; // Simpan store_id di sesi
                echo "<script>alert('Toko berhasil dibuat!'); window.location.href='dashboard.php';</script>";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Maaf, terjadi kesalahan saat mengunggah file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Toko - My SaaS App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1f1c2c, #928dab);
            min-height: 100vh;
            margin: 0;
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
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 sidebar">
            <h4>PrintingApp</h4>
            <a href="dashboard.php">Dashboard</a>
            <a href="create_store.php" class="active">Buat Toko</a>
            <a href="product_management.php">Manajemen Produk</a>
            <a href="profile.php">Profil Admin</a>
            <a href="login.php">Logout</a>
        </div>
        <!-- Content -->
        <div class="col-md-10 content">
            <h2>Buat Toko</h2>
            <form method="POST" action="create_store.php" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="store_name" class="form-label">Nama Toko:</label>
                    <input type="text" id="store_name" name="store_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Alamat:</label>
                    <input type="text" id="address" name="address" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="contact_person" class="form-label">Contact Person:</label>
                    <input type="text" id="contact_person" name="contact_person" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="subdomain" class="form-label">Subdomain:</label>
                    <input type="text" id="subdomain" name="subdomain" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="store_image" class="form-label">Gambar Toko:</label>
                    <input type="file" id="store_image" name="store_image" class="form-control" required>
                </div>
                <button type="submit" name="create_store" class="btn btn-primary">Buat Toko</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>