<?php
session_start();
require_once "core/Database.php";
require_once "app/controllers/HomeController.php";

$sayfa = isset($_GET["sayfa"]) ? $_GET["sayfa"] : "anasayfa";

switch ($sayfa) {
    case "anasayfa":
        $controller = new HomeController();
        $controller->index();
        break;
    default:
        header("HTTP/1.0 404 Not Found");
        echo "<h1>404 - Sayfa Bulunamadı</h1>";
        break;
}
