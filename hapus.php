<?php
include 'koneksi.php';

$id = $_GET['id'];

// Ambil data untuk mengetahui nama file foto yang akan dihapus dari folder
$query_pilih = mysqli_query($koneksi, "SELECT foto FROM absensi WHERE id='$id'");
$data = mysqli_fetch_assoc($query_pilih);
$nama_foto = $data['foto'];

// Hapus file fisik dari folder uploads
$path_foto = "uploads/" . $nama_foto;
if(file_exists($path_foto)) {
    unlink($path_foto);
}

// Hapus data dari database
$query_hapus = "DELETE FROM absensi WHERE id='$id'";
if(mysqli_query($koneksi, $query_hapus)){
    echo "<script>alert('Data berhasil dihapus!'); window.location='index.php';</script>";
} else {
    echo "Gagal menghapus data: " . mysqli_error($koneksi);
}
?>
