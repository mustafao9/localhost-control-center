<?php
clearstatcache();
$disk_toplam = @disk_total_space(DOC_ROOT);
$disk_bos = @disk_free_space(DOC_ROOT);
$disk_kullanilan = $disk_toplam - $disk_bos;
$disk_yuzde = ($disk_toplam && $disk_bos) ? round(($disk_kullanilan / $disk_toplam) * 100, 1) : 0;
$cpu_load = function_exists('sys_getloadavg') ? sys_getloadavg() : [0, 0, 0];
$toplam_proje_sayisi = count(array_filter(glob(DOC_ROOT . '/*'), 'is_dir'));
$toplam_yedek_sayisi = file_exists(BACKUP_DIR) ? count(glob(BACKUP_DIR . '/*.zip')) : 0;
?>
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">📁</div>
        <div><div class="stat-val"><?= $toplam_proje_sayisi; ?></div><div class="stat-lbl">Aktif Proje</div></div>
    </div>
    
    <div class="stat-card" onclick="modalAc('yedeklerModal')" style="cursor:pointer; border-color:rgba(168,85,247,0.4);">
        <div class="stat-icon" style="color:var(--purple); background:rgba(168,85,247,0.1);">📦</div>
        <div>
            <div class="stat-val"><?= $toplam_yedek_sayisi; ?></div>
            <div class="stat-lbl" style="color:var(--purple); font-weight:600;">Yedek (Arşiv) Yöneticisi ↗</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="color:var(--success); background:rgba(34,197,94,0.1);">💾</div>
        <div><div class="stat-val">%<?= $disk_yuzde; ?></div><div class="stat-lbl">Disk Doluluk</div></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="color:var(--warning); background:rgba(245,158,11,0.1);">⚡</div>
        <div><div class="stat-val"><?= $cpu_load[0]; ?></div><div class="stat-lbl">CPU Yükü (1m)</div></div>
    </div>
</div>
