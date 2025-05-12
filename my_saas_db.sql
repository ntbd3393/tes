-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Waktu pembuatan: 11 Bulan Mei 2025 pada 16.43
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `my_saas_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `unique_link` varchar(255) NOT NULL,
  `subdomain` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `unique_link`, `subdomain`, `created_at`) VALUES
(1, 'PERCETAKAN CHS', 'cvchs123@gmail.com', '$2y$10$LjgY7rojx9qFQrXynIGAwebzycRfnyEK/V993ZTmzY1Va9raBgO/2', 'admin_link_681f950546200', 'cvchs.com', '2025-05-10 18:03:49'),
(2, 'kost', 'kembangkertas@gmail.com', '$2y$10$yM/Py6zQxCHR5.E4zBEPWO5b4ZpIk2A0eMhvVzhF9Tpku4zFe3JYC', 'admin_link_681f95404182b', 'kost.com', '2025-05-10 18:04:48'),
(3, 'loveyou', 'hasyacantik123@gmail.com', '$2y$10$OyJuI8f/IH4Pj7mV8PireupEiaWrRThbqMBLv6NyuoyJlCZn5k8Jm', 'admin_link_682057433072e', 'chs.com', '2025-05-11 07:52:35'),
(4, 'cuck', 'cucktasik123@gmail.com', '$2y$10$hB7LyDtVad/eb2LfxkfeneGl8DZ0y5vRO5N9MNIRdtweFx5aygJ9W', 'admin_link_68209b0f5965f', 'cuck.com', '2025-05-11 12:41:51');

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `product` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `admin_id` int(11) DEFAULT NULL,
  `customer_phone` varchar(15) NOT NULL,
  `file_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `product`, `quantity`, `order_date`, `admin_id`, `customer_phone`, `file_path`) VALUES
(2, 'adam', 'Produk 1', 1, '2025-05-09 20:01:57', 1, '', ''),
(3, 'adam', '0', 100, '2025-05-11 11:41:36', 3, '08123', 'uploads/Logo CHS.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `status` enum('Belum Diproses','Sedang Diproses','Selesai','Dibatalkan') DEFAULT 'Belum Diproses',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pesanan`
--

INSERT INTO `pesanan` (`id`, `customer_name`, `order_number`, `status`, `created_at`, `image`) VALUES
(1, 'adam', 'ORD-681e5f46c90d7', 'Belum Diproses', '2025-05-09 20:02:14', NULL),
(2, 'adam', 'ORD-681eedc598072', 'Belum Diproses', '2025-05-10 06:10:13', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `admin_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image`, `description`, `admin_id`, `store_id`) VALUES
(1, 'banner', 10000.00, 'img/th.jpg', 'ini banner mahal', 0, 0),
(2, 'namatag', 3000.00, 'img/Logo CHS.png', 'ini harga satuan', 3, 2),
(3, 'Tes Macaroni', 10000.00, 'img/sg-11134201-23010-t7j77te5d7lv0f.jpeg', 'Macaroni pedas', 4, 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `stores`
--

CREATE TABLE `stores` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `subdomain` varchar(255) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `stores`
--

INSERT INTO `stores` (`id`, `name`, `subdomain`, `admin_id`, `image`, `address`, `contact_person`, `email`, `store_id`) VALUES
(2, 'PERCETAKAN CV.CHS', 'cvchs.com', 3, 'uploads/Logo CHS.png', 'jl.pasundan', '082217772372', 'chs@gmail.com', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `unique_link` (`unique_link`),
  ADD UNIQUE KEY `subdomain` (`subdomain`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subdomain` (`subdomain`),
  ADD KEY `admin_id` (`admin_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `stores`
--
ALTER TABLE `stores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `stores`
--
ALTER TABLE `stores`
  ADD CONSTRAINT `stores_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
