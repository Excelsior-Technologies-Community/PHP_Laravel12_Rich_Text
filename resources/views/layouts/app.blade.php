<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Rich Text CMS')</title>

    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trix@2.0.3/dist/trix.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg-body: #f5f7fb;
            --bg-card: #ffffff;
            --bg-card-header: #f7fafc;
            --text-primary: #2d3748;
            --text-secondary: #718096;
            --text-muted: #a0aec0;
            --border-color: #e2e8f0;
            --input-bg: #ffffff;
            --accent: #4299e1;
            --accent-hover: #3182ce;
            --badge-cat-bg: #ebf8ff;
            --badge-cat-text: #3182ce;
            --status-pub-bg: #c6f6d5;
            --status-pub-text: #276749;
            --status-draft-bg: #fed7d7;
            --status-draft-text: #9b2c2c;
            --btn-edit-bg: #edf2f7;
            --btn-edit-text: #4a5568;
            --btn-toggle-bg: #e9d8fd;
            --btn-toggle-text: #6b46c1;
            --btn-delete-bg: #fed7d7;
            --btn-delete-text: #c53030;
            --alert-bg: #c6f6d5;
            --alert-text: #276749;
            --shadow-color: rgba(0,0,0,0.1);
        }

        html.dark {
            --bg-body: #0f1419;
            --bg-card: #1a202c;
            --bg-card-header: #161e2c;
            --text-primary: #e2e8f0;
            --text-secondary: #a0aec0;
            --text-muted: #718096;
            --border-color: #2d3748;
            --input-bg: #2d3748;
            --accent: #63b3ed;
            --accent-hover: #4299e1;
            --badge-cat-bg: #1e3a5f;
            --badge-cat-text: #90cdf4;
            --status-pub-bg: #1c4532;
            --status-pub-text: #9ae6b4;
            --status-draft-bg: #4a1d1d;
            --status-draft-text: #feb2b2;
            --btn-edit-bg: #2d3748;
            --btn-edit-text: #cbd5e0;
            --btn-toggle-bg: #3c2a5e;
            --btn-toggle-text: #d6bcfa;
            --btn-delete-bg: #4a1d1d;
            --btn-delete-text: #feb2b2;
            --alert-bg: #1c4532;
            --alert-text: #9ae6b4;
            --shadow-color: rgba(0,0,0,0.5);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: var(--bg-body);
            color: var(--text-primary);
            transition: background 0.3s, color 0.3s;
        }

        .topnav {
            background: var(--bg-card);
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 30px;
        }

        .topnav-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 16px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topnav h1 {
            font-size: 1.3rem;
            color: var(--text-primary);
        }

        .theme-toggle {
            background: var(--btn-edit-bg);
            color: var(--text-primary);
            border: none;
            width: 42px;
            height: 42px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.2s;
        }

        .theme-toggle:hover {
            background: var(--border-color);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 30px 40px 30px;
        }

        .container.narrow {
            max-width: 900px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            font-size: 2.2rem;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .header p {
            color: var(--text-secondary);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--accent);
            text-decoration: none;
            margin-bottom: 20px;
        }

        .back-link:hover {
            color: var(--accent-hover);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: var(--bg-card);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 1px 3px var(--shadow-color);
            text-align: center;
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-card i {
            font-size: 28px;
            color: var(--accent);
            margin-bottom: 10px;
        }

        .stat-card .number {
            font-size: 32px;
            font-weight: bold;
            color: var(--text-primary);
        }

        .stat-card .label {
            color: var(--text-secondary);
            font-size: 14px;
            margin-top: 5px;
        }

        .editor-card {
            background: var(--bg-card);
            border-radius: 16px;
            box-shadow: 0 1px 3px var(--shadow-color);
            overflow: hidden;
            margin-bottom: 40px;
        }

        .editor-header {
            background: var(--bg-card-header);
            padding: 20px 25px;
            border-bottom: 1px solid var(--border-color);
        }

        .editor-header h2 {
            font-size: 1.3rem;
            color: var(--text-primary);
        }

        .editor-body {
            padding: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-secondary);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            background: var(--input-bg);
            color: var(--text-primary);
            transition: all 0.2s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(66,153,225,0.15);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .template-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .template-label {
            font-size: 13px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .btn-template {
            background: var(--btn-edit-bg);
            color: var(--btn-edit-text);
            border: none;
            padding: 7px 14px;
            border-radius: 20px;
            font-size: 13px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.2s;
        }

        .btn-template:hover {
            background: var(--border-color);
        }

        trix-toolbar .trix-button-row {
            background: var(--bg-card-header);
            border-color: var(--border-color);
        }

        trix-toolbar .trix-button {
            background: var(--bg-card);
            color: var(--text-primary);
            border-color: var(--border-color);
        }

        trix-toolbar .trix-button.trix-active {
            background: var(--accent);
            color: #ffffff;
        }

        trix-editor {
            min-height: 250px;
            background: var(--input-bg);
            color: var(--text-primary);
            border-radius: 8px;
        }

        .word-count {
            margin-top: 8px;
            font-size: 12px;
            color: var(--text-muted);
            text-align: right;
        }

        .field-error {
            color: #e53e3e;
            font-size: 13px;
            margin-top: 8px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .checkbox-group input {
            width: auto;
        }

        .btn-submit, .btn-update {
            background: var(--accent);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-submit:hover, .btn-update:hover {
            background: var(--accent-hover);
        }

        .form-footer {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .autosave-status {
            font-size: 12px;
            color: var(--text-muted);
        }

        .filters-card {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 30px;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
            justify-content: space-between;
        }

        .filters-left {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
        }

        .filter-input {
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            background: var(--input-bg);
            color: var(--text-primary);
        }

        .filter-select {
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--input-bg);
            color: var(--text-primary);
        }

        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }

        .content-card {
            background: var(--bg-card);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 3px var(--shadow-color);
            transition: all 0.2s;
        }

        .content-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px var(--shadow-color);
        }

        .featured-image {
            height: 180px;
            background-size: cover;
            background-position: center;
            background-color: var(--bg-card-header);
        }

        .card-content {
            padding: 20px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
            gap: 8px;
        }

        .badge-group {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-primary);
            text-decoration: none;
        }

        .card-title:hover {
            color: var(--accent);
        }

        .category-badge {
            background: var(--badge-cat-bg);
            color: var(--badge-cat-text);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 500;
        }

        .version-tag {
            background: var(--btn-edit-bg);
            color: var(--btn-edit-text);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 500;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 500;
        }

        .status-published {
            background: var(--status-pub-bg);
            color: var(--status-pub-text);
        }

        .status-draft {
            background: var(--status-draft-bg);
            color: var(--status-draft-text);
        }

        .excerpt {
            color: var(--text-secondary);
            font-size: 14px;
            line-height: 1.5;
            margin: 12px 0;
        }

        .meta-info {
            display: flex;
            gap: 15px;
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 15px;
        }

        .card-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            border-top: 1px solid var(--border-color);
            padding-top: 15px;
        }

        .btn-icon {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .btn-edit {
            background: var(--btn-edit-bg);
            color: var(--btn-edit-text);
        }

        .btn-edit:hover {
            background: var(--border-color);
        }

        .btn-toggle {
            background: var(--btn-toggle-bg);
            color: var(--btn-toggle-text);
        }

        .btn-delete {
            background: var(--btn-delete-bg);
            color: var(--btn-delete-text);
        }

        .btn-versions {
            background: var(--btn-edit-bg);
            color: var(--btn-edit-text);
        }

        .versions-list {
            margin-top: 12px;
            border-top: 1px dashed var(--border-color);
            padding-top: 12px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .version-row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: var(--text-secondary);
            background: var(--bg-card-header);
            padding: 8px 10px;
            border-radius: 8px;
        }

        .version-date {
            color: var(--text-muted);
        }

        .pagination {
            margin-top: 40px;
            display: flex;
            justify-content: center;
        }

        .pagination nav {
            display: flex;
            gap: 8px;
        }

        .alert {
            background: var(--alert-bg);
            color: var(--alert-text);
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 15px 30px 15px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .filters-card {
                flex-direction: column;
                align-items: stretch;
            }

            .filters-left {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

    <nav class="topnav">
        <div class="topnav-inner">
            <h1><i class="fa-solid fa-pen-nib"></i> Rich Text CMS</h1>
            <button type="button" class="theme-toggle" onclick="toggleTheme()">
                <i class="fa-solid fa-moon" id="themeIcon"></i>
            </button>
        </div>
    </nav>

    <div class="container @yield('container_class')">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/trix@2.0.3/dist/trix.umd.min.js"></script>

    <script>
        function applyThemeIcon() {
            const icon = document.getElementById('themeIcon');
            if (!icon) return;
            icon.className = document.documentElement.classList.contains('dark') ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
        }

        function toggleTheme() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
            applyThemeIcon();
        }

        document.addEventListener('DOMContentLoaded', applyThemeIcon);
    </script>

    @stack('scripts')
</body>
</html>