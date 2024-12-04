<?php
try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nik = $_POST['nik'] ?? null;
        $name = $_POST['name'] ?? null;

        if (!$nik || !$name) {
            throw new Exception('NIK dan Nama wajib diisi.');
        }

        $pdo = new PDO('mysql:host=localhost;dbname=task37', 'root', ''); // Sesuaikan koneksi
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = $pdo->prepare("INSERT INTO students (nik, nama) VALUES (:nik, :name)");
        $query->bindValue(':nik', $nik, PDO::PARAM_STR);
        $query->bindValue(':name', $name, PDO::PARAM_STR);

        if ($query->execute()) {
            header('Location: index.php?message=Data berhasil disimpan.');
        } else {
            throw new Exception('Gagal menyimpan data.');
        }
    }
} catch (Exception $e) {
    header('Location: index.php?error=' . urlencode($e->getMessage()));
}
?>
