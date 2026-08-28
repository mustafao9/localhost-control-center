    </div>

    <!-- Modals -->
    <div id="editorModal" class="modal">
        <div class="modal-body">
            <h3 id="modalBaslik" style="margin:0; color:var(--primary);">✏️ Hızlı Düzenleyici</h3>
            <form method="post" style="display:flex; flex-direction:column; gap:1rem;">
                <input type="hidden" name="islem_tipi" value="dosya_kaydet">
                <input type="hidden" name="hedef_dizin" id="modalDosyaYolu">
                <textarea name="dosya_icerik" id="modalIcerik" class="editor-textarea"></textarea>
                <div style="display:flex; justify-content:flex-end; gap:0.5rem;">
                    <button type="button" onclick="modalKapat('editorModal')" class="btn btn-secondary">İptal</button>
                    <button type="submit" class="btn btn-success">💾 Kaydet</button>
                </div>
            </form>
        </div>
    </div>

    <div id="terminalModal" class="modal">
        <div class="modal-body">
            <h3 style="margin:0; color:var(--primary); display:flex; justify-content:space-between; align-items:center;">
                <span>⚙️ Composer Canlı Kurulum Terminali</span>
                <span id="terminalStatus" style="font-size:0.8rem; color:var(--warning);">⏳ Kuruluyor...</span>
            </h3>
            <div id="terminalLog" class="terminal-box">🚀 Kurulum başlatılıyor, lütfen bekleyin...\n</div>
            <div style="display:flex; justify-content:flex-end;">
                <button type="button" id="terminalKapatBtn" onclick="temizYenile()" class="btn btn-secondary" disabled>Lütfen Bekleyin...</button>
            </div>
        </div>
    </div>

    <div id="silmeModal" class="modal">
        <div class="modal-body" style="max-width: 500px; text-align: center;">
            <div style="font-size:3rem; margin-bottom:0.5rem;">⚠️</div>
            <h3 style="margin:0; color:var(--danger);">Silme İşlemini Onayla</h3>
            <p style="font-size:0.9rem; color:var(--text-muted); margin-top:0.5rem;">
                <strong id="silinecekAd" style="color:#fff;"></strong> elemanını silmek istediğinizden emin misiniz? Bu işlem geri alınamaz!
            </p>
            <form method="post" style="display:flex; justify-content:center; gap:1rem; margin-top:1rem;">
                <input type="hidden" name="islem_tipi" value="sil">
                <input type="hidden" name="hedef_dizin" id="silinecekYol">
                <button type="button" onclick="modalKapat('silmeModal')" class="btn btn-secondary">Vazgeç</button>
                <button type="submit" class="btn btn-danger">🗑️ Evet, Kesinlikle Sil</button>
            </form>
        </div>
    </div>

    <div id="yedeklerModal" class="modal">
        <div class="modal-body" style="max-width: 750px;">
            <h3 style="margin:0; color:var(--purple); display:flex; justify-content:space-between; align-items:center;">
                <span>📦 Alınan Yedekler Arşivi</span>
                <button onclick="modalKapat('yedeklerModal')" class="btn btn-secondary btn-sm">✕ Kapat</button>
            </h3>
            <div style="max-height: 400px; overflow-y: auto;">
                <table class="table-custom" style="width:100%;">
                    <thead>
                        <tr style="text-align:left; color:var(--text-muted); border-bottom:1px solid var(--border);">
                            <th style="padding:0.5rem;">Yedek Dosyası</th>
                            <th>Boyut</th>
                            <th>Tarih</th>
                            <th style="text-align:right;">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $yedekler = file_exists(BACKUP_DIR) ? glob(BACKUP_DIR . '/*.zip') : [];
                        if (empty($yedekler)):
                        ?>
                            <tr><td colspan="4" style="text-align:center; padding:1.5rem; color:var(--text-muted);">Henüz alınmış bir yedek bulunmuyor.</td></tr>
                        <?php else: 
                            foreach ($yedekler as $y):
                                $y_adi = basename($y);
                                $y_boyut = formatBoyut(filesize($y));
                                $y_tarih = date("d.m.Y H:i", filemtime($y));
                        ?>
                            <tr>
                                <td style="padding:0.6rem; font-weight:600; color:#fff;">📦 <?= htmlspecialchars($y_adi); ?></td>
                                <td><?= $y_boyut; ?></td>
                                <td><?= $y_tarih; ?></td>
                                <td style="text-align:right;">
                                    <form method="post" style="display:inline;" onsubmit="return confirm('⚠️ UYARI: Bu yedeği geri yüklemek mevcut proje dosyalarını yedekteki haliyle değiştirecektir. Devam edilsin mi?');">
                                        <input type="hidden" name="islem_tipi" value="yedek_geri_yukle">
                                        <input type="hidden" name="yedek_dosyasi" value="<?= htmlspecialchars($y); ?>">
                                        <button type="submit" class="btn btn-success btn-sm">🔄 Geri Yükle (Restore)</button>
                                    </form>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="islem_tipi" value="sil">
                                        <input type="hidden" name="hedef_dizin" value="<?= htmlspecialchars($y); ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">🗑️ Sil</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function modalAc(id) { document.getElementById(id).style.display = 'flex'; }
        function modalKapat(id) { document.getElementById(id).style.display = 'none'; }
        function temizYenile() { window.location.href = window.location.pathname; }

        function turDegisti(deger) {
            const uzantiAlani = document.getElementById('uzantiAlani');
            if (uzantiAlani) uzantiAlani.style.display = (deger === 'klasor') ? 'none' : 'block';
        }

        function kodDuzenle(dosyaYolu, dosyaAdi) {
            document.getElementById('modalBaslik').innerText = '✏️ Düzenleniyor: ' + dosyaAdi;
            document.getElementById('modalDosyaYolu').value = dosyaYolu;
            fetch('?ajax_oku=' + encodeURIComponent(dosyaYolu))
                .then(r => r.text())
                .then(veri => {
                    document.getElementById('modalIcerik').value = veri;
                    modalAc('editorModal');
                });
        }

        function silmeOnayModal(tamYol, elemanAdi) {
            document.getElementById('silinecekAd').innerText = elemanAdi;
            document.getElementById('silinecekYol').value = tamYol;
            modalAc('silmeModal');
        }

        function canliComposerBaslat() {
            const proje = document.getElementById('composerProjeSelect').value;
            const paket = document.getElementById('composerPaketInput').value;
            if (!proje || !paket) { alert('Lütfen proje ve paketi seçin!'); return; }

            const termLog = document.getElementById('terminalLog');
            const termStatus = document.getElementById('terminalStatus');
            const termBtn = document.getElementById('terminalKapatBtn');
            
            termLog.innerText = `🚀 [BAŞLATILDI] ${paket} kuruluyor...\n\n`;
            termStatus.innerText = "⏳ Kuruluyor...";
            termStatus.style.color = "var(--warning)";
            termBtn.disabled = true;

            modalAc('terminalModal');
            const eventSource = new EventSource(`?ajax_composer_kur=1&proje=${encodeURIComponent(proje)}&paket=${encodeURIComponent(paket)}`);

            eventSource.onmessage = function(e) {
                const data = JSON.parse(e.data);
                if (data.msg) { termLog.innerText += data.msg; termLog.scrollTop = termLog.scrollHeight; }
                if (data.done) {
                    eventSource.close();
                    termStatus.innerText = "✅ Tamamlandı!";
                    termStatus.style.color = "var(--success)";
                    termBtn.disabled = false;
                    termBtn.innerText = "✅ Kurulum Tamamlandı (Paneli Yenile)";
                    termBtn.className = "btn btn-success";
                }
            };
        }

        function toggleFolder(element) {
            const li = element.closest('li');
            const subTree = li.querySelector(':scope > .tree-list');
            if (subTree) {
                const isBlock = subTree.style.display === 'block';
                subTree.style.display = isBlock ? 'none' : 'block';
                if (isBlock) li.classList.remove('folder-open'); else li.classList.add('folder-open');
            }
        }

        function tumunuAcKapat(acikMi) {
            document.querySelectorAll('.tree-list .tree-list').forEach(tree => {
                tree.style.display = acikMi ? 'block' : 'none';
                const li = tree.closest('li');
                if (acikMi) li.classList.add('folder-open'); else li.classList.remove('folder-open');
            });
        }

        const treeSearch = document.getElementById('treeSearch');
        if (treeSearch) {
            treeSearch.addEventListener('input', function(e) {
                const query = e.target.value.toLowerCase();
                const items = document.querySelectorAll('.tree-list li');
                if (query.trim() === '') { tumunuAcKapat(false); items.forEach(i => i.style.display = ''); return; }
                items.forEach(item => {
                    if (item.textContent.toLowerCase().includes(query)) {
                        item.style.display = '';
                        let parentTree = item.closest('.tree-list');
                        while (parentTree) {
                            parentTree.style.display = 'block';
                            const parentLi = parentTree.closest('li');
                            if (parentLi) parentLi.classList.add('folder-open');
                            parentTree = parentLi ? parentLi.parentElement.closest('.tree-list') : null;
                        }
                    } else { item.style.display = 'none'; }
                });
            });
        }
    </script>
</body>
</html>
