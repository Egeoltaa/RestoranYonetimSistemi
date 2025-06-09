<?php
// urun_resim.php
include('fonksiyon/fonksiyon.php'); // Veritabanı bağlantınızı sağlayan dosya

// Ürün ID'sini alıyoruz
$urunid = $_POST['urunid'];

// Veritabanından resim yolunu çekiyoruz
$query = $masam->benimsorgum2($db, "SELECT resim FROM urunler WHERE id = ?", array($urunid), 1);
$resim = $query->fetch_assoc();

// Resim yolunu geri döndürüyoruz
if (!empty($resim['resim'])) {
    echo $resim['resim']; // Resim yolunu döndür
} else {
    echo ''; // Eğer resim yoksa boş döndür
}
?>
