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

    $sql = "INSERT INTO admins (name, email, password) VALUES ('$name', '$email', '$password')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href='login.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
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
</head>
<body>
    <header>
        <div class="container">
            <img src="img/logo.png" alt="Logo" class="logo">
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
                
                <button type="submit" class="cta-button">Daftar</button>
            </form>
            <p>Sudah punya akun? <a href="login.php">Login di sini</a>.</p>
        </div>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; 2025 My SaaS App. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>