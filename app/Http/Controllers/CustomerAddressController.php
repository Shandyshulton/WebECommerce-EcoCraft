<?php

namespace App\Http\Controllers;

use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerAddressController extends Controller
{
    /**
     * Daftar alamat pengiriman milik customer.
     */
    public function index()
    {
        $addresses = $this->query()
            ->orderByDesc('is_default')
            ->orderByDesc('id_addresses')
            ->get();

        return view('customer.addresses.index', compact('addresses'));
    }

    /**
     * Form tambah alamat, diprefill dari data registrasi.
     */
    public function create(Request $request)
    {
        $customer = auth('customer')->user();

        $address = new CustomerAddress([
            'recipient_name' => $customer->name_customers,
            'phone' => $customer->phone_number,
            'address' => $customer->address,
            'city' => $customer->city,
            'province' => $customer->province,
        ]);

        return view('customer.addresses.create', [
            'address' => $address,
            'redirect' => $request->query('redirect'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $customerId = auth('customer')->id();

        DB::transaction(function () use ($data, $customerId) {
            $isFirst = ! CustomerAddress::where('customer_id', $customerId)->exists();

            if ($isFirst || ! empty($data['is_default'])) {
                CustomerAddress::where('customer_id', $customerId)->update(['is_default' => false]);
                $data['is_default'] = true;
            }

            $data['customer_id'] = $customerId;
            CustomerAddress::create($data);
        });

        // Bila datang dari checkout, lanjutkan kembali ke checkout.
        if ($request->input('redirect') === 'checkout') {
            return redirect()
                ->route('checkout.index')
                ->with('success', 'Alamat pengiriman tersimpan. Lanjutkan pesananmu.');
        }

        return redirect()
            ->route('customer.addresses.index')
            ->with('success', 'Alamat pengiriman berhasil ditambahkan.');
    }

    public function edit(CustomerAddress $address)
    {
        $this->authorizeAddress($address);

        return view('customer.addresses.edit', compact('address'));
    }

    public function update(Request $request, CustomerAddress $address)
    {
        $this->authorizeAddress($address);

        $data = $this->validated($request);
        $customerId = auth('customer')->id();

        DB::transaction(function () use ($data, $address, $customerId) {
            if (! empty($data['is_default'])) {
                CustomerAddress::where('customer_id', $customerId)
                    ->where('id_addresses', '!=', $address->id_addresses)
                    ->update(['is_default' => false]);
                $data['is_default'] = true;
            } else {
                // Jangan sampai tidak ada alamat utama.
                $data['is_default'] = $address->is_default;
            }

            $address->update($data);
        });

        return redirect()
            ->route('customer.addresses.index')
            ->with('success', 'Alamat pengiriman berhasil diperbarui.');
    }

    public function destroy(CustomerAddress $address)
    {
        $this->authorizeAddress($address);

        DB::transaction(function () use ($address) {
            $wasDefault = $address->is_default;
            $customerId = $address->customer_id;

            $address->delete();

            if ($wasDefault) {
                $next = CustomerAddress::where('customer_id', $customerId)->orderBy('id_addresses')->first();
                if ($next) {
                    $next->update(['is_default' => true]);
                }
            }
        });

        return redirect()
            ->route('customer.addresses.index')
            ->with('success', 'Alamat pengiriman dihapus.');
    }

    public function default(CustomerAddress $address)
    {
        $this->authorizeAddress($address);
        $customerId = auth('customer')->id();

        DB::transaction(function () use ($address, $customerId) {
            CustomerAddress::where('customer_id', $customerId)->update(['is_default' => false]);
            $address->update(['is_default' => true]);
        });

        return redirect()
            ->route('customer.addresses.index')
            ->with('success', 'Alamat utama berhasil diperbarui.');
    }

    private function query()
    {
        return CustomerAddress::where('customer_id', auth('customer')->id());
    }

    private function authorizeAddress(CustomerAddress $address): void
    {
        abort_unless((int) $address->customer_id === (int) auth('customer')->id(), 404);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'label' => ['nullable', 'string', 'max:50'],
            'recipient_name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:20'],
            'is_default' => ['nullable', 'boolean'],
        ], [
            'recipient_name.required' => 'Nama penerima wajib diisi.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'address.required' => 'Alamat lengkap wajib diisi.',
            'city.required' => 'Kota wajib diisi.',
            'province.required' => 'Provinsi wajib diisi.',
            'postal_code.required' => 'Kode pos wajib diisi.',
        ]);
    }
}
