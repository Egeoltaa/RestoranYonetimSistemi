<?php
// Bu dosyayı index.php olarak kaydedebilirsiniz.
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Sayfası</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h1 {
            margin-bottom: 30px;
            font-size: 24px;
            color: #333;
        }
        .btn-group {
            display: flex;
            justify-content: space-around;
            gap: 20px;
        }
        .btn {
            display: inline-block;
            padding: 15px 30px;
            font-size: 18px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            color: white;
            background-color: #007bff;
            transition: background-color 0.3s;
            cursor: pointer;
            width: 150px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn i {
            margin-right: 10px;
        }
        .admin {
            background-color: #dc3545;
        }
        .admin:hover {
            background-color: #c82333;
        }
        .garson {
            background-color: #28a745;
        }
        .garson:hover {
            background-color: #218838;
        }
        .mutfak {
            background-color: #ffc107;
        }
        .mutfak:hover {
            background-color: #e0a800;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Giriş Yapın</h1>
        <div class="btn-group">
            <a href="admin/index.php" class="btn admin"><i class="fas fa-user-shield"></i> Yönetici Girişi</a>
            <a href="siparis.php" class="btn garson"><i class="fas fa-utensils"></i> Garson Girişi</a>
            <a href="mutfak/index.php" class="btn mutfak"><i class="fas fa-hamburger"></i> Mutfak Girişi</a>
        </div>
    </div>
</body>
</html>
