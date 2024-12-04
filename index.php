<?php
$pdo = new PDO('mysql:host=localhost;dbname=task37', 'root', ''); // Sesuaikan koneksi

// Pagination setup
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Query data
$query = $pdo->prepare("SELECT * FROM students LIMIT :limit OFFSET :offset");
$query->bindValue(':limit', $limit, PDO::PARAM_INT);
$query->bindValue(':offset', $offset, PDO::PARAM_INT);
$query->execute();

$students = $query->fetchAll(PDO::FETCH_ASSOC);

// Total data untuk pagination
$countQuery = $pdo->query("SELECT COUNT(*) AS total FROM students");
$totalCount = $countQuery->fetch(PDO::FETCH_ASSOC)['total'];

$totalPages = ceil($totalCount / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form dan Tabel Data</title>
</head>
<body>
    <h1>Formulir Data</h1>
    <?php if (isset($_GET['message'])): ?>
        <p style="color: green;"><?php echo htmlspecialchars($_GET['message']); ?></p>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <p style="color: red;"><?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>

    <form action="save-students.php" method="post">
        <div>
            <label for="nik">NIK:</label>
            <input type="text" id="nik" name="nik" required>
        </div>
        <div>
            <label for="name">Nama:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <button type="submit">Simpan</button>
    </form>

    <h1>Daftar Data</h1>
    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>No</th>
                <th>NIK</th>
                <th>Nama</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $index => $student): ?>
                <tr>
                    <td><?php echo $offset + $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($student['nik']); ?></td>
                    <td><?php echo htmlspecialchars($student['nama']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div>
        <p>Halaman:</p>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?php echo $i; ?>&limit=<?php echo $limit; ?>" <?php if ($i == $page) echo 'style="font-weight: bold;"'; ?>>
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </div>

    <div>
        <p>Items per page:</p>
        <a href="?page=1&limit=5">5</a>
        <a href="?page=1&limit=10">10</a>
        <a href="?page=1&limit=15">15</a>
    </div>
</body>
</html>
