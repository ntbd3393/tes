<?php
session_start(); // Memulai sesi

// Koneksi ke database
$host = 'localhost';
$db = 'my_saas_db';
$user = 'root';
$pass = '';
$port = 3307;
$conn = new mysqli($host, $user, $pass, $db, 3307);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verifikasi admin yang login
if (!isset($_SESSION['admin_id'])) {
    die("Anda harus login untuk melihat dashboard.");
}

// Ambil ID admin dari sesi
$admin_id = $_SESSION['admin_id'];

// Ambil data pesanan yang terkait dengan admin
$sql = "SELECT * FROM orders WHERE admin_id = $admin_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Dashboard Admin - My SaaS App</title>
</head>
<body>
    <header>
        <div class="container">
            <img src="img/logo.png" alt="Logo" class="logo">
            <nav>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="order.php">Orders</a></li>
                    <li><a href="login.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>Dashboard Admin</h2>
            <h3>Order List</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nama Pelanggan</th>
                        <th>Order</th>
                        <th>Jumlah</th>
                        <th>Tanggal Pemesanan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['customer_name']; ?></td>
                                <td><?php echo $row['product']; ?></td>
                                <td><?php echo $row['quantity']; ?></td>
                                <td><?php echo $row['order_date']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">Tidak ada pesanan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 My SaaS App. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

<?php
$conn->close();
?>