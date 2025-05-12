<?php
session_start();
include 'db_connection.php'; // Pastikan ini mengarah ke file koneksi Anda

// Menyimpan produk baru
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product-name'])) {
    $product_name = $_POST['product-name'];
    $product_price = $_POST['product-price'];

    // Simpan produk ke database
    $sql = "INSERT INTO products (name, price) VALUES ('$product_name', '$product_price')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Produk berhasil ditambahkan.');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Mengambil daftar produk
$sql = "SELECT * FROM products";
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
        </div>
    </header>
    
    <main>
        <div class="container">
            <h2>Dashboard Admin</h2>
            <h3>Kelola Produk</h3>
            <form method="POST" action="admin.php">
                <label for="product-name">Nama Produk:</label>
                <input type="text" id="product-name" name="product-name" required>
                
                <label for="product-price">Harga:</label>
                <input type="number" id="product-price" name="product-price" required>
                
                <button type="submit" class="cta-button">Simpan Produk</button>
            </form>
            
            <h3>Daftar Produk</h3>
            <ul>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <li><?php echo htmlspecialchars($row['name']); ?> - Rp. <?php echo number_format($row['price'], 0, ',', '.'); ?></li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <li>Tidak ada produk tersedia.</li>
                <?php endif; ?>
            </ul>

            <h3>Kelola Status Pesanan</h3>
            <form method="POST" action="admin.php">
                <label for="order_number">Nomor Pesanan:</label>
                <input type="text" id="order_number" name="order_number" required>
                
                <label for="status">Status:</label>
                <select name="status" id="status" required>
                    <option value="Belum Diproses">Belum Diproses</option>
                    <option value="Sedang Diproses">Sedang Diproses</option>
                    <option value="Selesai">Selesai</option>
                </select>
                
                <button type="submit" name="update_status" class="cta-button">Perbarui Status</button>
            </form>

            <?php
            // Mengubah status pesanan
            if (isset($_POST['update_status'])) {
                $order_number = $_POST['order_number'];
                $status = $_POST['status'];

                $sql = "UPDATE pesanan SET status='$status' WHERE order_number='$order_number'";
                if ($conn->query($sql) === TRUE) {
                    echo "<script>alert('Status pesanan berhasil diperbarui.');</script>";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }
            ?>
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