<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }
    body {
      display: flex;
      height: 100vh;
    }
    .left-panel {
      flex: 1;
      background-color: #1F2937;
      color: white;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      /* padding: 40px; */
    }
    .left-panel img {
      width: 50%;
      margin-bottom: 20px;
      border-radius: 10%;
    }
    .right-panel {
      flex: 1;
      background-color: #F9FAFB;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 60px;
    }
    .login-box {
      width: 100%;
      max-width: 400px;
    }
    .login-box h2 {
      font-size: 28px;
      margin-bottom: 30px;
      color: #1F2937;
    }
    .login-box label {
      font-size: 14px;
      color: #374151;
    }
    .login-box input[type="email"],
    .login-box input[type="password"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0 20px 0;
      border: 1px solid #D1D5DB;
      border-radius: 8px;
    }
    .login-options {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      font-size: 14px;
    }
    .login-options a {
      color: #1F2937;
      text-decoration: none;
    }
    .login-box button {
      width: 100%;
      background-color: #1F2937;
      color: white;
      padding: 12px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    .login-box button:hover {
      background-color: #111827;
    }
    .text-center {
      text-align: center;
      margin-top: 15px;
      font-size: 14px;
    }
    .text-center a {
      color: #1F2937;
      text-decoration: none;
      font-weight: 600;
    }
  </style>
</head>
<body>

  <div class="left-panel">
    <img src="{{ asset('img/logo.jpg') }}" alt="Logo" width="500">
  </div>

  <div class="right-panel">
    <div class="login-box">
      <h2>Masuk</h2>
      <form method="POST" action="{{ route('login') }}">
        @csrf

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required placeholder="example@domain.com" value="{{ old('email') }}">
        
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required placeholder="********">
        
        <div class="login-options">
          <label><input type="checkbox" name="remember"> Ingat saya</label>
          <a href="{{ route('password.request') }}">Lupa password?</a>
        </div>
        
        <button type="submit">Masuk</button>
        <p class="text-center">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
      </form>
    </div>
  </div>

</body>
</html>