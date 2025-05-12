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

// Ambil data produk
$sql = "SELECT * FROM products"; // Pastikan Anda memiliki tabel untuk produk
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css"> <!-- Link ke file CSS -->
    <title>My SaaS App</title>
    <style>
        /* CSS Anda di sini */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        #header {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .header-navigation ul {
            list-style: none;
            padding: 0;
            display: flex;
        }
        .header-navigation ul li {
            margin-right: 20px;
        }
        .header-navigation ul li a {
            text-decoration: none;
            color: #333;
        }
        .woocommerce {
            padding: 20px;
        }
        .woocommerce h1, .woocommerce h2 {
            font-size: 25px;
            color: #333;
        }
        .woocommerce button {
            background-color: #f1d204;
            color: #ffffff;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }
        .woocommerce button:hover {
            background: rgba(241, 210, 4, 0.9);
            color: #ffffff;
        }
        footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: white;
        }
    </style>
</head>
<body>

<header id="header">
    <div class="top-header">
        <nav class="header-navigation">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="order.php">Pesan</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </nav>
    </div>
</header>

<main>
    <div class="woocommerce">
        <h1>Produk Kami</h1>
        <div class="product-grid">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="product-card">
                        <h2><?php echo htmlspecialchars($row['name']); ?></h2>
                        <p>Harga: Rp. <?php echo number_format($row['price'], 0, ',', '.'); ?></p>
                        <form method="POST" action="order.php">
                            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="woocommerce button">Tambah ke Keranjang</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Tidak ada produk tersedia.</p>
            <?php endif; ?>
        </div>
    </div>
</main>

<footer>
    <p>&copy; 2025 My SaaS App. All rights reserved.</p>
</footer>

</body>
</html>

<?php
$conn->close();
?>