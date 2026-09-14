@extends('seller.dashboard')

@section('content')
<style>
    .profile-wrap{max-width:640px;margin:auto}
    .profile-card{padding:24px;background:#fff;border:1px solid var(--line);border-radius:14px}
    .profile-form .form-group{margin-bottom:18px}
    .profile-form label{display:block;margin-bottom:6px;font-size:11px;font-weight:800;letter-spacing:.04em;text-transform:uppercase;color:var(--muted)}
    .profile-form input[type="text"],.profile-form input[type="email"],.profile-form input[type="file"]{width:100%;min-height:42px;padding:9px 11px;border:1px solid var(--line);border-radius:8px;background:#fff;font:13px 'Plus Jakarta Sans'}
    .profile-form input:focus{outline:0;border-color:var(--brand);box-shadow:0 0 0 3px rgba(30,75,56,.12)}
    .profile-photo{display:block;margin-bottom:14px;width:110px;height:110px;object-fit:cover;border-radius:50%;border:3px solid #fff;box-shadow:0 4px 14px rgba(23,35,28,.12)}
    .profile-form .alert{border-radius:10px}
</style>

<div class="page-heading">
    <div>
        <div class="eyebrow">Pengaturan toko</div>
        <h1>Edit Profil</h1>
        <p class="subtle mb-0">Perbarui identitas dan foto tokomu agar tetap terpercaya.</p>
    </div>
    <a class="btn-brand" href="{{ route('seller.dashboard') }}"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="profile-wrap">
    <div class="profile-card">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="profile-form" method="POST" action="{{ route('seller.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @php
                $photoPath = $seller->profile_image ? asset('storage/' . $seller->profile_image) : null;
            @endphp

            <div class="form-group">
                <label>Foto Profil</label>
                @if($photoPath && file_exists(public_path('storage/' . $seller->profile_image)))
                    <img id="profile-preview" src="{{ $photoPath }}" class="profile-photo" alt="Profile Image">
                @else
                    <img id="profile-preview" src="{{ asset('images/default-profile.png') }}" class="profile-photo" alt="No Image">
                @endif
                <input type="file" name="profile_image" id="profile_image" accept="image/*" onchange="previewImage(event)">
            </div>

            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="name_sellers" value="{{ old('name_sellers', $seller->name_sellers) }}" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email', $seller->email) }}" required>
            </div>

            <button type="submit" class="btn-brand"><i class="fas fa-floppy-disk"></i> Simpan Perubahan</button>
        </form>
    </div>
</div>

<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('profile-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) { preview.src = e.target.result; }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
