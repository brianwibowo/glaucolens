<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Terima Kasih</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #d0f0c0, #f9f9f9);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .thankyou-card {
      border: none;
      border-radius: 1.5rem;
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
      background: #fff;
      padding: 3rem;
      animation: fadeInUp 1s ease-out;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(40px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .thankyou-icon {
      width: 80px;
      height: 80px;
      margin-bottom: 1.2rem;
    }

    .btn-logout {
      border-radius: 30px;
      padding: 0.5rem 2rem;
      font-weight: 500;
    }
  </style>
</head>
<body>

  <div class="container vh-100 d-flex justify-content-center align-items-center">
    <div class="thankyou-card text-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="thankyou-icon text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
      </svg>
      <h1 class="mb-3 text-success">Terima Kasih, {{ Auth::user()->name }}!</h1>
      <p class="lead">
        @if (Auth::user()->getRoleNames()->first() !== 'user')
          Pengajuan akun sebagai <strong>{{ Auth::user()->getRoleNames()->first() }}</strong> berhasil.<br>
          Silakan login ulang untuk melanjutkan.
          <form action="{{ route('logout') }}" method="post" class="mt-4">
            @csrf
            <button type="submit" class="btn btn-danger btn-logout">Keluar & Login Ulang</button>
          </form>
        @else
          Pengajuan akun berhasil. Mohon tunggu konfirmasi dari admin kami.
        @endif
      </p>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
