<?php
// 1. Panggil header (yang sudah ada 'satpam'-nya)
include_once __DIR__ . '/../layouts/header.php';
include_once __DIR__ . '/../../../config/database.php';

// 2. Ambil ID user dari Session
$owner_id = $_SESSION['user_id'];

$pdo = getDBConnection();

// 3. Query JOIN untuk appointment milik user ini
$sql = "SELECT 
            a.tanggal_appointment, 
            a.jam_appointment, 
            a.status,
            p.nama_hewan,
            v.nama_dokter
        FROM 
            appointment a
        JOIN 
            pet p ON a.pet_id = p.pet_id
        JOIN 
            veterinarian v ON a.dokter_id = v.dokter_id
        WHERE 
            a.owner_id = ?  -- Filter Keamanan Penting!
        ORDER BY 
            a.tanggal_appointment DESC, a.jam_appointment ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$owner_id]);
?>

<h1>Riwayat Janji Temu (Booking)</h1>
<p>Berikut adalah riwayat janji temu Anda.</p>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-light">
            <tr>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Status</th>
                <th>Hewan</th>
                <th>Dokter</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $tanggal = date('d M Y', strtotime($row['tanggal_appointment']));
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
                    echo "<td>" . $tanggal . "</td>";
                    echo "<td>" . $jam . "</td>";
                    echo "<td><span class='badge " . $status_class . "'>" . $status . "</span></td>";
                    echo "<td>" . htmlspecialchars($row['nama_hewan']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nama_dokter']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo '<tr><td colspan="5" class="text-center">Anda belum memiliki riwayat janji temu.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<?php
// 4. Panggil footer
include_once __DIR__ . '/../layouts/footer.php';
?>