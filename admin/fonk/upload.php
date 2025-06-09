<?php
// Hata raporlamayı etkinleştir
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Veritabanı bağlantınızı burada yapın
new mysqli("localhost", "root", "", "ogrenci") or die("Baglanamadi");
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ürün ID'sini al
    $urun_id = $_POST['urun_id'];

    // Dosyayı al
    if (isset($_FILES['resim']) && $_FILES['resim']['error'] == UPLOAD_ERR_OK) {
        // Dosya bilgileri
        $file_name = basename($_FILES['resim']['name']);
        $upload_dir = 'uploads/';
        $upload_file = $upload_dir . $file_name;

        // Dosyayı yükle
        if (move_uploaded_file($_FILES['resim']['tmp_name'], $upload_file)) {
            // Veritabanına dosya adı ekle
            $sql = "UPDATE urunler SET resim = '$file_name' WHERE id = $urun_id";
            if ($conn->query($sql) === TRUE) {
                echo "Dosya başarıyla yüklendi ve veritabanına eklendi.";
            } else {
                echo "Veritabanına eklerken hata: " . $conn->error;
            }
        } else {
            echo "Dosya yüklenirken bir hata oluştu.";
        }
    } else {
        echo "Dosya yükleme hatası: " . $_FILES['resim']['error'];
    }
}
$conn->close();
?>
