<?php
class HomeController extends Controller {
    public function index() {
        $siteAdi = "{{SITE_NAME}}";
        
        $homeModel = $this->model("HomeModel");
        $veri = $homeModel ? $homeModel->getBilgi() : [];

        $this->view("anasayfa", [
            "siteAdi" => $siteAdi,
            "veri" => $veri
        ]);
    }
}
