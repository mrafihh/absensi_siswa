<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Absensi Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Data Absensi Siswa</h2>
    <a href="tambah.php" class="btn btn-primary mb-3">+ Tambah Absensi</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Foto Bukti</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Query JOIN untuk mengambil nama dan kelas dari tabel siswa
            $query = "SELECT absensi.*, siswa.nama, siswa.kelas 
                      FROM absensi 
                      JOIN siswa ON absensi.siswa_id = siswa.id 
                      ORDER BY absensi.tanggal DESC";
            $result = mysqli_query($koneksi, $query);
            $no = 1;

            while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $row['nama']; ?></td>
                <td><?= $row['kelas']; ?></td>
                <td><?= date('d-m-Y', strtotime($row['tanggal'])); ?></td>
                <td>
                    <?php if($row['status_hadir'] == 'Hadir') { ?>
                        <span class="badge bg-success">Hadir</span>
                    <?php } else { ?>
                        <span class="badge bg-danger">Tidak Hadir</span>
                    <?php } ?>
                </td>
                <td>
                    <img src="uploads/<?= $row['foto']; ?>" width="80" class="img-thumbnail" alt="Foto">
                </td>
                <td>
                    <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="hapus.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
