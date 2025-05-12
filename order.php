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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pastikan semua data diterima
    $customer_name = isset($_POST['customer-name']) ? $_POST['customer-name'] : '';
    $customer_phone = isset($_POST['customer-phone']) ? $_POST['customer-phone'] : '';
    $product = isset($_POST['product']) ? $_POST['product'] : '';
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;

    // Menangani upload file
    $file_name = $_FILES['file']['name'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $file_size = $_FILES['file']['size'];
    $file_error = $_FILES['file']['error'];

    // Validasi input
    if (!empty($customer_name) && !empty($customer_phone) && !empty($product) && $quantity > 0 && $file_error === 0) {
        // Tentukan direktori untuk menyimpan file
        $upload_dir = 'uploads/';
        $file_destination = $upload_dir . basename($file_name);

        // Pindahkan file ke direktori yang ditentukan
        if (move_uploaded_file($file_tmp, $file_destination)) {
            // Gunakan prepared statement untuk menghindari SQL injection
            $sql = "INSERT INTO orders (customer_name, customer_phone, product, quantity, admin_id, file_path) VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssiiis", $customer_name, $customer_phone, $product, $quantity, $admin_id, $file_destination);

            if ($stmt->execute()) {
                echo "<script>alert('Pesanan berhasil dibuat!');</script>";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "<script>alert('Gagal mengunggah file.');</script>";
        }
    } else {
        echo "<script>alert('Silakan isi semua kolom dengan benar.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Pemesanan - My SaaS App</title>
</head>
<body>
    <header>
        <div class="container">
            <img src="img/logo.png" alt="Logo" class="logo">
        </div>
    </header>
    
    <main>
        <div class="container">
            <h2>Pemesanan</h2>
            <form method="POST" action="order.php" enctype="multipart/form-data">
                <label for="customer-name">Nama:</label>
                <input type="text" id="customer-name" name="customer-name" required>
                
                <label for="customer-phone">Nomor HP:</label>
                <input type="text" id="customer-phone" name="customer-phone" required>
                
                <label for="product">Produk:</label>
                <select id="product" name="product" required>
                    <option value="Produk 1">Produk 1</option>
                    <option value="Produk 2">Produk 2</option>
                    <option value="Produk 3">Produk 3</option>
                </select>
                
                <label for="quantity">Jumlah:</label>
                <input type="number" id="quantity" name="quantity" required>
                
                <label for="file">Unggah File:</label>
                <input type="file" id="file" name="file" accept=".jpg,.jpeg,.png,.pdf" required>
                
                <button type="submit" class="cta-button">Kirim Pesanan</button>
            </form>
        </div>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; 2025 My SaaS App. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>