<?php
class HomeController {
    public function index() {
        $siteAdi = "{{SITE_NAME}}";
        require_once __DIR__ . "/../views/anasayfa.php";
    }
}
