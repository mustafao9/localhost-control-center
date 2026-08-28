<?php $projeler = dizinAgaciniGetir(DOC_ROOT); ?>
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
    <h2 style="margin:0; font-size:1.2rem; color:var(--primary);">📂 Proje Yöneticisi (`/var/www/html/`)</h2>
    <div style="display:flex; gap:0.5rem; align-items:center;">
        <button onclick="tumunuAcKapat(true)" class="btn btn-secondary btn-sm">▶ Tümünü Genişlet</button>
        <button onclick="tumunuAcKapat(false)" class="btn btn-secondary btn-sm">◀ Tümünü Daralt</button>
        <input type="text" id="treeSearch" placeholder="🔍 Proje veya dosya ara..." class="input-control" style="width:220px; padding:0.4rem 0.7rem;">
    </div>
</div>

<div class="card">
    <?php if (empty($projeler)): ?>
        <p style="color: var(--text-muted); text-align: center;">Henüz proje bulunmuyor.</p>
    <?php else: ?>
        <?php
        function agacYapisiniCiz($elemanlar) {
            echo '<ul class="tree-list">';
            foreach ($elemanlar as $item) {
                $hasChildren = ($item['klasor_mu'] && !empty($item['cocuklar']));
                echo '<li class="' . ($hasChildren ? 'has-children' : '') . '">';
                echo '<div class="tree-item">';
                echo '<div style="display:flex; align-items:center; gap:0.6rem; flex-wrap:wrap;">';
                if ($item['klasor_mu']) {
                    echo '<div class="folder-toggle" onclick="toggleFolder(this)">';
                    echo '<span class="toggle-icon">' . ($hasChildren ? '▶' : '•') . '</span>';
                    echo '📁 <strong>' . htmlspecialchars($item['ad']) . '</strong>';
                    echo '</div>';
                    echo '<a href="/' . htmlspecialchars($item['goreceli_yol']) . '" target="_blank" class="btn btn-sm btn-secondary" style="padding:0.15rem 0.4rem;">🚀 Aç ↗</a>';
                } else {
                    echo '<span style="width:14px; display:inline-block;"></span>';
                    echo '<a href="/' . htmlspecialchars($item['goreceli_yol']) . '" target="_blank" class="tree-link" style="color: var(--text-muted);">📄 ' . htmlspecialchars($item['ad']) . '</a>';
                }
                echo $item['yazilabilir'] ? '<span class="badge badge-success">777</span>' : '<span class="badge badge-danger">Kilitli</span>';
                echo '<span style="font-size: 0.75rem; color: var(--text-muted);">' . $item['boyut'] . ' | ' . $item['son_degisme'] . '</span>';
                echo '</div>';
                
                echo '<div style="display:flex; gap:0.3rem; flex-wrap:wrap;">';
                if (!$item['klasor_mu']) {
                    echo '<button onclick="kodDuzenle(\'' . addslashes(htmlspecialchars($item['tam_yol'])) . '\', \'' . addslashes(htmlspecialchars($item['ad'])) . '\')" class="btn btn-purple btn-sm">✏️ Düzenle</button>';
                } else {
                    echo '<form method="post" style="margin:0;"><input type="hidden" name="hedef_dizin" value="' . htmlspecialchars($item['tam_yol']) . '"><input type="hidden" name="islem_tipi" value="yedek_al"><button type="submit" class="btn btn-secondary btn-sm">📦 ZIP Al</button></form>';
                }
                echo '<form method="post" style="margin:0;"><input type="hidden" name="hedef_dizin" value="' . htmlspecialchars($item['tam_yol']) . '"><input type="hidden" name="islem_tipi" value="unlock"><button type="submit" class="btn btn-success btn-sm">🔑 777</button></form>';
                echo '<button type="button" onclick="silmeOnayModal(\'' . addslashes(htmlspecialchars($item['tam_yol'])) . '\', \'' . addslashes(htmlspecialchars($item['ad'])) . '\')" class="btn btn-danger btn-sm">🗑️ Sil</button>';
                echo '</div></div>';
                
                if ($hasChildren) agacYapisiniCiz($item['cocuklar']);
                echo '</li>';
            }
            echo '</ul>';
        }
        agacYapisiniCiz($projeler);
        ?>
    <?php endif; ?>
</div>
