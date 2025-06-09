<?php
include_once("fonk/yonfonk.php");
$yonclas = new yonetim;
$yonclas->cookcon($vt, false);
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="../dosya/jqu.js"></script>
    <link rel="stylesheet" href="../dosya/boost.css">
        <script>
            function yazdir() {
                
                window.print();
                window.close();
            }
        </script>
    <title>Çıktı Sayfası</title>
    </head>
    <body>
    <div class = "container-fluid bg-light">
        <div class = "row row-fluid">


            <?php
            @$islem = $_GET["islem"];

            switch ($islem) :

                case "ciktial":

                    @$tarih1 = $_GET["tar1"];
                    @$tarih2 = $_GET["tar2"];
                    
                    $veri = $yonclas->ciktiSorgusu($vt, "select * from rapor where DATE(tarih) BETWEEN '$tarih1' AND '$tarih2'");
                    $veri2 = $yonclas->ciktiSorgusu($vt, "select * from rapor where DATE(tarih) BETWEEN '$tarih1' AND '$tarih2'");



                    echo '<table class = "table text-center table-light table-bordered mx-auto mt-4 table-striped col-md-12">
                    <thead>
                        <tr>
                            <th colspan="5"><div class = "alert alert-info text-center mx-auto mt-4">Tarih Seçimi: '.$tarih1.' - '.$tarih2.'</div></th>
                                
                            <th colspan="2"><button onclick="yazdir()" class="btn btn-warning mx-auto mt-4">YAZDIR</a></th>
                       </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th colspan = "4">
                            <table class="table text-center table-bordered col-md-12">
                                <thead>
                                    <tr>
                                        <th colspan="4" class="table-dark">Masa Sipariş ve Hasılat</th>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr class="table-danger">
                                        <th colspan="2">Ad</th>
                                        <th colspan="1">Adet</th>
                                        <th colspan="1">Hasılat</th>
                                    </tr>
                                </thead><tbody>';

                    $kilit = $yonclas->ciktiSorgusu($vt, "select * from gecicimasa");
                    if ($kilit->num_rows == 0) :

                        while ($gel = $veri->fetch_assoc()):
                            // Raporlama için masa adını çekiyorum
                            $id = $gel["masaid"];
                            $masaveri = $yonclas->ciktiSorgusu($vt, "select * from masalar where id=$id")->fetch_assoc();
                            $masaad = $masaveri["ad"];
                            // Raporlama için masa adını çekiyorum

                            $raporbak = $yonclas->ciktiSorgusu($vt, "select * from gecicimasa where masaid=$id");

                            if ($raporbak->num_rows == 0) :
                                // gecici masaya, sipariş verilmediyse ekleme yap
                                $has = $gel["adet"] * $gel["urunfiyat"];
                                $adet = $gel["adet"];
                                $yonclas->ciktiSorgusu($vt, "insert into gecicimasa (masaid, masaad, hasilat, adet) values ($id, '$masaad', $has, $adet)");

                            else:
                                // yada güncelleme yap
                                $raporson = $raporbak->fetch_assoc();
                                $gelenadet = $raporson["adet"];
                                $gelenhas = $raporson["hasilat"];

                                $sonhasilat = $gelenhas + ($gel["adet"] * $gel["urunfiyat"]);
                                $sonadet = $gelenadet + $gel["adet"];

                                $yonclas->ciktiSorgusu($vt, "update gecicimasa set hasilat=$sonhasilat, adet=$sonadet where masaid=$id");

                            endif;

                        endwhile;

                    endif;

                    $son = $yonclas->ciktiSorgusu($vt, "select * from gecicimasa order by hasilat desc;");

                    $toplamadet = 0;
                    $toplamhasilat = 0;

                    while ($listele = $son->fetch_assoc()):
                        echo '<tr>
                                            <td colspan="2">' . $listele["masaad"] . '</td>
                                            <td colspan="1">' . $listele["adet"] . '</td>
                                            <td colspan="1">' . substr($listele["hasilat"], 0, 5) . '</td>
                                        </tr>';
                        $toplamadet += $listele["adet"];
                        $toplamhasilat += $listele["hasilat"];
                    endwhile;

                    echo '<tr class="table-danger">
                                <td colspan="2">TOPLAM</td>
                                <td colspan="1">' . $toplamadet . '</td>
                                <td colspan="1">' . substr($toplamhasilat, 0, 6) . '</td>
                            </tr>
                        
                                </tbody> 
                                    </table>                   
                                        </th>
                                            <th colspan = "4">
                                            <table class="table text-center table-bordered col-md-12">
                                <thead>
                                    <tr>
                                        <th colspan="4" class="table-dark">Ürün Sipariş ve Hasılat</th>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr class="table-danger">
                                        <th colspan="2">Ad</th>
                                        <th colspan="1">Adet</th>
                                        <th colspan="1">Hasılat</th>
                                    </tr>
                                </thead><tbody>';

                    $kilit2 = $yonclas->ciktiSorgusu($vt, "select * from geciciurun");
                    if ($kilit2->num_rows == 0) :

                        while ($gel2 = $veri2->fetch_assoc()):
                            // Raporlama için ürün id ve ad çekiyorum
                            $id = $gel2["urunid"];
                            $urunad = $gel2["urunad"];

                            $raporbak = $yonclas->ciktiSorgusu($vt, "select * from geciciurun where urunid=$id");

                            if ($raporbak->num_rows == 0) :
                                // gecici masaya, sipariş verilmediyse ekleme yap
                                $has = $gel2["adet"] * $gel2["urunfiyat"];
                                $adet = $gel2["adet"];
                                $yonclas->ciktiSorgusu($vt, "insert into geciciurun (urunid, urunad, hasilat, adet) values ($id, '$urunad', $has, $adet)");

                            else:
                                // yada güncelleme yap
                                $raporson = $raporbak->fetch_assoc();
                                $gelenadet = $raporson["adet"];
                                $gelenhas = $raporson["hasilat"];

                                $sonhasilat = $gelenhas + ($gel2["adet"] * $gel2["urunfiyat"]);
                                $sonadet = $gelenadet + $gel2["adet"];

                                $yonclas->ciktiSorgusu($vt, "update geciciurun set hasilat=$sonhasilat, adet=$sonadet where urunid=$id");

                            endif;

                        endwhile;

                    endif;

                    $son2 = $yonclas->ciktiSorgusu($vt, "select * from geciciurun order by hasilat desc;");

                    $toplamadet2 = 0;
                    $toplamhasilat2 = 0;

                    while ($listele2 = $son2->fetch_assoc()):
                        echo '<tr>
                                            <td colspan="2">' . $listele2["urunad"] . '</td>
                                            <td colspan="1">' . $listele2["adet"] . '</td>
                                            <td colspan="1">' . substr($listele2["hasilat"], 0, 5) . '</td>
                                        </tr>';
                        $toplamadet2 += $listele2["adet"];
                        $toplamhasilat2 += $listele2["hasilat"];
                    endwhile;

                    echo '<tr class="table-danger">
                                <td colspan="2">TOPLAM</td>
                                <td colspan="1">' . $toplamadet2 . '</td>
                                <td colspan="1">' . substr($toplamhasilat2, 0, 6) . '</td>
                            </tr>                        
                            </tbody> 
                        </table>    
                    </th>
                </tr>
            </tbody>
        </table>';

                    break;
                    
                case "garsoncikti":
                    
                    @$tarih1 = $_GET["tar1"];
                    @$tarih2 = $_GET["tar2"];
                    
                    $veri = $yonclas->ciktiSorgusu($vt, "select * from rapor where DATE(tarih) BETWEEN '$tarih1' AND '$tarih2'");
                    $veri2 = $yonclas->ciktiSorgusu($vt, "select * from rapor where DATE(tarih) BETWEEN '$tarih1' AND '$tarih2'");
                    
                    echo '<table class = "table text-center table-light table-bordered mx-auto mt-4 table-striped col-md-12">
                    <thead>
                        <tr>
                            <th colspan="5"><div class = "alert alert-info text-center mx-auto mt-4">Tarih Seçimi: '.$tarih1.' - '.$tarih2.'</div></th>            
                            <th colspan="2"><button onclick="yazdir()" class="btn btn-warning mx-auto mt-4">YAZDIR</a></th>
                       </tr>
                    </thead>
                    <tbody>
                    <tr>
   
                        <th colspan = "4">
                            <table class="table text-center table-bordered col-md-12">
                                <thead>
                                    <tr>
                                        <th colspan="4" class="table-dark">Garson Performans</th>
                                    </tr>
                                </thead>
                                <thead>
                                    <tr class="table-danger">
                                        <th colspan="2">Garson Ad</th>
                                        <th colspan="2">Adet</th>
                                    </tr>
                                </thead><tbody>';

                                $kilit = $yonclas->ciktiSorgusu($vt, "select * from gecicigarson");
                                if($kilit->num_rows==0) :

                                    while ($gel = $veri->fetch_assoc()):
                                        // Raporlama için masa adını çekiyorum
                                        $garsonid = $gel["garsonid"];
                                        $masaveri = $yonclas->ciktiSorgusu($vt, "select * from garson where id=$garsonid")->fetch_assoc();
                                        $garsonad = $masaveri["ad"];
                                        // Raporlama için masa adını çekiyorum

                                        $raporbak = $yonclas->ciktiSorgusu($vt, "select * from gecicigarson where garsonid=$garsonid");

                                        if ($raporbak->num_rows==0) :
                                            // gecici masaya, sipariş verilmediyse ekleme yap
                                            // $has = $gel["adet"] * $gel["urunfiyat"];
                                            $adet = $gel["adet"];
                                            $yonclas->ciktiSorgusu($vt, "insert into gecicigarson (garsonid, garsonad, adet) values ($garsonid, '$garsonad', $adet)");

                                        else:
                                            // yada güncelleme yap
                                            $raporson = $raporbak->fetch_assoc();
                                            $gelenadet = $raporson["adet"];

                                            $sonadet = $gelenadet + $gel["adet"];

                                            $yonclas->ciktiSorgusu($vt, "update gecicigarson set adet=$sonadet where garsonid=$garsonid");

                                        endif;

                                    endwhile;

                                endif;

                                $son = $yonclas->ciktiSorgusu($vt, "select * from gecicigarson order by adet desc;");

                                $toplamadet = 0;

                                while ($listele = $son->fetch_assoc()):
                                    echo '<tr>
                                            <td colspan="2">'.$listele["garsonad"].'</td>
                                            <td colspan="2">'.$listele["adet"].'</td>
                                        </tr>';
                                        $toplamadet += $listele["adet"];
                                endwhile;

                        echo '<tr class="table-danger">
                                <td colspan="2">TOPLAM</td>
                                <td colspan="2">'.$toplamadet.'</td>
                            </tr>
                        
                                </tbody> 
                                    </table>                   
                                        </th>

                            </tr>
                                </tbody>
                                    </table>';
                    
                    
                    break;
                    

            endswitch;
            ?>                           

        </div>
    </div>
</body>
</html>




