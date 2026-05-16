<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'TmcsSmart - Church Management System')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,400&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  @stack('styles')
</head>
<body>
  @csrf
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    /* DESIGN TOKENS */
    :root {
      --green-950: #021c13;
      --green-900: #042f1e;
      --green-800: #064e3b;
      --green-700: #065f46;
      --green-600: #047857;
      --green-500: #059669;
      --green-400: #10b981;
      --green-300: #34d399;
      --green-100: #d1fae5;
      --green-50:  #ecfdf5;

      --gold-500: #d97706;
      --gold-400: #f59e0b;
      --gold-300: #fbbf24;
      --gold-100: #fef3c7;

      --blue-600: #059669;
      --red-500:  #ef4444;

      /* Light mode */
      --bg-base:      #f0f9f4;
      --bg-card:      #ffffff;
      --bg-sidebar:   #042f1e;
      --bg-sidebar2:  #064e3b;
      --text-primary: #0a1a12;
      --text-secondary:#3d6b54;
      --text-muted:   #6b9e82;
      --border:       #c6e8d7;
      --border-light: #e6f7ef;
      --shadow:       0 4px 24px rgba(4,46,30,0.10);
      --shadow-lg:    0 8px 40px rgba(4,46,30,0.15);
      --input-bg:     #f4fbf7;
      --hover-row:    #f0fdf4;
      --badge-green-bg: #d1fae5;
      --badge-green-tx: #047857;
      --badge-amber-bg: #fef3c7;
      --badge-amber-tx: #92400e;
      --badge-red-bg:   #fee2e2;
      --badge-red-tx:   #b91c1c;
      --badge-blue-bg:  #d1fae5;
      --badge-blue-tx:  #047857;
    }
    [data-theme="dark"] {
      --bg-base:      #031a10;
      --bg-card:      #052819;
      --bg-sidebar:   #021c13;
      --bg-sidebar2:  #042f1e;
      --text-primary: #e2f5eb;
      --text-secondary:#7ecfa0;
      --text-muted:   #4d8a65;
      --border:       #0e4a2e;
      --border-light: #0b3d26;
      --shadow:       0 4px 24px rgba(0,0,0,0.40);
      --shadow-lg:    0 8px 40px rgba(0,0,0,0.60);
      --input-bg:     #042014;
      --hover-row:    #063320;
      --badge-green-bg: #064e3b;
      --badge-green-tx: #34d399;
      --badge-amber-bg: #451a03;
      --badge-amber-tx: #fbbf24;
      --badge-red-bg:   #450a0a;
      --badge-red-tx:   #f87171;
      --badge-blue-bg:  #1e3a8a;
      --badge-blue-tx:  #93c5fd;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--bg-base);
      color: var(--text-primary);
      min-height: 100vh;
      transition: background .3s, color .3s;
    }
    h1,h2,h3,h4,h5 { font-family: 'Sora', sans-serif; }
    code, .mono { font-family: 'JetBrains Mono', monospace; }

    /* SCROLLBAR */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: var(--bg-base); }
    ::-webkit-scrollbar-thumb { background: var(--green-600); border-radius: 99px; }

    /* LAYOUT */
    .app-shell { display: flex; height: 100vh; overflow: hidden; }

    /* SIDEBAR */
    .sidebar {
      width: 260px;
      background: var(--bg-sidebar);
      display: flex;
      flex-direction: column;
      flex-shrink: 0;
      height: 100vh;
      overflow-y: auto;
      transition: width .3s, transform .3s;
      position: relative;
      z-index: 50;
    }
    .sidebar.collapsed { width: 72px; }
    .sidebar.collapsed .nav-label,
    .sidebar.collapsed .sidebar-brand-text,
    .sidebar.collapsed .nav-section-label,
    .sidebar.collapsed .sidebar-footer-text { display: none; }
    .sidebar.collapsed .sidebar-brand { justify-content: center; }
    .sidebar.collapsed .nav-item { justify-content: center; padding: 12px; }
    .sidebar.collapsed .nav-item .nav-icon { margin: 0; }

    .sidebar-brand {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 20px 18px;
      border-bottom: 1px solid rgba(255,255,255,.07);
    }
    .brand-logo {
      width: 40px; height: 40px;
      background: linear-gradient(135deg, var(--green-500), var(--green-300));
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-family: 'Sora', sans-serif;
      font-weight: 800; font-size: 18px; color: #fff;
      flex-shrink: 0;
    }
    .sidebar-brand-text h2 { font-size: 15px; font-weight: 700; color: #fff; line-height: 1.2; }
    .sidebar-brand-text p { font-size: 10px; color: var(--green-300); letter-spacing: .04em; text-transform: uppercase; }

    .nav-section-label {
      font-size: 9px;
      font-weight: 700;
      letter-spacing: .1em;
      text-transform: uppercase;
      color: var(--green-400);
      padding: 18px 18px 6px;
    }
    .nav-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 18px;
      cursor: pointer;
      border-radius: 8px;
      margin: 1px 8px;
      color: #9dc9b2;
      font-size: 13.5px;
      font-weight: 500;
      transition: all .15s;
      position: relative;
      width: calc(100% - 16px);
      text-align: left;
      border: none;
      background: none;
    }
    .nav-item:hover { background: rgba(255,255,255,.06); color: #fff; }
    .nav-item.active {
      background: linear-gradient(90deg, var(--green-600), var(--green-700));
      color: #fff;
      box-shadow: 0 2px 12px rgba(5,150,105,.35);
    }
    .nav-item.active::before {
      content: '';
      position: absolute;
      left: -8px;
      top: 50%; transform: translateY(-50%);
      width: 3px; height: 60%;
      background: var(--green-300);
      border-radius: 99px;
    }
    .nav-dropdown {
      display: none;
      flex-direction: column;
      padding-left: 36px;
      margin-bottom: 4px;
    }
    .nav-dropdown.show { display: flex; }
    .dropdown-item {
      padding: 8px 12px;
      color: #9dc9b2;
      font-size: 12.5px;
      border-radius: 6px;
      margin: 1px 8px 1px 0;
      transition: all .15s;
    }
    .dropdown-item:hover { background: rgba(255,255,255,.04); color: #fff; }
    .dropdown-item.active { color: var(--green-300); font-weight: 600; }
    .dropdown-arrow {
      margin-left: auto;
      transition: transform .2s;
    }
    .nav-item.open .dropdown-arrow { transform: rotate(180deg); }
    .nav-icon { width: 18px; height: 18px; flex-shrink: 0; }
    .nav-badge {
      margin-left: auto;
      background: var(--gold-400);
      color: #000;
      font-size: 10px;
      font-weight: 700;
      padding: 1px 7px;
      border-radius: 99px;
    }

    .sidebar-footer {
      margin-top: auto;
      padding: 14px;
      border-top: 1px solid rgba(255,255,255,.07);
    }
    .user-pill {
      display: flex; align-items: center; gap: 10px;
      padding: 10px;
      background: rgba(255,255,255,.05);
      border-radius: 10px;
      cursor: pointer;
    }
    .user-avatar {
      width: 34px; height: 34px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--green-500), var(--green-300));
      display: flex; align-items: center; justify-content: center;
      font-weight: 700; font-size: 13px; color: #fff;
      flex-shrink: 0;
    }
    .sidebar-footer-text { font-size: 12px; }
    .sidebar-footer-text .name { color: #fff; font-weight: 600; }
    .sidebar-footer-text .role { color: var(--green-400); font-size: 10px; }

    /* MAIN CONTENT */
    .main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }

    /* TOPBAR */
    .topbar {
      display: flex; align-items: center; gap: 12px;
      padding: 14px 24px;
      background: var(--bg-card);
      border-bottom: 1px solid var(--border);
      box-shadow: var(--shadow);
      z-index: 40;
    }
    .topbar-toggle {
      width: 36px; height: 36px;
      display: flex; align-items: center; justify-content: center;
      background: var(--bg-base);
      border: 1px solid var(--border);
      border-radius: 8px;
      cursor: pointer;
      color: var(--text-secondary);
      flex-shrink: 0;
    }
    .page-title { font-size: 18px; font-weight: 700; color: var(--text-primary); }
    .breadcrumb { font-size: 12px; color: var(--text-muted); margin-top: 1px; }

    .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 10px; }
    .search-bar {
      display: flex; align-items: center;
      background: var(--input-bg);
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 8px 14px; gap: 8px;
      font-size: 13px; color: var(--text-muted);
    }
    .search-bar input {
      background: transparent; border: none; outline: none;
      color: var(--text-primary); font-size: 13px; width: 200px;
      font-family: 'DM Sans', sans-serif;
    }
    .icon-btn {
      width: 36px; height: 36px;
      display: flex; align-items: center; justify-content: center;
      background: var(--bg-base);
      border: 1px solid var(--border);
      border-radius: 8px;
      cursor: pointer;
      color: var(--text-secondary);
      position: relative;
      transition: all .15s;
    }
    .icon-btn:hover { background: var(--green-50); color: var(--green-700); }
    .notif-dot {
      position: absolute; top: 6px; right: 6px;
      width: 8px; height: 8px;
      background: var(--red-500);
      border-radius: 50%;
      border: 2px solid var(--bg-card);
    }
    .theme-toggle {
      background: var(--bg-base);
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 7px 12px;
      cursor: pointer;
      font-size: 12px;
      font-weight: 600;
      color: var(--text-secondary);
      display: flex; align-items: center; gap: 6px;
      transition: all .15s;
    }
    .theme-toggle:hover { color: var(--green-600); }

    /* TOPBAR DROPDOWNS */
    .topbar-dropdown {
      position: relative;
    }
    .dropdown-menu {
      position: absolute;
      top: 100%;
      right: 0;
      margin-top: 8px;
      width: 220px;
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 12px;
      box-shadow: var(--shadow-lg);
      padding: 8px;
      display: none;
      z-index: 100;
    }
    .dropdown-menu.show {
      display: block;
      animation: fadeIn .2s ease;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .dropdown-menu-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 12px;
      color: var(--text-primary);
      font-size: 13px;
      font-weight: 500;
      border-radius: 8px;
      transition: all .15s;
      cursor: pointer;
      width: 100%;
      text-align: left;
    }
    .dropdown-menu-item:hover {
      background: var(--hover-row);
      color: var(--green-600);
    }
    .dropdown-divider {
      height: 1px;
      background: var(--border-light);
      margin: 6px 0;
    }

    /* CONTENT */
    .content { flex: 1; overflow-y: auto; padding: 24px; }

    /* CARDS */
    .card {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 16px;
      box-shadow: var(--shadow);
      transition: box-shadow .2s;
    }
    .card:hover { box-shadow: var(--shadow-lg); }
    .card-body { padding: 20px; }
    .card-header { padding: 18px 20px; border-bottom: 1px solid var(--border-light); }
    .card-title { font-size: 15px; font-weight: 700; color: var(--text-primary); }
    .card-subtitle { font-size: 12px; color: var(--text-muted); margin-top: 2px; }

    /* STAT CARDS */
    .stat-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; }
    .grid-3 { grid-template-columns: repeat(3, 1fr); gap: 20px; }
    @media(max-width:1100px){ .stat-grid { grid-template-columns: repeat(2,1fr); } }
    @media(max-width:600px){ .stat-grid { grid-template-columns: 1fr; } }

    .stat-card {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 20px;
      box-shadow: var(--shadow);
      position: relative;
      overflow: hidden;
    }
    .stat-card::before {
      content: '';
      position: absolute;
      top: -30px; right: -30px;
      width: 100px; height: 100px;
      border-radius: 50%;
      opacity: .08;
    }
    .stat-card.green::before  { background: var(--green-500); }
    .stat-card.gold::before   { background: var(--gold-400); }
    .stat-card.blue::before   { background: var(--blue-600); }
    .stat-card.red::before    { background: var(--red-500); }

    .stat-icon {
      width: 42px; height: 42px;
      border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      margin-bottom: 12px;
    }
    .stat-icon.green { background: var(--badge-green-bg); color: var(--green-600); }
    .stat-icon.gold  { background: var(--badge-amber-bg); color: var(--gold-500); }
    .stat-icon.blue  { background: var(--badge-blue-bg); color: var(--blue-600); }
    .stat-icon.red   { background: var(--badge-red-bg); color: var(--red-500); }

    .stat-value { font-size: 26px; font-weight: 800; color: var(--text-primary); font-family: 'Sora', sans-serif; line-height: 1; }
    .stat-label { font-size: 12px; color: var(--text-muted); margin-top: 4px; }
    .stat-change { font-size: 11px; font-weight: 600; margin-top: 8px; display: flex; align-items: center; gap: 4px; }
    .stat-change.up { color: var(--green-500); }
    .stat-change.down { color: var(--red-500); }

    /* TABLES */
    .table-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; font-size: 13px; }
    thead th {
      background: var(--bg-base);
      color: var(--text-secondary);
      font-weight: 600; font-size: 11px;
      text-transform: uppercase; letter-spacing: .06em;
      padding: 10px 14px;
      text-align: left;
      border-bottom: 1px solid var(--border);
      white-space: nowrap;
    }
    tbody tr { border-bottom: 1px solid var(--border-light); transition: background .12s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: var(--hover-row); }
    tbody td { padding: 11px 14px; color: var(--text-primary); }

    /* BADGES */
    .badge {
      display: inline-flex; align-items: center; gap: 4px;
      padding: 3px 10px;
      border-radius: 99px;
      font-size: 11px; font-weight: 600;
    }
    .badge-dot { width: 6px; height: 6px; border-radius: 50%; }
    .badge.green { background: var(--badge-green-bg); color: var(--badge-green-tx); }
    .badge.amber { background: var(--badge-amber-bg); color: var(--badge-amber-tx); }
    .badge.red   { background: var(--badge-red-bg);   color: var(--badge-red-tx); }
    .badge.blue  { background: var(--badge-blue-bg);  color: var(--badge-blue-tx); }

    /* BUTTONS */
    .btn {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 9px 18px;
      border-radius: 10px;
      font-size: 13px; font-weight: 600;
      cursor: pointer; border: none;
      font-family: 'DM Sans', sans-serif;
      transition: all .15s;
    }
    .btn-primary {
      background: linear-gradient(135deg, var(--green-600), var(--green-700));
      color: #fff;
      box-shadow: 0 2px 12px rgba(5,150,105,.25);
    }
    .btn-primary:hover { background: linear-gradient(135deg, var(--green-500), var(--green-600)); transform: translateY(-1px); box-shadow: 0 4px 16px rgba(5,150,105,.35); }
    .btn-secondary {
      background: var(--bg-base); border: 1px solid var(--border);
      color: var(--text-secondary);
    }
    .btn-secondary:hover { border-color: var(--green-500); color: var(--green-600); }
    .btn-ghost { background: transparent; color: var(--text-secondary); padding: 8px 12px; }
    .btn-ghost:hover { background: var(--hover-row); color: var(--green-600); }
    .btn-sm { padding: 6px 12px; font-size: 12px; border-radius: 8px; }
    .btn-gold {
      background: linear-gradient(135deg, var(--gold-500), var(--gold-400));
      color: #fff;
      box-shadow: 0 2px 12px rgba(217,119,6,.25);
    }
    .btn-gold:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(217,119,6,.35); }

    /* ENHANCED DASHBOARD STYLES */
    .welcome-header {
      background: linear-gradient(135deg, var(--green-600), var(--green-500));
      border-radius: 20px;
      padding: 32px;
      color: white;
      margin-bottom: 24px;
    }
    .welcome-content {
      display: flex; justify-content: space-between; align-items: center;
    }
    .welcome-title { font-size: 28px; font-weight: 700; margin-bottom: 8px; }
    .welcome-subtitle { opacity: 0.9; font-size: 16px; }
    .welcome-actions { text-align: right; }
    .date-time { font-size: 14px; opacity: 0.8; margin-bottom: 12px; }
    .weather-widget { display: flex; align-items: center; gap: 12px; }
    .weather-icon { opacity: 0.7; }
    .temperature { font-size: 20px; font-weight: 600; }
    .condition { font-size: 12px; opacity: 0.8; }

    .metrics-overview { margin-bottom: 24px; }
    .metrics-header {
      display: flex; justify-content: space-between; align-items: center;
      margin-bottom: 20px;
    }
    .metrics-title { font-size: 20px; font-weight: 600; color: var(--text-primary); }
    .period-selector {
      background: var(--bg-base);
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 8px 12px;
      font-size: 13px;
      color: var(--text-primary);
    }

    .stat-card.premium {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 24px;
      position: relative;
      overflow: hidden;
      transition: all 0.3s ease;
    }
    .stat-card.premium:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    }
    .stat-header {
      display: flex; justify-content: space-between; align-items: center;
      margin-bottom: 16px;
    }
    .stat-trend { display: flex; align-items: center; gap: 4px; }
    .trend-indicator {
      display: flex; align-items: center; gap: 4px;
      padding: 4px 8px; border-radius: 8px;
      font-size: 12px; font-weight: 600;
    }
    .trend-indicator.positive {
      background: rgba(5,150,105,0.1);
      color: var(--green-600);
    }
    .stat-content { margin-bottom: 16px; }
    .stat-subtitle { font-size: 12px; color: var(--text-muted); margin-top: 4px; }
    .stat-footer { margin-top: 16px; }
    .progress-bar {
      height: 6px; background: var(--bg-base); border-radius: 3px; overflow: hidden;
    }
    .progress-fill {
      height: 100%; background: linear-gradient(90deg, var(--green-500), var(--green-400));
      border-radius: 3px; transition: width 0.3s ease;
    }
    .progress-text { font-size: 11px; color: var(--text-muted); margin-top: 8px; }

    .quick-actions-panel { margin-bottom: 24px; }
    .panel-header { margin-bottom: 20px; }
    .panel-title { font-size: 20px; font-weight: 600; color: var(--text-primary); }
    .panel-subtitle { color: var(--text-muted); margin-top: 4px; }
    .actions-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; }
    .action-card {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 20px;
      display: flex; align-items: center; gap: 16px;
      cursor: pointer; transition: all 0.3s ease;
    }
    .action-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(0,0,0,0.1);
      border-color: var(--green-500);
    }
    .action-icon {
      width: 48px; height: 48px;
      border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
    }
    .action-content { flex: 1; }
    .action-title { font-weight: 600; margin-bottom: 4px; }
    .action-subtitle { font-size: 12px; color: var(--text-muted); }
    .action-arrow { opacity: 0.5; }

    .analytics-dashboard { margin-bottom: 24px; }
    .analytics-header {
      display: flex; justify-content: space-between; align-items: center;
      margin-bottom: 20px;
    }
    .analytics-title { font-size: 20px; font-weight: 600; color: var(--text-primary); }
    .time-range-selector { display: flex; gap: 8px; }
    .range-btn {
      padding: 8px 16px; border: 1px solid var(--border);
      background: var(--bg-base); border-radius: 8px;
      font-size: 12px; cursor: pointer; transition: all 0.2s ease;
    }
    .range-btn.active {
      background: var(--green-500); color: white; border-color: var(--green-500);
    }
    .analytics-card {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 20px;
    }
    .analytics-header-small {
      display: flex; justify-content: space-between; align-items: center;
      margin-bottom: 16px;
    }
    .analytics-title-small { font-weight: 600; color: var(--text-primary); }
    .analytics-trend { display: flex; align-items: center; gap: 4px; color: var(--green-600); }
    .chart-container { position: relative; height: 120px; margin-bottom: 16px; }
    .analytics-footer { padding-top: 16px; border-top: 1px solid var(--border); }
    .stat-summary { display: flex; justify-content: space-between; font-size: 12px; }
    .summary-label { color: var(--text-muted); }
    .summary-value { font-weight: 600; color: var(--text-primary); }

    .activity-section { margin-bottom: 24px; }
    .activity-feed-card, .upcoming-events-card {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      transition: all 0.3s ease;
    }
    .activity-feed-card:hover, .upcoming-events-card:hover {
      box-shadow: 0 4px 16px rgba(0,0,0,0.1);
      transform: translateY(-2px);
    }
    .feed-refresh {
      background: var(--bg-base);
      border: 1px solid var(--border);
      border-radius: 8px; padding: 8px;
      cursor: pointer; transition: all 0.2s ease;
    }
    .feed-refresh:hover { background: var(--hover-row); }
    .activity-feed { max-height: 400px; overflow-y: auto; }
    .activity-item {
      display: flex; gap: 12px; padding: 16px; border-bottom: 1px solid var(--border);
      transition: background 0.2s ease;
    }
    .activity-item:hover { background: var(--hover-row); }
    .activity-icon {
      width: 40px; height: 40px; border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .activity-content { flex: 1; }
    .activity-title { font-weight: 600; margin-bottom: 4px; }
    .activity-meta { font-size: 12px; color: var(--text-muted); margin-bottom: 4px; }
    .activity-description { font-size: 13px; color: var(--text-secondary); }

    .events-list { max-height: 400px; overflow-y: auto; }
    .event-item {
      display: flex; gap: 16px; padding: 16px; border-bottom: 1px solid var(--border);
      transition: background 0.2s ease;
    }
    .event-item:hover { background: var(--hover-row); }
    .event-date { text-align: center; flex-shrink: 0; }
    .event-day { font-size: 10px; font-weight: 600; color: var(--text-muted); }
    .event-date-num { font-size: 18px; font-weight: 700; color: var(--text-primary); }
    .event-item.today .event-date-num { color: var(--green-600); }
    .event-item.tomorrow .event-date-num { color: var(--amber-600); }
    .event-details { flex: 1; }
    .event-title { font-weight: 600; margin-bottom: 4px; }
    .event-time, .event-location, .event-attendees {
      font-size: 12px; color: var(--text-muted); margin-bottom: 2px;
    }
    .event-action { align-self: center; }

    @media(max-width:768px) {
      .welcome-content { flex-direction: column; gap: 20px; text-align: center; }
      .welcome-actions { text-align: center; }
      .metrics-header { flex-direction: column; gap: 12px; }
      .analytics-header { flex-direction: column; gap: 12px; }
      .actions-grid { grid-template-columns: 1fr; }
      .grid-3 { grid-template-columns: repeat(3, 1fr); }
    }

    /* FORMS */
    .form-group { margin-bottom: 16px; }
    .form-label { display: block; font-size: 12px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px; }
    .form-control {
      width: 100%;
      background: var(--input-bg);
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 10px 14px;
      font-size: 13px;
      color: var(--text-primary);
      font-family: 'DM Sans', sans-serif;
      outline: none;
      transition: border .15s, box-shadow .15s;
    }
    .form-control:focus { border-color: var(--green-500); box-shadow: 0 0 0 3px rgba(5,150,105,.12); }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    @media(max-width:640px){ .form-row { grid-template-columns: 1fr; } }
    
    /* FILTER ROW LAYOUT */
    .filter-row {
      display: flex;
      gap: 16px;
      align-items: center;
      flex-wrap: wrap;
    }
    .filter-row .form-control {
      width: auto;
      min-width: 200px;
    }
    .filter-row .search-input {
      flex: 1;
      min-width: 300px;
    }
    .filter-row .filter-select {
      width: 200px;
    }
    @media(max-width:768px) {
      .filter-row {
        flex-direction: column;
        align-items: stretch;
      }
      .filter-row .form-control {
        width: 100%;
      }
    }

    /* MODAL */
    .modal-overlay {
      position: fixed; inset: 0;
      background: rgba(0,0,0,.55);
      backdrop-filter: blur(4px);
      z-index: 200;
      display: flex; align-items: center; justify-content: center;
      opacity: 0; pointer-events: none;
      transition: opacity .2s;
    }
    .modal-overlay.open { opacity: 1; pointer-events: all; }
    .modal {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 20px;
      width: 560px; max-width: 95vw;
      max-height: 90vh; overflow-y: auto;
      box-shadow: var(--shadow-lg);
      transform: scale(.95);
      transition: transform .2s;
    }
    .modal-overlay.open .modal { transform: scale(1); }
    .modal-header {
      padding: 20px 24px;
      border-bottom: 1px solid var(--border);
      display: flex; align-items: center; justify-content: space-between;
    }
    .modal-body { padding: 24px; }
    .modal-footer { padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px; }
    .modal-close {
      width: 30px; height: 30px;
      display: flex; align-items: center; justify-content: center;
      border-radius: 8px;
      background: var(--bg-base); border: 1px solid var(--border);
      cursor: pointer; font-size: 16px; color: var(--text-muted);
    }

    /* TOAST */
    .toast-container { position: fixed; bottom: 24px; right: 24px; z-index: 300; display: flex; flex-direction: column; gap: 10px; }
    .toast {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 14px 18px;
      box-shadow: var(--shadow-lg);
      display: flex; align-items: flex-start; gap: 12px;
      min-width: 280px; max-width: 360px;
      animation: slideIn .3s ease;
      border-left: 4px solid var(--green-500);
    }
    .toast.error { border-left-color: var(--red-500); }
    .toast.warning { border-left-color: var(--gold-400); }
    @keyframes slideIn { from { opacity:0; transform: translateX(40px); } to { opacity:1; transform: translateX(0); } }

    /* RESPONSIVE SIDEBAR OVERLAY */
    .sidebar-backdrop {
      display: none;
      position: fixed; inset: 0;
      background: rgba(0,0,0,.5);
      z-index: 45;
    }
    @media(max-width:900px){
      .sidebar { position: fixed; transform: translateX(-100%); }
      .sidebar.mobile-open { transform: translateX(0); }
      .sidebar-backdrop.show { display: block; }
    }

    /* UTILS */
    .flex { display: flex; }
    .flex-center { display: flex; align-items: center; justify-content: center; }
    .items-center { align-items: center; }
    .gap-2 { gap: 8px; }
    .gap-3 { gap: 12px; }
    .gap-4 { gap: 16px; }
    .mb-2 { margin-bottom: 8px; }
    .mb-3 { margin-bottom: 12px; }
    .mb-4 { margin-bottom: 16px; }
    .mb-6 { margin-bottom: 24px; }
    .mt-2 { margin-top: 8px; }
    .mt-4 { margin-top: 16px; }
    .text-sm { font-size: 12px; }
    .text-lg { font-size: 16px; }
    .font-bold { font-weight: 700; }
    .text-muted { color: var(--text-muted); }
    .text-green { color: var(--green-600); }
    .text-gold { color: var(--gold-500); }
    .text-red { color: var(--red-500); }
    .w-full { width: 100%; }
    .rounded-full { border-radius: 50%; }
    .border-light { border-color: var(--border-light); }
  </style>

  @stack('scripts')
</head>
<body>
  <!-- APP SHELL -->
  <div class="app-shell">
    <!-- SIDEBAR BACKDROP -->
    <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="closeMobileSidebar()"></div>

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
      <div class="sidebar-brand">
        <div class="brand-logo">TM</div>
        <div class="sidebar-brand-text">
          <h2>TmcsSmart</h2>
          <p>Church Management System</p>
        </div>
      </div>

      <!-- NAV -->
      <div style="flex:1; padding: 8px 0;">
        @if(auth()->user()->hasPermission('dashboard.view'))
        <div class="nav-section-label">Main</div>
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
          <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
          <span class="nav-label">Dashboard</span>
        </a>
        @endif

        @if(auth()->user()->member)
        <div class="nav-section-label">Member Portal</div>
        <a href="{{ route('member.profile.index') }}" class="nav-item {{ request()->is('member/profile*') ? 'active' : '' }}">
          <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
          <span class="nav-label">My Profile</span>
        </a>
        
        <a href="{{ route('member.communities') }}" class="nav-item {{ request()->is('member/communities*') ? 'active' : '' }}">
          <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          <span class="nav-label">My Communities</span>
        </a>

        <a href="{{ route('member.groups') }}" class="nav-item {{ request()->is('member/groups*') ? 'active' : '' }}">
          <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
          <span class="nav-label">My Groups</span>
        </a>

        <div class="nav-group">
          <button onclick="toggleDropdown(this)" class="nav-item {{ request()->is('member/contributions*') || request()->is('member/pay*') ? 'open' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="nav-label">My Contributions</span>
            <svg class="dropdown-arrow w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </button>
          <div class="nav-dropdown {{ request()->is('member/contributions*') || request()->is('member/pay*') ? 'show' : '' }}">
            <a href="{{ route('member.contributions.index') }}" class="dropdown-item {{ request()->is('member/contributions') ? 'active' : '' }}">Contribution History</a>
            <a href="{{ route('member.profile.pay') }}" class="dropdown-item {{ request()->is('member/pay') ? 'active' : '' }}">Make Payment</a>
          </div>
        </div>

        <a href="{{ route('member.events') }}" class="nav-item {{ request()->is('member/events*') ? 'active' : '' }}">
          <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          <span class="nav-label">Events</span>
        </a>
        @endif

        @if(!auth()->user()->member && auth()->user()->hasPermission('members.view'))
        <div class="nav-section-label">Administration</div>
        <div class="nav-group">
          <button onclick="toggleDropdown(this)" class="nav-item {{ request()->is('members*') ? 'open' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span class="nav-label">Members</span>
            <svg class="dropdown-arrow w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </button>
          <div class="nav-dropdown {{ request()->is('members*') ? 'show' : '' }}">
            <a href="{{ route('members.index') }}" class="dropdown-item {{ request()->is('members') ? 'active' : '' }}">All Members</a>
            <a href="{{ route('members.create') }}" class="dropdown-item {{ request()->is('members/create') ? 'active' : '' }}">Register Member</a>
            <a href="{{ route('members.categories') }}" class="dropdown-item {{ request()->is('members/categories') ? 'active' : '' }}">Member Categories</a>
          </div>
        </div>
        @endif

        @if(!auth()->user()->member && auth()->user()->hasPermission('finance.view'))
        <div class="nav-group">
          <button onclick="toggleDropdown(this)" class="nav-item {{ request()->is('finance*') ? 'open' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="nav-label">Finance</span>
            <svg class="dropdown-arrow w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </button>
          <div class="nav-dropdown {{ request()->is('finance*') ? 'show' : '' }}">
            <a href="{{ route('finance.index') }}" class="dropdown-item {{ request()->is('finance') ? 'active' : '' }}">Contributions</a>
            <a href="{{ route('finance.types.index') }}" class="dropdown-item {{ request()->is('finance/types*') ? 'active' : '' }}">Contribution Types</a>
            <a href="{{ route('finance.create') }}" class="dropdown-item {{ request()->is('finance/create') ? 'active' : '' }}">Record Giving</a>
            <a href="{{ route('expenses.index') }}" class="dropdown-item {{ request()->is('expenses*') ? 'active' : '' }}">Expenses</a>
            <a href="{{ route('reconciliation.index') }}" class="dropdown-item {{ request()->is('reconciliation*') ? 'active' : '' }}">Reconciliation</a>
            <a href="{{ route('finance.reports') }}" class="dropdown-item {{ request()->is('finance/reports') ? 'active' : '' }}">Financial Reports</a>
            <a href="{{ route('finance.settings') }}" class="dropdown-item {{ request()->is('finance/settings') ? 'active' : '' }}">Payment Settings</a>
          </div>
        </div>
        @endif

        @if(!auth()->user()->member && auth()->user()->hasPermission('groups.view'))
        <div class="nav-group">
          <button onclick="toggleDropdown(this)" class="nav-item {{ request()->is('groups*') ? 'open' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span class="nav-label">Groups</span>
            <svg class="dropdown-arrow w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </button>
          <div class="nav-dropdown {{ request()->is('groups*') ? 'show' : '' }}">
            <a href="{{ route('groups.index') }}" class="dropdown-item {{ request()->is('groups') ? 'active' : '' }}">All Groups</a>
            <a href="{{ route('groups.communities') }}" class="dropdown-item {{ request()->is('groups/communities') ? 'active' : '' }}">Small Communities</a>
            <a href="{{ route('groups.activities') }}" class="dropdown-item {{ request()->is('groups/activities') ? 'active' : '' }}">Group Activities</a>
          </div>
        </div>
        @endif

        @if(!auth()->user()->member && auth()->user()->hasPermission('communications.view'))
        <div class="nav-group">
          <button onclick="toggleDropdown(this)" class="nav-item {{ request()->is('communications*') ? 'open' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <span class="nav-label">Communications</span>
            <svg class="dropdown-arrow w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </button>
          <div class="nav-dropdown {{ request()->is('communications*') || request()->is('api-configs*') ? 'show' : '' }}">
            <a href="{{ route('communications.index') }}" class="dropdown-item {{ request()->is('communications') ? 'active' : '' }}">Messages</a>
            <a href="{{ route('communications.create') }}" class="dropdown-item {{ request()->is('communications/create') ? 'active' : '' }}">Send SMS/Email</a>
            <a href="{{ route('communications.announcements') }}" class="dropdown-item {{ request()->is('communications/announcements') ? 'active' : '' }}">Announcements</a>
            <a href="{{ route('api-configs.index') }}" class="dropdown-item {{ request()->is('api-configs*') ? 'active' : '' }}">Api Config</a>
          </div>
        </div>
        @endif

        @if(!auth()->user()->member && auth()->user()->hasPermission('certificates.view'))
        <div class="nav-group">
          <button onclick="toggleDropdown(this)" class="nav-item {{ request()->is('certificates*') ? 'open' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="nav-label">Certificates</span>
            <svg class="dropdown-arrow w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </button>
          <div class="nav-dropdown {{ request()->is('certificates*') ? 'show' : '' }}">
            <a href="{{ route('certificates.index') }}" class="dropdown-item {{ request()->is('certificates') ? 'active' : '' }}">All Certificates</a>
            <a href="{{ route('certificates.create') }}" class="dropdown-item {{ request()->is('certificates/create') ? 'active' : '' }}">Generate New</a>
            <a href="{{ route('certificates.verify') }}" class="dropdown-item {{ request()->is('certificates/verify') ? 'active' : '' }}">Verification</a>
          </div>
        </div>
        @endif

        @if(!auth()->user()->member && auth()->user()->hasPermission('events.view'))
        <div class="nav-group">
          <button onclick="toggleDropdown(this)" class="nav-item {{ request()->is('events*') ? 'open' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span class="nav-label">Events</span>
            <svg class="dropdown-arrow w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </button>
          <div class="nav-dropdown {{ request()->is('events*') ? 'show' : '' }}">
            <a href="{{ route('events.index') }}" class="dropdown-item {{ request()->is('events') ? 'active' : '' }}">Calendar</a>
            <a href="{{ route('events.create') }}" class="dropdown-item {{ request()->is('events/create') ? 'active' : '' }}">Plan Event</a>
            <a href="{{ route('events.attendance') }}" class="dropdown-item {{ request()->is('events/attendance') ? 'active' : '' }}">Attendance Tracking</a>
          </div>
        </div>
        @endif

        @if(!auth()->user()->member && auth()->user()->hasPermission('elections.view'))
        <div class="nav-group">
          <button onclick="toggleDropdown(this)" class="nav-item {{ request()->is('elections*') ? 'open' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <span class="nav-label">Elections</span>
            <svg class="dropdown-arrow w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </button>
          <div class="nav-dropdown {{ request()->is('elections*') ? 'show' : '' }}">
            <a href="{{ route('elections.index') }}" class="dropdown-item {{ request()->is('elections') ? 'active' : '' }}">All Elections</a>
            <a href="{{ route('elections.create') }}" class="dropdown-item {{ request()->is('elections/create') ? 'active' : '' }}">New Election</a>
            <a href="{{ route('elections.results') }}" class="dropdown-item {{ request()->is('elections/results') ? 'active' : '' }}">Voting Results</a>
          </div>
        </div>
        @endif

        @if(!auth()->user()->member && auth()->user()->hasPermission('assets.view'))
        <div class="nav-group">
          <button onclick="toggleDropdown(this)" class="nav-item {{ request()->is('assets*') ? 'open' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <span class="nav-label">Assets</span>
            <svg class="dropdown-arrow w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </button>
          <div class="nav-dropdown {{ request()->is('assets*') ? 'show' : '' }}">
            <a href="{{ route('assets.index') }}" class="dropdown-item {{ request()->is('assets') ? 'active' : '' }}">Inventory</a>
            <a href="{{ route('assets.maintenance') }}" class="dropdown-item {{ request()->is('assets/maintenance') ? 'active' : '' }}">Maintenance</a>
            <a href="{{ route('assets.assignments') }}" class="dropdown-item {{ request()->is('assets/assignments') ? 'active' : '' }}">Assignments</a>
          </div>
        </div>
        @endif

        @if(!auth()->user()->member && auth()->user()->hasPermission('shop.view'))
        <div class="nav-group">
          <button onclick="toggleDropdown(this)" class="nav-item {{ request()->is('shop*') ? 'open' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span class="nav-label">Shop (POS)</span>
            <svg class="dropdown-arrow w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </button>
          <div class="nav-dropdown {{ request()->is('shop*') ? 'show' : '' }}">
            <a href="{{ route('shop.index') }}" class="dropdown-item {{ request()->is('shop') ? 'active' : '' }}">Products</a>
            <a href="{{ route('shop.create-sale') }}" class="dropdown-item {{ request()->is('shop/create-sale') ? 'active' : '' }}">New Sale</a>
            <a href="{{ route('shop.sales') }}" class="dropdown-item {{ request()->is('shop/sales') ? 'active' : '' }}">Sales History</a>
          </div>
        </div>
        @endif

      
        <div class="nav-section-label">Administrator</div>
        <div class="nav-group">
          <button onclick="toggleDropdown(this)" class="nav-item {{ request()->is('settings*') ? 'open' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
            <span class="nav-label">System Settings</span>
            <svg class="dropdown-arrow w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </button>
          <div class="nav-dropdown {{ request()->is('settings*') ? 'show' : '' }}">
            <a href="{{ route('settings.monitoring.dashboard') }}" class="dropdown-item {{ request()->is('settings/monitoring*') ? 'active' : '' }}">Activity Monitor</a>
            <a href="{{ route('settings.security.index') }}" class="dropdown-item {{ request()->is('settings/security*') ? 'active' : '' }}">Security Controls</a>
            <a href="{{ route('settings.roles.index') }}" class="dropdown-item {{ request()->is('settings/roles*') ? 'active' : '' }}">Role Management</a>
            <a href="{{ route('users.index') }}" class="dropdown-item {{ request()->is('users*') ? 'active' : '' }}">User Accounts</a>
            <a href="{{ route('settings.index') }}" class="dropdown-item {{ request()->is('settings') ? 'active' : '' }}">General Config</a>
          </div>
        </div>
   
      </div>

      <!-- SIDEBAR FOOTER REMOVED -->
    </aside>

    <!-- MAIN -->
    <div class="main">
      <!-- TOPBAR -->
      <div class="topbar">
        <button class="topbar-toggle" onclick="toggleSidebar()" title="Toggle Sidebar">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <div class="hidden md:block">
          <div class="page-title">@yield('page-title', 'Dashboard')</div>
          <div class="breadcrumb">@yield('breadcrumb', 'TmcsSmart / Dashboard')</div>
        </div>
        <div class="topbar-right">
          <button class="icon-btn" title="Notifications">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            <span class="notif-dot"></span>
          </button>

          <!-- THEME TOGGLE (ICON ONLY) -->
          <button class="icon-btn" onclick="toggleTheme()" title="Toggle Theme">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" id="themeIcon">
              <path d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
          </button>

          <!-- PROFILE DROPDOWN -->
          <div class="topbar-dropdown">
            <button class="flex items-center gap-2 p-1 hover:bg-light rounded-lg transition-all" onclick="toggleTopbarDropdown('profileMenu')">
              <div class="user-avatar overflow-hidden" style="width:32px; height:32px; font-size:11px;">
                @if(auth()->user()->member && auth()->user()->member->photo)
                  <img src="{{ asset('storage/' . auth()->user()->member->photo) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                @elseif(auth()->user()->profile_image)
                  <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                @else
                  {{ substr(auth()->user()->name, 0, 2) }}
                @endif
              </div>
              <div class="hidden md:block text-left">
                <div class="text-xs font-bold leading-none">{{ auth()->user()->name }}</div>
                <div class="text-[10px] text-muted">{{ auth()->user()->roles->first()->display_name ?? 'User' }}</div>
              </div>
              <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div class="dropdown-menu" id="profileMenu" style="width: 260px;">
              <!-- Profile Header -->
              <div class="px-4 py-3 border-b border-light flex items-center gap-3">
                <div class="user-avatar overflow-hidden" style="width:40px; height:40px; font-size:14px;">
                  @if(auth()->user()->member && auth()->user()->member->photo)
                    <img src="{{ asset('storage/' . auth()->user()->member->photo) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                  @elseif(auth()->user()->profile_image)
                    <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                  @else
                    {{ substr(auth()->user()->name, 0, 2) }}
                  @endif
                </div>
                <div class="overflow-hidden">
                  <div class="text-sm font-bold truncate">{{ auth()->user()->name }}</div>
                  <div class="text-[11px] text-muted truncate">{{ auth()->user()->email }}</div>
                  <div class="mt-1">
                    <span class="badge green" style="font-size: 9px; padding: 1px 6px;">{{ auth()->user()->roles->first()->display_name ?? 'User' }}</span>
                  </div>
                </div>
              </div>
              
              <div class="p-1">
                @if(auth()->user()->member)
                <a href="{{ route('member.profile.index') }}" class="dropdown-menu-item">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                  My Profile
                </a>
                @endif
                <a href="{{ route('settings.security.index') }}" class="dropdown-menu-item">
                  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                  Account Security
                </a>
              </div>
              
              <div class="dropdown-divider"></div>
              <div class="p-1">
                <form action="{{ route('logout') }}" method="POST">
                  @csrf
                  <button type="submit" class="dropdown-menu-item text-red w-full">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- CONTENT -->
      <div class="content">
        <div style="min-height: calc(100vh - 160px);">
          @yield('content')
        </div>
        
        <!-- FOOTER -->
        <footer class="mt-10 py-6 border-t border-light flex flex-col md:flex-row justify-between items-center gap-4 text-muted text-xs">
          <div>
            &copy; {{ date('Y') }} <span class="font-bold text-green-600">TmcsSmart</span>. Church Management System.
          </div>
          <div class="flex items-center gap-4">
            <span class="flex items-center gap-1">
              <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
               System Active
             </span>
             <span class="opacity-30">|</span>
             <span>v1.0.4</span>
           </div>
         </footer>
      </div>
    </div>
  </div>

  <!-- TOAST CONTAINER -->
  <div class="toast-container" id="toastContainer"></div>

  @stack('modals')

  <script>
    // Theme Toggle
    let darkMode = false;
    function toggleTheme() {
      darkMode = !darkMode;
      const theme = darkMode ? 'dark' : 'light';
      document.documentElement.setAttribute('data-theme', theme);
      localStorage.setItem('theme', theme);
      
      const icon = document.getElementById('themeIcon');
      icon.innerHTML = darkMode
        ? '<circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>'
        : '<path d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>';
      
      // Dispatch event for charts to update
      window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme: theme } }));
    }

    // Initialize theme from localStorage
    document.addEventListener('DOMContentLoaded', () => {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            darkMode = false; // will be toggled
            toggleTheme();
        }
    });

    // Topbar Dropdown Toggle
    function toggleTopbarDropdown(id) {
      const dropdown = document.getElementById(id);
      const allDropdowns = document.querySelectorAll('.dropdown-menu');
      
      allDropdowns.forEach(d => {
        if (d.id !== id) d.classList.remove('show');
      });
      
      dropdown.classList.toggle('show');
    }

    // Close dropdowns when clicking outside
    window.addEventListener('click', function(e) {
      if (!e.target.closest('.topbar-dropdown')) {
        document.querySelectorAll('.dropdown-menu').forEach(d => d.classList.remove('show'));
      }
    });

    // Sidebar Toggle
    let sidebarCollapsed = false;
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const backdrop = document.getElementById('sidebarBackdrop');
      if (window.innerWidth <= 900) {
        sidebar.classList.toggle('mobile-open');
        backdrop.classList.toggle('show');
      } else {
        sidebarCollapsed = !sidebarCollapsed;
        sidebar.classList.toggle('collapsed', sidebarCollapsed);
      }
    }
    function closeMobileSidebar() {
      document.getElementById('sidebar').classList.remove('mobile-open');
      document.getElementById('sidebarBackdrop').classList.remove('show');
    }

    // Toast Notifications
    function showToast(message, type = 'success') {
      const container = document.getElementById('toastContainer');
      const toast = document.createElement('div');
      const icons = {
        success: '✓',
        error: '✕',
        info: 'ℹ',
        warning: '⚠'
      };
      const colors = { success: 'var(--green-500)', error: 'var(--red-500)', info: 'var(--blue-600)', warning: 'var(--gold-400)' };
      toast.className = 'toast ' + (type === 'error' ? 'error' : type === 'warning' ? 'warning' : '');
      toast.style.borderLeftColor = colors[type] || colors.success;
      toast.innerHTML = `
        <div style="width:24px;height:24px;border-radius:50%;background:${colors[type]||colors.success};color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;">${icons[type]||icons.success}</div>
        <div style="flex:1;">
          <div style="font-size:13px;font-weight:600;color:var(--text-primary);">${type.charAt(0).toUpperCase()+type.slice(1)}</div>
          <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">${message}</div>
        </div>
        <div onclick="this.parentElement.remove()" style="cursor:pointer;color:var(--text-muted);font-size:14px;padding:2px 4px;">✕</div>
      `;
      container.appendChild(toast);
      setTimeout(() => toast.style.opacity='0', 4000);
      setTimeout(() => toast.remove(), 4300);
    }

    // Enhanced Dashboard Functionality
    function toggleDropdown(button) {
      const group = button.parentElement;
      const dropdown = group.querySelector('.nav-dropdown');
      const allGroups = document.querySelectorAll('.nav-group');
      
      // Close other dropdowns
      allGroups.forEach(g => {
        if (g !== group) {
          g.querySelector('.nav-item').classList.remove('open');
          g.querySelector('.nav-dropdown').classList.remove('show');
        }
      });

      // Toggle current
      button.classList.toggle('open');
      dropdown.classList.toggle('show');
    }

    function refreshActivityFeed() {
      const feed = document.getElementById('activityFeed');
      feed.style.opacity = '0.5';
      
      // Simulate API call
      setTimeout(() => {
        feed.style.opacity = '1';
        showToast('Activity feed refreshed', 'success');
        
        // Add new activity item at top
        const newItem = document.createElement('div');
        newItem.className = 'activity-item';
        newItem.innerHTML = `
          <div class="activity-icon green">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
          </div>
          <div class="activity-content">
            <div class="activity-title">Dashboard refreshed</div>
            <div class="activity-meta">Just now</div>
            <div class="activity-description">Latest activities loaded</div>
          </div>
        `;
        feed.insertBefore(newItem, feed.firstChild);
        
        // Remove last item if too many
        if (feed.children.length > 5) {
          feed.removeChild(feed.lastChild);
        }
      }, 1000);
    }

    // Time Range Selector
    document.addEventListener('DOMContentLoaded', function() {
      const rangeBtns = document.querySelectorAll('.range-btn');
      rangeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
          rangeBtns.forEach(b => b.classList.remove('active'));
          this.classList.add('active');
          updateCharts(this.dataset.range);
        });
      });

      // Period Selector
      const periodSelector = document.getElementById('metricsPeriod');
      if (periodSelector) {
        periodSelector.addEventListener('change', function() {
          updateMetrics(this.value);
        });
      }

      // Update DateTime
      updateDateTime();
      setInterval(updateDateTime, 60000);

      // Initialize Charts
      initializeCharts();
    });

    function updateDateTime() {
      const dateTimeElement = document.getElementById('currentDateTime');
      if (dateTimeElement) {
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        dateTimeElement.textContent = now.toLocaleDateString('en-US', options);
      }
    }

    function updateCharts(range) {
      showToast(`Charts updated for ${range}`, 'info');
      // Here you would make actual API calls to update chart data
    }

    function updateMetrics(period) {
      showToast(`Metrics updated for ${period}`, 'info');
      // Here you would make actual API calls to update metrics
    }

    function initializeCharts() {
      // Enhanced Chart.js configuration
      const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              display: false
            }
          },
          x: {
            grid: {
              display: false
            }
          }
        }
      };

      // Contributions Chart
      const contributionsCtx = document.getElementById('contributionsChart');
      if (contributionsCtx) {
        new Chart(contributionsCtx, {
          type: 'line',
          data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
              label: 'Contributions',
              data: [120000, 150000, 180000, 160000, 200000, 180000],
              borderColor: 'rgb(5, 150, 105)',
              backgroundColor: 'rgba(5, 150, 105, 0.1)',
              tension: 0.4,
              fill: true
            }]
          },
          options: chartOptions
        });
      }

      // Types Chart
      const typesCtx = document.getElementById('typesChart');
      if (typesCtx) {
        new Chart(typesCtx, {
          type: 'doughnut',
          data: {
            labels: ['Tithes', 'Offerings', 'Special', 'Building', 'Mission'],
            datasets: [{
              data: [45, 25, 15, 10, 5],
              backgroundColor: [
                'rgb(5, 150, 105)',
                'rgb(217, 119, 6)',
                'rgb(59, 130, 246)',
                'rgb(239, 68, 68)',
                'rgb(107, 114, 128)'
              ]
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                position: 'bottom',
                labels: {
                  padding: 15,
                  font: {
                    size: 11
                  }
                }
              }
            }
          }
        });
      }

      // Growth Chart
      const growthCtx = document.getElementById('growthChart');
      if (growthCtx) {
        new Chart(growthCtx, {
          type: 'bar',
          data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
              label: 'New Members',
              data: [12, 19, 15, 25, 22, 30],
              backgroundColor: 'rgba(5, 150, 105, 0.8)',
              borderRadius: 8
            }]
          },
          options: chartOptions
        });
      }
    }

    // Auto-show toasts from session
    @if(session('success'))
      showToast('{{ session('success') }}', 'success');
    @endif
    @if(session('error'))
      showToast('{{ session('error') }}', 'error');
    @endif
    @if(session('info'))
      showToast('{{ session('info') }}', 'info');
    @endif
    @if(session('warning'))
      showToast('{{ session('warning') }}', 'warning');
    @endif
  </script>
  @stack('scripts')
</body>
</html>
