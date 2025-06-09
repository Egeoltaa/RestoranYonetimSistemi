<?php
// Veritabanı ayar dosyasını dahil et
include '../../config/vtabani.php';

try {
    // Kayıtın id bilgisini al
    $id = isset($_GET['id']) ? $_GET['id'] : die('HATA: Id bilgisi bulunamadı.');

    // Silinecek kayıt bilgilerini oku
    // Seçme sorgusunu hazırla
    $sorgu = "SELECT id, resim FROM urunler WHERE id = ? LIMIT 0,1";
    $stmt = $con->prepare($sorgu);
    // id parametresini bağla
    $stmt->bindParam(1, $id);
    // Sorguyu çalıştır
    $stmt->execute();
    $kayit = $stmt->fetch(PDO::FETCH_ASSOC);

    // Okunan resim bilgilerini bir değişkene kaydet
    $resim = $kayit['resim'];

    // Silme sorgusu
    $sorgu = "DELETE FROM urunler WHERE id = ?";
    $stmt = $con->prepare($sorgu);
    $stmt->bindParam(1, $id);

    // Sorguyu çalıştır
    if ($stmt->execute()) {
        // Kayıt listeleme sayfasına yönlendir ve kullanıcıya kaydın silindiğini bildir
        // Kayda ait resim varsa sunucudan sil
        if (!empty($resim)) {
            $silinecek_resim = "../../content/images/" . $resim;
            if (file_exists($silinecek_resim)) unlink($silinecek_resim);
        }
        header('Location: liste.php?islem=silindi');
    } else {
        // Silinemediğini bildir
        header('Location: liste.php?islem=silinemedi');
    }
} catch (PDOException $exception) {
    // Hata varsa göster
    die('HATA: ' . $exception->getMessage());
}
?>
