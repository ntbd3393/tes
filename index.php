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

// Ambil data toko
$sql = "SELECT * FROM stores"; // Ambil semua toko
$result = $conn->query($sql);

// Cek status pesanan
$status_message = "";
if (isset($_GET['order_number'])) {
    $order_number = $_GET['order_number'];
    $sql = "SELECT * FROM pesanan WHERE order_number=?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $order_number);
    $stmt->execute();
    $result_order = $stmt->get_result();

    if ($result_order->num_rows > 0) {
        $order = $result_order->fetch_assoc();
        $status_message = "Status Pesanan: " . $order['status'];
    } else {
        $status_message = "Nomor pesanan tidak ditemukan.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Halaman Utama - My SaaS App</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        #header {
            background-color: #fff;
            padding: 5px 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
        }
        .logo {
            height: 30px;
            margin-right: 10px;
        }
        .app-name {
            font-size: 20px;
            font-weight: bold;
            margin-right: auto;
            color: #f1d204;
        }
        .header-navigation ul {
            list-style: none;
            padding: 0;
            display: flex;
            margin-left: auto;
            align-items: center;
        }
        .header-navigation ul li {
            margin-right: 15px;
        }
        .header-navigation ul li a {
            text-decoration: none;
            color: #333;
            padding: 5px;
        }
        .header-navigation ul li a:hover {
            color: #f1d204;
        }
        .container {
            padding: 60px 20px 20px;
        }
        .store-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .store-card {
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            width: calc(33.333% - 20px);
            position: relative; /* Untuk posisi gambar */
        }
        .store-card img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 10px; /* Jarak antara gambar dan judul */
        }
        .cta-button {
            background-color: #f1d204;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            text-transform: uppercase;
        }
        .cta-button:hover {
            background: rgba(241, 210, 4, 0.9);
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
        <div class="container">
            <img src="img/logo.png" alt="Logo" class="logo">
            <span class="app-name">PrintingApp</span>
            <nav class="header-navigation">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>Toko Kami</h2>
            <div class="store-grid">
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="store-card">
                            <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Gambar Toko">
                            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                            <form method="GET" action="products.php">
                                <input type="hidden" name="store_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="cta-button">Lihat Produk</button>
                            </form>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Tidak ada toko tersedia.</p>
                <?php endif; ?>
            </div>

            <h2>Cek Status Pesanan</h2>
            <form method="GET">
                <label for="order_number">Masukkan Nomor Pesanan:</label>
                <input type="text" id="order_number" name="order_number" required>
                <button type="submit" class="cta-button">Cek Status</button>
            </form>

            <?php if ($status_message): ?>
                <p><?php echo $status_message; ?></p>
            <?php endif; ?>
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