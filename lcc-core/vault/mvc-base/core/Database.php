<?php
class Database {
    private static $instance = null;
    public static function baglan() {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO("mysql:host=localhost;dbname={{DB_NAME}};charset=utf8mb4", "{{DB_USER}}", "{{DB_PASS}}");
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Bağlantı hatası: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
