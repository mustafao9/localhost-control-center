<?php
require_once __DIR__ . "/../models/HomeModel.php";

class HomeController {
    public function index() {
        $siteAdi = "{{SITE_NAME}}";
        
        $model = new HomeModel();
        $veri = $model->getBilgi();

        require_once __DIR__ . "/../views/anasayfa.php";
    }
}
