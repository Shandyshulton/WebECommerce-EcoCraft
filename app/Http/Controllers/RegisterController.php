<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer; // Model Customer
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('register', ['policies' => config('policies')]);
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name_customers' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:customers',
            ],
            'phone_number' => 'required|string|max:20',
            'dob' => 'required|date|before:today',  // validasi DOB harus tanggal dan sebelum hari ini
            'gender' => 'required|in:male,female',
            'address' => 'required|string|max:255',
            'province' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'password' => 'required|string|min:8|max:255|confirmed',
            'terms' => ['accepted'],
        ], [
            'terms.accepted' => 'Kamu harus menyetujui Ketentuan Layanan dan Kebijakan Privasi EcoCraft.',
        ]);

        $customer = Customer::create([
            'name_customers' => $validatedData['name_customers'],
            'email' => $validatedData['email'],
            'phone_number' => $validatedData['phone_number'],
            'dob' => $validatedData['dob'],
            'gender' => $validatedData['gender'],
            'address' => $validatedData['address'],
            'province' => $validatedData['province'],
            'city' => $validatedData['city'],
            'password' => Hash::make($validatedData['password']),
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil!');
    }
}
