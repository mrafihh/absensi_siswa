<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Absensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="card" style="max-width: 600px; margin: auto;">
        <div class="card-header bg-primary text-white">
            <h4>Tambah Data Absensi</h4>
        </div>
        <div class="card-body">
            <form action="" method="POST" enctype="multipart/form-data">
                
                <div class="mb-3">
                    <label>Nama Siswa</label>
                    <select name="siswa_id" class="form-select" required>
                        <option value="">-- Pilih Siswa --</option>
                        <?php
                        $q_siswa = mysqli_query($koneksi, "SELECT * FROM siswa");
                        while($s = mysqli_fetch_assoc($q_siswa)){
                            echo "<option value='".$s['id']."'>".$s['nama']." (".$s['kelas'].")</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                </div>

                <div class="mb-3">
                    <label>Status Kehadiran</label>
                    <select name="status_hadir" class="form-select" required>
                        <option value="Hadir">Hadir</option>
                        <option value="Tidak Hadir">Tidak Hadir</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Upload Foto Bukti</label>
                    <input type="file" name="foto" class="form-control" required accept=".jpg, .jpeg, .png">
                    <small class="text-muted">Format: jpg, jpeg, png. Maksimal 2MB.</small>
                </div>

                <button type="submit" name="submit" class="btn btn-success">Simpan Data</button>
                <a href="index.php" class="btn btn-secondary">Kembali</a>
            </form>

            <?php
            if(isset($_POST['submit'])){
                $siswa_id   = $_POST['siswa_id'];
                $tanggal    = $_POST['tanggal'];
                $status     = $_POST['status_hadir'];

                // Proses Upload Foto
                $nama_file  = $_FILES['foto']['name'];
                $ukuran     = $_FILES['foto']['size'];
                $tmp_file   = $_FILES['foto']['tmp_name'];
                
                // Validasi Ekstensi
                $ekstensi_diperbolehkan = array('png','jpg','jpeg');
                $x = explode('.', $nama_file);
                $ekstensi = strtolower(end($x));

                // Generate nama file baru agar tidak bentrok
                $nama_file_baru = time() . '_' . $nama_file;
                $path = "uploads/" . $nama_file_baru;

                if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
                    if($ukuran < 2097152){ // 2MB = 2097152 bytes
                        // Pindahkan file ke folder uploads
                        move_uploaded_file($tmp_file, $path);

                        // Simpan ke database
                        $query = "INSERT INTO absensi (siswa_id, tanggal, status_hadir, foto) 
                                  VALUES ('$siswa_id', '$tanggal', '$status', '$nama_file_baru')";
                        
                        if(mysqli_query($koneksi, $query)){
                            echo "<script>alert('Data berhasil ditambahkan!'); window.location='index.php';</script>";
                        } else {
                            echo "<div class='alert alert-danger mt-3'>Gagal menyimpan ke database.</div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger mt-3'>Ukuran file terlalu besar (Maks 2MB).</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger mt-3'>Ekstensi file tidak diperbolehkan (Hanya jpg, jpeg, png).</div>";
                }
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>
