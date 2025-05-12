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
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verifikasi password
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_name'] = $row['name'];
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Password salah!');</script>";
        }
    } else {
        echo "<script>alert('Email tidak ditemukan!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Login Admin - My SaaS App</title>
    <style>
        .app-name {
            font-size: 21px; /* Ukuran font untuk nama aplikasi */
            font-weight: bold; /* Menebalkan teks */
            margin-right: auto;
            color:rgb(255, 255, 255);
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
            <h2>Login Admin</h2>
            <form method="POST" action="login.php">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                
                <button type="submit" class="cta-button">Login</button>
            </form>
            <p>Belum punya akun? <a href="register.php">Registrasi di sini</a>.</p>
        </div>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; 2025 PrintingApp. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>