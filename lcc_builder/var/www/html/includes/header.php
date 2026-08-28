<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Localhost Control Center v12.0</title>
    <style>
        :root {
            --bg: #090d16;
            --surface: #111827;
            --surface-card: #1f2937;
            --border: #374151;
            --primary: #38bdf8;
            --primary-hover: #0284c7;
            --success: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;
            --purple: #a855f7;
            --text-main: #f9fafb;
            --text-muted: #9ca3af;
        }
        * { box-sizing: border-box; transition: all 0.15s ease-in-out; }
        body { font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: var(--bg); color: var(--text-main); margin: 0; padding: 2rem; }
        .container { max-width: 1440px; margin: 0 auto; }
        header { background: linear-gradient(135deg, rgba(31,41,55,0.7), rgba(17,24,39,0.9)); backdrop-filter: blur(10px); border: 1px solid var(--border); padding: 1.25rem 2rem; border-radius: 16px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3); }
        .logo-area { display: flex; align-items: center; gap: 0.8rem; }
        .logo-icon { font-size: 1.8rem; }
        h1 { margin: 0; font-size: 1.35rem; font-weight: 700; color: #fff; letter-spacing: -0.5px; }
        .system-badge { background: rgba(15, 23, 42, 0.8); border: 1px solid var(--border); padding: 0.5rem 1rem; border-radius: 10px; font-size: 0.82rem; color: var(--text-muted); }
        .system-badge strong { color: var(--primary); }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; margin-bottom: 2rem; }
        .stat-card { background: var(--surface); border: 1px solid var(--border); border-radius: 14px; padding: 1.2rem; display: flex; align-items: center; gap: 1rem; }
        .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; justify-content: center; align-items: center; font-size: 1.4rem; background: rgba(56, 189, 248, 0.1); color: var(--primary); }
        .stat-val { font-size: 1.25rem; font-weight: 700; color: #fff; }
        .stat-lbl { font-size: 0.78rem; color: var(--text-muted); }
        .alert { padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1.5rem; font-size: 0.88rem; }
        .alert-basari { background: rgba(34, 197, 94, 0.12); border: 1px solid var(--success); color: #4ade80; }
        .alert-bilgi { background: rgba(56, 189, 248, 0.12); border: 1px solid var(--primary); color: #7dd3fc; }
        .alert-hata { background: rgba(239, 68, 68, 0.12); border: 1px solid var(--danger); color: #fca5a5; }
        .main-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .card { background: var(--surface); border: 1px solid var(--border); border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .card-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border); padding-bottom: 0.8rem; margin-bottom: 1.2rem; }
        .card-title { margin: 0; font-size: 1.05rem; font-weight: 600; color: var(--primary); display: flex; align-items: center; gap: 0.5rem; }
        .input-control { width: 100%; background: rgba(15, 23, 42, 0.8); border: 1px solid var(--border); color: #fff; padding: 0.65rem 0.85rem; border-radius: 8px; font-size: 0.85rem; outline: none; }
        .input-control:focus { border-color: var(--primary); box-shadow: 0 0 0 2px rgba(56, 189, 248, 0.2); }
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 0.4rem; background: var(--primary); color: #000; padding: 0.55rem 1rem; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.82rem; border: none; cursor: pointer; }
        .btn:hover { background: var(--primary-hover); color: #fff; transform: translateY(-1px); }
        .btn-success { background: var(--success); color: #000; }
        .btn-success:hover { background: #16a34a; color: #fff; }
        .btn-danger { background: var(--danger); color: #fff; }
        .btn-danger:hover { background: #dc2626; }
        .btn-secondary { background: #374151; color: var(--text-main); }
        .btn-secondary:hover { background: #4b5563; }
        .btn-purple { background: var(--purple); color: #fff; }
        .btn-purple:hover { background: #9333ea; }
        .btn-sm { padding: 0.3rem 0.6rem; font-size: 0.75rem; border-radius: 6px; }
        .table-custom { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
        .table-custom td { padding: 0.5rem 0; border-bottom: 1px solid rgba(55, 65, 81, 0.5); }
        .table-custom td:last-child { text-align: right; font-weight: 600; }
        .badge { padding: 0.2rem 0.5rem; border-radius: 6px; font-size: 0.72rem; font-weight: 600; }
        .badge-success { background: rgba(34, 197, 94, 0.15); color: var(--success); border: 1px solid var(--success); }
        .badge-danger { background: rgba(239, 68, 68, 0.15); color: var(--danger); border: 1px solid var(--danger); }
        .tree-list { list-style: none; padding-left: 0; margin: 0; }
        .tree-list .tree-list { padding-left: 1.2rem; border-left: 2px dashed var(--border); margin: 0.4rem 0; display: none; }
        .tree-item { display: flex; align-items: center; justify-content: space-between; background: rgba(17, 24, 39, 0.8); padding: 0.6rem 0.9rem; border-radius: 10px; border: 1px solid var(--border); margin-bottom: 0.45rem; }
        .tree-item:hover { border-color: var(--primary); background: rgba(30, 41, 59, 0.6); }
        .folder-toggle { cursor: pointer; user-select: none; display: inline-flex; align-items: center; gap: 0.5rem; }
        .toggle-icon { font-size: 0.75rem; color: var(--primary); display: inline-block; width: 14px; transition: transform 0.2s ease; }
        .folder-open > .tree-item .toggle-icon { transform: rotate(90deg); }
        .tree-link { color: var(--text-main); text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 0.4rem; }
        .tree-link:hover { color: var(--primary); }
        .progress-bar { background: #0f172a; border-radius: 6px; height: 8px; width: 100%; overflow: hidden; margin-top: 0.5rem; border: 1px solid var(--border); }
        .progress-fill { background: linear-gradient(90deg, var(--primary), var(--success)); height: 100%; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); backdrop-filter: blur(5px); z-index: 1000; justify-content: center; align-items: center; }
        .modal-body { background: var(--surface); border: 1px solid var(--border); width: 85%; max-width: 900px; border-radius: 16px; padding: 1.5rem; display: flex; flex-direction: column; gap: 1rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5); }
        .editor-textarea { width: 100%; height: 420px; background: #090d16; color: var(--primary); border: 1px solid var(--border); font-family: 'Fira Code', monospace; padding: 1rem; border-radius: 10px; resize: vertical; outline: none; font-size: 0.9rem; }
        .terminal-box { width: 100%; height: 350px; background: #000; color: #22c55e; border: 1px solid var(--border); font-family: monospace; padding: 1rem; border-radius: 10px; overflow-y: auto; font-size: 0.85rem; line-height: 1.4; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="logo-area">
                <span class="logo-icon">🎛️</span>
                <div>
                    <h1>Localhost Control Center</h1>
                    <div style="font-size:0.75rem; color:var(--text-muted);">
                        Geliştirici: <a href="<?= DEVELOPER_GITHUB; ?>" target="_blank" style="color:var(--primary); text-decoration:none; font-weight:600;"><?= DEVELOPER_NAME; ?></a> | Open Source Linux Project
                    </div>
                </div>
            </div>
            <div class="system-badge">
                Server: <strong><?= $_SERVER['SERVER_SOFTWARE'] ?? 'Apache2'; ?></strong> | PHP: <strong><?= phpversion(); ?></strong>
            </div>
        </header>
