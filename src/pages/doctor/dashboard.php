<?php
// 1. Panggil header (yang sudah ada 'satpam'-nya)
include_once __DIR__ . '/layouts/header.php';

// ===============================================
// BLOK NOTIFIKASI (Tetap ada)
// ===============================================
if (isset($_GET['status']) && $_GET['status'] == 'sukses_rekam_medis') {
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
            <strong>Berhasil!</strong> Rekam medis telah disimpan dan janji temu diselesaikan.
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
}
// ===============================================
// AKHIR BLOK NOTIFIKASI
// ===============================================

include_once __DIR__ . '/../../config/database.php';

// ===============================================
// LOGIKA FILTER BARU
// ===============================================
// 1. Ambil filter dari URL. Default-nya adalah 'today' (hari ini).
$filter = $_GET['filter'] ?? 'today';

// 2. Siapkan variabel untuk query SQL dan judul halaman
$sql_where_condition = "";
$page_title = "";
$column_span = 7; // Untuk colspan tabel jika kosong

switch ($filter) {
    case 'future':
        $page_title = "Jadwal Akan Datang";
        // Tampilkan semua yang 'Confirmed' di masa depan
        $sql_where_condition = "AND a.tanggal_appointment > CURDATE() AND a.status = 'Confirmed'";
        $column_span = 7;
        break;
        
    case 'past':
        $page_title = "Riwayat Janji Temu";
        // (SUDAH DIPERBAIKI) Tampilkan semua yang statusnya sudah final
        $sql_where_condition = "AND a.status IN ('Completed', 'Cancelled', 'No_Show')";
        $column_span = 7;
        break;
        
    default: // 'today'
        $page_title = "Jadwal Anda Hari Ini";
        // Tampilkan yang 'Confirmed' untuk hari ini
        $sql_where_condition = "AND a.tanggal_appointment = CURDATE() AND a.status = 'Confirmed'";
        $column_span = 6; // Hanya 6 kolom di tab 'Hari Ini'
        break;
}
// ===============================================
// AKHIR LOGIKA FILTER
// ===============================================

$dokter_id = $_SESSION['user_id'];
$pdo = getDBConnection();

// 3. Query SQL DINAMIS
$sql = "SELECT 
            a.appointment_id, 
            a.jam_appointment, 
            a.keluhan_awal,
            a.tanggal_appointment,
            a.status,
            p.nama_hewan,
            o.nama_lengkap AS nama_pemilik
        FROM 
            appointment a
        JOIN 
            pet p ON a.pet_id = p.pet_id
        JOIN 
            owner o ON a.owner_id = o.owner_id
        WHERE 
            a.dokter_id = ? 
            $sql_where_condition  -- Ini adalah bagian yang dinamis
        ORDER BY 
            a.tanggal_appointment DESC, a.jam_appointment ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$dokter_id]);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1><?php echo $page_title; ?></h1>
        <p class="lead">Pasien yang ditugaskan kepada Anda.</p>
    </div>
</div>

<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link <?php if($filter == 'future') echo 'active'; ?>" 
           href="/pages/doctor/dashboard.php?filter=future">Akan Datang</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php if($filter == 'today') echo 'active'; ?>" 
           href="/pages/doctor/dashboard.php?filter=today">Hari Ini</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php if($filter == 'past') echo 'active'; ?>" 
           href="/pages/doctor/dashboard.php?filter=past">Riwayat</a>
    </li>
</ul>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <?php if($filter != 'today') echo '<th>Tanggal</th>'; // Tampilkan tanggal jika bukan 'Hari Ini' ?>
                        <th>Jam</th>
                        <th>Nama Hewan</th>
                        <th>Pemilik</th>
                        <th>Keluhan Awal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($stmt->rowCount() > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $jam = date('H:i', strtotime($row['jam_appointment']));
                            
                            // Styling Status
                            $status = htmlspecialchars($row['status']);
                            $status_class = 'bg-secondary'; // Default
                            if ($status == 'Pending') $status_class = 'bg-warning text-dark';
                            if ($status == 'Confirmed') $status_class = 'bg-info text-dark';
                            if ($status == 'Completed') $status_class = 'bg-success';
                            if ($status == 'Cancelled') $status_class = 'bg-danger';
                            if ($status == 'No_Show') $status_class = 'bg-dark';

                            echo "<tr>";
                            
                            // Tampilkan tanggal jika filternya bukan 'today'
                            if($filter != 'today') {
                                echo "<td>" . date('d M Y', strtotime($row['tanggal_appointment'])) . "</td>";
                            }
                            
                            echo "<td><strong>" . $jam . "</strong></td>";
                            echo "<td>" . htmlspecialchars($row['nama_hewan']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nama_pemilik']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['keluhan_awal'] ?? 'N/A') . "</td>";
                            echo "<td><span class='badge " . $status_class . "'>" . $status . "</span></td>";
                            echo "<td>";
                            
                            // Logika Tombol Aksi:
                            if ($row['status'] == 'Confirmed') {
                                echo "<a href='/pages/doctor/medical_record.php?appointment_id=" . htmlspecialchars($row['appointment_id']) . "' class='btn btn-primary'>Buka Rekam Medis</a>";
                            } else if ($row['status'] == 'Completed') {
                                // (BARU) Link untuk melihat rekam medis/resep yang sudah jadi
                                echo "<a href='#' class='btn btn-outline-secondary btn-sm disabled'>Lihat Detail</a>";
                            }
                            
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        // Gunakan colspan dinamis
                        echo '<tr><td colspan="' . $column_span . '" class="text-center">Tidak ada jadwal yang ditemukan.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
// 4. Panggil footer
include_once __DIR__ . '/layouts/footer.php';
?>