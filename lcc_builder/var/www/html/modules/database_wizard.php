<?php
$env_dir = '/var/www/env';
if (!file_exists($env_dir)) {
    @mkdir($env_dir, 0777, true);
}
?>
<div class="main-grid" style="margin-bottom: 2rem;">
    <div class="card" style="grid-column: span 2;">
        <div class="card-header">
            <h2 class="card-title">🗄️ Veritabanı & Secure ENV Sihirbazı</h2>
        </div>
        <form method="post" style="display:flex; flex-direction:column; gap:0.8rem;">
            <input type="hidden" name="islem_tipi" value="veritabani_baglantisi_olustur">
            
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem;">
                <div>
                    <label style="font-size:0.8rem; color:var(--text-muted);">1. Hedef Proje Seçin:</label>
                    <select name="db_proje" required class="input-control">
                        <option value="">-- Proje Seç --</option>
                        <?php
                        $dizinler = array_filter(glob(DOC_ROOT . '/*'), 'is_dir');
                        foreach ($dizinler as $d) {
                            $b = basename($d);
                            if ($b !== 'phpmyadmin' && $b !== 'backups' && $b !== 'includes' && $b !== 'modules') {
                                echo '<option value="' . htmlspecialchars($b) . '">📁 ' . htmlspecialchars($b) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label style="font-size:0.8rem; color:var(--text-muted);">2. Veritabanı Adı (DB Name):</label>
                    <input type="text" name="db_adi" placeholder="Örn: sohbet_db" required class="input-control">
                </div>
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:1rem;">
                <div>
                    <label style="font-size:0.8rem; color:var(--text-muted);">Veritabanı Kullanıcısı:</label>
                    <input type="text" name="db_kullanici" value="root" required class="input-control">
                </div>
                <div>
                    <label style="font-size:0.8rem; color:var(--text-muted);">Veritabanı Şifresi:</label>
                    <input type="password" name="db_sifre" placeholder="Localhost için boş kalabilir" class="input-control">
                </div>
                <div>
                    <label style="font-size:0.8rem; color:var(--text-muted);">Sunucu (Host):</label>
                    <input type="text" name="db_host" value="localhost" required class="input-control">
                </div>
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center; margin-top:0.3rem;">
                <span style="font-size:0.75rem; color:var(--text-muted);">
                    🔒 Bilgiler web erişimine kapalı <code>/var/www/env/</code> klasöründe saklanır.
                </span>
                <button type="submit" class="btn btn-success">⚡ Otomatik Bağlantı & DB Oluştur</button>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">🔌 Bağlantı & Tablo Kontrolü</h2>
        </div>
        <form method="post" style="display:flex; flex-direction:column; gap:0.8rem;">
            <input type="hidden" name="islem_tipi" value="veritabani_test_et">
            <label style="font-size:0.8rem; color:var(--text-muted);">Test Edilecek Proje:</label>
            <select name="test_proje" required class="input-control">
                <option value="">-- Proje Seç --</option>
                <?php
                $dizinler = array_filter(glob(DOC_ROOT . '/*'), 'is_dir');
                foreach ($dizinler as $d) {
                    $b = basename($d);
                    if ($b !== 'phpmyadmin' && $b !== 'backups' && $b !== 'includes' && $b !== 'modules') {
                        $env_var_mi = file_exists('/var/www/env/' . $b . '.env.php');
                        $etiket = $env_var_mi ? '🟢 ' . $b . ' (ENV Mevcut)' : '⚪ ' . $b;
                        echo '<option value="' . htmlspecialchars($b) . '">' . htmlspecialchars($etiket) . '</option>';
                    }
                }
                ?>
            </select>
            <button type="submit" class="btn btn-purple" style="margin-top:0.4rem;">🔍 Bağlantıyı Kontrol Et</button>
        </form>
    </div>
</div>
