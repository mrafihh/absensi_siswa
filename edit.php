<?php 
include 'koneksi.php'; 
$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM absensi WHERE id='$id'");
$data = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Absensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="card" style="max-width: 600px; margin: auto;">
        <div class="card-header bg-warning">
            <h4>Edit Data Absensi</h4>
        </div>
        <div class="card-body">
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $data['id']; ?>">
                <input type="hidden" name="foto_lama" value="<?= $data['foto']; ?>">

                <div class="mb-3">
                    <label>Nama Siswa</label>
                    <select name="siswa_id" class="form-select" required>
                        <?php
                        $q_siswa = mysqli_query($koneksi, "SELECT * FROM siswa");
                        while($s = mysqli_fetch_assoc($q_siswa)){
                            $selected = ($s['id'] == $data['siswa_id']) ? "selected" : "";
                            echo "<option value='".$s['id']."' $selected>".$s['nama']." (".$s['kelas'].")</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="<?= $data['tanggal']; ?>" required>
                </div>

                <div class="mb-3">
                    <label>Status Kehadiran</label>
                    <select name="status_hadir" class="form-select" required>
                        <option value="Hadir" <?= ($data['status_hadir'] == 'Hadir') ? 'selected' : ''; ?>>Hadir</option>
                        <option value="Tidak Hadir" <?= ($data['status_hadir'] == 'Tidak Hadir') ? 'selected' : ''; ?>>Tidak Hadir</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Foto Saat Ini:</label><br>
                    <img src="uploads/<?= $data['foto']; ?>" width="100" class="mb-2"><br>
                    <label>Ganti Foto (Biarkan kosong jika tidak ingin diganti)</label>
                    <input type="file" name="foto" class="form-control" accept=".jpg, .jpeg, .png">
                </div>

                <button type="submit" name="update" class="btn btn-warning">Update Data</button>
                <a href="index.php" class="btn btn-secondary">Kembali</a>
            </form>

            <?php
            if(isset($_POST['update'])){
                $id         = $_POST['id'];
                $siswa_id   = $_POST['siswa_id'];
                $tanggal    = $_POST['tanggal'];
                $status     = $_POST['status_hadir'];
                $foto_lama  = $_POST['foto_lama'];
                
                // Cek apakah user upload foto baru
                if($_FILES['foto']['error'] === 4){
                    // Jika tidak upload file baru, gunakan nama foto lama
                    $nama_file_baru = $foto_lama;
                } else {
                    // Jika upload file baru, jalankan validasi seperti di tambah.php
                    $nama_file  = $_FILES['foto']['name'];
                    $ukuran     = $_FILES['foto']['size'];
                    $tmp_file   = $_FILES['foto']['tmp_name'];
                    
                    $ekstensi_diperbolehkan = array('png','jpg','jpeg');
                    $x = explode('.', $nama_file);
                    $ekstensi = strtolower(end($x));
                    $nama_file_baru = time() . '_' . $nama_file;
                    $path = "uploads/" . $nama_file_baru;

                    if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
                        if($ukuran < 2097152){
                            // Hapus foto lama di folder uploads
                            if(file_exists("uploads/".$foto_lama)) {
                                unlink("uploads/".$foto_lama);
                            }
                            // Upload foto baru
                            move_uploaded_file($tmp_file, $path);
                        } else {
                            echo "<script>alert('Ukuran file terlalu besar!'); window.location='edit.php?id=$id';</script>";
                            exit;
                        }
                    } else {
                        echo "<script>alert('Ekstensi file tidak diperbolehkan!'); window.location='edit.php?id=$id';</script>";
                        exit;
                    }
                }

                // Update database
                $query = "UPDATE absensi SET 
                            siswa_id='$siswa_id', 
                            tanggal='$tanggal', 
                            status_hadir='$status', 
                            foto='$nama_file_baru' 
                          WHERE id='$id'";
                
                if(mysqli_query($koneksi, $query)){
                    echo "<script>alert('Data berhasil diupdate!'); window.location='index.php';</script>";
                } else {
                    echo "<div class='alert alert-danger mt-3'>Gagal mengupdate database.</div>";
                }
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>
