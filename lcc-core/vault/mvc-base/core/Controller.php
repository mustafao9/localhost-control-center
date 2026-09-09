<?php
class Controller {
    public function model($model) {
        if (file_exists(__DIR__ . "/../app/models/" . $model . ".php")) {
            require_once __DIR__ . "/../app/models/" . $model . ".php";
            return new $model();
        }
        return null;
    }

    public function view($view, $data = []) {
        extract($data);
        if (file_exists(__DIR__ . "/../app/views/" . $view . ".php")) {
            require_once __DIR__ . "/../app/views/" . $view . ".php";
        } else {
            die("View bulunamadı: " . $view);
        }
    }
}
