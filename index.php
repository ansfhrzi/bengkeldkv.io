<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}
?>

<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html>
<head>
<style>
body {
    background: url('bg_sekolah.jpg') no-repeat center center fixed;
    background-size: cover;
}

.overlay {
    background: rgba(245,255,255,0.80);
    min-height: 100vh;
    padding-bottom: 50px;
}
</style>
<style>
.card {
    border-radius: 15px;
}
.navbar {
    border-bottom: 8px solid #ffc107;
}
</style>


<title>Sistem Inventaris Bengkel DKV</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
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

<div class="ms-auto">
<a href="logout.php" class="btn btn-light btn-sm">
Logout
</a>
</div>

</div>
</nav>

<div class="container mt-4">

<h3 class="text-center mb-4">Peminjaman Alat Bengkel DKV</h3>

<?php
if(isset($_POST['simpan'])){

$nama  = $_POST['nama'];
$kelas = $_POST['kelas'];
$alat_id = $_POST['alat_id'];
$jumlah = $_POST['jumlah'];
$tgl   = $_POST['tanggal'];

$cek = mysqli_query($conn,"SELECT * FROM alat WHERE id='$alat_id'");
$dataAlat = mysqli_fetch_array($cek);

if($dataAlat['stok_tersedia'] < $jumlah){
echo "<div class='alert alert-danger'>Stok tidak mencukupi!</div>";
}else{

$stokBaru = $dataAlat['stok_tersedia'] - $jumlah;

mysqli_query($conn,"UPDATE alat SET stok_tersedia='$stokBaru' WHERE id='$alat_id'");
mysqli_query($conn,"INSERT INTO peminjaman
VALUES(NULL,'$nama','$kelas','$alat_id','$jumlah','$tgl',NULL,'Dipinjam')");

echo "<div class='alert alert-success'>Peminjaman berhasil!</div>";
}
}
?>

<div class="card mb-4">
<div class="card-body">

<form method="POST">

<div class="mb-2">
<label>Nama</label>
<input type="text" name="nama" class="form-control" required>
</div>

<div class="mb-2">
<label>Kelas</label>
<select name="kelas" class="form-select" required>
<option value="">--Pilih--</option>
<option>Guru</option>
<option>X DKV 1</option>
<option>X DKV 2</option>
<option>XI DKV 1</option>
<option>XI DKV 2</option>
<option>XII DKV 1</option>
<option>XII DKV 2</option>
</select>
</div>

<div class="mb-2">
<label>Alat</label>
<select name="alat_id" class="form-select" required>
<option value="">--Pilih Alat--</option>
<?php
$alat = mysqli_query($conn,"SELECT * FROM alat");
while($a = mysqli_fetch_array($alat)){
echo "<option value='$a[id]'>$a[nama_alat] (Stok: $a[stok_tersedia])</option>";
}
?>
</select>
</div>

<div class="mb-2">
<label>Jumlah</label>
<input type="number" name="jumlah" class="form-control" required>
</div>

<div class="mb-2">
<label>Tanggal</label>
<input type="date" name="tanggal" class="form-control" required>
</div>

<button type="submit" name="simpan" class="btn btn-primary w-100">
Pinjam
</button>

</form>

</div>
</div>
<a href="export_excel.php" class="btn btn-success mb-3">
    Export ke Excel
</a>


<a href="kelola_alat.php" class="btn btn-warning mb-3">
Kelola Alat
</a>


<h5>Data Peminjaman</h5>

<table class="table table-bordered">
<tr>
<th>Nama</th>
<th>Kelas</th>
<th>Alat</th>
<th>Jumlah</th>
<th>Tanggal Pinjam</th>
<th>Tanggal Kembali</th>
<th>Status</th>
<th>Aksi</th>
</tr>

<?php
$data = mysqli_query($conn,"
SELECT p.*, a.nama_alat 
FROM peminjaman p
JOIN alat a ON p.alat_id = a.id
ORDER BY p.id DESC
");

while($d = mysqli_fetch_array($data)){
?>

<tr>
<td><?= $d['nama_peminjam']; ?></td>
<td><?= $d['kelas']; ?></td>
<td><?= $d['nama_alat']; ?></td>
<td><?= $d['jumlah']; ?></td>
<td><?= $d['tanggal_pinjam']; ?></td>
<td><?= $d['tanggal_kembali']; ?></td>
<td><?= $d['status']; ?></td>
<td>
<?php if($d['status']=="Dipinjam"){ ?>
<a href="kembali.php?id=<?= $d['id']; ?>" class="btn btn-success btn-sm">Kembalikan</a>
<?php } ?>
</td>
</tr>

<?php } ?>

</table>


</div>
</div> <!-- overlay -->

</body>

</body>
</html>
