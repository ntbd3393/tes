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
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash password
    $subdomain = strtolower(trim($_POST['subdomain'])); // Ambil subdomain dan ubah jadi huruf kecil

    // Validasi subdomain
    $checkSubdomain = $conn->query("SELECT * FROM admins WHERE subdomain = '$subdomain'");
    if ($checkSubdomain->num_rows > 0) {
        echo "<script>alert('Subdomain sudah digunakan. Silakan pilih yang lain.');</script>";
    } else {
        $unique_link = uniqid('admin_link_'); // Membuat link unik

        $sql = "INSERT INTO admins (name, email, password, subdomain, unique_link) VALUES ('$name', '$email', '$password', '$subdomain', '$unique_link')";
        
        if ($conn->query($sql) === TRUE) {
            // Ambil ID admin yang baru saja ditambahkan
            $admin_id = $conn->insert_id; // Mendapatkan ID admin yang baru

            // Simpan admin_id ke dalam sesi
            session_start();
            $_SESSION['admin_id'] = $admin_id;

            echo "<script>alert('Pendaftaran berhasil! Link Anda: <a href=\"index.php?admin_link=$unique_link\">index.php?admin_link=$unique_link</a>'); window.location.href='login.php';</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Registrasi Admin - My SaaS App</title>
    <style>
        .app-name {
            font-size: 21px;
            font-weight: bold;
            margin-right: auto;
            color: rgb(255, 255, 255);
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <img src="img/logo.png" alt="Logo" class="logo">
            <span class="app-name">PrintingApp</span>
        </div>
    </header>
    
    <main>
        <div class="container">
            <h2>Registrasi Admin</h2>
            <form method="POST" action="register.php">
                <label for="name">Nama:</label>
                <input type="text" id="name" name="name" required>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                
                <label for="subdomain">Subdomain:</label>
                <input type="text" id="subdomain" name="subdomain" required>
                
                <button type="submit" class="cta-button">Daftar</button>
            </form>
            <p>Sudah punya akun? <a href="login.php">Login di sini</a>.</p>
        </div>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; 2025 PrintingApp. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

<?php
$conn->close();
?>