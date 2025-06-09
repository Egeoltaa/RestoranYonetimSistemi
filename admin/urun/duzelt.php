<?php include "../header.php"; ?>
 
 <div class="container">
 <div class="page-header">
 <h1>Ürün Güncelleme</h1>
 </div>
 <?php
 // gelen parametre değerini oku, bizim örneğimizde bu Id bilgisidir
 $id=isset($_GET['id']) ? $_GET['id'] : die('HATA: Id bilgisi bulunamadı.');
 
 // aktif kayıt bilgilerini oku
 try { 
 // veritabanı bağlantı dosyasını dahil et
 include '../../config/vtabani.php';
 // seçme sorgusunu hazırla
 $sorgu = "SELECT id, urunadi, aciklama, fiyat FROM urunler WHERE id = ? 
LIMIT 0,1";
 $stmt = $con->prepare( $sorgu );
 
 // id parametresini bağla (? işaretini id değeri ile değiştir)
 $stmt->bindParam(1, $id);
 
 // sorguyu çalıştır
 $stmt->execute();
 
 // okunan kayıt bilgilerini bir değişkene kaydet
 $kayit = $stmt->fetch(PDO::FETCH_ASSOC);
 
 // formu dolduracak değişken bilgileri
 $urunadi = $kayit['urunadi'];
 $aciklama = $kayit['aciklama'];
 $fiyat = $kayit['fiyat'];
 }
 // hata varsa göster
 catch(PDOException $exception){
 die('HATA: ' . $exception->getMessage());
 }
 ?>

 <!-- kaydı güncelleyecek PHP kodu burada yer alacak -->
 <?php
 // Kaydet butonu tıklanmışsa
 if($_POST){
 try{
 // güncelleme sorgusu
 // çok fazla parametre olduğundan karışmaması için 
 // soru işaretleri yerine etiketler kullanacağız
 $sorgu = "UPDATE urunler SET urunadi=:urunadi, aciklama=:aciklama, 
fiyat=:fiyat WHERE id = :id";
 
 // sorguyu hazırla
 $stmt = $con->prepare($sorgu);
 
 // gelen bilgileri değişkenlere kaydet
 $urunadi=htmlspecialchars(strip_tags($_POST['urunadi']));
 $aciklama=htmlspecialchars(strip_tags($_POST['aciklama']));
 $fiyat=htmlspecialchars(strip_tags($_POST['fiyat']));
 
 // parametreleri bağla
 $stmt->bindParam(':urunadi', $urunadi);
 $stmt->bindParam(':aciklama', $aciklama);
 $stmt->bindParam(':fiyat', $fiyat);
 $stmt->bindParam(':id', $id);
 
 // sorguyu çalıştır
 if($stmt->execute()){
 echo "<div class='alert alert-success'>Kayıt güncellendi.</div>";
 }else{
    echo "<div class='alert alert-danger'>Kayıt 
güncellenemedi.</div>";
 }
 
 }
 // hata varsa göster
 catch(PDOException $exception){
 die('HATA: ' . $exception->getMessage());
 }
 }
 ?>

<!-- kayıt bilgilerini güncelleyebileceğimiz HTML formu -->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] .
"?id={$id}");?>" method="post">
 <table class='table table-hover table-responsive table-bordered'>
 <tr>
 <td>Ürün adı</td>
 <td><input type='text' name='urunadi' value="<?php echo
htmlspecialchars($urunadi, ENT_QUOTES); ?>" class='form-control' /></td>
 </tr>
 <tr>
 <td>Açıklama</td>
 <td><textarea name='aciklama' class='form-control'><?php echo
htmlspecialchars($aciklama, ENT_QUOTES); ?></textarea></td>
 </tr>
 <tr>
 <td>Fiyat</td>
 <td><input type='text' name='fiyat' value="<?php echo
htmlspecialchars($fiyat, ENT_QUOTES); ?>" class='form-control' /></td>
 </tr>
 <tr>
 <td></td>
 <td>
 <input type='submit' value='Kaydet' class='btn btn-primary' />
 <a href='liste.php' class='btn btn-danger'>Ürün listesi</a>
 </td>
 </tr>
 </table>
 </form>
 </div> <!-- container -->
 
 <?php include "../footer.php"; ?>