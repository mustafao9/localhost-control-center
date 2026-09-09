<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title><?php echo $siteAdi; ?></title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; background: #f4f4f9; color: #333; }
        .container { background: #fff; padding: 30px; border-radius: 8px; display: inline-block; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .badge { background: #e0f2fe; color: #0369a1; padding: 5px 10px; border-radius: 4px; font-weight: bold; margin-top: 15px; display: inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hoş Geldiniz!</h1>
        <p>Bu proje <strong><?php echo $siteAdi; ?></strong> adıyla LCC Core tarafından üretilmiştir.</p>
        <div class="badge"><?php echo $veri["mesaj"]; ?></div>
    </div>
</body>
</html>
