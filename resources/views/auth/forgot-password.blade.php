<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Forgot Password - TmcsSmart</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,400&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
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
      --bg-base: #f0f9f4;
      --bg-card: #ffffff;
      --text-primary: #0a1a12;
      --text-secondary: #3d6b54;
      --border: #c6e8d7;
    }
    [data-theme="dark"] {
      --bg-base: #031a10;
      --bg-card: #052819;
      --text-primary: #e2f5eb;
      --text-secondary: #7ecfa0;
      --border: #0e4a2e;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--bg-base);
      color: var(--text-primary);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    h1,h2,h3,h4,h5 { font-family: 'Sora', sans-serif; }
    .login-container {
      width: 100%;
      max-width: 420px;
      padding: 1.5rem;
    }
    .card {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 24px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.04);
      padding: 2.5rem 2rem;
    }
    @media (max-width: 480px) {
      .login-container {
        padding: 1rem;
      }
      .card {
        padding: 2rem 1.5rem;
      }
    }
    .brand-logo {
      width: 60px; height: 60px;
      background: linear-gradient(135deg, var(--green-500), var(--green-300));
      border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      font-family: 'Sora', sans-serif;
      font-weight: 800; font-size: 24px; color: #fff;
      margin: 0 auto 1.5rem;
    }
    .form-control {
      width: 100%;
      background: var(--bg-base);
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 12px 16px;
      font-size: 14px;
      color: var(--text-primary);
      margin-bottom: 1rem;
    }
    .form-control:focus {
      outline: none;
      border-color: var(--green-500);
      box-shadow: 0 0 0 3px rgba(5,150,105,0.12);
    }
    .btn {
      width: 100%;
      background: linear-gradient(135deg, var(--green-600), var(--green-700));
      color: #fff;
      border: none;
      border-radius: 10px;
      padding: 12px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: all .15s;
    }
    .btn:hover {
      background: linear-gradient(135deg, var(--green-500), var(--green-600));
      transform: translateY(-1px);
    }
    .error {
      color: #ef4444;
      font-size: 12px;
      margin-top: -0.5rem;
      margin-bottom: 1rem;
    }
    .alert {
      padding: 1rem;
      border-radius: 10px;
      margin-bottom: 1.5rem;
      font-size: 13px;
    }
    .alert-danger {
      background: #fef2f2;
      color: #991b1b;
      border: 1px solid #fee2e2;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="card">
      <div class="brand-logo">TM</div>
      
      <h2 style="text-align: center; margin-bottom: 0.5rem; font-weight: 800; font-size: 1.5rem;">Reset Password</h2>
      <p style="text-align: center; color: var(--text-secondary); margin-bottom: 2rem; font-size: 0.875rem; line-height: 1.5;">Enter your email address and we'll send you a new password.</p>
      
      @if(session('error'))
        <div class="alert alert-danger">
          {{ session('error') }}
        </div>
      @endif

      <form method="POST" action="{{ route('password.email') }}">
        @csrf
        
        <div style="margin-bottom: 1.5rem;">
          <label style="display: block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-secondary); margin-bottom: 0.5rem;">Email Address</label>
          <input type="email" name="email" class="form-control" placeholder="Enter your registered email" required autofocus style="margin-bottom: 0;">
          @error('email')
            <div class="error" style="margin-top: 0.5rem; margin-bottom: 0;">{{ $message }}</div>
          @enderror
        </div>
        
        <button type="submit" class="btn" style="padding: 14px; font-size: 15px; font-weight: 700;">Send New Password</button>
      </form>

      <div style="text-align: center; margin-top: 2rem; font-size: 13px;">
        <p style="color: var(--text-secondary);">Remember your password? <a href="{{ route('login') }}" style="color: var(--green-600); font-weight: 700; text-decoration: none;">Sign In here</a></p>
      </div>
    </div>
    
    <div style="text-align: center; margin-top: 2rem; color: var(--text-secondary); font-size: 11px; font-weight: 500; opacity: 0.8;">
      <p>© 2026 TmcsSmart Church Management System</p>
    </div>
  </div>
</body>
</html>
