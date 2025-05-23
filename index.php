<?php
include("koneksi.php");
$nama = "Adam";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tugas = $_POST['tugas'];
    $waktuStr = $_POST['waktu'];
    $waktu = (int) $waktuStr;

    if (empty($tugas) || empty($waktu)) {
        header('Location: ' . $_SERVER['SCRIPT_NAME']);
        exit;
    } else {
        $db->masukData($tugas, $waktu);
        header('Location: ' . $_SERVER['SCRIPT_NAME']);
        exit;
    }
}

// Handle request GET Hapus / Delete all the Data From Database
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $method = isset($_GET['method']) ? $_GET['method'] : false;
    if ($method === "hapus-semua"){
        $db->hapusSemuaData();
        header('Location: ' . $_SERVER['SCRIPT_NAME']);
        exit;
    }

    $delete = isset($_GET['d']) ? $_GET['d'] : false;
    if ($delete !== false){
        $db->hapusData($delete);
        header('Location: ' . $_SERVER['SCRIPT_NAME']);
        exit;
    }
}

$tugasList = $db->getAllTugas();

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo App - <?= $nama; ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<header>
    <p><?= $nama; ?>@UTPAS.23</p>
</header>
<main>
    <div class="container-flex">
        <div class="form-container">
            <form action="/" method="post">
                <label for="tugas">Masukan tugasmu</label>
                <input type="text" name="tugas" id="tugas" required>
                <select name="waktu" id="waktu" required>
                    <option value="0" disabled selected>Pilih waktu pengerjaan</option>
                    <?php 
                        for($i = 1; $i <= 24; $i++) {
                            echo "<option value='$i'>$i</option>";
                        }
                    ?>
                </select>
                <button type="submit">Simpan</button>
            </form>
        </div>
        <div class="list-tugas">
            <?php if(!empty($tugasList)): ?>
                <h2>Daftar Tugas</h2>
                <ul>
                    <?php foreach($tugasList as $value): ?>
                        <li>
                            <div class="tugas">
                                <p>Tugas : <u><?= htmlspecialchars($value['deskripsi']) ?></u></p>
                            </div>
                            <div class="waktu">
                                <p>Waktu : <?= $value['waktu'] ?> jam</p>
                            </div>
                            <div class="act-button">
                                <a class="hapus" href="?d=<?= $value['id'] ?>"><i class="bi bi-trash3-fill"></i></a>
                                <a class="edit" href="u.php?u=<?= $value['id'] ?>"><i class="bi bi-pencil-fill"></i></a>
                                <a class="info" href="display.php?u=<?= $value['id'] ?>"><i class="bi bi-eye-fill"></i></a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Belum ada tugas</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="some-button">
        <a class="bomb-sess" href="?method=hapus-semua" onclick="return confirm('Bomb database with love :)')">
            <i class="bi bi-file-earmark-x-fill"></i>
        </a>
    </div>
</main>
</body>
</html>