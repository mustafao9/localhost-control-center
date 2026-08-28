<?php
$mysql_aktif = false;
try {
    $conn = @new mysqli('localhost', 'root', '');
    $mysql_aktif = !$conn->connect_error;
} catch (Exception $e) {
    $baglanti = @fsockopen('localhost', 3306);
    if (is_resource($baglanti)) { $mysql_aktif = true; fclose($baglanti); }
}
$phpmyadmin_kurulu = file_exists('/usr/share/phpmyadmin') || file_exists('/usr/share/phpMyAdmin');
$disk_toplam = @disk_total_space(DOC_ROOT);
$disk_bos = @disk_free_space(DOC_ROOT);
$disk_kullanilan = $disk_toplam - $disk_bos;
$disk_yuzde = ($disk_toplam && $disk_bos) ? round(($disk_kullanilan / $disk_toplam) * 100, 1) : 0;
?>
<div class="main-grid">
    <div class="card">
        <div class="card-header"><h2 class="card-title">🗄️ Veritabanı & Servisler</h2></div>
        <table class="table-custom">
            <tr><td>MariaDB / MySQL:</td><td><?= $mysql_aktif ? '<span class="badge badge-success">🟢 Çalışıyor</span>' : '<span class="badge badge-danger">🔴 Kapalı</span>'; ?></td></tr>
            <tr><td>phpMyAdmin:</td><td><?= $phpmyadmin_kurulu ? '<span class="badge badge-success">Kurulu</span>' : '<span class="badge badge-danger">Eksik</span>'; ?></td></tr>
        </table>
        <div style="margin-top:1rem;"><a href="/phpmyadmin" target="_blank" class="btn" style="width:100%;">🗄️ phpMyAdmin Başlat ↗</a></div>
    </div>

    <div class="card">
        <div class="card-header"><h2 class="card-title">🌐 Sunucu Araçları</h2></div>
        <div style="font-size:0.8rem; color:var(--text-muted); margin-bottom:0.4rem;">Disk Doluluğu: <?= formatBoyut($disk_kullanilan); ?> / <?= formatBoyut($disk_toplam); ?></div>
        <div class="progress-bar"><div class="progress-fill" style="width: <?= $disk_yuzde; ?>%;"></div></div>
        <div style="display:flex; gap:0.5rem; margin-top:1rem;">
            <a href="?islem=opcache_temizle" class="btn btn-secondary" style="flex:1;">⚡ OPcache</a>
            <a href="?islem=session_temizle" class="btn btn-secondary" style="flex:1;">🧹 Session</a>
            <a href="?islem=phpinfo" target="_blank" class="btn btn-secondary" style="flex:1;">📄 phpinfo()</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h2 class="card-title">🔐 İzin Kontrolü (777 / 644)</h2></div>
        <p style="font-size:0.78rem; color:var(--text-muted); margin-top:0;">Bluefish / IDE kilitlerini açın:</p>
        <div style="display:flex; gap:0.5rem;">
            <form method="post" style="flex:1;"><input type="hidden" name="hedef_dizin" value="<?= DOC_ROOT; ?>"><input type="hidden" name="islem_tipi" value="unlock"><button type="submit" class="btn btn-success" style="width:100%;">🔓 777 İzin Ver</button></form>
            <form method="post" style="flex:1;"><input type="hidden" name="hedef_dizin" value="<?= DOC_ROOT; ?>"><input type="hidden" name="islem_tipi" value="lock"><button type="submit" class="btn btn-danger" style="width:100%;">🔒 Kilitle</button></form>
        </div>
    </div>
</div>
