<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <!-- intl-tel-input CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css" />

    <style>
        /* Style sama dengan yang kamu pakai */
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
            min-height: 100vh;
        }

        .login-container {
            display: flex;
            width: 970px;
            min-height: 700px;
            background-color: white;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-section {
            flex: 1;
            padding: 40px 40px 50px;
            display: flex;
            flex-direction: column;
        }

        .form-section h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 700;
        }

        form {
            flex-grow: 1;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }

        .form-col {
            flex: 1;
        }

        label {
            font-weight: 600;
            font-size: 14px;
            display: block;
            margin-bottom: 6px;
        }

        .input-group {
            position: relative;
        }

        .input-group input,
        .input-group select {
            width: 100%;
            padding: 12px 12px 12px 40px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 14px;
        }

        .input-group i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            font-size: 18px;
            cursor: pointer;
        }

        .input-group select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-color: #fff;
            background-image: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 4 5"%3E%3Cpath fill="none" stroke="%23aaa" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M1 1l2 2 2-2"%3E%3C/path%3E%3C/svg%3E');
            background-position: right 10px center;
            background-repeat: no-repeat;
            background-size: 10px 5px;
        }

        .input-group select:focus {
            border-color: #4CAF50;
            outline: none;
        }

        .btn-login {
            background-color: #4CAF50;
            color: white;
            padding: 14px;
            width: 100%;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            font-weight: 600;
            margin-top: 10px;
        }

        .btn-login:hover {
            background-color: #45a049;
        }

        .form-footer {
            margin-top: 25px;
            text-align: center;
            font-size: 16px;
            color: #555;
        }

        .form-footer a {
            text-decoration: none;
            color: #7C7575;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            transition: color 0.3s ease;
        }

        .form-footer a i {
            margin-left: 6px;
            transition: transform 0.3s ease;
        }

        .form-footer a:hover i {
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
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.6);
        }

        .error-message {
            color: red;
            font-size: 13px;
            margin-top: 4px;
        }

        @media (max-width: 900px) {
            .login-container {
                flex-direction: column;
                width: 90vw;
                height: auto;
                border-radius: 15px;
            }

            .image-section {
                height: 200px;
                order: -1;
                border-radius: 15px 15px 0 0;
            }

            .form-section {
                padding: 20px;
            }

            .form-row {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="form-section">
            <h2>Register</h2>
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Row 1: Name + Email -->
                <div class="form-row">
                    <div class="form-col">
                        <label for="name_customers">Name</label>
                        <div class="input-group">
                            <i class="fa fa-user"></i>
                            <input type="text" id="name_customers" name="name_customers"
                                value="{{ old('name_customers') }}" placeholder="Name" required>
                        </div>
                        @error('name_customers')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-col">
                        <label for="email">Email</label>
                        <div class="input-group">
                            <i class="fa fa-envelope"></i>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                placeholder="Email" required>
                        </div>
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Row 2: Phone Number + Date of Birth -->
                <div class="form-row">
                    <div class="form-col">
                        <label for="phone_number">Phone Number</label>
                        <div class="input-group">
                            <i class="fa fa-phone"></i>
                            <input type="tel" id="phone_number" name="phone_number"
                                value="{{ old('phone_number') }}" placeholder="Phone number" required>
                        </div>
                        @error('phone_number')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-col">
                        <label for="dob">Date of Birth</label>
                        <div class="input-group">
                            <i class="fa fa-calendar-alt"></i>
                            <input type="date" id="dob" name="dob" value="{{ old('dob') }}" required>
                        </div>
                        @error('dob')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Row 3: Gender + Address -->
                <div class="form-row">
                    <div class="form-col">
                        <label for="gender">Gender</label>
                        <div class="input-group">
                            <i class="fa fa-venus-mars"></i>
                            <select id="gender" name="gender" required>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female
                                </option>
                            </select>
                        </div>
                        @error('gender')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-col">
                        <label for="address">Address</label>
                        <div class="input-group">
                            <i class="fa fa-address-card"></i>
                            <input type="text" id="address" name="address" value="{{ old('address') }}"
                                placeholder="Address" required>
                        </div>
                        @error('address')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Row 4: Province + City -->
                <div class="form-row">
                    <div class="form-col">
                        <label for="province">Province</label>
                        <div class="input-group">
                            <i class="fa fa-map-marker-alt"></i>
                            <select id="province" name="province" required>
                                <option value="">-- Select Province --</option>
                            </select>
                        </div>
                        @error('province')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-col">
                        <label for="city">City</label>
                        <div class="input-group">
                            <i class="fa fa-city"></i>
                            <select id="city" name="city" required>
                                <option value="">-- Select City --</option>
                            </select>
                        </div>
                        @error('city')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Row 5: Password + Confirm Password -->
                <div class="form-row">
                    <div class="form-col">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <i class="fa fa-lock" id="password-icon"
                                onclick="togglePassword('password', 'password-icon')"></i>
                            <input type="password" id="password" name="password" placeholder="Password" required>
                        </div>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-col">
                        <label for="password_confirmation">Confirm Password</label>
                        <div class="input-group">
                            <i class="fa fa-lock" id="confirm-password-icon"
                                onclick="togglePassword('confirm-password', 'confirm-password-icon')"></i>
                            <input type="password" id="confirm-password" name="password_confirmation"
                                placeholder="Confirm password" required>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-login">Register</button>

            </form>

            <div class="form-footer">
                Have an Account? <a href="{{ route('login') }}">Login <i class="fa fa-arrow-right"></i></a>
            </div>
        </div>

        <div class="image-section">
            <div class="welcome-text">HELLO,<br>WELCOME BACK</div>
        </div>
    </div>

    <!-- intl-tel-input JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

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
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInputField = document.querySelector("#phone_number");
            const phoneInput = window.intlTelInput(phoneInputField, {
                initialCountry: "id",
                separateDialCode: true,
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            });

            const toast = document.getElementById("toast");
            const toastMessage = document.getElementById("toast-message");
            const toastIcon = toast.querySelector("i");

            function showToast(message, type = 'success') {
                toastMessage.textContent = message;
                toast.classList.add("show");
                if (type === 'error') {
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

            // Hardcoded data provinsi dan kota
            const provinces = [{
                    id: '1',
                    name: 'Jawa Barat',
                    cities: ['Bandung', 'Bogor', 'Depok', 'Sukabumi']
                },
                {
                    id: '2',
                    name: 'Jawa Timur',
                    cities: ['Surabaya', 'Malang', 'Kediri', 'Mojokerto']
                },
                {
                    id: '3',
                    name: 'DKI Jakarta',
                    cities: ['Jakarta Selatan', 'Jakarta Utara', 'Jakarta Pusat', 'Jakarta Barat']
                },
                {
                    id: '4',
                    name: 'Bali',
                    cities: ['Denpasar', 'Ubud', 'Kuta', 'Seminyak']
                },
                // Tambahkan provinsi & kota lain sesuai kebutuhan
            ];

            const provinceSelect = document.getElementById('province');
            const citySelect = document.getElementById('city');

            // Load provinsi ke dropdown
            provinces.forEach(province => {
                const option = document.createElement('option');
                option.value = province.id;
                option.text = province.name;
                provinceSelect.appendChild(option);
            });

            // Load kota saat provinsi berubah
            provinceSelect.addEventListener('change', () => {
                const selectedId = provinceSelect.value;
                citySelect.innerHTML = '<option value="">-- Select City --</option>';

                const province = provinces.find(p => p.id === selectedId);
                if (province) {
                    province.cities.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city;
                        option.text = city;
                        citySelect.appendChild(option);
                    });
                }
            });

            // Form submit handler
            const form = document.querySelector("form");
            form.addEventListener("submit", function(e) {
                if (!phoneInput.isValidNumber()) {
                    e.preventDefault();
                    showToast("Please enter a valid phone number.", 'error');
                } else {
                    phoneInputField.value = phoneInput.getNumber();
                }
            });

            // Show toast on session messages from backend (success or error)
            window.onload = function() {
                @if (session('success'))
                    showToast("{{ session('success') }}", 'success');
                @elseif (session('error'))
                    showToast("{{ session('error') }}", 'error');
                @endif

                // Restore old input if exists
                @if (old('province'))
                    provinceSelect.value = "{{ old('province') }}";
                    provinceSelect.dispatchEvent(new Event('change'));
                @endif
                @if (old('city'))
                    setTimeout(() => {
                        citySelect.value = "{{ old('city') }}";
                    }, 500);
                @endif
            };
        });
    </script>

    <!-- Toast container -->
    <div id="toast" role="alert" aria-live="assertive" aria-atomic="true"
        style="position: fixed; top: 30px; left: 50%; transform: translateX(-50%);
            min-width: 320px; max-width: 90vw; background-color: #28a745; color: #fff;
            font-weight: 600; padding: 16px 20px; border-radius: 8px; box-shadow: 0 6px 15px rgba(0,0,0,0.25);
            display: flex; align-items: center; gap: 15px; opacity: 0; visibility: hidden; transition: opacity 0.4s ease, visibility 0.4s ease; z-index: 9999;">
        <i class="fa fa-check-circle"></i>
        <span id="toast-message"></span>
        <button class="close-btn" aria-label="Close"
            onclick="document.getElementById('toast').classList.remove('show')">&times;</button>
    </div>
</body>

</html>
