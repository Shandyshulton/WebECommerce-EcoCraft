<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Profile Seller</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f0f0;
            padding: 40px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }
        input[type="text"],
        input[type="email"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        .profile-photo {
            display: block;
            margin-bottom: 15px;
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #ccc;
        }
        .btn {
            background: #28a745;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-back {
            display: inline-block;
            margin-bottom: 20px;
            background-color: #6c757d;
            color: white;
            padding: 10px 15px;
            border-radius: 6px;
            text-decoration: none;
        }
        .btn:hover {
            background: #218838;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 6px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
<div class="container">

         <a href="{{ route('seller.dashboard') }}" class="btn-back">← Back to Dashboard</a>

    <h1>Edit Profile Seller</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('seller.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @php
            $photoPath = $seller->profile_image ? asset('storage/' . $seller->profile_image) : null;
        @endphp

        <div class="form-group">
            <label>Profile Photo</label>
            @if($photoPath && file_exists(public_path('storage/' . $seller->profile_image)))
                <img id="profile-preview" src="{{ $photoPath }}" class="profile-photo" alt="Profile Image">
            @else
                <img id="profile-preview" src="https://via.placeholder.com/120?text=No+Image" class="profile-photo" alt="No Image">
            @endif
            <input type="file" name="profile_image" id="profile_image" accept="image/*" onchange="previewImage(event)">
        </div>

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name_sellers" value="{{ old('name_sellers', $seller->name_sellers) }}" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $seller->email) }}" required>
        </div>

        <button type="submit" class="btn">Save Changes</button>
    </form>
</div>

<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('profile-preview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
</body>
</html>
