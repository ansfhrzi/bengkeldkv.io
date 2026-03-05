<?php
include 'koneksi.php';

$id = $_GET['id'];

$data = mysqli_query($conn,"
SELECT p.*, a.stok_tersedia, a.id as alat_id
FROM peminjaman p
JOIN alat a ON p.alat_id = a.id
WHERE p.id='$id'
");

$d = mysqli_fetch_array($data);

$stokBaru = $d['stok_tersedia'] + $d['jumlah'];

mysqli_query($conn,"UPDATE alat SET stok_tersedia='$stokBaru' WHERE id='".$d['alat_id']."'");

mysqli_query($conn,"UPDATE peminjaman 
SET status='Sudah dikembalikan', tanggal_kembali=NOW()
WHERE id='$id'");

header("location:index.php");
?>
