<?php
/**
 * Localhost Control Center (LCC) - Config & Helpers
 * Lead Developer: Mustafa Satılmış (@mustafao9)
 */
define('DOC_ROOT', '/var/www/html');
define('BACKUP_DIR', DOC_ROOT . '/backups');
define('DEVELOPER_NAME', 'Mustafa Satılmış');
define('DEVELOPER_GITHUB', 'https://github.com/mustafao9');

function guvenliYolMu($hedef, $kok = DOC_ROOT) {
    $gercek_hedef = realpath($hedef);
    $gercek_kok = realpath($kok);
    if ($gercek_hedef === false) {
        $parent = realpath(dirname($hedef));
        return ($parent && (strpos($parent, $gercek_kok) === 0 || $parent === $gercek_kok));
    }
    return ($gercek_hedef && (strpos($gercek_hedef, $gercek_kok) === 0 || $gercek_hedef === $gercek_kok));
}

function formatBoyut($bayt) {
    if ($bayt >= 1073741824) return number_format($bayt / 1073741824, 2) . ' GB';
    if ($bayt >= 1048576) return number_format($bayt / 1048576, 2) . ' MB';
    if ($bayt >= 1024) return number_format($bayt / 1024, 2) . ' KB';
    return $bayt . ' B';
}

function dizinAgaciniGetir($dizinYolu, $goreceliYol = '') {
    $sonuc = [];
    $elemanlar = @scandir($dizinYolu);
    if (!$elemanlar) return $sonuc;

    foreach ($elemanlar as $eleman) {
        if ($eleman === '.' || $eleman === '..' || $eleman === 'phpmyadmin' || $eleman === 'index.php' || $eleman === 'config.php' || $eleman === 'includes' || $eleman === 'modules' || $eleman === 'backups') continue;
        
        $tamYol = $dizinYolu . '/' . $eleman;
        $yeniGoreceli = $goreceliYol ? $goreceliYol . '/' . $eleman : $eleman;
        $klasorMu = is_dir($tamYol);
        
        $item = [
            'ad' => $eleman,
            'tam_yol' => $tamYol,
            'goreceli_yol' => $yeniGoreceli,
            'klasor_mu' => $klasorMu,
            'yazilabilir' => is_writable($tamYol),
            'boyut' => $klasorMu ? '-' : formatBoyut(@filesize($tamYol)),
            'son_degisme' => date("d.m.Y H:i", @filemtime($tamYol)),
            'cocuklar' => []
        ];
        
        if ($klasorMu) {
            $item['cocuklar'] = dizinAgaciniGetir($tamYol, $yeniGoreceli);
        }
        $sonuc[] = $item;
    }
    return $sonuc;
}

function tumAltKlasorleriGetir($dizinYolu, $kokDizin = DOC_ROOT) {
    $liste = [];
    $elemanlar = @scandir($dizinYolu);
    if (!$elemanlar) return $liste;

    foreach ($elemanlar as $eleman) {
        if ($eleman === '.' || $eleman === '..' || $eleman === 'phpmyadmin' || $eleman === 'backups' || $eleman === 'vendor' || $eleman === 'includes' || $eleman === 'modules') continue;
        $tamYol = $dizinYolu . '/' . $eleman;
        if (is_dir($tamYol)) {
            $goreceli = str_replace($kokDizin . '/', '', $tamYol);
            $liste[] = ['tam_yol' => $tamYol, 'goreceli' => $goreceli];
            $liste = array_merge($liste, tumAltKlasorleriGetir($tamYol, $kokDizin));
        }
    }
    return $liste;
}
