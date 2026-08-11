<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('m.login') }} — Hotel POS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+Ethiopic:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-app: #f5f6f8; --bg-surface: #ffffff; --border: #e8eaed;
            --text-primary: #1a1d21; --text-secondary: #5f6368; --text-muted: #9aa0a6;
            --accent: #1a73e8;
        }
        [data-bs-theme="dark"] {
            --bg-app: #1a1a1a; --bg-surface: #242424; --border: #333;
            --text-primary: #e8eaed; --text-secondary: #9aa0a6; --text-muted: #6b7280;
            --accent: #8ab4f8;
        }
        body {
            font-family: 'Inter', 'Noto Sans Ethiopic', system-ui, sans-serif;
            background: var(--bg-app);
            color: var(--text-primary);
            display: flex; align-items: center; justify-content: center;
            min-height: 100vh; margin: 0;
            -webkit-font-smoothing: antialiased;
        }
        .login-card {
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 36px 32px; width: 100%; max-width: 380px;
        }
        .login-card .brand-icon { color: var(--accent); font-size: 28px; }
        .login-card h2 { font-size: 20px; font-weight: 700; margin: 8px 0 4px; }
        .login-card p { font-size: 13px; color: var(--text-muted); margin-bottom: 20px; }
        .form-label { font-size: 12.5px; font-weight: 500; color: var(--text-secondary); }
        .form-control {
            font-size: 13px; padding: 9px 12px;
            border: 1px solid var(--border); border-radius: 6px;
            background: var(--bg-surface); color: var(--text-primary);
        }
        .form-control:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(26,115,232,.1); }
        .btn-primary {
            background: var(--accent); border-color: var(--accent);
            border-radius: 6px; padding: 10px;
            font-size: 13.5px; font-weight: 600; width: 100%;
        }
        .btn-primary:hover { opacity: .88; }
        .form-check-label { font-size: 12.5px; color: var(--text-secondary); }
        .theme-toggle {
            position: absolute; top: 20px; right: 20px;
            background: none; border: none; color: var(--text-muted);
            font-size: 18px; cursor: pointer;
        }
        .theme-toggle:hover { color: var(--text-primary); }
    </style>
    <script>
        (function(){ const t = localStorage.getItem('theme') || 'light'; document.documentElement.setAttribute('data-bs-theme', t); })();
    </script>
</head>
<body>
    <button class="theme-toggle" id="loginThemeToggle"><i class="bi bi-moon"></i></button>
    <div class="login-card">
        <div class="text-center">
            <i class="bi bi-building brand-icon"></i>
            <h2>Hotel POS</h2>
            <p>{{ __('m.login') }}</p>
        </div>

        @if($errors->any())
        <div class="alert alert-danger py-2" style="font-size:12.5px;border-radius:6px;">
            @foreach($errors->all() as $error){{ $error }}@endforeach
        </div>
        @endif

        <form method="POST" action="/login">
            @csrf
            <div class="mb-3">
                <label class="form-label">{{ __('m.email') }}</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label">{{ __('m.password') }}</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="remember" style="border-color:var(--border);">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
            <button type="submit" class="btn btn-primary">{{ __('m.login') }}</button>
        </form>

        <div class="text-center mt-3">
            @php $otherLocale = app()->getLocale() === 'en' ? 'am' : 'en'; @endphp
            <a href="{{ route('language.switch', $otherLocale) }}" style="font-size:12.5px;color:var(--accent);">
                {{ app()->getLocale() === 'en' ? 'አማርኛ' : 'English' }}
            </a>
        </div>
    </div>
    <script>
        const btn = document.getElementById('loginThemeToggle');
        function setT(t) {
            document.documentElement.setAttribute('data-bs-theme', t);
            localStorage.setItem('theme', t);
            btn.querySelector('i').className = t === 'dark' ? 'bi bi-sun' : 'bi bi-moon';
        }
        setT(localStorage.getItem('theme') || 'light');
        btn.addEventListener('click', () => setT(document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark'));
    </script>
</body>
</html>
