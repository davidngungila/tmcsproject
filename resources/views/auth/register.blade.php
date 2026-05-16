<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Public Registration - TmcsSmart</title>
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
      padding: 2rem 0;
    }
    h1,h2,h3,h4,h5 { font-family: 'Sora', sans-serif; }
    .registration-container {
      width: 100%;
      max-width: 800px;
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
    .card {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.05);
      overflow: hidden;
    }
    .form-control {
      width: 100%;
      background: var(--bg-base);
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 12px 16px;
      font-size: 14px;
      color: var(--text-primary);
      margin-bottom: 0.5rem;
    }
    .form-control:focus {
      outline: none;
      border-color: var(--green-500);
      box-shadow: 0 0 0 3px rgba(5,150,105,0.12);
    }
    .form-label {
      display: block;
      font-size: 12px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: var(--text-secondary);
      margin-bottom: 0.5rem;
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
      margin-bottom: 1rem;
    }
    .grid-cols-2 {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1.5rem;
    }
    @media (max-width: 640px) {
      .grid-cols-2 {
        grid-template-columns: 1fr;
      }
    }
    .group-selection {
      max-height: 200px;
      overflow-y: auto;
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 10px;
      background: var(--bg-base);
    }
    .group-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 8px;
      border-bottom: 1px solid var(--border);
      cursor: pointer;
    }
    .group-item:last-child { border-bottom: none; }
    .group-item input { width: 16px; height: 16px; }
  </style>
</head>
<body>
  <div class="registration-container">
    <div class="brand-logo">TM</div>
    
    <h2 style="text-align: center; margin-bottom: 0.5rem;">Join TmcsSmart</h2>
    <p style="text-align: center; color: var(--text-secondary); margin-bottom: 2rem;">Register your self to our Church Management System</p>
    
    <div class="card">
      <div style="padding: 2rem;">
        <form method="POST" action="{{ route('register.post') }}">
          @csrf
          
          <div class="grid-cols-2">
            <div>
              <label class="form-label">Full Name *</label>
              <input type="text" name="full_name" class="form-control" placeholder="Your full name" value="{{ old('full_name') }}" required>
              @error('full_name') <div class="error">{{ $message }}</div> @enderror
            </div>
            
            <div>
              <label class="form-label">Email Address *</label>
              <input type="email" name="email" class="form-control" placeholder="email@example.com" value="{{ old('email') }}" required>
              @error('email') <div class="error">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="grid-cols-2" style="margin-top: 1rem;">
            <div>
              <label class="form-label">Phone Number *</label>
              <input type="text" name="phone" class="form-control" placeholder="e.g. 0712345678" value="{{ old('phone') }}" required>
              @error('phone') <div class="error">{{ $message }}</div> @enderror
            </div>
            
            <div>
              <label class="form-label">Category *</label>
              <select name="category_id" class="form-control" required>
                <option value="">Select Category</option>
                @foreach($categories as $category)
                  <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
              </select>
              @error('category_id') <div class="error">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="grid-cols-2" style="margin-top: 1rem;">
            <div>
              <label class="form-label">Gender *</label>
              <select name="gender" class="form-control" required>
                <option value="">Select Gender</option>
                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
              </select>
              @error('gender') <div class="error">{{ $message }}</div> @enderror
            </div>
            
            <div>
              <label class="form-label">Date of Birth *</label>
              <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}" required>
              @error('date_of_birth') <div class="error">{{ $message }}</div> @enderror
            </div>
          </div>

          <div style="margin-top: 1rem;">
            <label class="form-label">Home Address *</label>
            <textarea name="address" class="form-control" rows="2" placeholder="Street, Ward, District..." required>{{ old('address') }}</textarea>
            @error('address') <div class="error">{{ $message }}</div> @enderror
          </div>

          <div style="margin-top: 1rem;">
            <label class="form-label">Join Communities / Groups</label>
            <p style="font-size: 11px; color: var(--text-secondary); margin-bottom: 0.5rem;">Select any existing community you wish to join.</p>
            <div class="group-selection">
              @foreach($groups as $group)
              <label class="group-item">
                <input type="checkbox" name="groups[]" value="{{ $group->id }}" {{ is_array(old('groups')) && in_array($group->id, old('groups')) ? 'checked' : '' }}>
                <div>
                  <div style="font-size: 13px; font-weight: 600;">{{ $group->name }}</div>
                  <div style="font-size: 11px; color: var(--text-secondary);">{{ $group->type }}</div>
                </div>
              </label>
              @endforeach
            </div>
          </div>

          <div class="grid-cols-2" style="margin-top: 1.5rem;">
            <div>
              <label class="form-label">Password *</label>
              <input type="password" name="password" class="form-control" placeholder="Min 8 characters" required>
              @error('password') <div class="error">{{ $message }}</div> @enderror
            </div>
            
            <div>
              <label class="form-label">Confirm Password *</label>
              <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
            </div>
          </div>
          
          <div style="margin-top: 2rem;">
            <button type="submit" class="btn">Register My Account</button>
          </div>
        </form>
      </div>
      <div style="background: var(--bg-base); padding: 1.5rem; text-align: center; border-top: 1px solid var(--border);">
        <p style="font-size: 13px; color: var(--text-secondary);">Already have an account? <a href="{{ route('login') }}" style="color: var(--green-600); font-weight: 600; text-decoration: none;">Sign In here</a></p>
      </div>
    </div>
    
    <div style="text-align: center; margin-top: 2rem; color: var(--text-secondary); font-size: 12px;">
      <p>© 2026 TmcsSmart Church Management System</p>
    </div>
  </div>
</body>
</html>