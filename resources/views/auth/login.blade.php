<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - TmcsSmart</title>
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
      max-width: 400px;
      padding: 2rem;
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
  </style>
</head>
<body>
  <div class="login-container">
    <div class="brand-logo">TM</div>
    
    <h2 style="text-align: center; margin-bottom: 0.5rem;">Welcome Back</h2>
    <p style="text-align: center; color: var(--text-secondary); margin-bottom: 2rem;">Sign in to TmcsSmart Church Management System</p>
    
    <form method="POST" action="{{ route('login.post') }}">
      @csrf
      
      <div>
        <input type="email" name="email" class="form-control" placeholder="Email address" required autofocus>
        @error('email')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>
      
      <div>
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        @error('password')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>
      
      <button type="submit" class="btn">Sign In</button>
    </form>
    
    <div style="text-align: center; margin-top: 2rem; color: var(--text-secondary); font-size: 12px;">
      <p>© 2026 TmcsSmart Church Management System</p>
    </div>
  </div>
</body>
</html>
