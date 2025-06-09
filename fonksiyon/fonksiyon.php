<?php

$db = new mysqli("localhost","root","","ogrenci") or die ("Baglanamadi");
$db->set_charset("utf8");

class sistem {

    private function benimsorgum($vt,$sorgu,$tercih) {
        $a=$sorgu;
        $b=$vt->prepare($a);
        $b->execute();
        if ($tercih==1):
            return $c=$b->get_result();  
        endif;

    }

     function benimsorgum2($vt,$sorgu,$tercih) {
        $a=$sorgu;
        $b=$vt->prepare($a);
        $b->execute();
        if ($tercih==1):
            return $c=$b->get_result();  
        endif;

    }

    function masacek($dv){
        $masalar="select * from masalar";
        $sonuc=$this->benimsorgum($dv,$masalar,1);

        $bosmasasayisi = 0;
        $dolumasasayisi = 0;
      
        while ($masason=$sonuc->fetch_assoc()) :

            $siparisler='select * from anliksiparis where masaid='.$masason["id"].'';

            //ternary sorgu: masada sipariş yoksa rengi yeşil yap sipariş varsa kırmızı yap
            $this->benimsorgum($dv,$siparisler,1)->num_rows==0 ? $renk="success" : $renk="danger";

            // masada sipariş yoksa bosmasasayisi degisken degerini arttır masada sip varsa dolumasasayisi degisken degerini arttır.
            $this->benimsorgum($dv,$siparisler,1)->num_rows==0 ? $bosmasasayisi++ : $dolumasasayisi++;
		
			if($masason["rezervedurum"]==0){
				
				 echo '<div id="mas" class="col-md-2 col-sm-6 mr-2 mx-auto p-4 text-center">
            <a href="masadetay.php?masaid='.$masason["id"].'">           
            <div class="bg-'.$renk.' mx-auto p-4 text-center text-white" id="masa">'.$masason["ad"].'</div></a>
            </div>';
			}else{
				
				 echo '<div id="mas" class="col-md-2 col-sm-6 mr-2 mx-auto p-4 text-center">
                      
            <div class="bg-dark mx-auto p-4 text-center text-white" id="masa">'.$masason["ad"].'</div>
			<div style="font-size:12px; line-height:15px; position:relative;    top: 12px;
    left: 5px;
    font-weight: bold;
    text-align: center;">Kişi :'.$masason["kisi"].'</div>
            </div>';
			}

           

        endwhile;

         // dolu ve bos masa sayıslarını table da set et
        $dolu = "update doluluk set bos = $bosmasasayisi, dolu = $dolumasasayisi where id = 1";
        $dolusondurum = $dv->prepare($dolu);
        $dolusondurum->execute();
    }

    // Doluluk oranı belirleme fonksiyonu
    function doluluk($dv) {
        $son=$this->benimsorgum($dv,"select * from doluluk",1);
        $dolulukdurum = $son->fetch_assoc();
        $toplam = $dolulukdurum["bos"] + $dolulukdurum["dolu"];
        $oran = ($dolulukdurum["dolu"] / $toplam) * 100;

        // Doluluk oranı virgülden sonrakileri azaltmak icin 5 i düsür.
        echo $oran = substr($oran, 0, 5). " %";

    }
    // Doluluk oranı belirleme fonksiyonu


    // Masa Toplam Sayısı fonksiyonu
    function masatoplam($dv){
        echo $sonuc=$this->benimsorgum($dv,"select * from masalar",1)->num_rows;
    }
    // Masa Toplam Sayısı fonksiyonu

    // Toplam Sipariş sayısı fonksiyonu
    function siparistoplam($dv){
        echo $sonuc=$this->benimsorgum($dv,"select * from anliksiparis",1)->num_rows;
    }
    // Toplam Sipariş sayısı fonksiyonu

    // Masa Detay Fonksiyon
    function masagetir($vt, $id) {
        $get="select * from masalar where id=$id";
        return $this->benimsorgum($vt,$get,1);
        
    }
    // Masa Detay Fonksiyon sonu

    // Kategori Fonksiyon
    function urungrup($db) {
        $se = "select * from kategoriler";
        $gelen=$this->benimsorgum($db,$se,1);

        while($son = $gelen->fetch_assoc()) :

            echo '<a class="btn btn-dark mt-1" sectionId="'.$son["id"].'">'.$son["ad"].'</a><br>';

           // echo '<span class="badge badge-pill badge-secondary"><a class="btn- btn-dark mt-2" sectionId="'.$son["id"].'">'.$son["ad"].'</a></span><br>';

        endwhile;
    }
    // Kategori Fonksiyon sonu


    //Garson logout fonksiyonu
    function garsonbak($db) {
        
        $gelen = $this->benimsorgum2($db, "select * from garson where durum = 1", 1)->fetch_assoc();

        if ($gelen["ad"]!=""):

            echo $gelen["ad"];
            echo '<a href = "islemler.php?islem=cikis" class="m-3"> <kbd class="bg-info">ÇIKIŞ</kbd></a>';

        else:
           echo "Giriş yapan garson yok";

        endif;
    }
    //Kullanıcı logout fonksiyonu sonu
    
}

?>