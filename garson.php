
<link rel="icon" href="img/cutlery.png" type="image/x-icon">

<?php
session_start(); // Oturumu başlat

// Veritabanı bağlantısını yapın
$host = 'localhost';
$dbname = 'ogrenci';  // Veritabanı ismini ekleyin
$username = 'root';          // Veritabanı kullanıcı adını ekleyin
$password = '';              // Veritabanı şifrenizi ekleyin

$vt = new mysqli($host, $username, $password, $dbname);

if ($vt->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $vt->connect_error);
}

// Mevcut benimsorgum2 fonksiyonunuzu kullanın
function benimsorgum2($vt, $sorgu, $tercih) {
    $b = $vt->prepare($sorgu);
    $b->execute();
    if ($tercih == 1) {
        return $b->get_result();
    }
}

// Giriş işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ad = htmlspecialchars($_POST["ad"]);
    $sifre = htmlspecialchars($_POST["sifre"]);

    if (!empty($ad) && !empty($sifre)) {
        // Kullanıcı adı ve şifreye göre garsonu sorgulama
        $var = benimsorgum2($vt, "SELECT * FROM garson WHERE ad='$ad' AND sifre='$sifre'", 1);

        if ($var->num_rows == 0) {
            echo '<div class="alert alert-danger text-center">Kullanıcı Adı veya Şifre Hatalı</div>';
        } else {
            // Giriş başarılı, garsonun durumunu aktif yap
            $garson = $var->fetch_assoc();
            $garsonid = $garson["id"];
            benimsorgum2($vt, "UPDATE garson SET durum = 1 WHERE id = $garsonid", 1);

            $_SESSION['kullanici'] = $garsonid; // Oturum değişkenini ayarla

            // index.php sayfasına yönlendir
            header("Location: garson.php");
            exit();
        }
    } else {
        echo '<div class="alert alert-danger text-center">Kullanıcı Adı ve Şifrenizi Giriniz</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garson Giriş</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-4">
            <h3 class="text-center">Garson Giriş</h3>

            <form action="" method="POST">
                <div class="form-group">
                    <label for="ad">Kullanıcı Adı</label>
                    <input type="text" name="ad" class="form-control" placeholder="Kullanıcı Adı" required>
                </div>
                <div class="form-group">
                    <label for="sifre">Şifre</label>
                    <input type="password" name="sifre" class="form-control" placeholder="Şifre" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Giriş Yap</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
