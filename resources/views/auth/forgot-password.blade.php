<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lupa Password</title>
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
      padding: 40px;
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
    .form-box {
      width: 100%;
      max-width: 400px;
    }
    .form-box h2 {
      font-size: 24px;
      margin-bottom: 20px;
      color: #1F2937;
    }
    .form-box p {
      font-size: 14px;
      color: #4B5563;
      margin-bottom: 20px;
    }
    .form-box label {
      font-size: 14px;
      color: #374151;
    }
    .form-box input[type="email"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0 20px 0;
      border: 1px solid #D1D5DB;
      border-radius: 8px;
    }
    .form-box button {
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
    .form-box button:hover {
      background-color: #111827;
    }
    .status, .errors {
      font-size: 14px;
      margin-bottom: 20px;
      padding: 10px;
      border-radius: 6px;
    }
    .status {
      background-color: #D1FAE5;
      color: #065F46;
    }
    .errors {
      background-color: #FEE2E2;
      color: #B91C1C;
    }
  </style>
</head>
<body>
    <div class="left-panel">
        <img src="{{ asset('img/logo.jpg') }}" alt="Logo" width="500">
      </div>

  <div class="right-panel">
    <div class="form-box">
      <h2>Reset Password</h2>
      <p>Kami akan mengirimkan link untuk mereset password ke email Anda.</p>

      <!-- Status Pesan -->
      @if (session('status'))
        <div class="status">
          {{ session('status') }}
        </div>
      @endif

      <!-- Error Validasi -->
      @if ($errors->any())
        <div class="errors">
          <ul style="list-style: none; padding-left: 0;">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <label for="email">Alamat Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="example@domain.com">

        <button type="submit">Kirim Link Reset Password</button>
      </form>
    </div>
  </div>

</body>
</html>
