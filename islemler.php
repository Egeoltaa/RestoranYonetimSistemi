<?php
	ob_start();
   session_start();
   include("fonksiyon/fonksiyon.php");
   @$masaid = $_GET["masaid"];
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="dosya/jqu.js"></script>
    <link rel="stylesheet" href="dosya/boost.css">
    <link rel="stylesheet" href="dosya/stil.css">
    <link rel="stylesheet" href="dosya/tema2.css">
    <script>
function gosterResmi(radio) {
    // Seçili radio butonunun data-resim özelliğini al
    var resimUrl = radio.getAttribute("data-resim");

    // Resim gösterim alanını güncelle
    var urunResmiDiv = document.getElementById("urun-resmi");
    urunResmiDiv.innerHTML = ''; // Önceki resmi temizle

    // Yeni resmi oluştur
    if (resimUrl) {
        var img = document.createElement("img");
        img.src = resimUrl;
        img.alt = "Ürün Resmi";
        img.style.width = "400px"; // Resim boyutu
        img.style.height = "400px"; // Otomatik yükseklik
        urunResmiDiv.appendChild(img); // Resmi div'e ekle
    }
}
</script>
  
    <script>
        $(document).ready(function(){
            
$('#iskontoform').hide();
$('#parcaform').hide();
$('#degistirform').hide();
$('#birlestirform').hide();
            
  $('#degistir a').click(function(){
				
                $('#birlestirform').slideUp();
                $('#degistirform').slideDown();
            });
            
  $('#birlestir a').click(function(){
				
                $('#degistirform').slideUp();
                $('#birlestirform').slideDown();  
            });

  $('#hesapalbtn').click(function(){
                $.ajax({
                    type : "POST",
                    url : 'islemler.php?islem=hesap',
                    data : $('#hesapalform').serialize(),
                    success: function(donen_veri){
                    $('#hesapalform').trigger("reset");
                    window.location.reload();
                    }

                });
            });
            
  $('#degistirbtn').click(function(){
                $.ajax({
                    type : "POST",
                    url : 'islemler.php?islem=masaislem',
                    data : $('#degistirformveri').serialize(),
                    success: function(donen_veri){
                    $('#degistirformveri').trigger("reset");
                    window.location.reload();
                    }

                });
            });
            
  $('#birlestirbtn').click(function(){
                $.ajax({
                    type : "POST",
                    url : 'islemler.php?islem=masaislem',
                    data : $('#birlestirformveri').serialize(),
                    success: function(donen_veri){
                    $('#birlestirformveri').trigger("reset");
                    window.location.reload();
                    }

                });
            });

  $('#yakala a').click(function(){
                var sectionId = $(this).attr('sectionId');
                var sectionId2 = $(this).attr('sectionId2');
                $.post("islemler.php?islem=sil",{"urunid":sectionId, "masaid":sectionId2}, function(post_veri){
                    window.location.reload();
                });
            }); 
			
			
$('#bildirimlink a').click(function(){
                var sectionId = $(this).attr('sectionId');
			 $.post("islemler.php?islem=hazirurunsil",{"id":sectionId}, function(){
                   
				$('#uy'+sectionId).hide();	
				$("#bekleyenler").load("islemler.php?islem=garsonbilgigetir");
					
                });
            }); 
$("#rezerveformkapat").click(function() { 
	// burada bişe yapacağız
	$("#rezerveformalan").animate({ 	
		opacity:'hide',
		width:'hide'},'fast','linear',function() {
	});
	
	$("#rezervelistesi").animate({ 	
		opacity:'hide',
		width:'hide'},'fast','linear',function() {
	});
	
	window.location.reload();
	
	
	});
$('#rezervelistem a').click(function(){
                var sectionId = $(this).attr('sectionId');
			 $.post("islemler.php?islem=rezervekaldir",{"id":sectionId}, function(){
                   
				$('#mas'+sectionId).hide();	
				$("#rezervelistesi").load("islemler.php?islem=rezervelistesi");
					
                });
            });
			
$('#iskontoAc a').click(function() { 
		$('#iskontoform').toggle();
	});	
$('#iskontobtn ').click(function() {
				
		$.ajax({			
			type : "POST",
			url :'islemler.php?islem=iskontoUygula',
			data :$('#iskontoForm').serialize(),			
			success: function(donen_veri){
			$('#iskontoForm').trigger("reset");
				window.location.reload();
			},			
		})		
	});
			
$('#parcaHesapAc a').click(function() { 
	
	
	$('#parcaform').toggle();
	
	
	});		
$('#parcabtn').click(function() {
				
		$.ajax({			
			type : "POST",
			url :'islemler.php?islem=parcaHesapOde',
			data :$('#parcaForm').serialize(),			
			success: function(donen_veri){
			$('#parcaForm').trigger("reset");
				window.location.reload();
			},			
		})		
	});		
});
			
         var popupWindow = null;

            function ortasayfa(url, winName, w, h, scroll) {
                LeftPosition = (screen.width) ? (screen.width - w) / 2 : 0;
                TopPosition = (screen.height) ? (screen.height - h) / 2 : 0;
                settings = 'height=' + h + ', width=' + w + ',top=' + TopPosition + ',left=' + LeftPosition + ',scrollbars=' + scroll + ', resizable'
                popupWindow = window.open(url, winName, settings)
            }
    </script>
</head>


<?php

function benimsorgum2($vt,$sorgu,$tercih) {
    $a=$sorgu;
    $b=$vt->prepare($a);
    $b->execute();
    if ($tercih==1):
        return $c=$b->get_result();  
    endif;

}

function uyari($mesaj, $renk) {
    echo '<div class="alert alert-'.$renk.' mt-4 text-center">'.$mesaj.'</div>';
}

function formgetir($masaid, $db, $baslik, $durum, $btnvalue, $btnid, $formvalue) {
        echo '<div class="card border-success m-3" style="max-width:18rem;">
            <div class="card-header">'.$baslik.'</div><div class="card-body text-success">
                <form id = "'.$formvalue.'">

                        <input type="hidden" name="mevcutmasaid" value="'.$masaid.'"/>
                            <select name="hedefmasa" class="form-control">';

                            $masadeg=benimsorgum2($db,"select * from masalar where durum=$durum and rezervedurum=0",1);
                            
                            while ($son = $masadeg->fetch_assoc()):
                                
                                if($masaid!=$son["id"]):
                                    echo '<option value="'.$son["id"].'">'.$son["ad"].'</option>';
                                endif;
    
                            endwhile;
                            
                     echo '</select><input type="button" id="'.$btnid.'" value="'.$btnvalue.'" class="btn btn-success btn-block mt-2"></form></div>
            </div>';
    }

function garsonbilgi($db) {

        $siparisler = benimsorgum2($db, "select * from mutfaksiparis where durum = 1 order by masaid desc",1);

	echo '<div class="col-md-12" id="bildirimlink">';
        while ($geldiler = $siparisler->fetch_assoc()) :
            $masaid = $geldiler["masaid"];
			$masaad = benimsorgum2($db, "select * from masalar where id=$masaid",1);
            $masabilgi = $masaad->fetch_assoc();
	
	echo '<div class="alert alert-success" id="uy'.$geldiler["id"].'">Masa : <strong>'.$masabilgi["ad"].'</strong> | Ürün : <strong>'.$geldiler["urunad"].'</strong> | Adet : <strong>'.$geldiler["adet"].'</strong>
	<a sectionId="'.$geldiler["id"].'" class="fas fa-check float-right m-1 text-danger" style="font-size:20px;"></a></div>';
      endwhile;                       
     echo '</div>';
 
    }




$islem=htmlspecialchars($_GET["islem"]);

switch($islem) :

case "parcaHesapOde":

$tutar=$_POST["tutar"];
$masaid=$_POST["masaid"];

if (!empty($tutar)) :





$verilericek=benimsorgum2($db,"select * from masabakiye where masaid=$masaid",1);

	if ($verilericek->num_rows==0) :
	//insert
	benimsorgum2($db,"insert into masabakiye (masaid,tutar) VALUES($masaid,$tutar)",1);

	else:
	$mevcutdeger=$verilericek->fetch_assoc();	
	$sontutar=$mevcutdeger["tutar"] + $tutar;
	benimsorgum2($db,"update masabakiye set tutar=$sontutar where masaid=$masaid",1);
		
	
	endif;
	
	endif;


break;

	case "iskontoUygula":


		$iskontoOran = $_POST["iskontoOran"];
		$masaid = $_POST["masaid"];


	
		$verilericek=benimsorgum2($db,"select * from anliksiparis where masaid=$masaid",1);

					while($don=$verilericek->fetch_assoc()):
					$urunid=$don["urunid"];
					$urunhesap=($don["urunfiyat"] / 100) * $iskontoOran; // 0.50
					$sonfiyat=$don["urunfiyat"] - $urunhesap;     // 4.50


	
	benimsorgum2($db,"update anliksiparis set urunfiyat=$sonfiyat where urunid=$urunid",1);


					endwhile;	




	break;
    
case "masaislem":
        
    $mevcutmasaid = $_POST["mevcutmasaid"];
    $hedefmasa = $_POST["hedefmasa"];
    $bakiyesifirla=benimsorgum2($db,"select * from  masabakiye where masaid=$mevcutmasaid",1);
    $hedefmasabak=benimsorgum2($db,"select * from  masabakiye where masaid=$hedefmasa",1);
        if($bakiyesifirla->num_rows!=0):
        $masaninBakiyesi = $bakiyesifirla->fetch_assoc();
            
        $odenenTutar = $masaninBakiyesi["tutar"];
        
        if($hedefmasabak->num_rows!=0):
            $HedefMasaBakiyesi = $hedefmasabak->fetch_assoc();
            $gunceltutar=$odenenTutar + $HedefMasaBakiyesi["tutar"];
        benimsorgum2($db,"update masabakiye set tutar=$gunceltutar where masaid=$hedefmasa",1);
        benimsorgum2($db,"delete from masabakiye where masaid=$mevcutmasaid",1);
        else:
 benimsorgum2($db,"update masabakiye set masaid=$hedefmasa where masaid=$mevcutmasaid",1);
        endif;	
        
        
    endif;

    
    benimsorgum2($db,"update anliksiparis set masaid=$hedefmasa where masaid=$mevcutmasaid",1);
    
    // Masanın durumunu güncelleyeceğim
    $ekleson2 = $db->prepare("update masalar set durum=0 where id=$mevcutmasaid");
    $ekleson2->execute();
    
    // Masanın durumunu güncelleyeceğim
    $ekleson2 = $db->prepare("update masalar set durum=1 where id=$hedefmasa");
    $ekleson2->execute();
    
    break;

case "hesap":

    if(!$_POST):

        echo "Postdan Gelmiyorsun";

    else:

        $masaid = htmlspecialchars($_POST["masaid"]);
       
        $verilericek = benimsorgum2($db, "select * from anliksiparis where masaid = $masaid", 1);
        
        while($dongu = $verilericek->fetch_assoc()):
            $a = $dongu["masaid"];
            $b = $dongu["urunid"];
            $c = $dongu["urunad"];
            $d = $dongu["urunfiyat"];
            $e = $dongu["adet"];
            $f = $dongu["garsonid"];
            $bugun = date("Y-m-d");
            
            $raporekle = "insert into rapor (masaid, garsonid, urunid, urunad, urunfiyat, adet, tarih) values ($a, $f, $b, '$c', $d, $e, '$bugun')";
            $raporekles = $db->prepare($raporekle);
            $raporekles->execute();

        endwhile;
       
     
        $silme = $db->prepare("delete from anliksiparis where masaid = $masaid");
        $silme->execute();

	
        $silme2 = $db->prepare("delete from masabakiye where masaid = $masaid");
        $silme2->execute();
        
        // Masanın durumunu güncelleyeceğim
        $ekleson2 = $db->prepare("update masalar set durum=0 where id=$masaid");
        $ekleson2->execute();
        
        // Masanın log kaydı
        $ekleson23 = $db->prepare("update masalar set saat=0, dakika=0 where id=$masaid");
        $ekleson23->execute();

    endif;

    break;
    

case "mutfaksip":

        if(!$_POST):

            echo "Postdan Gelmiyorsun";

        else:
            // Mutfakdan silme işlemi yapılacak
            $urunid = htmlspecialchars($_POST["urunid"]);
            $masaid = htmlspecialchars($_POST["masaid"]);
            
            $sorgu2 = "update mutfaksiparis set durum = 1 where urunid = $urunid and masaid = $masaid";
            $silme2 = $db->prepare($sorgu2);
            $silme2->execute();

        endif;

    break;

 case "sil":

        if(!$_POST):

            echo "Postdan Gelmiyorsun";

        else:
            // Silme işlemi yapılacak
            $urunid = htmlspecialchars($_POST["urunid"]);
            $masaid = htmlspecialchars($_POST["masaid"]);
            
            $sorgu = "delete from anliksiparis where urunid = $urunid and masaid = $masaid";
            $silme = $db->prepare($sorgu);
            $silme->execute();
            
            $mutfaksorgu = "delete from mutfaksiparis where urunid = $urunid and masaid = $masaid";
            $mutfaksilme = $db->prepare($mutfaksorgu);
            $mutfaksilme->execute();

        endif;

    break;
 
    case "goster":

        $id = htmlspecialchars($_GET["id"]);
    
        $d = benimsorgum2($db, "select * from anliksiparis where masaid=$id", 1);
        $verilericek = benimsorgum2($db, "select * from masabakiye where masaid=$id", 1);
    
        if ($d->num_rows == 0) :
            echo '<div class="alert alert-danger mt-4 text-center">Henüz Sipariş Yok</div>';
            benimsorgum2($db, "delete from masabakiye where masaid=$id", 1);
            // Masanın durumunu güncelleyeceğim
            $ekleson2 = $db->prepare("update masalar set durum=0 where id=$id");
            $ekleson2->execute();
    
            // Masanın log kaydı
            $ekleson2 = $db->prepare("update masalar set saat=0, dakika=0 where id=$id");
            $ekleson2->execute();
    
        else:
            echo '<table class="table table-bordered table-striped text-center mt-2">
                    <thead>
                        <tr class="bg-dark text-white">
                            <th scope="col" id="hop1">Ürün Resmi</th>
                            <th scope="col" id="hop2">Ürün Adı</th>
                            <th scope="col" id="hop3">Adet</th>
                            <th scope="col" id="hop4">Tutar</th>
                            <th scope="col" id="hop5">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>';
    
            $adet = 0;
            $sontutar = 0;
    
            while ($gelenson = $d->fetch_assoc()) :
                $tutar = $gelenson["adet"] * $gelenson["urunfiyat"];
    
                $adet += $gelenson["adet"];
                $sontutar += $tutar;
    
                $masaid = $gelenson["masaid"];
                $urunid = $gelenson["urunid"];
    
                // Ürün resmini veritabanından alalım
                $urunResim = benimsorgum2($db, "select resim from urunler where id = $urunid", 1)->fetch_assoc()["resim"];
    
                echo '<tr>
                        <td class="text-center p-4"><img src="'.$urunResim.'" alt="'.$gelenson["urunad"].'" width="50" height="50"></td>
                        <td class="mx-auto text-center p-4">'.$gelenson["urunad"].'</td>
                        <td class="mx-auto text-center p-4">'.$gelenson["adet"].'</td>
                        <td class="mx-auto text-center p-4">'.number_format($tutar, 2, '.', ',').'</td>
                        <td id="yakala"><a class="btn btn-danger mt-2 text-white" sectionId="'.$gelenson["urunid"].'" sectionId2="'.$masaid.'">SİL</a></td>
                    </tr>';
    
            endwhile;
    
            echo '<tr class="bg-dark text-white text-center">
                    <td class="font-weight-bold">TOPLAM</td>
                    <td class="font-weight-bold">'.$adet.'</td>
                    <td class="font-weight-bold text-warning" colspan="3">';
    
            if ($verilericek->num_rows != 0) {
                $masaninBakiyesi = $verilericek->fetch_assoc();
                $odenenTutar = $masaninBakiyesi["tutar"];
                $kalantutar = $sontutar - $odenenTutar;
                echo "<del>".number_format($sontutar, 2, '.', ',')." TL</del><br>
                      Ödenen : ".number_format($odenenTutar, 2, '.', ',')."<br>
                      Kalan : ".number_format($kalantutar, 2, '.', ',')."";
            } else {
                echo number_format($sontutar, 2, '.', ','). " TL";
            }
    
            echo '</td>
                  </tr>
                  </tbody></table>
                  <div class="row">
                      <div class="col-md-12">
                          <form id="hesapalform">
                              <input type="hidden" name="masaid" value="'.$masaid.'"/>
                              <button type="button" id="hesapalbtn" value="HESAP AL" style="font-weight:bold; height:40px;" class="btn btn-dark btn-block mt-2">HESAP AL</button>
                          </form>
                          <p><a href="fisbastir.php?masaid='.$masaid.'" onclick="ortasayfa(this.href,\'mywindow\',\'450\',\'450\',\'yes\');return false" class="btn btn-dark btn-block mt-2">FİŞ BASTIR</a></p>
                      </div>
                      <div class="col-md-12">
                          <div class="row">
                              <div class="col-md-6" id="degistir"><a class="btn btn-warning btn-block mt-1" style="height:40px;">MASA DEĞİŞTİR</a></div>
                              <div class="col-md-6" id="birlestir"><a class="btn btn-warning btn-block mt-1" style="height:40px;">M.BİRLEŞTİR</a></div>
                          </div>
                          <div class="row">
                              <div class="col-md-12" id="degistirform">'; 
                              formgetir($id, $db, "Masa Değiştir", 0, "DEĞİŞTİR", "degistirbtn", "degistirformveri"); 
                              echo '</div>
                              <div class="col-md-12" id="birlestirform">'; 
                              formgetir($id, $db, "Masa Birleştir", 1, "BİRLEŞTİR", "birlestirbtn", "birlestirformveri"); 
                              echo '</div>
                          </div> 
                      </div>
                  </div>';
        endif;
    
    break;
    
case "ekle":

        if ($_POST) :

            @$masaid=htmlspecialchars($_POST["masaid"]);
            @$urunid=htmlspecialchars($_POST["urunid"]);
            @$adet=htmlspecialchars($_POST["adet"]);
            @$iskonto=htmlspecialchars($_POST["iskonto"]);

            if ($masaid=="" || $urunid=="" || $adet==""):
                uyari("Ürün ve Adet Seçiniz", "danger");
   
            else:
                
                $d=benimsorgum2($db,"select * from urunler where id = $urunid",1);
                $son = $d->fetch_assoc();
                $urunad=$son["ad"];
                $katid = $son["katid"];
                $urunfiyat=$son["fiyat"];
                    
                $saat = date("H");
                $dakika = date("i");
                
                $mutfak = "select * from mutfaksiparis where urunid = $urunid and masaid = $masaid";
                $var2 = benimsorgum2($db, $mutfak, 1);

                if ($var2->num_rows!=0):

                    $urundizi = $var2->fetch_assoc();
                    $sonadet = $adet + $urundizi["adet"];
                    $islemid = $urundizi["id"];
                    
                    $guncel = "update mutfaksiparis set adet = $sonadet where id = $islemid";
                    $guncelson = $db->prepare($guncel);
                    $guncelson->execute();
                    
                    else:
                    // Mutfağa bilgi gönderiliyor
                    $durumba = benimsorgum2($db,"select * from kategoriler where id = $katid",1);
                    $durumbak = $durumba->fetch_assoc();
                    
                    if($durumbak["mutfakdurum"] == 0):
                        benimsorgum2($db,"insert into mutfaksiparis (masaid, urunid, urunad, adet, saat, dakika) values ($masaid, $urunid, '$urunad', $adet, $saat, $dakika)",0);
                        
                    endif;
                endif;

                // Sepette aynı üründen varmı kontrolü
               
                $var = benimsorgum2($db, "select * from anliksiparis where urunid = $urunid and masaid = $masaid", 1);

                if ($var->num_rows!=0):

                    $urundizi = $var->fetch_assoc();
                    $sonadet = $adet + $urundizi["adet"];
                    $islemid = $urundizi["id"];

                   
                    $guncelson = $db->prepare("update anliksiparis set adet = $sonadet where id = $islemid");
                    $guncelson->execute();
                    uyari("Adet Güncellendi", "success");
                    
                    // Masanın Log kaydı
                    
                    $ekleson2 = $db->prepare("update masalar set saat=$saat, dakika=$dakika where id=$masaid");
                    $ekleson2->execute();

                else:
			
				if($iskonto!=""){
					
					$sonuc = ($urunfiyat / 100)* $iskonto;
					$urunfiyat = $urunfiyat - $sonuc;
					
				}

 
				                   
                    // Garsonun id sini alıyorum, garson performans için
                    $gelen = benimsorgum2($db, "select * from garson where durum = 1", 1)->fetch_assoc();
                    $garsonidyaz = $gelen["id"];

                  
                    
                    // Masanın durumunu güncelleyeceğim
                    $ekleson2 = $db->prepare("update masalar set durum=1 where id=$masaid");
                    $ekleson2->execute();
                    
                    // Masanın log kaydı
                    $saat = date("H");
                    $dakika = date("i");
                    $ekleson2 = $db->prepare("update masalar set saat=$saat, dakika=$dakika where id=$masaid");
                    $ekleson2->execute();
				

					$ekle="insert into anliksiparis (masaid, garsonid, urunid, urunad, urunfiyat, adet) VALUES ($masaid, $garsonidyaz, $urunid, '$urunad', $urunfiyat, $adet)";
                    $ekleson = $db->prepare($ekle);
                    $ekleson->execute();   

                    

                    
 
                    uyari("Ürün Eklendi", "success");

                endif;

            endif;

        else:
          
            uyari("Hata var", "danger");

        endif;

    break;

    case "urun":

        $katid = htmlspecialchars($_GET["katid"]);
        $a = "SELECT * FROM urunler WHERE katid = $katid";
        $d = benimsorgum2($db, $a, 1);
    
        while ($sonuc = $d->fetch_assoc()):
            // Resim URL'sini al
            $resimUrl = htmlspecialchars($sonuc["resim"]); 
            echo '<label class="btn btn-dark m-2">';
            echo '<input name="urunid" type="radio" value="'.$sonuc["id"].'" data-resim="'.$resimUrl.'" onchange="gosterResmi(this)"/>  '.$sonuc["ad"];
            echo '</label>';
        endwhile;
    
        // Boş bir alan, resim için
        echo '<div id="urun-resmi" style="margin-top: 20px;"></div>';
    
    break;
    
    

 case "kontrol":

        $ad = htmlspecialchars($_POST["ad"]);
        $sifre = htmlspecialchars($_POST["sifre"]);

        if (@$ad!="" && @$sifre!="") :
            $var = benimsorgum2($db, "select * from garson where ad='$ad' and sifre='$sifre'",1);

                if ($var->num_rows == 0):
                    echo '<div class = "alert alert-danger text-center">Kullanıcı Adı yada Şifre Hatalı</div>';
                else:

                    $garson = $var->fetch_assoc();
                    $garsonid = $garson["id"];
                    benimsorgum2($db, "update garson set durum = 1 where id = $garsonid",1);
                    ?>
                        <script>
                            window.location.reload();
                        </script>
                    <?php

                endif;
        else:

            echo '<div class = "alert alert-danger text-center">Kullanıcı Adı ve Şifrenizi Giriniz</div>';

        endif;

    break;

case "cikis":

        benimsorgum2($db, "update garson set durum=0", 1);
        header("Location:garson.php");

    break;

case "garsonbilgigetir" :

	garsonbilgi($db);
		
	break ;

case "hazirurunsil":

 if(!$_POST):

            echo "Postdan Gelmiyorsun";

        else:
            // Silme işlemi yapılacak
            $id = htmlspecialchars($_POST["id"]);
                      
            $mutfaksorgu = "delete from mutfaksiparis where id = $id";
            $mutfaksilme = $db->prepare($mutfaksorgu);
            $mutfaksilme->execute();

        endif;

break;


case "rezerveet":
	
	 if(!$_POST):

            echo "Postdan Gelmiyorsun";

        else:
            // Silme işlemi yapılacak
            $masaid = htmlspecialchars($_POST["masaid"]);
            $kisi = htmlspecialchars($_POST["kisi"]);
				
				if ($kisi==""){
					$kisi= "İsim Belirtilmemiş";
				}

            $rezerveet = $db->prepare("update masalar set durum=1,rezervedurum=1,kisi='$kisi' where id=$masaid");
            $rezerveet->execute();

        endif;
	
break;

case "rezervelistesi" :

	$siparisler = benimsorgum2($db, "select * from masalar where rezervedurum = 1",1);

	echo '<div class="col-md-12" id="rezervelistem">';
        while ($geldiler = $siparisler->fetch_assoc()) :
      
// kişi için yazılacak
	
	echo '<div class="alert alert-info" id="mas'.$geldiler["id"].'">Masa : <strong>'.$geldiler["ad"].'</strong> <br>  <strong>'.$geldiler["kisi"].' </strong> için rezerve edildi.
	<a sectionId="'.$geldiler["id"].'" class="fas fa-check float-right m-1 text-danger" style="font-size:20px;"></a></div>';
      endwhile;                       
     echo '</div>';
		
	break ;

case "rezervekaldir":

	if($_POST):
// Güncellemee işlemi yapılacak
            $id = htmlspecialchars($_POST["id"]);
             
			 $rezerveet = $db->prepare("update masalar set durum=0,rezervedurum=0,kisi='yok' where id=$id");
            $rezerveet->execute();


        endif;

break;


endswitch;

?>

