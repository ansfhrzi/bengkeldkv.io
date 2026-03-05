<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}
?>

<?php
include 'koneksi.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* =========================
   TAMBAH ALAT
========================= */
if(isset($_POST['tambah'])){

    $nama = trim($_POST['nama_alat']);
    $stok = (int)$_POST['stok_total'];

    if($nama != "" && $stok > 0){
        mysqli_query($conn,"INSERT INTO alat 
        (nama_alat, stok_total, stok_tersedia) 
        VALUES('$nama','$stok','$stok')");
        $pesan = "<div class='alert alert-success'>Alat berhasil ditambahkan</div>";
    } else {
        $pesan = "<div class='alert alert-danger'>Nama dan stok harus diisi</div>";
    }
}

/* =========================
   UPDATE ALAT
========================= */
if(isset($_POST['update'])){

    $id   = (int)$_POST['id'];
    $nama = trim($_POST['nama_alat']);
    $stok_total_baru = (int)$_POST['stok_total'];

    $data = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM alat WHERE id='$id'"));

    $stok_lama = $data['stok_total'];
    $stok_tersedia_lama = $data['stok_tersedia'];

    $dipinjam = $stok_lama - $stok_tersedia_lama;

    if($stok_total_baru >= $dipinjam){

        $stok_tersedia_baru = $stok_total_baru - $dipinjam;

        mysqli_query($conn,"UPDATE alat SET
        nama_alat='$nama',
        stok_total='$stok_total_baru',
        stok_tersedia='$stok_tersedia_baru'
        WHERE id='$id'");

        $pesan = "<div class='alert alert-success'>Data berhasil diupdate</div>";

    } else {

        $pesan = "<div class='alert alert-danger'>
        Tidak bisa update! Stok baru lebih kecil dari jumlah yang sedang dipinjam ($dipinjam)
        </div>";
    }
}

/* =========================
   HAPUS ALAT
========================= */
if(isset($_GET['hapus'])){

    $id = (int)$_GET['hapus'];
    mysqli_query($conn,"DELETE FROM alat WHERE id='$id'");
    header("Location: kelola_alat.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Data Peralatan Bengkel DKV</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
body {
    background: url('bg_sekolah.jpg') no-repeat center center fixed;
    background-size: cover;
}

.overlay {
    background: rgba(255,255,255,0.90);
    min-height: 100vh;
    padding-bottom: 50px;
}
</style>

<body>
<div class="overlay">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
<div class="container">

<div class="d-flex align-items-center">
<img src="logo_sekolah.png" width="45" class="me-2">
<img src="logo_dkv.png" width="40" class="me-3">
<div>
<div class="fw-bold text-white">
SISTEM MANAJEMEN BENGKEL DKV
</div>
<small class="text-light">
SMK Negeri 1 Cerme Gresik
</small>
</div>
</div>

</div>
</nav>

<div class="container mt-4">



<div class="d-flex justify-content-between align-items-center mb-3">
<p align="center">

<div class="text-center mb-4">
<h3 class="fw-bold">TATA KELOLA ALAT BENGKEL DKV</h3>
<p class="text-muted">SMK Negeri 1 Cerme Gresik</p>
</div>
</p>



</div>

<div class="container mt-4">



<?php if(isset($pesan)) echo $pesan; ?>

<!-- =========================
     FORM TAMBAH
========================= -->
<div class="card mb-4">
<div class="card-body">

<form method="POST" class="row g-3">

<div class="col-md-5">
<input type="text" name="nama_alat" 
class="form-control" placeholder="Nama Alat" required>
</div>

<div class="col-md-3">
<input type="number" name="stok_total" 
class="form-control" placeholder="Stok Total" required>
</div>

<div class="col-md-2">
<button type="submit" name="tambah" 
class="btn btn-primary w-100">
Tambah
</button>
</div>

</form>

</div>
</div>

<!-- =========================
     TABEL DATA
========================= -->
<div class="card">
<div class="card-body">

<table class="table table-bordered table-hover">

<tr class="table-primary">
<th>Nama Alat</th>
<th>Stok Total</th>
<th>Stok Tersedia</th>
<th width="200">Aksi</th>
</tr>

<?php
$data = mysqli_query($conn,"SELECT * FROM alat ORDER BY id DESC");
while($d = mysqli_fetch_array($data)){
?>

<tr>

<form method="POST">
<td>
<input type="hidden" name="id" value="<?= $d['id']; ?>">
<input type="text" name="nama_alat"
value="<?= $d['nama_alat']; ?>"
class="form-control">
</td>

<td>
<input type="number" name="stok_total"
value="<?= $d['stok_total']; ?>"
class="form-control">
</td>

<td class="align-middle text-center fw-bold">
<?= $d['stok_tersedia']; ?>
</td>

<td class="text-center">

<button type="submit" name="update"
class="btn btn-warning btn-sm">
Update
</button>

<a href="kelola_alat.php?hapus=<?= $d['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Yakin hapus alat ini?')">
Hapus
</a>

</td>
</form>

</tr>

<?php } ?>

</table>

</div>
</div>

</div>
<div class="text-center mt-4">
    <a href="index.php" class="btn btn-secondary">
        ← Kembali ke Halaman Utama
    </a>
</div>


</div> <!-- overlay -->

</body>

</html>
