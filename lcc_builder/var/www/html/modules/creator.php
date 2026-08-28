<?php $tum_klasorler = tumAltKlasorleriGetir(DOC_ROOT); ?>
<div class="main-grid">
    <div class="card">
        <div class="card-header"><h2 class="card-title">🚀 Proje Sihirbazı</h2></div>
        <form method="post" style="display:flex; flex-direction:column; gap:0.8rem;">
            <input type="hidden" name="islem_tipi" value="yeni_proje_olustur">
            <label style="font-size:0.8rem; color:var(--text-muted);">Yeni Proje İsmi:</label>
            <input type="text" name="proje_adi" placeholder="Örn: e-ticaret-v1" required class="input-control">
            <button type="submit" class="btn btn-success" style="padding:0.65rem;">✨ Projeyi Oluştur</button>
        </form>
    </div>

    <div class="card" style="grid-column: span 2;">
        <div class="card-header"><h2 class="card-title">➕ Projeye Özel Dosya / Klasör Ekle</h2></div>
        <form method="post" style="display:flex; flex-direction:column; gap:0.8rem;">
            <input type="hidden" name="islem_tipi" value="ozel_eleman_olustur">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1rem;">
                <div>
                    <label style="font-size:0.8rem; color:var(--text-muted);">1. Hedef Proje / Klasör:</label>
                    <select name="hedef_klasor_yolu" required class="input-control">
                        <option value="<?= DOC_ROOT; ?>">📁 / (Kök Dizin)</option>
                        <?php foreach ($tum_klasorler as $k): ?>
                            <option value="<?= htmlspecialchars($k['tam_yol']); ?>">📁 <?= htmlspecialchars($k['goreceli']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label style="font-size:0.8rem; color:var(--text-muted);">2. Oluşturulacak Tür:</label>
                    <select name="olusturma_turu" onchange="turDegisti(this.value)" class="input-control">
                        <option value="dosya">📄 Sayfa / Dosya</option>
                        <option value="klasor">📁 Klasör</option>
                    </select>
                </div>
            </div>
            <div style="display:grid; grid-template-columns: 2fr 1fr; gap:1rem;">
                <div>
                    <label style="font-size:0.8rem; color:var(--text-muted);">3. İsmi:</label>
                    <input type="text" name="eleman_ismi" placeholder="Örn: iletisim veya config" required class="input-control">
                </div>
                <div id="uzantiAlani">
                    <label style="font-size:0.8rem; color:var(--text-muted);">4. Türü / Uzantısı:</label>
                    <select name="dosya_uzantisi" class="input-control">
                        <option value=".php">.php (PHP Betiği)</option>
                        <option value=".html">.html (HTML5 Sayfası)</option>
                        <option value=".js">.js (JavaScript)</option>
                        <option value=".css">.css (Style Sheet)</option>
                        <option value=".json">.json (Veri/Config)</option>
                        <option value=".htaccess">.htaccess (Apache Konfig)</option>
                        <option value=".sql">.sql (Veritabanı Yedeği)</option>
                        <option value=".txt">.txt (Metin Dosyası)</option>
                    </select>
                </div>
            </div>
            <div style="display:flex; justify-content:flex-end; margin-top:0.3rem;">
                <button type="submit" class="btn btn-success">➕ Eklemeyi Gerçekleştir</button>
            </div>
        </form>
    </div>
</div>
