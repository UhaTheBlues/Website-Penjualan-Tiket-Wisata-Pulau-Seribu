-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 23 Feb 2020 pada 06.17
-- Versi server: 10.1.38-MariaDB
-- Versi PHP: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kuis`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_jawaban`
--

CREATE TABLE `tbl_jawaban` (
  `id` int(11) NOT NULL,
  `id_soal` int(11) NOT NULL,
  `pilihan_jawab` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_jawaban`
--

INSERT INTO `tbl_jawaban` (`id`, `id_soal`, `pilihan_jawab`) VALUES
(33, 10, 'kang lampu'),
(34, 10, 'junalah'),
(37, 10, 'sujono lagi'),
(38, 10, 'sukoco lagi'),
(39, 13, 'inggris'),
(40, 13, 'indonesia'),
(41, 13, 'malaysia'),
(42, 13, 'nigeria'),
(43, 14, 'nigeria'),
(44, 14, 'indonesia'),
(45, 14, 'korea'),
(46, 14, 'inggris'),
(47, 15, '4'),
(48, 15, '3'),
(49, 15, '6'),
(50, 15, '16'),
(51, 16, 'nigeria'),
(52, 16, 'argentina'),
(53, 16, 'spanyol'),
(54, 16, 'italia'),
(55, 17, 'chelsea'),
(56, 17, 'newcastle united'),
(57, 17, 'man city'),
(58, 17, 'manchester united'),
(59, 18, 'sporting lisbon'),
(60, 18, 'benfica'),
(61, 18, 'manchester united'),
(62, 18, 'arsenal'),
(63, 19, 'rugby'),
(64, 19, 'baseball'),
(65, 19, 'sepak bola'),
(66, 19, 'renang'),
(67, 20, 'apple'),
(68, 20, 'fruit'),
(69, 20, 'eyes'),
(70, 20, 'hair'),
(71, 21, 'hidupkan'),
(72, 21, 'matikan'),
(73, 21, 'buang'),
(74, 21, 'tidur'),
(75, 22, 'toko hp'),
(76, 22, 'toko bunga'),
(77, 22, 'kang telor'),
(78, 22, 'material'),
(91, 26, 'duck'),
(92, 26, 'fish'),
(93, 26, 'pig'),
(94, 26, 'turtle');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_score`
--

CREATE TABLE `tbl_score` (
  `id` int(11) NOT NULL,
  `score` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_score`
--

INSERT INTO `tbl_score` (`id`, `score`) VALUES
(14, 8);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_soal`
--

CREATE TABLE `tbl_soal` (
  `id` int(11) NOT NULL,
  `soal` varchar(225) NOT NULL,
  `jawaban` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_soal`
--

INSERT INTO `tbl_soal` (`id`, `soal`, `jawaban`) VALUES
(10, 'siapa yang jualan lampu?', 'kang lampu'),
(13, 'Manchester united club asal negara mana?', 'inggris'),
(14, 'Persija club asal negara mana?', 'indonesia'),
(15, '4 x 4 : 4', '4'),
(16, 'Messi pemain asal negara mana?', 'argentina'),
(17, 'Juara Liga inggris 20 kali adalah klub?', 'manchester united'),
(18, 'cristiano ronaldo sebelum bermain untuk real madrid, dia membela klub mana?', 'manchester united'),
(19, 'olahraga paling populer di dunia?', 'sepak bola'),
(20, 'apa bahasa inggrisnya buah?', 'fruit'),
(21, 'arti dari \"turn off\" adalah?', 'matikan'),
(22, 'beli pasir di?', 'material'),
(26, 'apa bahasa inggrisnya bebek?', 'duck');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(5) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$dmRDHBpBm/HU6qYXDfhyhuE4US9fsBekoWKuwWd00Im9vh2ke./8y'),
(9, 'jono', '$2y$10$7VFNoOsNvI/cPsHkPFnHsON8l/LWkC8w0gVYyt2xsdYVaTn86.psO'),
(14, 'user1234', '$2y$10$K0uOJCmd/mSB7EmRWaqPYOk2HlG5Ll1F0m9WfhDugZ7jzbJFiFdjm');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tbl_jawaban`
--
ALTER TABLE `tbl_jawaban`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_soal`
--
ALTER TABLE `tbl_soal`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tbl_jawaban`
--
ALTER TABLE `tbl_jawaban`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT untuk tabel `tbl_soal`
--
ALTER TABLE `tbl_soal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
