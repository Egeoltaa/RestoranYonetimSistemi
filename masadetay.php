<?php 
session_start(); 
include("fonksiyon/fonksiyon.php"); 
$masam = new sistem;
include("admin/fonk/temaiki.php"); 
$tema2 = new temadestek;

// Garson kontrolü
$veri = $masam->benimsorgum2($db, "select * from garson where durum=1", 1)->num_rows;

if ($veri == 0) {
    header("Location:index.php");
}

// masaid kontrolü
$masaid = isset($_GET["masaid"]) ? $_GET["masaid"] : null;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="dosya/jqu.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJw8ERdknLPMO" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <link rel="stylesheet" href="dosya/temaikistil.css" >
    <link rel="icon" href="img/cutlery.png" type="image/x-icon">

    <script>
        $(document).ready(function() {
            var id = "<?php echo $masaid; ?>";
            $("#veri").load("islemler.php?islem=goster&id=" + id);
            $('#btn').click(function() {
                $.ajax({
                    type: "POST",
                    url: 'islemler.php?islem=ekle',
                    data: $('#formum').serialize(),
                    success: function(donen_veri) {
                        $("#veri").load("islemler.php?islem=goster&id=" + id);
                        $('#formum').trigger("reset");
                        $("#cevap").html(donen_veri).fadeOut(1400);
                        window.location.reload();
                    }
                });
            });

            $('#urunler a').click(function() {
                var sectionId = $(this).attr('sectionId');
                $("#sonuc").load("islemler.php?islem=urun&katid=" + sectionId);
            });
        });
    </script>
    <style>
        html, body {
            height: 100%;
        }
    </style>
    <title>Restaurant Sipariş Sistemi</title>
</head>
<body>
<div class="container-fluid h-100">
<?php 
if ($masaid != "") :
    $son = $masam->masagetir($db, $masaid);
    $dizi = $son->fetch_assoc();
?>
    <div class="row justify-content-center h-100">
        <!-- Sol Menü -->
        <div class="col-md-3">
            <div class="row">
                <div class="col-md-12 bg-white border-bottom border-dark">
                    <div class="row">
                        <div class="col-md-4 border-right border-light">
                            <a href="index.php" class="btn btn-success" style="margin-top:20px; margin-left:15px;">
                                <i class="fas fa-arrow-left" style="font-size:38px;"></i>
                            </a>
                        </div>
                        <div class="col-md-8 text-center mx-auto p-3"><?php echo $dizi["ad"]; ?></div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12 mx-auto bg-white border-bottom border-right border-light" id="veri"></div>
                        <div class="col-md-12" id="cevap"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Orta Bölüm -->
        <div class="col-md-9 border-left" style="border:#405353;">
            <form id="formum">
                <div class="row pt-2">
                    <div class="col-md-12" style="min-height:200px;" id="urunler">
                        <?php $tema2->temaikiurungrup($db); ?>
                    </div>
                    <div class="col-md-12 text-center" style="min-height:410px; background-color:#dde1e1;" id="sonuc">
                        <i class="fas fa-arrow-up" style="font-size:90px; color:#F8F8F8; margin-top:10%;"></i>
                    </div>
                    <!-- Ürün Adet ve Ekle Butonu -->
                    <div class="col-md-12" style="min-height:170px;">
                        <div class="row">
                            <!-- EKLE Butonu solda -->
                            <div class="col-md-2 text-center border-right">
                                <input type="hidden" name="masaid" value="<?php echo $dizi["id"]; ?>" />
                                <input type="button" id="btn" value="EKLE" class="btn mt-5 mb-1" style="font-size:30px; height:80px; min-width:100px; color:#000; background-color:#2da51d;" />
                            </div>
                            <!-- ÜRÜN ADET Ortada -->
                            <div class="col-md-5 offset-md-2 text-center">
        <h2>ÜRÜN ADET</h2><hr />
        <div class="input-group">
        <button class="btn btn-outline-secondary" type="button" onclick="decreaseQuantity()">-</button>
        <input type="number" id="adet" name="adet" class="form-control text-center" value="1" readonly />
        <button class="btn btn-outline-secondary" type="button" onclick="increaseQuantity()">+</button>
        </div>

<script>
function increaseQuantity() {
    var input = document.getElementById('adet');
    var currentValue = parseInt(input.value);
    input.value = currentValue + 1;
}

function decreaseQuantity() {
    var input = document.getElementById('adet');
    var currentValue = parseInt(input.value);
    if (currentValue > 1) {
        input.value = currentValue - 1;
    }
}
</script>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php 
else:
    echo "Hata var, masaid boş.";
    header("refresh:1, url=index.php");
endif; ?>
</div>
</body>
</html>
