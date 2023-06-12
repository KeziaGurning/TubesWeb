<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE');
header ('Content-Type: application/json; charset=utf8');

$host = 'localhost';
$dbname = 'visitmekarsari';
$username = 'root';
$password = '';

try {
    $koneksi = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $koneksi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Koneksi database berhasil."; // Hapus atau comment baris ini
} catch(PDOException $e) {
    echo "Koneksi database gagal: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = "SELECT * FROM destination";
    $statement = $koneksi->query($query);
    $data = $statement->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($data);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Hapus kode berikut:
    // $id = $_POST['id'];
    // $nama = $_POST['nama'];
    // $deskripsi = $_POST['deskripsi'];

    // Ganti dengan kode berikut:
    $requestData = json_decode(file_get_contents("php://input"), true);
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];

    $query = "INSERT INTO destination (id, nama, deskripsi) VALUES (?, ?, ?)";
    $statement = $koneksi->prepare($query);
    $result = $statement->execute([$id, $nama, $deskripsi]);

    if ($result) {
        // Setelah berhasil menambahkan data, ambil data terbaru dari database
        $query = "SELECT * FROM destination";
        $statement = $koneksi->query($query);
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($data);
    } else {
        echo json_encode(array('message' => 'Gagal menambahkan data'));
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $putData);
    $id = $putData['id'];
    $nama = $putData['nama'];
    $deskripsi = $putData['deskripsi'];

    $query = "UPDATE destination SET id = ?, nama = ?, deskripsi = ? WHERE id = ?";
    $statement = $koneksi->prepare($query);
    $result = $statement->execute([$id, $nama, $deskripsi, $id]);

    if ($result) {
        // Setelah berhasil mengupdate data, ambil data terbaru dari database
        $query = "SELECT * FROM destination";
        $statement = $koneksi->query($query);
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($data);
    } else {
        echo json_encode(array('message' => 'Gagal mengupdate data'));
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $deleteData);
    $id = $deleteData['id'];

    $query = "DELETE FROM destination WHERE id = ?";
    $statement = $koneksi->prepare($query);
    $result = $statement->execute([$id]);

    if ($result) {
        // Setelah berhasil menghapus data, ambil data terbaru dari database
        $query = "SELECT * FROM destination";
        $statement = $koneksi->query($query);
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($data);
    } else {
        echo json_encode(array('message' => 'Gagal menghapus data'));
    }
}
?>
