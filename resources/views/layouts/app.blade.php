<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="ltr" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? __('m.dashboard') }} — Hotel POS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Noto+Sans+Ethiopic:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        /* ── Design Tokens ─────────────────────────────── */
        :root {
            --sidebar-w: 220px;
            --topbar-h: 52px;
            --radius: 10px;
            --radius-sm: 6px;
            --font: 'Inter', 'Noto Sans Ethiopic', system-ui, sans-serif;
            --transition: .18s ease;
            /* Light palette */
            --bg-app: #f5f6f8;
            --bg-surface: #ffffff;
            --bg-sidebar: #ffffff;
            --border: #e8eaed;
            --text-primary: #1a1d21;
            --text-secondary: #5f6368;
            --text-muted: #9aa0a6;
            --accent: #1a73e8;
            --accent-subtle: #e8f0fe;
            --hover-bg: #f1f3f4;
            --active-bg: #e8f0fe;
        }

        [data-bs-theme="dark"] {
            --bg-app: #1a1a1a;
            --bg-surface: #242424;
            --bg-sidebar: #1e1e1e;
            --border: #333333;
            --text-primary: #e8eaed;
            --text-secondary: #9aa0a6;
            --text-muted: #6b7280;
            --accent: #8ab4f8;
            --accent-subtle: rgba(138, 180, 248, .12);
            --hover-bg: #2d2d2d;
            --active-bg: rgba(138, 180, 248, .12);
        }

        /* ── Reset ──────────────────────────────────────── */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            font-family: var(--font);
            font-size: 13.5px;
            background: var(--bg-app);
            color: var(--text-primary);
            margin: 0;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        a {
            color: var(--accent);
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* ── Sidebar ────────────────────────────────────── */
        .app-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--sidebar-w);
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            z-index: 200;
            transition: transform var(--transition);
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 16px 16px 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 15px;
            color: var(--text-primary);
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        .sidebar-brand i {
            color: var(--accent);
            font-size: 18px;
        }

        .sidebar-brand small {
            display: block;
            font-size: 11px;
            font-weight: 400;
            color: var(--text-muted);
            line-height: 1.2;
        }

        .sidebar-cta {
            padding: 8px 12px 8px;
        }

        .sidebar-cta .btn-new-order {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: 100%;
            padding: 9px 0;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity var(--transition);
        }

        .sidebar-cta .btn-new-order:hover {
            opacity: .88;
            text-decoration: none;
        }

        .sidebar-nav {
            flex: 1;
            padding: 6px 0;
            overflow-y: auto;
        }

        .sidebar-nav .nav-section {
            padding: 14px 16px 4px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: var(--text-muted);
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 16px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-secondary);
            border-radius: 0;
            transition: all var(--transition);
            text-decoration: none;
        }

        .sidebar-nav .nav-link:hover {
            background: var(--hover-bg);
            color: var(--text-primary);
        }

        .sidebar-nav .nav-link.active {
            background: var(--active-bg);
            color: var(--accent);
            font-weight: 700;
        }

        .sidebar-nav .nav-link i {
            font-size: 17px;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }

        /* ── Top Bar ────────────────────────────────────── */
        .app-topbar {
            position: fixed;
            top: 0;
            right: 0;
            left: var(--sidebar-w);
            height: var(--topbar-h);
            background: var(--bg-surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            z-index: 150;
        }

        .topbar-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .topbar-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: var(--radius-sm);
            border: none;
            background: transparent;
            color: var(--text-secondary);
            font-size: 16px;
            cursor: pointer;
            transition: all var(--transition);
            position: relative;
            text-decoration: none;
        }

        .topbar-btn:hover {
            background: var(--hover-bg);
            color: var(--text-primary);
        }

        .topbar-divider {
            width: 1px;
            height: 24px;
            background: var(--border);
            margin: 0 6px;
        }

        .lang-toggle {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px 10px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            background: transparent;
            cursor: pointer;
            text-decoration: none;
            transition: all var(--transition);
            white-space: nowrap;
        }

        .lang-toggle:hover {
            border-color: var(--accent);
            color: var(--accent);
            text-decoration: none;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 8px;
            border-radius: var(--radius-sm);
            font-size: 12.5px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .topbar-user .user-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--accent);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
        }

        .notif-badge {
            position: absolute;
            top: 4px;
            right: 4px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #1a73e8;
        }

        [data-bs-theme="dark"] .notif-badge {
            background: #8ab4f8;
        }

        /* ── Main Content ───────────────────────────────── */
        .app-main {
            margin-left: var(--sidebar-w);
            padding-top: var(--topbar-h);
            min-height: 100vh;
        }

        .page-body {
            padding: 20px;
        }

        /* ── Breadcrumb ──────────────────────────────────── */
        .page-breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 4px;
        }

        .page-breadcrumb a {
            color: var(--text-muted);
        }

        .page-breadcrumb a:hover {
            color: var(--accent);
        }

        .page-heading {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .page-subtitle {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        /* ── Cards & Stat Cards ──────────────────────────── */
        .stat-card {
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 16px 18px;
            transition: border-color var(--transition);
        }

        .stat-card:hover {
            border-color: color-mix(in srgb, var(--accent) 30%, var(--border));
        }

        .stat-card .stat-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-card .stat-label i {
            font-size: 16px;
            color: var(--text-muted);
        }

        .stat-card .stat-value {
            font-size: 26px;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1.1;
        }

        .stat-card .stat-unit {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-secondary);
        }

        .card {
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: none;
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border);
            padding: 12px 16px;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header .view-all {
            font-size: 12px;
            font-weight: 600;
            color: var(--accent);
        }

        .card-body {
            padding: 16px;
        }

        /* ── Tables ──────────────────────────────────────── */
        .table {
            margin-bottom: 0;
            font-size: 13px;
            color: var(--text-primary);
        }

        .table th {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
            padding: 10px 14px;
            white-space: nowrap;
        }

        .table td {
            padding: 10px 14px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table-hover tbody tr:hover {
            background: var(--hover-bg);
        }

        /* ── Badges ──────────────────────────────────────── */
        .badge {
            font-size: 11px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 4px;
        }

        .badge-available {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-occupied {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-maintenance {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-reserved {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-cash {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-bank_transfer {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-bank {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-telebirr {
            background: #fce7f3;
            color: #9d174d;
        }

        .badge-cbe_birr {
            background: #e0e7ff;
            color: #3730a3;
        }

        .badge-cbe {
            background: #e0e7ff;
            color: #3730a3;
        }

        .badge-credit {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-paid {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }

        [data-bs-theme="dark"] .badge-available {
            background: rgba(16, 185, 129, .15);
            color: #6ee7b7;
        }

        [data-bs-theme="dark"] .badge-occupied {
            background: rgba(239, 68, 68, .15);
            color: #fca5a5;
        }

        [data-bs-theme="dark"] .badge-maintenance {
            background: rgba(245, 158, 11, .15);
            color: #fcd34d;
        }

        [data-bs-theme="dark"] .badge-reserved {
            background: rgba(59, 130, 246, .15);
            color: #93c5fd;
        }

        [data-bs-theme="dark"] .badge-cash {
            background: rgba(16, 185, 129, .15);
            color: #6ee7b7;
        }

        [data-bs-theme="dark"] .badge-bank_transfer,
        [data-bs-theme="dark"] .badge-bank {
            background: rgba(59, 130, 246, .15);
            color: #93c5fd;
        }

        [data-bs-theme="dark"] .badge-telebirr {
            background: rgba(236, 72, 153, .15);
            color: #f9a8d4;
        }

        [data-bs-theme="dark"] .badge-cbe_birr,
        [data-bs-theme="dark"] .badge-cbe {
            background: rgba(99, 102, 241, .15);
            color: #a5b4fc;
        }

        [data-bs-theme="dark"] .badge-credit {
            background: rgba(239, 68, 68, .15);
            color: #fca5a5;
        }

        [data-bs-theme="dark"] .badge-paid {
            background: rgba(16, 185, 129, .15);
            color: #6ee7b7;
        }

        [data-bs-theme="dark"] .badge-pending {
            background: rgba(245, 158, 11, .15);
            color: #fcd34d;
        }

        /* ── Buttons ─────────────────────────────────────── */
        .btn {
            font-size: 13px;
            font-weight: 500;
            border-radius: var(--radius-sm);
            padding: 7px 14px;
            transition: all var(--transition);
        }

        .btn-sm {
            font-size: 12px;
            padding: 5px 10px;
        }

        .btn-primary {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        .btn-primary:hover {
            opacity: .88;
        }

        .btn-outline-secondary {
            border-color: var(--border);
            color: var(--text-secondary);
            background: transparent;
        }

        .btn-outline-secondary:hover {
            background: var(--hover-bg);
            border-color: var(--border);
            color: var(--text-primary);
        }

        /* ── Forms ────────────────────────────────────────── */
        .form-control,
        .form-select {
            font-size: 13px;
            padding: 8px 12px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            background: var(--bg-surface);
            color: var(--text-primary);
            transition: border var(--transition), box-shadow var(--transition);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(26, 115, 232, .1);
        }

        .form-label {
            font-size: 12.5px;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 4px;
        }

        .form-check-input:checked {
            background-color: var(--accent);
            border-color: var(--accent);
        }

        /* ── Alerts ───────────────────────────────────────── */
        .alert {
            font-size: 13px;
            padding: 10px 14px;
            border-radius: var(--radius-sm);
            border: none;
        }

        /* ── Modals ───────────────────────────────────────── */
        .modal-content {
            border-radius: var(--radius);
            border: 1px solid var(--border);
            background: var(--bg-surface);
        }

        .modal-header {
            border-bottom: 1px solid var(--border);
            padding: 14px 18px;
        }

        .modal-header .modal-title {
            font-size: 15px;
            font-weight: 600;
        }

        .modal-body {
            padding: 18px;
        }

        .modal-footer {
            border-top: 1px solid var(--border);
            padding: 12px 18px;
        }

        /* ── Notification Dropdown ───────────────────────── */
        .notif-dropdown {
            width: 320px;
            max-height: 380px;
            overflow-y: auto;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: var(--bg-surface);
            box-shadow: 0 4px 16px rgba(0, 0, 0, .08);
        }

        [data-bs-theme="dark"] .notif-dropdown {
            box-shadow: 0 4px 16px rgba(0, 0, 0, .3);
        }

        .notif-dropdown .dropdown-header {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: var(--text-muted);
            padding: 10px 14px;
        }

        .notif-dropdown .dropdown-item {
            font-size: 12.5px;
            padding: 10px 14px;
            border-bottom: 1px solid var(--border);
            white-space: normal;
            color: var(--text-primary);
        }

        .notif-dropdown .dropdown-item:hover {
            background: var(--hover-bg);
        }

        .notif-dropdown .notif-read {
            opacity: .5;
        }

        /* ── Pagination ──────────────────────────────────── */
        .pagination {
            gap: 2px;
        }

        .page-link {
            font-size: 12.5px;
            padding: 6px 12px;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
            color: var(--text-secondary);
            background: var(--bg-surface);
        }

        .page-link:hover {
            background: var(--hover-bg);
            color: var(--text-primary);
        }

        .page-item.active .page-link {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        .page-item.disabled .page-link {
            background: var(--bg-surface);
            color: var(--text-muted);
            border-color: var(--border);
            opacity: .5;
        }

        /* ── Responsive ──────────────────────────────────── */
        .mobile-toggle {
            display: none;
        }

        .sidebar-overlay {
            display: none;
        }

        @media (max-width: 768px) {
            .mobile-toggle {
                display: inline-flex;
            }

            .app-sidebar {
                transform: translateX(-100%);
            }

            .app-sidebar.show {
                transform: translateX(0);
            }

            .sidebar-overlay.show {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, .4);
                z-index: 199;
            }

            .app-topbar {
                left: 0;
            }

            .app-main {
                margin-left: 0;
            }
        }

        @media (max-width: 576px) {
            .page-heading {
                font-size: 18px;
            }

            .stat-card .stat-value {
                font-size: 18px;
            }

            .page-body {
                padding: 14px;
            }
        }
    </style>
    @stack('styles')
    <script>
        /* Apply saved theme BEFORE paint to prevent flash */
        (function () {
            const t = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', t);
        })();
    </script>
</head>

<body>
    {{-- Sidebar Overlay (Mobile) --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    {{-- Sidebar --}}
    <aside class="app-sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div>
                <i class="bi bi-building"></i>
            </div>
            <div>
                Hotel Management
            </div>
        </div>

        @if(auth()->user()->canCreateOrders())
            <div class="sidebar-cta">
                <a href="{{ route('orders.create') }}" class="btn-new-order">
                    <i class="bi bi-plus-lg"></i> + {{ __('m.new_order') }}
                </a>
            </div>
        @endif

        <nav class="sidebar-nav">
            <ul class="nav flex-column" id="sidebarMenu">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        href="{{ route('dashboard') }}">
                        <i class="bi bi-grid-1x2"></i> {{ __('m.dashboard') }}
                    </a>
                </li>

                @if(auth()->user()->canCreateOrders())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('orders.index') ? 'active' : '' }}"
                            href="{{ route('orders.index') }}">
                            <i class="bi bi-receipt"></i> {{ __('m.orders') }}
                        </a>
                    </li>
                @endif

                @if(auth()->user()->canManageProducts())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
                            href="{{ route('products.index') }}">
                            <i class="bi bi-box-seam"></i> {{ __('m.products') }}
                        </a>
                    </li>
                @endif

                @if(auth()->user()->isAdmin())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"
                            href="{{ route('categories.index') }}">
                            <i class="bi bi-tags"></i> {{ __('m.categories') }}
                        </a>
                    </li>
                @endif

                @if(auth()->user()->canManageRooms())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('rooms.index') || request()->routeIs('rooms.manage') ? 'active' : '' }}"
                            href="{{ route('rooms.index') }}">
                            <i class="bi bi-door-open"></i> {{ __('m.rooms') }}
                        </a>
                    </li>

                @endif

                @if(auth()->user()->canAccessReports())
                    <div class="nav-section">{{ __('m.reports') }}</div>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reports.daily') ? 'active' : '' }}"
                            href="{{ route('reports.daily') }}">
                            <i class="bi bi-calendar-day"></i> {{ __('m.daily_report') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reports.period') ? 'active' : '' }}"
                            href="{{ route('reports.period') }}">
                            <i class="bi bi-calendar-range"></i> {{ __('m.period_report') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reports.product-sales') ? 'active' : '' }}"
                            href="{{ route('reports.product-sales') }}">
                            <i class="bi bi-bar-chart"></i> {{ __('m.product_sales') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reports.stock') ? 'active' : '' }}"
                            href="{{ route('reports.stock') }}">
                            <i class="bi bi-boxes"></i> {{ __('m.stock_report') }}
                        </a>
                    </li>
                @endif

                @if(auth()->user()->isAdmin())
                    <div class="nav-section">{{ __('m.settings') }}</div>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                            href="{{ route('users.index') }}">
                            <i class="bi bi-people"></i> {{ __('m.users') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}"
                            href="{{ route('settings.index') }}">
                            <i class="bi bi-gear"></i> {{ __('m.settings') }}
                        </a>
                    </li>
                @endif

                @if(auth()->user()->canAccessReports())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('audit.*') ? 'active' : '' }}"
                            href="{{ route('audit.index') }}">
                            <i class="bi bi-shield-check"></i> {{ __('m.audit_log') }}
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </aside>

    {{-- Top Bar --}}
    <header class="app-topbar">
        <div class="d-flex align-items-center gap-2">
            <button class="topbar-btn mobile-toggle" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <span class="topbar-title">{{ $title ?? __('m.dashboard') }}</span>
        </div>
        <div class="topbar-actions">
            {{-- Notifications --}}
            @auth
                @php
                    $unreadCount = auth()->user()->unreadNotifications()->count();
                    $recentNotifications = auth()->user()->notifications()->take(5)->get();
                @endphp
                <div class="dropdown">
                    <button class="topbar-btn" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell"></i>
                        @if($unreadCount > 0)<span class="notif-badge"></span>@endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end notif-dropdown p-0">
                        <li>
                            <div class="dropdown-header">Notifications</div>
                        </li>
                        @forelse($recentNotifications as $notification)
                            <li>
                                <a class="dropdown-item {{ $notification->read_at ? 'notif-read' : '' }}"
                                    href="{{ route('notifications.read', $notification->id) }}">
                                    <div class="fw-semibold mb-1 {{ $notification->read_at ? '' : ($notification->data['type'] === 'low_stock' ? 'text-danger' : 'text-warning') }}"
                                        style="font-size:12.5px;">
                                        <i
                                            class="bi {{ $notification->data['type'] === 'low_stock' ? 'bi-exclamation-triangle' : 'bi-clock-history' }} me-1"></i>
                                        {{ $notification->data['message'] }}
                                    </div>
                                    <div style="font-size:11px;color:var(--text-muted);"><i
                                            class="bi bi-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}</div>
                                </a>
                            </li>
                        @empty
                            <li><span class="dropdown-item text-center py-3"
                                    style="font-size:12.5px;color:var(--text-muted);">No notifications</span></li>
                        @endforelse
                        @if($unreadCount > 0)
                            <li><a class="dropdown-item text-center py-2 fw-bold"
                                    style="font-size:12px;color:var(--accent);border-top:1px solid var(--border);"
                                    href="{{ route('notifications.markAllRead') }}">Mark all as read</a></li>
                        @endif
                    </ul>
                </div>
            @endauth

            <div class="topbar-divider"></div>

            {{-- Language Toggle --}}
            @php $otherLocale = app()->getLocale() === 'en' ? 'am' : 'en'; @endphp
            <a href="{{ route('language.switch', $otherLocale) }}" class="lang-toggle">
                EN / አማ <i class="bi bi-chevron-down" style="font-size:10px;"></i>
            </a>

            <div class="topbar-divider"></div>

            {{-- Theme Toggle --}}
            <button class="topbar-btn" id="theme-mode-toggle" title="Toggle dark mode">
                <i class="bi bi-moon"></i>
            </button>

            <div class="topbar-divider"></div>

            {{-- User --}}
            <div class="topbar-user">
                <span class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
            </div>

            {{-- Logout --}}
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="topbar-btn" title="{{ __('m.logout') }}">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="app-main">
        <div class="page-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>@endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        /* ── Theme Toggle ─────────────────────── */
        const html = document.documentElement;
        const modeBtn = document.getElementById('theme-mode-toggle');

        function applyTheme(theme) {
            html.setAttribute('data-bs-theme', theme);
            localStorage.setItem('theme', theme);
            if (modeBtn) {
                modeBtn.querySelector('i').className = theme === 'dark' ? 'bi bi-sun' : 'bi bi-moon';
            }
        }
        // Already applied by inline script, just sync icon
        applyTheme(localStorage.getItem('theme') || 'light');

        if (modeBtn) {
            modeBtn.addEventListener('click', (e) => {
                e.preventDefault();
                applyTheme(html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark');
            });
        }

        /* ── Mobile Sidebar Toggle ─────────────── */
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }

    </script>
    @stack('scripts')
</body>

</html>