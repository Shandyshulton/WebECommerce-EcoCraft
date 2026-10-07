<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer; // Model Customer
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('register', [
            'policies' => config('policies'),
            'regions' => config('indonesia_regions'),
            'postalCodes' => config('indonesia_postal_codes'),
        ]);
    }

    public function register(Request $request)
    {
        $regions = config('indonesia_regions');

        $validatedData = $request->validate([
            'name_customers' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:customers',
            ],
            'phone_number' => ['required', 'regex:/^(\+62|62|0)8[1-9][0-9]{6,11}$/'],
            'dob' => 'required|date|before:today',  // validasi DOB harus tanggal dan sebelum hari ini
            'gender' => 'required|in:male,female',
            'address' => 'required|string|max:255',
            'province' => ['required', 'string', Rule::in(array_keys($regions))],
            'city' => 'required|string|max:100',
            'postal_code' => ['required', 'regex:/^[0-9]{5}$/'],
            'password' => 'required|string|min:8|max:255|confirmed',
            'terms' => ['accepted'],
        ], [
            'phone_number.regex' => 'Nomor WhatsApp harus nomor Indonesia yang valid (hanya angka, contoh: 081234567890).',
            'postal_code.regex' => 'Kode pos harus 5 digit angka.',
            'terms.accepted' => 'Kamu harus menyetujui Ketentuan Layanan dan Kebijakan Privasi EcoCraft.',
        ]);

        // Pastikan kota benar-benar milik provinsi yang dipilih.
        $validCities = $regions[$validatedData['province']] ?? [];
        if (! in_array($validatedData['city'], $validCities, true)) {
            return back()
                ->withErrors(['city' => 'Kota / kabupaten tidak sesuai dengan provinsi yang dipilih.'])
                ->withInput();
        }

        $customer = Customer::create([
            'name_customers' => $validatedData['name_customers'],
            'email' => $validatedData['email'],
            'phone_number' => $validatedData['phone_number'],
            'dob' => $validatedData['dob'],
            'gender' => $validatedData['gender'],
            'address' => $validatedData['address'],
            'province' => $validatedData['province'],
            'city' => $validatedData['city'],
            'postal_code' => $validatedData['postal_code'],
            'password' => Hash::make($validatedData['password']),
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil!');
    }
}
