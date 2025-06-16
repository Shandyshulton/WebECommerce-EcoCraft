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
        return view('register');
    }

    public function register(Request $request)
    {
        $messages = [
            'email.regex' => 'Email harus menggunakan domain gmail.com, yahoo.com, atau outlook.com',
        ];
        $validatedData = $request->validate([
            'name_customers' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:customers',
                'regex:/^[\w.+\-]+@(gmail\.com|yahoo\.com|outlook\.com)$/i',
            ],
            'phone_number' => 'required|string|max:20',
            'dob' => 'required|date|before:today',  // validasi DOB harus tanggal dan sebelum hari ini
            'gender' => 'required|in:male,female',
            'address' => 'required|string|max:255',
            'province' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'password' => 'required|string|max:8|confirmed',
        ], $messages);

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
