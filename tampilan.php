<?php
include 'PHP1/config.php'; 

$query = 'SELECT "Nama", "Email", "Pesan" FROM public."Connect with Me" ORDER BY "Nama" ASC'; 

$result = pg_query($db_conn, $query);

if (!$result) {
    die("<h3>GAGAL MENAMPILKAN DATA</h3><p>Terjadi error saat menjalankan query SELECT. Pastikan nama tabel <strong>public.\"Connect with Me\"</strong> benar dan kolomnya <strong>\"Nama\", \"Email\", \"Pesan\"</strong> sudah dibuat.</p><p>Detail Error: " . pg_last_error($db_conn) . "</p>");
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tampilan Data Kontak - Database Protofolio</title>
    <style>
        body { font-family: 'Inter', Arial, sans-serif; margin: 0; background-color: #f3f4f6; padding: 20px; }
        .data-container { max-width: 1200px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 6px 15px rgba(0,0,0,0.1); }
        h1 { color: #1E3A8A; margin-bottom: 20px; border-bottom: 3px solid #2563EB; padding-bottom: 10px; font-size: 1.8rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px 15px; text-align: left; vertical-align: top; font-size: 14px; }
        th { background-color: #1E3A8A; color: white; font-weight: 600; text-transform: uppercase; }
        tr:nth-child(even) { background-color: #f7f7f7; }
        tr:hover { background-color: #e6f0ff; }
        .back-link { display: inline-block; margin-bottom: 20px; padding: 8px 15px; background-color: #2563EB; color: white; text-decoration: none; border-radius: 5px; font-weight: 500; }
        .empty-message { text-align: center; color: #666; padding: 20px; border: 1px dashed #ccc; border-radius: 5px; }
    </style>
</head>
<body>

    <div class="data-container">
        <h1>Pesan Masuk (Connect with Me)</h1>
        <a href="index.html" class="back-link">← Kembali ke Portofolio</a>

        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Pesan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $jumlah_data = pg_num_rows($result);
                if ($jumlah_data > 0) {
                    while ($row = pg_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['Nama']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Email']) . "</td>";
                        echo "<td>" . nl2br(htmlspecialchars($row['Pesan'])) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'><div class='empty-message'>Belum ada pesan masuk. Silakan kirim pesan melalui formulir kontak.</div></td></tr>";
                }
                ?>
            </tbody>
        </table>

        <?php
        pg_close($db_conn);
        ?>
    </div>
</body>
</html>