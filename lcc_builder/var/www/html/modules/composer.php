<div class="card" style="margin-bottom: 2rem;">
    <div class="card-header"><h2 class="card-title">📦 Composer & Vendor Yöneticisi</h2></div>
    <div style="display:flex; flex-direction:column; gap:0.8rem;">
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem;">
            <div>
                <label style="font-size:0.8rem; color:var(--text-muted);">1. Hedef Proje Seçin:</label>
                <select id="composerProjeSelect" class="input-control">
                    <option value="">-- Proje Klasörü --</option>
                    <?php
                    $dizinler = array_filter(glob(DOC_ROOT . '/*'), 'is_dir');
                    foreach ($dizinler as $d) {
                        $b = basename($d);
                        if ($b !== 'phpmyadmin' && $b !== 'backups' && $b !== 'includes' && $b !== 'modules') {
                            echo '<option value="' . htmlspecialchars($d) . '">📁 ' . htmlspecialchars($b) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div>
                <label style="font-size:0.8rem; color:var(--text-muted);">2. Kurulacak Paket:</label>
                <input type="text" id="composerPaketInput" placeholder="phpmailer/phpmailer" class="input-control">
            </div>
        </div>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:0.3rem;">
            <span style="font-size:0.75rem; color:var(--text-muted);">
                Hızlı Ekle: 
                <a href="#" onclick="document.getElementById('composerPaketInput').value='google/apiclient'; return false;" style="color:var(--primary);">Google API</a> | 
                <a href="#" onclick="document.getElementById('composerPaketInput').value='phpmailer/phpmailer'; return false;" style="color:var(--primary);">PHPMailer</a> | 
                <a href="#" onclick="document.getElementById('composerPaketInput').value='guzzlehttp/guzzle'; return false;" style="color:var(--primary);">Guzzle</a>
            </span>
            <button type="button" onclick="canliComposerBaslat()" class="btn btn-success">⚡ Vendor & Paket Kur</button>
        </div>
    </div>
</div>
