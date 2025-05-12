<?php
// Koneksi ke database
$host = 'localhost';
$db = 'my_saas_db';
$user = 'root'; // Ganti jika perlu
$pass = ''; // Ganti jika perlu
$port = 3307;

$conn = new mysqli($host, $user, $pass, $db, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verify admin login
if (!isset($_SESSION['admin_id'])) {
    die("Anda harus login untuk melihat dashboard.");
}

// Get admin ID from session
$admin_id = $_SESSION['admin_id'];

// Handle store creation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_store'])) {
    $store_name = $_POST['store_name'];
    $subdomain = strtolower(trim($_POST['subdomain']));

    // Validate subdomain
    $checkStmt = $conn->prepare("SELECT * FROM stores WHERE subdomain = ?");
    $checkStmt->bind_param("s", $subdomain);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        echo "<script>alert('Subdomain sudah digunakan. Silakan pilih yang lain.');</script>";
    } else {
        // Save store to database
        $insertStmt = $conn->prepare("INSERT INTO stores (name, subdomain, admin_id) VALUES (?, ?, ?)");
        $insertStmt->bind_param("ssi", $store_name, $subdomain, $admin_id);

        if ($insertStmt->execute()) {
            echo "<script>alert('Toko berhasil dibuat!');</script>";
        } else {
            echo "Error: " . $insertStmt->error;
        }

        $insertStmt->close();
    }
    $checkStmt->close();
}

// Fetch orders related to admin
$sql_orders = "SELECT * FROM orders WHERE admin_id = ?";
$stmt_orders = $conn->prepare($sql_orders);
$stmt_orders->bind_param("i", $admin_id);
$stmt_orders->execute();
$result_orders = $stmt_orders->get_result();

// Handle order deletion
if (isset($_GET['delete_order'])) {
    $order_id = $_GET['delete_order'];
    $delete_stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $delete_stmt->bind_param("i", $order_id);
    $delete_stmt->execute();
    $delete_stmt->close();
    
    echo "<script>alert('Order berhasil dihapus.'); window.location.href='dashboard.php';</script>";
}

// Update order status
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    $update_stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $update_stmt->bind_param("si", $new_status, $order_id);
    $update_stmt->execute();
    $update_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - My SaaS App</title>
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
            <a href="dashboard.php" class="active">Dashboard</a>
            <a href="create_store.php">Buat Toko</a>
            <a href="product_management.php">Manajemen Produk</a>
            <a href="profile.php">Profil Admin</a>
            <a href="login.php">Logout</a>
        </div>
        <!-- Content -->
        <div class="col-md-10 content">
            <h2 class="mb-4">Dashboard Admin</h2>

            <!-- Display Order List -->
            <h3 class="mt-4">Order List</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nama Pelanggan</th>
                        <th>Order</th>
                        <th>Jumlah</th>
                        <th>Tanggal Pemesanan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_orders->num_rows > 0): ?>
                        <?php while($row = $result_orders->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['product']); ?></td>
                                <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                                <td><?php echo htmlspecialchars($row['order_date']); ?></td>
                                <td>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                        <select name="status" onchange="this.form.submit()">
                                            <option value="Belum Diproses" <?php echo $row['status'] == 'Belum Diproses' ? 'selected' : ''; ?>>Belum Diproses</option>
                                            <option value="Sedang Diproses" <?php echo $row['status'] == 'Sedang Diproses' ? 'selected' : ''; ?>>Sedang Diproses</option>
                                            <option value="Selesai" <?php echo $row['status'] == 'Selesai' ? 'selected' : ''; ?>>Selesai</option>
                                            <option value="Dibatalkan" <?php echo $row['status'] == 'Dibatalkan' ? 'selected' : ''; ?>>Dibatalkan</option>
                                        </select>
                                        <button type="submit" name="update_status" class="btn btn-primary btn-sm">Ubah</button>
                                    </form>
                                </td>
                                <td>
                                    <a href="?delete_order=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">Tidak ada pesanan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<footer>
    <div class="container">
        <p>&copy; 2025 PrintingApp. All rights reserved.</p>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>