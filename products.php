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

// Ambil ID toko dari parameter GET
$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;

// Ambil produk dari toko yang dipilih
$sql = "SELECT * FROM products WHERE store_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $store_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk Toko - My SaaS App</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Link to your CSS file -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
        }
        header {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin-top: 20px;
        }
        .product-card {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 15px;
            margin: 10px;
            background-color: white;
            width: calc(30% - 20px); /* Adjust width for 3 cards per row */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .product-card img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .cta-button {
            background-color: #f1d204;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            text-transform: uppercase;
            display: block;
            text-align: center;
            margin-top: 10px;
            text-decoration: none;
        }
        .cta-button:hover {
            background: rgba(241, 210, 4, 0.9);
        }
    </style>
</head>
<body>
    <header>
        <h2>Produk Toko</h2>
    </header>
    <main>
        <div class="container">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="product-card">
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p>Harga: Rp. <?php echo number_format($row['price'], 0, ',', '.'); ?></p>
                        <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                        <a href="order.php?product_id=<?php echo $row['id']; ?>" class="cta-button">Pesan Sekarang</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Tidak ada produk tersedia di toko ini.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>