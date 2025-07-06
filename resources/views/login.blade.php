<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f7f7f7;
      font-family: Arial, sans-serif;
      text-align: center;
      padding-top: 40px;
    }
    .logo {
      font-size: 2.5rem;
      font-weight: bold;
    }
    .free {
      color: #7c3aed; /* Purple tone */
    }
    .class {
      color: #000000;
    }
    .tagline {
      color: #7c3aed;
      font-weight: 1000;
      margin-bottom: 30px;
    }
    .login-card {
      background-color: #c9a7ff;
      padding: 30px;
      border-radius: 15px;
      max-width: 400px;
      margin: auto;
      color: white;
      margin-bottom: 20px;
    }
    .form-control {
      background-color: transparent;
      border: none;
      border-bottom: 2px solid white;
      border-radius: 0;
      color: white;
    }
    .form-control:focus {
      box-shadow: none;
      border-color: #fff;
    }
    .login-btn {
      background-color: #000;
      color: white;
      border-radius: 10px;
      padding: 10px 0;
      margin-top: 20px;
    }

  </style>
</head>
<body>
  <div class="container">
    <div class="logo">
        <img src="{{ asset('assets/FREECLASS-LOGO.png') }}" alt="freeclass" width="200" height="200">
    </div>
    <div class="tagline">Reserve With Ease, Learn In Peace</div>

    <div class="login-card">
      <h4 class="mb-4"><strong>ADMIN LOGIN</strong></h4>
      <form method="POST" action="{{ route('login.submit') }}">
        @csrf
        <div class="mb-3">
          <input type="text" class="form-control" placeholder="Enter Your ID">
        </div>
        <div class="mb-4">
          <input type="password" class="form-control" placeholder="Enter Your Password">
        </div>
        <a class="btn bg-black w-100 text-white" type="submit" href="http://127.0.0.1:8000/profile/dashboard" role="button">Login</a>
      </form>
    </div>
  </div>

  
</body>
</html>
