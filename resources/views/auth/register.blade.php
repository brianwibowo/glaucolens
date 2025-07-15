<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register - Dashboard Perusahaan</title>
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
    .register-box {
      width: 100%;
      max-width: 450px;
    }
    .register-box h2 {
      font-size: 28px;
      margin-bottom: 30px;
      color: #1F2937;
    }
    .register-box label {
      font-size: 14px;
      color: #374151;
    }
    .register-box input[type="text"],
    .register-box input[type="email"],
    .register-box input[type="password"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0 20px 0;
      border: 1px solid #D1D5DB;
      border-radius: 8px;
    }
    .register-box button {
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
    .register-box button:hover {
      background-color: #111827;
    }
    .register-box .bottom-text {
      text-align: center;
      margin-top: 16px;
      font-size: 14px;
    }
    .register-box .bottom-text a {
      color: #1F2937;
      text-decoration: none;
    }
  </style>
</head>
<body>
    <div class="left-panel">
        <img src="{{ asset('img/logo.jpg') }}" alt="Logo" width="500">
      </div>

  <div class="right-panel">
    <div class="register-box">
      <h2>Buat Akun Baru</h2>

      <!-- Tempat menampilkan validasi error -->
      <!-- Simulasi error, bisa diganti dengan Laravel Blade jika pakai server-side -->
      <!-- <div class="error">Email sudah terdaftar</div> -->

      <form method="POST" action="{{ route('register') }}">
        @csrf

        <label for="name">Nama Lengkap</label>
        <input type="text" id="name" name="name" required placeholder="Nama Lengkap" value="{{ old('name') }}">

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required placeholder="email@example.com" value="{{ old('email') }}">

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required placeholder="Minimal 8 karakter">

        <label for="password_confirmation">Konfirmasi Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi password">

        <button type="submit">Daftar</button>
      </form>

      <div class="bottom-text">
        Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a>
      </div>
    </div>
  </div>

</body>
</html>
