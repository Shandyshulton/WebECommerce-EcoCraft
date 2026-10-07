<?php

/*
|--------------------------------------------------------------------------
| Wilayah Indonesia (Provinsi -> Kota/Kabupaten)
|--------------------------------------------------------------------------
|
| Dipakai bersama oleh form registrasi customer dan form edit profil.
| Kunci = nama provinsi, nilai = daftar kota/kabupaten di provinsi itu.
| Mencakup 38 provinsi (termasuk pemekaran Papua 2022).
|
| Daftar kota/kabupaten tidak dimaksudkan 100% lengkap untuk setiap
| provinsi, tetapi mencakup kota/kabupaten utama tiap provinsi sehingga
| dropdown kota selalu punya pilihan sesuai provinsi yang dipilih.
|
*/

return [

    'Aceh' => [
        'Banda Aceh', 'Langsa', 'Lhokseumawe', 'Sabang', 'Subulussalam',
        'Aceh Besar', 'Pidie', 'Bireuen', 'Aceh Utara', 'Aceh Timur',
        'Aceh Tengah', 'Aceh Barat', 'Nagan Raya', 'Aceh Selatan',
    ],

    'Sumatera Utara' => [
        'Medan', 'Binjai', 'Pematangsiantar', 'Tebing Tinggi', 'Tanjungbalai',
        'Sibolga', 'Padang Sidempuan', 'Gunungsitoli', 'Deli Serdang',
        'Serdang Bedagai', 'Langkat', 'Karo', 'Simalungun', 'Toba', 'Asahan',
    ],

    'Sumatera Barat' => [
        'Padang', 'Bukittinggi', 'Padang Panjang', 'Payakumbuh', 'Pariaman',
        'Sawahlunto', 'Solok', 'Agam', 'Tanah Datar', 'Pesisir Selatan',
        'Lima Puluh Kota', 'Dharmasraya',
    ],

    'Riau' => [
        'Pekanbaru', 'Dumai', 'Kampar', 'Rokan Hulu', 'Rokan Hilir',
        'Bengkalis', 'Siak', 'Pelalawan', 'Indragiri Hulu', 'Indragiri Hilir',
        'Kuantan Singingi', 'Kepulauan Meranti',
    ],

    'Kepulauan Riau' => [
        'Batam', 'Tanjungpinang', 'Bintan', 'Karimun', 'Lingga',
        'Natuna', 'Kepulauan Anambas',
    ],

    'Jambi' => [
        'Jambi', 'Sungai Penuh', 'Batanghari', 'Muaro Jambi', 'Tebo',
        'Bungo', 'Merangin', 'Sarolangun', 'Kerinci', 'Tanjung Jabung Barat',
        'Tanjung Jabung Timur',
    ],

    'Sumatera Selatan' => [
        'Palembang', 'Prabumulih', 'Lubuklinggau', 'Pagar Alam', 'Banyuasin',
        'Ogan Komering Ilir', 'Ogan Komering Ulu', 'Muara Enim', 'Lahat',
        'Musi Banyuasin', 'Musi Rawas', 'Ogan Ilir',
    ],

    'Kepulauan Bangka Belitung' => [
        'Pangkalpinang', 'Bangka', 'Bangka Barat', 'Bangka Tengah',
        'Bangka Selatan', 'Belitung', 'Belitung Timur',
    ],

    'Bengkulu' => [
        'Bengkulu', 'Rejang Lebong', 'Bengkulu Utara', 'Bengkulu Selatan',
        'Kaur', 'Seluma', 'Mukomuko', 'Lebong', 'Kepahiang',
    ],

    'Lampung' => [
        'Bandar Lampung', 'Metro', 'Lampung Selatan', 'Lampung Tengah',
        'Lampung Utara', 'Lampung Timur', 'Lampung Barat', 'Tanggamus',
        'Tulang Bawang', 'Pesawaran', 'Pringsewu', 'Way Kanan', 'Mesuji',
    ],

    'DKI Jakarta' => [
        'Jakarta Pusat', 'Jakarta Utara', 'Jakarta Barat', 'Jakarta Selatan',
        'Jakarta Timur', 'Kepulauan Seribu',
    ],

    'Jawa Barat' => [
        'Bandung', 'Kota Bandung', 'Bandung Barat', 'Bekasi', 'Kota Bekasi',
        'Bogor', 'Kota Bogor', 'Cimahi', 'Cirebon', 'Kota Cirebon', 'Depok',
        'Garut', 'Indramayu', 'Karawang', 'Kuningan', 'Majalengka',
        'Pangandaran', 'Purwakarta', 'Subang', 'Sukabumi', 'Kota Sukabumi',
        'Sumedang', 'Tasikmalaya', 'Kota Tasikmalaya', 'Ciamis', 'Banjar',
    ],

    'Banten' => [
        'Serang', 'Kota Serang', 'Cilegon', 'Tangerang', 'Kota Tangerang',
        'Tangerang Selatan', 'Lebak', 'Pandeglang',
    ],

    'Jawa Tengah' => [
        'Semarang', 'Kota Semarang', 'Surakarta', 'Salatiga', 'Magelang',
        'Kota Magelang', 'Pekalongan', 'Kota Pekalongan', 'Tegal',
        'Kota Tegal', 'Banyumas', 'Purbalingga', 'Banjarnegara', 'Kebumen',
        'Purworejo', 'Wonosobo', 'Boyolali', 'Klaten', 'Sukoharjo', 'Wonogiri',
        'Karanganyar', 'Sragen', 'Grobogan', 'Blora', 'Rembang', 'Pati',
        'Kudus', 'Jepara', 'Demak', 'Kendal', 'Batang', 'Pemalang', 'Brebes',
        'Cilacap', 'Temanggung',
    ],

    'DI Yogyakarta' => [
        'Yogyakarta', 'Sleman', 'Bantul', 'Kulon Progo', 'Gunungkidul',
    ],

    'Jawa Timur' => [
        'Surabaya', 'Malang', 'Kota Malang', 'Batu', 'Kediri', 'Kota Kediri',
        'Blitar', 'Kota Blitar', 'Madiun', 'Kota Madiun', 'Mojokerto',
        'Kota Mojokerto', 'Pasuruan', 'Kota Pasuruan', 'Probolinggo',
        'Kota Probolinggo', 'Sidoarjo', 'Gresik', 'Lamongan', 'Tuban',
        'Bojonegoro', 'Jombang', 'Nganjuk', 'Ponorogo', 'Pacitan', 'Trenggalek',
        'Tulungagung', 'Magetan', 'Ngawi', 'Bangkalan', 'Sampang', 'Pamekasan',
        'Sumenep', 'Banyuwangi', 'Jember', 'Bondowoso', 'Situbondo', 'Lumajang',
    ],

    'Bali' => [
        'Denpasar', 'Badung', 'Gianyar', 'Tabanan', 'Klungkung', 'Bangli',
        'Karangasem', 'Buleleng', 'Jembrana',
    ],

    'Nusa Tenggara Barat' => [
        'Mataram', 'Lombok Barat', 'Lombok Tengah', 'Lombok Timur',
        'Lombok Utara', 'Bima', 'Kota Bima', 'Dompu', 'Sumbawa',
        'Sumbawa Barat',
    ],

    'Nusa Tenggara Timur' => [
        'Kupang', 'Kota Kupang', 'Ende', 'Sikka', 'Flores Timur', 'Manggarai',
        'Manggarai Barat', 'Ngada', 'Nagekeo', 'Sumba Timur', 'Sumba Barat',
        'Belu', 'Timor Tengah Selatan', 'Timor Tengah Utara', 'Alor', 'Rote Ndao',
    ],

    'Kalimantan Barat' => [
        'Pontianak', 'Singkawang', 'Kubu Raya', 'Mempawah', 'Sambas',
        'Bengkayang', 'Landak', 'Sanggau', 'Sekadau', 'Sintang', 'Melawi',
        'Ketapang', 'Kayong Utara', 'Kapuas Hulu',
    ],

    'Kalimantan Tengah' => [
        'Palangka Raya', 'Kotawaringin Barat', 'Kotawaringin Timur', 'Kapuas',
        'Barito Selatan', 'Barito Utara', 'Barito Timur', 'Katingan', 'Seruyan',
        'Sukamara', 'Lamandau', 'Gunung Mas', 'Pulang Pisau', 'Murung Raya',
    ],

    'Kalimantan Selatan' => [
        'Banjarmasin', 'Banjarbaru', 'Banjar', 'Barito Kuala', 'Tapin',
        'Hulu Sungai Selatan', 'Hulu Sungai Tengah', 'Hulu Sungai Utara',
        'Tabalong', 'Kotabaru', 'Tanah Laut', 'Tanah Bumbu', 'Balangan',
    ],

    'Kalimantan Timur' => [
        'Samarinda', 'Balikpapan', 'Bontang', 'Kutai Kartanegara',
        'Kutai Barat', 'Kutai Timur', 'Berau', 'Paser', 'Penajam Paser Utara',
        'Mahakam Ulu',
    ],

    'Kalimantan Utara' => [
        'Tarakan', 'Bulungan', 'Malinau', 'Nunukan', 'Tana Tidung',
    ],

    'Sulawesi Utara' => [
        'Manado', 'Bitung', 'Tomohon', 'Kotamobagu', 'Minahasa',
        'Minahasa Utara', 'Minahasa Selatan', 'Minahasa Tenggara',
        'Bolaang Mongondow', 'Kepulauan Sangihe', 'Kepulauan Talaud',
        'Kepulauan Siau Tagulandang Biaro',
    ],

    'Gorontalo' => [
        'Gorontalo', 'Kota Gorontalo', 'Boalemo', 'Bone Bolango',
        'Pohuwato', 'Gorontalo Utara',
    ],

    'Sulawesi Tengah' => [
        'Palu', 'Donggala', 'Sigi', 'Parigi Moutong', 'Poso', 'Tojo Una-Una',
        'Banggai', 'Banggai Kepulauan', 'Banggai Laut', 'Morowali',
        'Morowali Utara', 'Tolitoli', 'Buol',
    ],

    'Sulawesi Barat' => [
        'Mamuju', 'Mamuju Tengah', 'Pasangkayu', 'Majene', 'Polewali Mandar',
        'Mamasa',
    ],

    'Sulawesi Selatan' => [
        'Makassar', 'Parepare', 'Palopo', 'Gowa', 'Maros', 'Takalar', 'Bone',
        'Bulukumba', 'Sinjai', 'Bantaeng', 'Jeneponto', 'Pinrang', 'Sidrap',
        'Enrekang', 'Luwu', 'Luwu Utara', 'Luwu Timur', 'Wajo', 'Soppeng',
        'Barru', 'Pangkajene Kepulauan', 'Selayar', 'Tana Toraja', 'Toraja Utara',
    ],

    'Sulawesi Tenggara' => [
        'Kendari', 'Baubau', 'Konawe', 'Konawe Selatan', 'Konawe Utara',
        'Konawe Kepulauan', 'Kolaka', 'Kolaka Utara', 'Kolaka Timur', 'Bombana',
        'Buton', 'Buton Utara', 'Buton Selatan', 'Buton Tengah', 'Muna',
        'Muna Barat', 'Wakatobi',
    ],

    'Maluku' => [
        'Ambon', 'Tual', 'Maluku Tengah', 'Maluku Tenggara',
        'Kepulauan Tanimbar', 'Buru', 'Buru Selatan', 'Seram Bagian Barat',
        'Seram Bagian Timur', 'Kepulauan Aru',
    ],

    'Maluku Utara' => [
        'Ternate', 'Tidore Kepulauan', 'Halmahera Barat', 'Halmahera Tengah',
        'Halmahera Utara', 'Halmahera Selatan', 'Halmahera Timur',
        'Kepulauan Sula', 'Pulau Morotai', 'Pulau Taliabu',
    ],

    'Papua' => [
        'Jayapura', 'Kota Jayapura', 'Keerom', 'Sarmi', 'Mamberamo Raya',
        'Jayawijaya', 'Biak Numfor', 'Kepulauan Yapen', 'Waropen', 'Supiori',
    ],

    'Papua Barat' => [
        'Manokwari', 'Manokwari Selatan', 'Pegunungan Arfak', 'Fakfak',
        'Kaimana', 'Teluk Bintuni', 'Teluk Wondama',
    ],

    'Papua Barat Daya' => [
        'Sorong', 'Kota Sorong', 'Sorong Selatan', 'Raja Ampat', 'Tambrauw',
        'Maybrat',
    ],

    'Papua Tengah' => [
        'Nabire', 'Mimika', 'Paniai', 'Dogiyai', 'Deiyai', 'Intan Jaya',
        'Puncak', 'Puncak Jaya',
    ],

    'Papua Pegunungan' => [
        'Wamena', 'Jayawijaya', 'Lanny Jaya', 'Nduga', 'Tolikara', 'Yahukimo',
        'Yalimo', 'Mamberamo Tengah', 'Pegunungan Bintang',
    ],

    'Papua Selatan' => [
        'Merauke', 'Boven Digoel', 'Mappi', 'Asmat',
    ],

];
