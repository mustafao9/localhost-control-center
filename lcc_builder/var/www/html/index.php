<?php
/**
 * Localhost Control Center v12.0 - Core Router & Controller
 * Lead Developer: Mustafa Satılmış (@mustafao9)
 */
require_once __DIR__ . '/config.php';

$bildirim = '';

// AJAX İstemleri
if (isset($_GET['ajax_oku']) && guvenliYolMu($_GET['ajax_oku'])) {
    header('Content-Type: text/plain; charset=utf-8');
    echo @file_get_contents($_GET['ajax_oku']);
    exit;
}

if (isset($_GET['ajax_composer_kur'])) {
    header('Content-Type: text/event-stream');
    header('Cache-Control: no-cache');
    $hedef_proje = $_GET['proje'] ?? '';
    $paket_adi = trim($_GET['paket'] ?? '');

    if (!empty($hedef_proje) && !empty($paket_adi) && guvenliYolMu($hedef_proje)) {
        ini_set('max_execution_time', 300);
        $komut = "export COMPOSER_HOME=/tmp/composer; export COMPOSER_DISABLE_DEPRECATIONS=1; cd " . escapeshellarg($hedef_proje) . " && composer require " . escapeshellarg($paket_adi) . " --no-interaction 2>&1";
        $handle = popen($komut, "r");
        if ($handle) {
            while (!feof($handle)) {
                $line = fgets($handle);
                if ($line !== false && strpos($line, 'Deprecation Notice') === false) {
                    echo "data: " . json_encode(['msg' => $line]) . "\n\n";
                    @ob_flush(); flush();
                }
            }
            pclose($handle);
        }
    }
    echo "data: " . json_encode(['done' => true]) . "\n\n";
    @ob_flush(); flush(); exit;
}

if (isset($_GET['islem']) && $_GET['islem'] === 'phpinfo') { phpinfo(); exit; }

// POST İşlemleri
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['islem_tipi'])) {
    $islem = $_POST['islem_tipi'];
    $hedef = $_POST['hedef_dizin'] ?? DOC_ROOT;

    if (guvenliYolMu($hedef)) {
        if ($islem === 'yeni_proje_olustur') {
            $proje_adi = preg_replace('/[^a-zA-Z0-9_-]/', '', trim($_POST['proje_adi'] ?? ''));
            if (!empty($proje_adi)) {
                $yeni_yol = DOC_ROOT . '/' . $proje_adi;
                if (!file_exists($yeni_yol) && @mkdir($yeni_yol, 0777, true)) {
                    $index_content = "<?php\n/** Proje: " . htmlspecialchars($proje_adi) . " */\nif (file_exists(__DIR__ . '/vendor/autoload.php')) require_once __DIR__ . '/vendor/autoload.php';\n?>\n<h1>🚀 " . htmlspecialchars($proje_adi) . " Çalışıyor!</h1>";
                    @file_put_contents($yeni_yol . '/index.php', $index_content);
                    exec("sudo /usr/local/bin/fix-html-permissions.sh unlock " . escapeshellarg($yeni_yol));
                    $bildirim = '<div class="alert alert-basari">🚀 <strong>' . htmlspecialchars($proje_adi) . '</strong> projesi oluşturuldu.</div>';
                }
            }
        } elseif ($islem === 'veritabani_baglantisi_olustur') {
            $proje_adi = preg_replace('/[^a-zA-Z0-9_-]/', '', trim($_POST['db_proje'] ?? ''));
            $db_adi = preg_replace('/[^a-zA-Z0-9_-]/', '', trim($_POST['db_adi'] ?? ''));
            $db_user = trim($_POST['db_kullanici'] ?? 'root');
            $db_pass = trim($_POST['db_sifre'] ?? '');
            $db_host = trim($_POST['db_host'] ?? 'localhost');

            if (!empty($proje_adi) && !empty($db_adi)) {
                try {
                    $pdo = new PDO("mysql:host=$db_host", $db_user, $db_pass);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db_adi` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
                    $db_olustu = true;
                } catch (PDOException $e) {
                    $db_olustu = false;
                    $db_hata = $e->getMessage();
                }

                $env_dir = '/var/www/env';
                $env_dosya = $env_dir . '/' . $proje_adi . '.env.php';
                
                $env_icerik = "<?php\nreturn [\n";
                $env_icerik .= "    'DB_HOST' => '" . addslashes($db_host) . "',\n";
                $env_icerik .= "    'DB_NAME' => '" . addslashes($db_adi) . "',\n";
                $env_icerik .= "    'DB_USER' => '" . addslashes($db_user) . "',\n";
                $env_icerik .= "    'DB_PASS' => '" . addslashes($db_pass) . "',\n";
                $env_icerik .= "    'DB_CHARSET' => 'utf8mb4'\n";
                $env_icerik .= "];\n";

                @file_put_contents($env_dosya, $env_icerik);

                $proje_baglanti_dosyasi = DOC_ROOT . '/' . $proje_adi . '/baglanti.php';
                $baglanti_kod = "<?php\n";
                $baglanti_kod .= "/** %100 Güvenli Dinamik PDO Bağlantısı */\n";
                $baglanti_kod .= "\$env_yol = '/var/www/env/" . $proje_adi . ".env.php';\n";
                $baglanti_kod .= "if (!file_exists(\$env_yol)) {\n";
                $baglanti_kod .= "    \$env_yol = __DIR__ . '/.env.php';\n";
                $baglanti_kod .= "}\n";
                $baglanti_kod .= "if (!file_exists(\$env_yol)) {\n";
                $baglanti_kod .= "    die(\"❌ Güvenlik Hatası: Veritabanı yapılandırma dosyası (.env.php) bulunamadı!\");\n";
                $baglanti_kod .= "}\n";
                $baglanti_kod .= "\$config = require \$env_yol;\n";
                $baglanti_kod .= "try {\n";
                $baglanti_kod .= "    \$db = new PDO(\"mysql:host=\" . \$config['DB_HOST'] . \";dbname=\" . \$config['DB_NAME'] . \";charset=\" . (\$config['DB_CHARSET'] ?? 'utf8mb4'), \$config['DB_USER'], \$config['DB_PASS']);\n";
                $baglanti_kod .= "    \$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);\n";
                $baglanti_kod .= "    \$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);\n";
                $baglanti_kod .= "} catch (PDOException \$e) {\n";
                $baglanti_kod .= "    die(\"Veritabanı Bağlantı Hatası: \" . \$e->getMessage());\n";
                $baglanti_kod .= "}\n";

                @file_put_contents($proje_baglanti_dosyasi, $baglanti_kod);
                exec("sudo /usr/local/bin/fix-html-permissions.sh unlock " . escapeshellarg(DOC_ROOT . '/' . $proje_adi));

                if ($db_olustu) {
                    $bildirim = '<div class="alert alert-basari">🗄️ <strong>' . htmlspecialchars($db_adi) . '</strong> veritabanı oluşturuldu ve <code>' . htmlspecialchars($proje_adi) . '/baglanti.php</code> (Sıfır Şifre / %100 Güvenli) bağlandı!</div>';
                } else {
                    $bildirim = '<div class="alert alert-bilgi">⚠️ Bağlantı dosyası oluşturuldu ancak MySQL hatası: ' . htmlspecialchars($db_hata) . '</div>';
                }
            }
        } elseif ($islem === 'veritabani_test_et') {
            $test_proje = preg_replace('/[^a-zA-Z0-9_-]/', '', trim($_POST['test_proje'] ?? ''));
            $env_yol = '/var/www/env/' . $test_proje . '.env.php';

            if (file_exists($env_yol)) {
                $config = require $env_yol;
                try {
                    $test_db = new PDO("mysql:host=" . $config['DB_HOST'] . ";dbname=" . $config['DB_NAME'] . ";charset=" . $config['DB_CHARSET'], $config['DB_USER'], $config['DB_PASS']);
                    $test_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                    $sorgu = $test_db->query("SHOW TABLES");
                    $tablolar = $sorgu->fetchAll(PDO::FETCH_COLUMN);

                    if (!empty($tablolar)) {
                        $tablo_listesi = implode(', ', array_map(function($t) { return '<code>' . htmlspecialchars($t) . '</code>'; }, $tablolar));
                        $bildirim = '<div class="alert alert-basari">🟢 <strong>' . htmlspecialchars($test_proje) . '</strong> projesinin <code>' . htmlspecialchars($config['DB_NAME']) . '</code> veritabanı bağlantısı **BAŞARILI**!<br>📊 <strong>' . count($tablolar) . '</strong> adet tablo bulundu: ' . $tablo_listesi . '</div>';
                    } else {
                        $bildirim = '<div class="alert alert-basari">🟢 <strong>' . htmlspecialchars($test_proje) . '</strong> projesinin <code>' . htmlspecialchars($config['DB_NAME']) . '</code> veritabanı bağlantısı **BAŞARILI**!<br>ℹ️ Veritabanında henüz hiç tablo bulunmuyor (Boş Veritabanı).</div>';
                    }
                } catch (PDOException $e) {
                    $bildirim = '<div class="alert alert-hata">❌ <strong>' . htmlspecialchars($test_proje) . '</strong> veritabanı bağlantısı BAŞARISIZ!<br>Hata Detayı: ' . htmlspecialchars($e->getMessage()) . '</div>';
                }
            } else {
                $bildirim = '<div class="alert alert-hata">❌ <strong>' . htmlspecialchars($test_proje) . '</strong> projesi için henüz bir <code>.env.php</code> yapılandırması oluşturulmamış!</div>';
            }
        } elseif ($islem === 'ozel_eleman_olustur') {
            $hedef_klasor = $_POST['hedef_klasor_yolu'] ?? DOC_ROOT;
            $tur = $_POST['olusturma_turu'] ?? 'dosya';
            $isim = trim($_POST['eleman_ismi'] ?? '');
            $uzanti = $_POST['dosya_uzantisi'] ?? '.php';

            if (!empty($isim) && guvenliYolMu($hedef_klasor)) {
                if ($tur === 'klasor') {
                    $tam_yol = rtrim($hedef_klasor, '/') . '/' . $isim;
                    if (!file_exists($tam_yol) && @mkdir($tam_yol, 0777, true)) {
                        exec("sudo /usr/local/bin/fix-html-permissions.sh unlock " . escapeshellarg($tam_yol));
                        $bildirim = '<div class="alert alert-basari">📁 <strong>' . htmlspecialchars($isim) . '</strong> klasörü oluşturuldu.</div>';
                    }
                } else {
                    $isim = (substr($isim, -strlen($uzanti)) !== $uzanti && $uzanti !== '.htaccess') ? $isim . $uzanti : $isim;
                    $tam_yol = rtrim($hedef_klasor, '/') . '/' . ($uzanti === '.htaccess' ? '.htaccess' : $isim);
                    if (!file_exists($tam_yol) && @file_put_contents($tam_yol, "<?php\n") !== false) {
                        exec("sudo /usr/local/bin/fix-html-permissions.sh unlock " . escapeshellarg($tam_yol));
                        $bildirim = '<div class="alert alert-basari">📄 <strong>' . htmlspecialchars(basename($tam_yol)) . '</strong> oluşturuldu.</div>';
                    }
                }
            }
        } elseif ($islem === 'yedek_al') {
            $gercek_hedef = realpath($hedef);
            $proje_adi = basename($gercek_hedef);
            $backup_dir = BACKUP_DIR;
            if (!file_exists($backup_dir)) {
                @mkdir($backup_dir, 0777, true);
                exec("sudo /usr/local/bin/fix-html-permissions.sh unlock " . escapeshellarg($backup_dir));
            }
            if (!class_exists('ZipArchive')) {
                $bildirim = '<div class="alert alert-hata">❌ Hata: PHP ZipArchive modülü sunucuda yüklü değil!</div>';
            } elseif ($gercek_hedef && is_dir($gercek_hedef)) {
                $zip_tam_yol = $backup_dir . '/' . $proje_adi . '_' . date("Y-m-d_H-i") . '.zip';
                $zip = new ZipArchive();
                $res = $zip->open($zip_tam_yol, ZipArchive::CREATE | ZipArchive::OVERWRITE);
                if ($res === TRUE) {
                    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($gercek_hedef, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::LEAVES_ONLY);
                    $eklenen_sayi = 0;
                    foreach ($files as $file) {
                        if (!$file->isDir()) {
                            $filePath = $file->getRealPath();
                            $relativePath = substr($filePath, strlen($gercek_hedef) + 1);
                            $zip->addFile($filePath, $relativePath);
                            $eklenen_sayi++;
                        }
                    }
                    $zip->close();
                    clearstatcache();
                    if (file_exists($zip_tam_yol) && filesize($zip_tam_yol) > 0) {
                        exec("sudo /usr/local/bin/fix-html-permissions.sh unlock " . escapeshellarg($zip_tam_yol));
                        $bildirim = '<div class="alert alert-basari">📦 <strong>' . htmlspecialchars($proje_adi) . '</strong> projesinin yedeği (' . $eklenen_sayi . ' dosya) başarıyla alındı.</div>';
                    } else {
                        $bildirim = '<div class="alert alert-hata">❌ Hata: ZIP dosyası oluşturuldu ancak 0 bayt görünüyor!</div>';
                    }
                } else {
                    $bildirim = '<div class="alert alert-hata">❌ ZIP Açma Hatası! Kodu: ' . $res . '</div>';
                }
            } else {
                $bildirim = '<div class="alert alert-hata">❌ Geçersiz Proje Klasörü!</div>';
            }
        } elseif ($islem === 'yedek_geri_yukle') {
            $zip_yol = $_POST['yedek_dosyasi'] ?? '';
            if (file_exists($zip_yol) && class_exists('ZipArchive')) {
                $zip_adi = basename($zip_yol);
                $parcalar = explode('_', $zip_adi);
                $proje_adi = $parcalar[0];
                $hedef_proje_dizini = DOC_ROOT . '/' . $proje_adi;

                if (guvenliYolMu($hedef_proje_dizini)) {
                    $zip = new ZipArchive();
                    if ($zip->open($zip_yol) === TRUE) {
                        if (!file_exists($hedef_proje_dizini)) {
                            @mkdir($hedef_proje_dizini, 0777, true);
                        }
                        $zip->extractTo($hedef_proje_dizini);
                        $zip->close();
                        exec("sudo /usr/local/bin/fix-html-permissions.sh unlock " . escapeshellarg($hedef_proje_dizini));
                        $bildirim = '<div class="alert alert-basari">🔄 <strong>' . htmlspecialchars($proje_adi) . '</strong> projesi başarıyla <code>' . htmlspecialchars($zip_adi) . '</code> yedeğine geri döndürüldü!</div>';
                    }
                }
            }
        } elseif ($islem === 'unlock' || $islem === 'lock') {
            exec("sudo /usr/local/bin/fix-html-permissions.sh " . escapeshellarg($islem) . " " . escapeshellarg(realpath($hedef)));
            $bildirim = '<div class="alert alert-basari">🔐 İzinler güncellendi.</div>';
        } elseif ($islem === 'sil') {
            if (realpath($hedef) !== realpath(DOC_ROOT)) {
                exec("rm -rf " . escapeshellarg(realpath($hedef)));
                clearstatcache();
                $bildirim = '<div class="alert alert-bilgi">🗑️ Silindi: <strong>' . htmlspecialchars(basename($hedef)) . '</strong></div>';
            }
        } elseif ($islem === 'dosya_kaydet') {
            @file_put_contents(realpath($hedef), $_POST['dosya_icerik'] ?? '');
            $bildirim = '<div class="alert alert-basari">💾 Kaydedildi.</div>';
        }
    }
}

// GET Önbellek İstemleri
if (isset($_GET['islem'])) {
    if ($_GET['islem'] === 'opcache_temizle' && function_exists('opcache_reset')) {
        opcache_reset(); $bildirim = '<div class="alert alert-basari">⚡ OPcache sıfırlandı!</div>';
    } elseif ($_GET['islem'] === 'session_temizle') {
        session_unset(); $bildirim = '<div class="alert alert-basari">🧹 Session temizlendi.</div>';
    }
}

// Sayfa Çıktısı (View Rendering)
require_once __DIR__ . '/includes/header.php';
echo $bildirim;
require_once __DIR__ . '/modules/dashboard.php';
require_once __DIR__ . '/modules/creator.php';
require_once __DIR__ . '/modules/database_wizard.php';
require_once __DIR__ . '/modules/composer.php';
require_once __DIR__ . '/modules/server.php';
require_once __DIR__ . '/modules/projects.php';
require_once __DIR__ . '/includes/footer.php';
