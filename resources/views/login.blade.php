<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>User Login</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

<style>
  * {
    margin: 0; padding: 0;
    font-family: 'Inter', sans-serif;
    box-sizing: border-box;
  }
  body {
    background-color: #f8f8dc;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }
  .login-container {
    display: flex;
    width: 970px;
    height: 502.5px;
    background-color: white;
    border-radius: 30px;
    overflow: hidden;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
  }
  .form-section {
    flex: 1;
    padding: 50px 40px;
  }
  .form-section h2 {
    text-align: center;
    margin-bottom: 40px;
    font-size: 28px;
  }
  label {
    font-weight: 600;
    font-size: 14px;
  }
  .input-group {
    margin-bottom: 20px;
    position: relative;
  }
  .input-group input {
    width: 100%;
    padding: 12px 12px 12px 40px;
    margin-top: 6px;
    border: 1px solid #ccc;
    border-radius: 10px;
    font-size: 14px;
  }
  .input-group i {
    position: absolute;
    left: 12px;
    top: 47px;
    transform: translateY(-50%);
    color: #888;
    font-size: 18px;
  }
  .btn-login {
    background-color: #4CAF50;
    color: white;
    padding: 12px;
    width: 100%;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    cursor: pointer;
  }
  .btn-login:hover {
    background-color: #45a049;
  }
  .form-footer {
    margin-top: 0.5rem;
    text-align: left;
    font-size: 13px;
    color: #888;
  }
  .form-footer a {
    text-decoration: none;
    color: #888;
  }
  .form-footer a:hover {
    text-decoration: underline;
  }
  .image-section {
    flex: 1;
    background: url('/images/Login-Background.png') no-repeat center center;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .image-section .welcome-text {
    color: white;
    font-size: 32px;
    font-weight: bold;
    text-align: center;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.6);
  }
  .create-account-section {
    text-align: center;
    margin-top: 55px;
  }
  .create-account-section a {
    font-size: 16px;
    color: #7C7575;
    text-decoration: none;
    font-weight: 600;
  }
  .create-account-section i {
    margin-left: 8px;
    font-size: 18px;
    transition: transform 0.3s ease-in-out;
  }
  .create-account-section a:hover i {
    transform: translateX(5px);
  }

  /* Toast Container */
  #toast {
    position: fixed;
    top: 30px;
    left: 50%;
    transform: translateX(-50%);
    min-width: 320px;
    max-width: 90vw;
    background-color: #28a745;
    color: #fff;
    font-weight: 600;
    padding: 16px 20px;
    border-radius: 8px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.25);
    display: flex;
    align-items: center;
    gap: 15px;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.4s ease, visibility 0.4s ease;
    z-index: 9999;
  }
  #toast.error {
    background-color: #dc3545;
  }
  #toast.show {
    opacity: 1;
    visibility: visible;
  }
  #toast i {
    font-size: 20px;
  }
  #toast button.close-btn {
    margin-left: auto;
    background: transparent;
    border: none;
    color: #fff;
    font-size: 20px;
    cursor: pointer;
    outline: none;
    transition: color 0.3s ease;
  }
  #toast button.close-btn:hover {
    color: #bbb;
  }
</style>
</head>
<body>
<div class="login-container">
  <div class="form-section">
    <h2>User Login</h2>
    <form method="POST" action="{{ route('login') }}">
      @csrf
      <div class="input-group">
        <label>Email</label>
        <div>
          <i class="fa fa-user"></i>
          <input type="email" name="email" placeholder="Email" required>
        </div>
      </div>
      <div class="input-group">
        <label>Password</label>
        <div>
          <i class="fa fa-lock" id="password-icon" onclick="togglePassword()"></i>
          <input type="password" name="password" id="password" placeholder="Password" required>
        </div>
      </div>
      <button type="submit" class="btn-login">Login</button>
    </form>
    <div class="form-footer">
      <a href="{{ route('password.reset') }}" class="text-muted text-decoration-none">
        Forgot Password?
      </a>
    </div>
    <div class="create-account-section">
      <a href="{{ route('register') }}">Create Your Account<i class="fa fa-arrow-right"></i></a>
    </div>
  </div>
  <div class="image-section">
    <div class="welcome-text">HELLO,<br>WELCOME BACK</div>
  </div>
</div>

<!-- Toast container -->
<div id="toast" role="alert" aria-live="assertive" aria-atomic="true">
  <i class="fa fa-check-circle"></i>
  <span id="toast-message"></span>
  <button class="close-btn" aria-label="Close" onclick="hideToast()">&times;</button>
</div>

<script>
  function togglePassword() {
    var passwordField = document.getElementById("password");
    var passwordIcon = document.getElementById("password-icon");
    if (passwordField.type === "password") {
      passwordField.type = "text";
      passwordIcon.classList.remove("fa-lock");
      passwordIcon.classList.add("fa-unlock");
    } else {
      passwordField.type = "password";
      passwordIcon.classList.remove("fa-unlock");
      passwordIcon.classList.add("fa-lock");
    }
  }

  const toast = document.getElementById("toast");
  const toastMessage = document.getElementById("toast-message");
  const toastIcon = toast.querySelector("i");

  function showToast(message, type = 'success') {
    toastMessage.textContent = message;
    toast.classList.add("show");
    if(type === 'error'){
      toast.classList.add('error');
      toastIcon.className = 'fa fa-exclamation-circle';
    } else {
      toast.classList.remove('error');
      toastIcon.className = 'fa fa-check-circle';
    }
  }

  function hideToast() {
    toast.classList.remove("show");
  }

  // Show toast on session messages
  window.onload = function() {
    @if(session('success'))
      showToast("{{ session('success') }}", 'success');
    @elseif(session('error'))
      showToast("{{ session('error') }}", 'error');
    @endif
  };
</script>
</body>
</html>
