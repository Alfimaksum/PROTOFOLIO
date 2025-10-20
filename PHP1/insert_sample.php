<?php
include 'config.php'; 

$insert_queries = [
    'INSERT INTO public."Connect with Me" ("Nama", "Email", "Pesan") VALUES (\'Risa Amelia\', \'risa.amelia@gmail.com\', \'Halo, saya tertarik dengan kemampuan networking Anda, khususnya di Mikrotik.\')',
    'INSERT INTO public."Connect with Me" ("Nama", "Email", "Pesan") VALUES (\'Budi Santoso\', \'budi.s@ptdigital.co.id\', \'Kami dari PT Digital butuh jasa untuk redesign website perusahaan. Bisakah kirim portofolio desain Anda?\')',
    'INSERT INTO public."Connect with Me" ("Nama", "Email", "Pesan") VALUES (\'Citra Dewi\', \'citra.d@umkmkopi.com\', \'Apakah Anda bisa membuat sistem inventory sederhana untuk angkringan kopi saya?\')',
    'INSERT INTO public."Connect with Me" ("Nama", "Email", "Pesan") VALUES (\'Faisal Ramadhan\', \'faisal.r@telkom.net\', \'Butuh konsultasi untuk troubleshooting jaringan MPLS di kantor cabang. Bagaimana ketersediaan Anda?\')',
    'INSERT INTO public."Connect with Me" ("Nama", "Email", "Pesan") VALUES (\'Maya Sari\', \'maya.sari.dev@gmail.com\', \'Salam kenal. Saya melihat desain grafis Anda di Canva, apakah menerima proyek pembuatan logo?\')',
    'INSERT INTO public."Connect with Me" ("Nama", "Email", "Pesan") VALUES (\'Handoko Wijaya\', \'handoko.w@workshop.com\', \'Kami produsen workshirt. Tertarik kerja sama membuat sistem e-commerce untuk penjualan B2B.\')',
    'INSERT INTO public."Connect with Me" ("Nama", "Email", "Pesan") VALUES (\'Dewi Lestari\', \'dewi.l@freelance.com\', \'Perlu bantuan dalam setting router Mikrotik untuk hotspot area publik di cafe.\')',
    'INSERT INTO public."Connect with Me" ("Nama", "Email", "Pesan") VALUES (\'Yoga Pratama\', \'yoga.p@polinema.ac.id\', \'Saya dosen di Polinema. Ingin tahu lebih detail tentang proyek Olimpiade Mikrotik Anda.\')',
    'INSERT INTO public."Connect with Me" ("Nama", "Email", "Pesan") VALUES (\'Siska Putri\', \'siska.putri@gmail.com\', \'Pesan ini untuk menanyakan tarif untuk pembuatan UI/UX design aplikasi manajemen bisnis.\')',
    'INSERT INTO public."Connect with Me" ("Nama", "Email", "Pesan") VALUES (\'Ahmad Kholil\', \'ahmad.kholil@vendor.net\', \'Tertarik membeli workshirt dengan desain kustom dalam jumlah besar. Mohon info pricelist.\')',
];

$success_count = 0;
$error_messages = [];

foreach ($insert_queries as $query) {
    $result = @pg_query($db_conn, $query);

    if ($result) {
        $success_count++;
    } else {
        $error_messages[] = "Gagal: " . substr($query, 0, 50) . "... Error: " . pg_last_error($db_conn);
    }
}

pg_close($db_conn);
?>
