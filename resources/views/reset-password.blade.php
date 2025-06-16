<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
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
            width: 900px;
            height: 520px;
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
            cursor: pointer;
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
            margin-top: 10px;
        }

        .btn-login:hover {
            background-color: #45a049;
        }

        .back-to-login {
            margin-top: 25px;
            text-align: center;
            font-size: 16px;
        }

        .back-to-login a {
            color: #7C7575;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease-in-out;
        }

        .back-to-login i {
            margin-left: 6px;
            transition: transform 0.3s ease-in-out;
        }

        .back-to-login a:hover i {
            transform: translateX(5px);
        }

        .image-section {
            flex: 1;
            background: url('/images/Login-Background.png') no-repeat center center;
            background-size: cover;
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
    </style>
</head>
<body>
    <div class="login-container">
        <div class="form-section">
            <h2>Reset Password</h2>
            @if($errors->any())
         <div class="alert alert-danger">
    <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
            <form method="POST" action="{{ route('password.reset') }}">
                @csrf
                <div class="input-group">
                    <label>Email</label>
                    <div>
                        <i class="fa fa-envelope"></i>
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                </div> 
                <div class="input-group">
                    <label>New Password</label>
                    <div>
                        <i class="fa fa-lock" id="toggle-password" onclick="togglePassword('new-password', 'toggle-password')"></i>
                        <input type="password" name="new_password" id="new-password" placeholder="New Password" required>
                    </div>
                </div>
                <div class="input-group">
                    <label>Confirm Password</label>
                    <div>
                        <i class="fa fa-lock" id="toggle-confirm-password" onclick="togglePassword('confirm-password', 'toggle-confirm-password')"></i>
                        <input type="password" name="new_password_confirmation" id="confirm-password" placeholder="Confirm Password" required>
                    </div>
                </div>
                <button type="submit" class="btn-login">Reset Password</button>
            </form>
            <div class="back-to-login">
                <a href="{{ route('login') }}">Back To Login <i class="fa fa-arrow-right"></i></a>
            </div>
        </div>
        <div class="image-section">
            <div class="welcome-text">HELLO,<br>WELCOME BACK</div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            var input = document.getElementById(inputId);
            var icon = document.getElementById(iconId);

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-lock");
                icon.classList.add("fa-unlock");
            } else {
                input.type = "password";
                icon.classList.remove("fa-unlock");
                icon.classList.add("fa-lock");
            }
        }
    </script>
</body>
</html>
