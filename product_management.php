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

// Ambil ID admin dari session
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
$admin_id = $_SESSION['admin_id'];

// Ambil ID toko dari session
if (isset($_SESSION['store_id'])) {
    $store_id = $_SESSION['store_id'];
} else {
    die("Store ID tidak ditemukan. Pastikan Anda telah membuat toko.");
}

// Tambah produk
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Penanganan file gambar
    $target_dir = "img/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Cek apakah gambar adalah file gambar
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        echo "File bukan gambar.";
        $uploadOk = 0;
    }

    // Cek ukuran file
    if ($_FILES["image"]["size"] > 500000) {
        echo "Maaf, ukuran file terlalu besar.";
        $uploadOk = 0;
    }

    // Izinkan hanya format tertentu
    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "Maaf, file tidak diunggah.";
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Simpan data ke database dengan admin_id dan store_id
            $sql = "INSERT INTO products (name, price, image, description, admin_id, store_id) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sdssii", $name, $price, $target_file, $description, $admin_id, $store_id);

            if ($stmt->execute()) {
                echo "<script>alert('Produk berhasil ditambahkan!');</script>";
            } else {
                echo "Error: " . $stmt->error; // Menggunakan $stmt->error untuk detail kesalahan
            }
            $stmt->close();
        } else {
            echo "Maaf, terjadi kesalahan saat mengunggah file.";
        }
    }
}

// Ambil produk untuk diedit, jika ada
$product = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $sql = "SELECT * FROM products WHERE id=? AND admin_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id, $admin_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
}

// Update produk
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_product'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    if ($_FILES["image"]["name"]) {
        $target_dir = "img/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            echo "File bukan gambar.";
            $uploadOk = 0;
        }

        if ($_FILES["image"]["size"] > 500000) {
            echo "Maaf, ukuran file terlalu besar.";
            $uploadOk = 0;
        }

        if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "Maaf, file tidak diunggah.";
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Update data ke database
                $sql = "UPDATE products SET name=?, price=?, image=?, description=? WHERE id=? AND admin_id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sdssii", $name, $price, $target_file, $description, $id, $admin_id);

                if ($stmt->execute()) {
                    echo "<script>alert('Produk berhasil diperbarui!');</script>";
                } else {
                    echo "Error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "Maaf, terjadi kesalahan saat mengunggah file.";
            }
        }
    } else {
        // Jika tidak ada gambar baru, hanya perbarui data lain
        $sql = "UPDATE products SET name=?, price=?, description=? WHERE id=? AND admin_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdiii", $name, $price, $description, $id, $admin_id);

        if ($stmt->execute()) {
            echo "<script>alert('Produk berhasil diperbarui!');</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrintingApp</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #6a11cb;
            color: white;
        }
        tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.1);
        }
        tr:hover {
            background-color: rgba(255, 255, 255, 0.2);
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
            <a href="product_management.php" class="active">Manajemen Produk</a>
            <a href="profile.php">Profil Admin</a>
            <a href="login.php">Logout</a>
        </div>

        <!-- Content -->
        <div class="col-md-10 content">
            <h2><?php echo $product ? 'Edit Produk' : 'Tambah Produk'; ?></h2>
            <form method="POST" action="product_management.php" enctype="multipart/form-data">
                <?php if ($product): ?>
                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                <?php endif; ?>
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Produk:</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?php echo $product ? htmlspecialchars($product['name']) : ''; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Harga:</label>
                    <input type="number" id="price" name="price" class="form-control" value="<?php echo $product ? htmlspecialchars($product['price']) : ''; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Unggah Gambar:</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*" <?php echo $product ? '' : 'required'; ?>>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi:</label>
                    <textarea id="description" name="description" class="form-control" required><?php echo $product ? htmlspecialchars($product['description']) : ''; ?></textarea>
                </div>

                <button type="submit" name="<?php echo $product ? 'update_product' : 'add_product'; ?>" class="btn btn-primary">
                    <?php echo $product ? 'Perbarui Produk' : 'Tambah Produk'; ?>
                </button>
            </form>

            <h3 class="mt-4">Daftar Produk</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM products WHERE admin_id=$admin_id"; // Filter berdasarkan admin_id
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['name']}</td>
                                    <td>Rp. " . number_format($row['price'], 0, ',', '.') . "</td>
                                    <td>
                                        <a href='product_management.php?edit={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>Tidak ada produk.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<footer>
    <div class="container">
        <p>&copy; 2025 PrintingApp All rights reserved.</p>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>