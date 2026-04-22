<?php 
include 'koneksi.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Absensi Siswa</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7f6;
            color: #333;
        }
        .navbar {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            padding: 1rem 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .main-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            background: #fff;
        }
        .table thead {
            background-color: #f8f9fc;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
            color: #aeb1be;
        }
        .table-hover tbody tr:hover {
            background-color: #fdfdfd;
            transition: 0.3s;
        }
        /* UKURAN FOTO DI TABEL DIPERBESAR */
        .img-preview {
            width: 100px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .img-preview:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.8rem;
        }
        .btn-action {
            border-radius: 8px;
            padding: 5px 12px;
            font-size: 0.85rem;
        }
        .btn-tambah {
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark mb-5">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="#">
            <i class="fas fa-user-check fs-3 me-2"></i>
            <span>PRESENSI SISWA</span>
        </a>
    </div>
</nav>

<div class="container">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h4 class="fw-bold m-0">Riwayat Kehadiran</h4>
            <p class="text-muted small">Memantau data absensi harian siswa</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="tambah.php" class="btn btn-primary btn-tambah">
                <i class="fas fa-plus-circle me-1"></i> Tambah Data Baru
            </a>
        </div>
    </div>

    <div class="card main-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4 py-3">No</th>
                            <th>Informasi Siswa</th>
                            <th>Waktu Absen</th>
                            <th>Status</th>
                            <th>Bukti Foto</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT absensi.*, siswa.nama, siswa.kelas 
                                  FROM absensi 
                                  JOIN siswa ON absensi.siswa_id = siswa.id 
                                  ORDER BY absensi.tanggal DESC";
                        $result = mysqli_query($koneksi, $query);
                        $no = 1;

                        if(mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <tr>
                            <td class="ps-4 fw-bold text-muted"><?= $no++; ?></td>
                            <td>
                                <div class="fw-bold"><?= $row['nama']; ?></div>
                                <div class="text-muted small text-uppercase fw-semibold" style="font-size: 0.7rem;">
                                    <i class="fas fa-graduation-cap me-1"></i> <?= $row['kelas']; ?>
                                </div>
                            </td>
                            <td>
                                <div class="text-dark d-flex align-items-center">
                                    <i class="far fa-calendar-alt me-2 text-primary"></i>
                                    <?= date('d M Y', strtotime($row['tanggal'])); ?>
                                </div>
                            </td>
                            <td>
                                <?php if($row['status_hadir'] == 'Hadir') { ?>
                                    <span class="badge badge-status bg-success-subtle text-success border border-success-subtle">
                                        <i class="fas fa-check-circle me-1"></i> Hadir
                                    </span>
                                <?php } else { ?>
                                    <span class="badge badge-status bg-danger-subtle text-danger border border-danger-subtle">
                                        <i class="fas fa-times-circle me-1"></i> Tidak Hadir
                                    </span>
                                <?php } ?>
                            </td>
                            <td>
                                <img src="uploads/<?= $row['foto']; ?>" 
                                     class="img-preview shadow-sm" 
                                     alt="Bukti"
                                     data-bs-toggle="modal" 
                                     data-bs-target="#imageModal<?= $row['id']; ?>">

                                <div class="modal fade" id="imageModal<?= $row['id']; ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content shadow">
                                            <div class="modal-header border-0">
                                                <h6 class="modal-title fw-bold">Bukti Foto: <?= $row['nama']; ?></h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center p-0">
                                                <img src="uploads/<?= $row['foto']; ?>" class="img-fluid rounded-bottom" style="max-height: 80vh; width: 100%; object-fit: contain;">
                                            </div>
                                            <div class="modal-footer border-0 p-2">
                                                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                <a href="uploads/<?= $row['foto']; ?>" download class="btn btn-sm btn-primary">
                                                    <i class="fas fa-download me-1"></i> Unduh Gambar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center px-4">
                                <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-action btn-outline-warning me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="hapus.php?id=<?= $row['id']; ?>" class="btn btn-action btn-outline-danger" onclick="return confirm('Hapus data ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php 
                            } 
                        } else {
                            echo "<tr><td colspan='6' class='text-center py-5 text-muted'>Belum ada data hari ini.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="text-center mt-4 text-muted small">
        &copy; <?= date('Y'); ?> Sistem Absensi Siswa Digital. All rights reserved.
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
