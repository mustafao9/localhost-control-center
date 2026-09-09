<?php
session_start();
require_once "core/Database.php";

$sayfa = isset($_GET["sayfa"]) ? $_GET["sayfa"] : "anasayfa";

// Basit MVC yönlendirici iskeleti
switch ($sayfa) {
    case "anasayfa":
        echo "<h1>{{SITE_NAME}} Projesine Hoş Geldiniz</h1>";
        break;
    default:
        echo "<h1>404 - Sayfa Bulunamadı</h1>";
        break;
}

