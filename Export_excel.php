<?php
include 'koneksi.php';

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Peminjaman_Bengkel_DKV.xls");

$tanggal_cetak = date("d-m-Y");
?>

<h3 style="text-align:center;">LAPORAN PEMINJAMAN ALAT BENGKEL DKV</h3>
<h4 style="text-align:center;">SMK Negeri 1 Cerme Gresik</h4>
<p style="text-align:center;">Tanggal Cetak: <?= $tanggal_cetak; ?></p>

<br>

<table border="1" width="100%" style="border-collapse:collapse;">
<tr style="background-color:#d9edf7; font-weight:bold; text-align:center;">
    <th>No</th>
    <th>Nama Peminjam</th>
    <th>Kelas</th>
    <th>Nama Alat</th>
    <th>Jumlah</th>
    <th>Tanggal Pinjam</th>
    <th>Tanggal Kembali</th>
    <th>Status</th>
</tr>

<?php
$no = 1;
$total = 0;
$data = mysqli_query($conn,"SELECT p.*, a.nama_alat FROM peminjaman p JOIN alat a ON p.alat_id = a.id ORDER BY p.id DESC");

while($d = mysqli_fetch_array($data)){
    $total++;
?>

<tr>
    <td align="center"><?= $no++; ?></td>
    <td><?= $d['nama_peminjam']; ?></td>
    <td><?= $d['kelas']; ?></td>
    <td><?= $d['nama_alat']; ?></td>
    <td align="center"><?= $d['jumlah']; ?></td>
    <td align="center"><?= $d['tanggal_pinjam']; ?></td>
    <td align="center"><?= $d['tanggal_kembali']; ?></td>
    <td align="center"><?= $d['status']; ?></td>
</tr>

<?php } ?>

<tr>
    <td colspan="4" align="right"><b>TOTAL DATA</b></td>
    <td align="center"><b><?= $total; ?></b></td>
    <td colspan="3"></td>
</tr>

</table>
