<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Register Seller</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css" />

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
            min-height: 100vh;
            padding: 20px;
        }

        .login-container {
            display: flex;
            width: 1200px;
            max-width: 100%;
            background-color: white;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-section {
            flex: 1;
            padding: 40px 30px;
        }

        .form-section h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
        }

        form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px 30px;
            max-width: 700px;
            margin: auto;
        }

        /* Full width items (file upload, buttons) */
        form>.full-width {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 14px;
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
            left: 10px;
            margin-top: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            font-size: 18px;
        }

        .input-file-container input[type="file"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 14px;
            background-color: white;
            color: #555;
            cursor: pointer;
        }

        .btn-login {
            background-color: #4CAF50;
            color: white;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .btn-login:hover {
            background-color: #45a049;
        }

        .form-footer {
            margin-top: 20px;
            text-align: center;
            font-size: 16px;
            color: #555;
            grid-column: 1 / -1;
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
            padding: 0 20px;
        }

        #toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #28a745;
            /* hijau default success */
            color: #fff;
            font-weight: 600;
            padding: 16px 20px;
            border-radius: 8px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.25);
            display: flex;
            align-items: center;
            gap: 15px;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.4s ease, visibility 0.4s ease;
            z-index: 9999;
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


        /* Responsive for mobile */
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                height: auto;
            }

            form {
                grid-template-columns: 1fr;
                gap: 15px 0;
            }

            .form-footer {
                grid-column: auto;
            }

            .image-section {
                height: 200px;
            }
        }

        /* Error message */
        .error-message {
            color: red;
            font-size: 13px;
            margin-top: 4px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="form-section">
            <h2>Register Seller</h2>
            <form action="{{ route('seller.register.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="input-group">
                    <label>Name</label>
                    <i class="fa fa-user"></i>
                    <input type="text" name="name_sellers" value="{{ old('name_sellers') }}" placeholder="Name"
                        required />
                    @error('name_sellers')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-group">
                    <label>Email</label>
                    <i class="fa fa-envelope"></i>
                    <input type="email" name="email" value="{{ Auth::user()->email ?? old('email') }}"
                        placeholder="Email" readonly />
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-group">
                    <label>Phone Number</label>
                    <i class="fa fa-phone"></i>
                    <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number') }}"
                        placeholder="Phone number" required />
                    @error('phone_number')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-group">
                    <label>Address</label>
                    <i class="fa fa-address-card"></i>
                    <input type="text" name="address" value="{{ old('address') }}" placeholder="Address" required />
                    @error('address')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-group">
                    <label>Province</label>
                    <i class="fa fa-map-marker-alt"></i>
                    <select id="province" name="province" required>
                        <option value="">-- Select Province --</option>
                        <!-- Akan diisi dengan JS -->
                    </select>
                    @error('province')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-group">
                    <label>City</label>
                    <i class="fa fa-city"></i>
                    <select id="city" name="city" required>
                        <option value="">-- Select City --</option>
                        <!-- Akan diisi dengan JS -->
                    </select>
                    @error('city')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-group">
                    <label>Gender</label>
                    <i class="fa fa-venus-mars"></i>
                    <select name="gender" required>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                    @error('gender')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-group">
                    <label>Store Name</label>
                    <i class="fa fa-store"></i>
                    <input type="text" name="store_name" value="{{ old('store_name') }}" placeholder="Store Name"
                        required />
                    @error('store_name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <i class="fa fa-lock" id="password-icon" onclick="togglePassword('password', 'password-icon')"></i>
                    <input type="password" name="password" id="password" placeholder="Password" required />
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-group">
                    <label>Confirm Password</label>
                    <i class="fa fa-lock" id="confirm-password-icon"
                        onclick="togglePassword('confirm-password', 'confirm-password-icon')"></i>
                    <input type="password" name="password_confirmation" id="confirm-password"
                        placeholder="Confirm password" required />
                </div>

                <div class="input-file-container full-width">
                    <label for="ktp_image">Upload KTP:</label>
                    <input type="file" name="ktp_image" id="ktp_image" accept="image/*" required />
                    @error('ktp_image')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="full-width">
                    <button type="submit" class="btn-login">Register</button>
                </div>
            </form>

            <div class="form-footer full-width">
                Have an Account? <a href="{{ route('login') }}">Login <i class="fa fa-arrow-right"></i></a>
            </div>
        </div>

        <div class="image-section">
            <div class="welcome-text">HELLO,<br />WELCOME BACK</div>
        </div>
    </div>

    <div id="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <i class="fa fa-check-circle"></i>
        <span id="toast-message"></span>
        <button class="close-btn" aria-label="Close" onclick="hideToast()">&times;</button>
    </div>

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

        const phoneInputField = document.querySelector("#phone_number");
        const phoneInput = window.intlTelInput(phoneInputField, {
            initialCountry: "id",
            separateDialCode: true,
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        });

        const form = document.querySelector("form");
        const toast = document.getElementById("toast");
        const toastMessage = document.getElementById("toast-message");

        form.addEventListener("submit", function(e) {
            if (!phoneInput.isValidNumber()) {
                e.preventDefault();
                showToast("Please enter a valid phone number.", false);
            } else {
                phoneInputField.value = phoneInput.getNumber();
            }
        });

        function showToast(message, success = true) {
            toastMessage.textContent = message;
            toast.style.backgroundColor = success ? "#28a745" : "#dc3545";
            toast.querySelector("i").className = success ? "fa fa-check-circle" : "fa fa-exclamation-circle";
            toast.classList.add("show");
            setTimeout(() => {
                toast.classList.remove("show");
            }, 3500);
        }

        function hideToast() {
            toast.classList.remove("show");
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Data statis provinsi dan kota
            const provinces = [
                { id: '1', name: 'Jawa Barat', cities: ['Bandung', 'Bogor', 'Depok', 'Sukabumi'] },
                { id: '2', name: 'Jawa Timur', cities: ['Surabaya', 'Malang', 'Kediri', 'Mojokerto'] },
                { id: '3', name: 'DKI Jakarta', cities: ['Jakarta Selatan', 'Jakarta Utara', 'Jakarta Pusat', 'Jakarta Barat'] },
                { id: '4', name: 'Bali', cities: ['Denpasar', 'Ubud', 'Kuta', 'Seminyak'] },
                // Tambahkan provinsi dan kota lainnya jika perlu
            ];

            const provinceSelect = document.getElementById('province');
            const citySelect = document.getElementById('city');

            // Isi dropdown provinsi
            provinces.forEach(province => {
                const option = document.createElement('option');
                option.value = province.id;
                option.text = province.name;
                provinceSelect.appendChild(option);
            });

            // Saat provinsi dipilih, isi dropdown kota
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

            // Restore old input jika ada validasi gagal
            @if(old('province'))
                provinceSelect.value = "{{ old('province') }}";
                provinceSelect.dispatchEvent(new Event('change'));
            @endif
            @if(old('city'))
                setTimeout(() => {
                    citySelect.value = "{{ old('city') }}";
                }, 500);
            @endif

            // Tampilkan toast error/sukses dari backend jika ada
            @if ($errors->has('phone_number'))
                showToast("{{ $errors->first('phone_number') }}", false);
            @endif
            @if (session('success'))
                showToast("{{ session('success') }}", true);
            @elseif (session('error'))
                showToast("{{ session('error') }}", false);
            @endif
        });
    </script>
</body>

</html>
