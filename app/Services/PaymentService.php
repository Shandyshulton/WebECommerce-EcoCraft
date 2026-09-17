<?php

namespace App\Services;

use App\Models\Order;

/**
 * Simulasi pembayaran. Tidak terhubung ke payment gateway mana pun — nomor
 * Virtual Account dan payload QRIS dibuat sendiri, lalu pelanggan menandai
 * sendiri bahwa ia sudah membayar.
 */
class PaymentService
{
    /** Kode bank untuk Virtual Account (simulasi). */
    private const VA_PREFIX = '8808';

    /** Merchant ID simulasi pada payload QRIS. */
    private const MERCHANT_ID = '936000000000000000';

    /**
     * Nomor Virtual Account untuk pesanan.
     *
     * Dibuat sekali lalu disimpan, supaya nomornya tidak berubah kalau halaman
     * dibuka ulang — nomor yang berubah-ubah akan membingungkan pembeli.
     */
    public function virtualAccount(Order $order): string
    {
        if (! $order->virtual_account) {
            $order->update([
                'virtual_account' => self::VA_PREFIX.str_pad((string) $order->getKey(), 10, '0', STR_PAD_LEFT),
            ]);
        }

        return $order->virtual_account;
    }

    /**
     * Payload QRIS format EMVCo: deretan tag-length-value ditutup CRC16.
     */
    public function qrisPayload(Order $order): string
    {
        $payload = $this->tag('00', '01')                 // versi payload
            .$this->tag('01', '12')                       // QR dinamis (sekali pakai)
            .$this->tag('26', $this->tag('00', 'ID.CO.QRIS.WWW').$this->tag('01', self::MERCHANT_ID))
            .$this->tag('52', '5411')                     // kategori merchant
            .$this->tag('53', '360')                      // mata uang: IDR
            .$this->tag('54', number_format((float) $order->total, 2, '.', ''))
            .$this->tag('58', 'ID')
            .$this->tag('59', 'ECOCRAFT')
            .$this->tag('60', 'JAKARTA')
            .$this->tag('61', $order->shipping_postal_code ?: '00000')
            // Reference label: membuat tiap pesanan punya payload unik walau
            // nominalnya kebetulan sama.
            .$this->tag('62', $this->tag('05', $order->order_number))
            .'6304';                                      // awal nilai CRC

        return $payload.$this->crc16($payload);
    }

    public function markPaid(Order $order): void
    {
        if ($order->isPaid()) {
            return;
        }

        $order->update([
            'payment_status' => Order::PAYMENT_PAID,
            'paid_at' => now(),
        ]);
    }

    /**
     * Satu field EMVCo: id 2 digit + panjang 2 digit + nilai.
     */
    private function tag(string $id, string $value): string
    {
        return $id.str_pad((string) strlen($value), 2, '0', STR_PAD_LEFT).$value;
    }

    /**
     * CRC16-CCITT (polinomial 0x1021, nilai awal 0xFFFF) sesuai spesifikasi EMVCo.
     *
     * Dibuat publik karena merupakan fungsi murni yang nilainya bisa diperiksa
     * terhadap nilai acuan standar.
     */
    public function crc16(string $payload): string
    {
        $crc = 0xFFFF;

        for ($i = 0; $i < strlen($payload); $i++) {
            $crc ^= ord($payload[$i]) << 8;

            for ($bit = 0; $bit < 8; $bit++) {
                $crc = ($crc & 0x8000) ? (($crc << 1) ^ 0x1021) : ($crc << 1);
                $crc &= 0xFFFF;
            }
        }

        return strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
    }
}
