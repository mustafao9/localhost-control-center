<?php
class HomeModel {
    public function getBilgi() {
        $db = Database::baglan();
        return [
            "mesaj" => "Model katmanı aktif ve veritabanı bağlantısı başarılı!",
            "versiyon" => "1.0.0"
        ];
    }
}
