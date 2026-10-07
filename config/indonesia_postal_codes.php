<?php

/*
|--------------------------------------------------------------------------
| Kode pos representatif per kota/kabupaten
|--------------------------------------------------------------------------
|
| Dipakai untuk mengisi kode pos secara otomatis saat provinsi + kota
| dipilih pada form registrasi & edit profil. Kode pos Indonesia
| sebenarnya detail sampai tingkat kelurahan; nilai di sini adalah
| kode pos pusat kota/kaburaten sebagai titik awal yang masih bisa
| diubah pengguna bila alamatnya di kelurahan dengan kode berbeda.
|
| Struktur: [ 'Provinsi' => [ 'Kota' => '12345', ... ], ... ]
| Kunci kota harus sama persis dengan config/indonesia_regions.php.
|
*/

return [

    'Aceh' => [
        'Banda Aceh' => '23111', 'Langsa' => '24354', 'Lhokseumawe' => '24351',
        'Sabang' => '23511', 'Subulussalam' => '24782', 'Aceh Besar' => '23919',
        'Pidie' => '24151', 'Bireuen' => '24251', 'Aceh Utara' => '24382',
        'Aceh Timur' => '24454', 'Aceh Tengah' => '24511', 'Aceh Barat' => '23681',
        'Nagan Raya' => '23661', 'Aceh Selatan' => '23711',
    ],

    'Sumatera Utara' => [
        'Medan' => '20111', 'Binjai' => '20712', 'Pematangsiantar' => '21111',
        'Tebing Tinggi' => '20632', 'Tanjungbalai' => '21311', 'Sibolga' => '22513',
        'Padang Sidempuan' => '22711', 'Gunungsitoli' => '22813',
        'Deli Serdang' => '20511', 'Serdang Bedagai' => '20915', 'Langkat' => '20811',
        'Karo' => '22111', 'Simalungun' => '21151', 'Toba' => '22311', 'Asahan' => '21214',
    ],

    'Sumatera Barat' => [
        'Padang' => '25111', 'Bukittinggi' => '26111', 'Padang Panjang' => '27112',
        'Payakumbuh' => '26211', 'Pariaman' => '25511', 'Sawahlunto' => '27416',
        'Solok' => '27311', 'Agam' => '26411', 'Tanah Datar' => '27211',
        'Pesisir Selatan' => '25611', 'Lima Puluh Kota' => '26271', 'Dharmasraya' => '27573',
    ],

    'Riau' => [
        'Pekanbaru' => '28111', 'Dumai' => '28811', 'Kampar' => '28411',
        'Rokan Hulu' => '28557', 'Rokan Hilir' => '28911', 'Bengkalis' => '28711',
        'Siak' => '28671', 'Pelalawan' => '28381', 'Indragiri Hulu' => '29311',
        'Indragiri Hilir' => '29212', 'Kuantan Singingi' => '29566', 'Kepulauan Meranti' => '28753',
    ],

    'Kepulauan Riau' => [
        'Batam' => '29432', 'Tanjungpinang' => '29111', 'Bintan' => '29135',
        'Karimun' => '29611', 'Lingga' => '29872', 'Natuna' => '29783',
        'Kepulauan Anambas' => '29791',
    ],

    'Jambi' => [
        'Jambi' => '36111', 'Sungai Penuh' => '37111', 'Batanghari' => '36613',
        'Muaro Jambi' => '36381', 'Tebo' => '37571', 'Bungo' => '37214',
        'Merangin' => '37313', 'Sarolangun' => '37381', 'Kerinci' => '37161',
        'Tanjung Jabung Barat' => '36511', 'Tanjung Jabung Timur' => '36719',
    ],

    'Sumatera Selatan' => [
        'Palembang' => '30111', 'Prabumulih' => '31121', 'Lubuklinggau' => '31611',
        'Pagar Alam' => '31511', 'Banyuasin' => '30911', 'Ogan Komering Ilir' => '30611',
        'Ogan Komering Ulu' => '32111', 'Muara Enim' => '31311', 'Lahat' => '31411',
        'Musi Banyuasin' => '30711', 'Musi Rawas' => '31661', 'Ogan Ilir' => '30811',
    ],

    'Kepulauan Bangka Belitung' => [
        'Pangkalpinang' => '33111', 'Bangka' => '33212', 'Bangka Barat' => '33311',
        'Bangka Tengah' => '33611', 'Bangka Selatan' => '33711', 'Belitung' => '33411',
        'Belitung Timur' => '33511',
    ],

    'Bengkulu' => [
        'Bengkulu' => '38111', 'Rejang Lebong' => '39112', 'Bengkulu Utara' => '38611',
        'Bengkulu Selatan' => '38511', 'Kaur' => '38911', 'Seluma' => '38811',
        'Mukomuko' => '38765', 'Lebong' => '39264', 'Kepahiang' => '39172',
    ],

    'Lampung' => [
        'Bandar Lampung' => '35111', 'Metro' => '34111', 'Lampung Selatan' => '35511',
        'Lampung Tengah' => '34212', 'Lampung Utara' => '34511', 'Lampung Timur' => '34311',
        'Lampung Barat' => '34811', 'Tanggamus' => '35611', 'Tulang Bawang' => '34611',
        'Pesawaran' => '35451', 'Pringsewu' => '35373', 'Way Kanan' => '34711', 'Mesuji' => '34698',
    ],

    'DKI Jakarta' => [
        'Jakarta Pusat' => '10110', 'Jakarta Utara' => '14110', 'Jakarta Barat' => '11110',
        'Jakarta Selatan' => '12110', 'Jakarta Timur' => '13110', 'Kepulauan Seribu' => '14550',
    ],

    'Jawa Barat' => [
        'Bandung' => '40111', 'Kota Bandung' => '40111', 'Bandung Barat' => '40721',
        'Bekasi' => '17111', 'Kota Bekasi' => '17111', 'Bogor' => '16111',
        'Kota Bogor' => '16111', 'Cimahi' => '40511', 'Cirebon' => '45111',
        'Kota Cirebon' => '45111', 'Depok' => '16411', 'Garut' => '44111',
        'Indramayu' => '45214', 'Karawang' => '41311', 'Kuningan' => '45511',
        'Majalengka' => '45411', 'Pangandaran' => '46396', 'Purwakarta' => '41111',
        'Subang' => '41211', 'Sukabumi' => '43111', 'Kota Sukabumi' => '43111',
        'Sumedang' => '45311', 'Tasikmalaya' => '46111', 'Kota Tasikmalaya' => '46111',
        'Ciamis' => '46211', 'Banjar' => '46311',
    ],

    'Banten' => [
        'Serang' => '42111', 'Kota Serang' => '42111', 'Cilegon' => '42411',
        'Tangerang' => '15111', 'Kota Tangerang' => '15111', 'Tangerang Selatan' => '15311',
        'Lebak' => '42311', 'Pandeglang' => '42211',
    ],

    'Jawa Tengah' => [
        'Semarang' => '50111', 'Kota Semarang' => '50111', 'Surakarta' => '57111',
        'Salatiga' => '50711', 'Magelang' => '56111', 'Kota Magelang' => '56111',
        'Pekalongan' => '51111', 'Kota Pekalongan' => '51111', 'Tegal' => '52111',
        'Kota Tegal' => '52111', 'Banyumas' => '53192', 'Purbalingga' => '53311',
        'Banjarnegara' => '53411', 'Kebumen' => '54311', 'Purworejo' => '54111',
        'Wonosobo' => '56311', 'Boyolali' => '57311', 'Klaten' => '57411',
        'Sukoharjo' => '57511', 'Wonogiri' => '57611', 'Karanganyar' => '57711',
        'Sragen' => '57211', 'Grobogan' => '58111', 'Blora' => '58211',
        'Rembang' => '59211', 'Pati' => '59111', 'Kudus' => '59311', 'Jepara' => '59411',
        'Demak' => '59511', 'Kendal' => '51311', 'Batang' => '51211',
        'Pemalang' => '52311', 'Brebes' => '52211', 'Cilacap' => '53211', 'Temanggung' => '56211',
    ],

    'DI Yogyakarta' => [
        'Yogyakarta' => '55111', 'Sleman' => '55511', 'Bantul' => '55711',
        'Kulon Progo' => '55611', 'Gunungkidul' => '55811',
    ],

    'Jawa Timur' => [
        'Surabaya' => '60111', 'Malang' => '65111', 'Kota Malang' => '65111',
        'Batu' => '65311', 'Kediri' => '64111', 'Kota Kediri' => '64111',
        'Blitar' => '66111', 'Kota Blitar' => '66111', 'Madiun' => '63111',
        'Kota Madiun' => '63111', 'Mojokerto' => '61311', 'Kota Mojokerto' => '61311',
        'Pasuruan' => '67111', 'Kota Pasuruan' => '67111', 'Probolinggo' => '67211',
        'Kota Probolinggo' => '67211', 'Sidoarjo' => '61211', 'Gresik' => '61111',
        'Lamongan' => '62211', 'Tuban' => '62311', 'Bojonegoro' => '62111',
        'Jombang' => '61411', 'Nganjuk' => '64411', 'Ponorogo' => '63411',
        'Pacitan' => '63511', 'Trenggalek' => '66311', 'Tulungagung' => '66211',
        'Magetan' => '63311', 'Ngawi' => '63211', 'Bangkalan' => '69111',
        'Sampang' => '69211', 'Pamekasan' => '69311', 'Sumenep' => '69411',
        'Banyuwangi' => '68411', 'Jember' => '68111', 'Bondowoso' => '68211',
        'Situbondo' => '68311', 'Lumajang' => '67311',
    ],

    'Bali' => [
        'Denpasar' => '80111', 'Badung' => '80351', 'Gianyar' => '80511',
        'Tabanan' => '82111', 'Klungkung' => '80711', 'Bangli' => '80611',
        'Karangasem' => '80811', 'Buleleng' => '81111', 'Jembrana' => '82211',
    ],

    'Nusa Tenggara Barat' => [
        'Mataram' => '83111', 'Lombok Barat' => '83311', 'Lombok Tengah' => '83511',
        'Lombok Timur' => '83611', 'Lombok Utara' => '83711', 'Bima' => '84111',
        'Kota Bima' => '84111', 'Dompu' => '84211', 'Sumbawa' => '84311', 'Sumbawa Barat' => '84411',
    ],

    'Nusa Tenggara Timur' => [
        'Kupang' => '85111', 'Kota Kupang' => '85111', 'Ende' => '86311',
        'Sikka' => '86111', 'Flores Timur' => '86211', 'Manggarai' => '86511',
        'Manggarai Barat' => '86711', 'Ngada' => '86411', 'Nagekeo' => '86911',
        'Sumba Timur' => '87111', 'Sumba Barat' => '87211', 'Belu' => '85711',
        'Timor Tengah Selatan' => '85511', 'Timor Tengah Utara' => '85611',
        'Alor' => '85811', 'Rote Ndao' => '85982',
    ],

    'Kalimantan Barat' => [
        'Pontianak' => '78111', 'Singkawang' => '79111', 'Kubu Raya' => '78311',
        'Mempawah' => '78911', 'Sambas' => '79411', 'Bengkayang' => '79211',
        'Landak' => '78357', 'Sanggau' => '78511', 'Sekadau' => '79583',
        'Sintang' => '78611', 'Melawi' => '78672', 'Ketapang' => '78811',
        'Kayong Utara' => '78852', 'Kapuas Hulu' => '78711',
    ],

    'Kalimantan Tengah' => [
        'Palangka Raya' => '73111', 'Kotawaringin Barat' => '74111',
        'Kotawaringin Timur' => '74311', 'Kapuas' => '73511', 'Barito Selatan' => '73711',
        'Barito Utara' => '73811', 'Barito Timur' => '73671', 'Katingan' => '74411',
        'Seruyan' => '74211', 'Sukamara' => '74712', 'Lamandau' => '74611',
        'Gunung Mas' => '74511', 'Pulang Pisau' => '74811', 'Murung Raya' => '73911',
    ],

    'Kalimantan Selatan' => [
        'Banjarmasin' => '70111', 'Banjarbaru' => '70711', 'Banjar' => '70611',
        'Barito Kuala' => '70511', 'Tapin' => '71111', 'Hulu Sungai Selatan' => '71211',
        'Hulu Sungai Tengah' => '71311', 'Hulu Sungai Utara' => '71411',
        'Tabalong' => '71511', 'Kotabaru' => '72111', 'Tanah Laut' => '70811',
        'Tanah Bumbu' => '72211', 'Balangan' => '71611',
    ],

    'Kalimantan Timur' => [
        'Samarinda' => '75111', 'Balikpapan' => '76111', 'Bontang' => '75311',
        'Kutai Kartanegara' => '75511', 'Kutai Barat' => '75711', 'Kutai Timur' => '75611',
        'Berau' => '77311', 'Paser' => '76211', 'Penajam Paser Utara' => '76141',
        'Mahakam Ulu' => '75765',
    ],

    'Kalimantan Utara' => [
        'Tarakan' => '77111', 'Bulungan' => '77211', 'Malinau' => '77511',
        'Nunukan' => '77421', 'Tana Tidung' => '77611',
    ],

    'Sulawesi Utara' => [
        'Manado' => '95111', 'Bitung' => '95511', 'Tomohon' => '95411',
        'Kotamobagu' => '95711', 'Minahasa' => '95611', 'Minahasa Utara' => '95316',
        'Minahasa Selatan' => '95914', 'Minahasa Tenggara' => '95995',
        'Bolaang Mongondow' => '95755', 'Kepulauan Sangihe' => '95811',
        'Kepulauan Talaud' => '95885', 'Kepulauan Siau Tagulandang Biaro' => '95862',
    ],

    'Gorontalo' => [
        'Gorontalo' => '96111', 'Kota Gorontalo' => '96111', 'Boalemo' => '96513',
        'Bone Bolango' => '96511', 'Pohuwato' => '96419', 'Gorontalo Utara' => '96611',
    ],

    'Sulawesi Tengah' => [
        'Palu' => '94111', 'Donggala' => '94311', 'Sigi' => '94364',
        'Parigi Moutong' => '94411', 'Poso' => '94611', 'Tojo Una-Una' => '94683',
        'Banggai' => '94711', 'Banggai Kepulauan' => '94881', 'Banggai Laut' => '94891',
        'Morowali' => '94911', 'Morowali Utara' => '94961', 'Tolitoli' => '94511', 'Buol' => '94564',
    ],

    'Sulawesi Barat' => [
        'Mamuju' => '91511', 'Mamuju Tengah' => '91563', 'Pasangkayu' => '91571',
        'Majene' => '91411', 'Polewali Mandar' => '91311', 'Mamasa' => '91362',
    ],

    'Sulawesi Selatan' => [
        'Makassar' => '90111', 'Parepare' => '91111', 'Palopo' => '91911',
        'Gowa' => '92111', 'Maros' => '90511', 'Takalar' => '92211', 'Bone' => '92711',
        'Bulukumba' => '92511', 'Sinjai' => '92611', 'Bantaeng' => '92411',
        'Jeneponto' => '92311', 'Pinrang' => '91211', 'Sidrap' => '91611',
        'Enrekang' => '91711', 'Luwu' => '91994', 'Luwu Utara' => '92911',
        'Luwu Timur' => '92981', 'Wajo' => '90911', 'Soppeng' => '90811',
        'Barru' => '90711', 'Pangkajene Kepulauan' => '90611', 'Selayar' => '92812',
        'Tana Toraja' => '91811', 'Toraja Utara' => '91831',
    ],

    'Sulawesi Tenggara' => [
        'Kendari' => '93111', 'Baubau' => '93711', 'Konawe' => '93411',
        'Konawe Selatan' => '93811', 'Konawe Utara' => '93311', 'Konawe Kepulauan' => '93396',
        'Kolaka' => '93511', 'Kolaka Utara' => '93911', 'Kolaka Timur' => '93571',
        'Bombana' => '93771', 'Buton' => '93752', 'Buton Utara' => '93673',
        'Buton Selatan' => '93794', 'Buton Tengah' => '93763', 'Muna' => '93611',
        'Muna Barat' => '93655', 'Wakatobi' => '93791',
    ],

    'Maluku' => [
        'Ambon' => '97111', 'Tual' => '97611', 'Maluku Tengah' => '97511',
        'Maluku Tenggara' => '97651', 'Kepulauan Tanimbar' => '97664', 'Buru' => '97571',
        'Buru Selatan' => '97351', 'Seram Bagian Barat' => '97561',
        'Seram Bagian Timur' => '97581', 'Kepulauan Aru' => '97662',
    ],

    'Maluku Utara' => [
        'Ternate' => '97711', 'Tidore Kepulauan' => '97815', 'Halmahera Barat' => '97757',
        'Halmahera Tengah' => '97853', 'Halmahera Utara' => '97762',
        'Halmahera Selatan' => '97911', 'Halmahera Timur' => '97862',
        'Kepulauan Sula' => '97795', 'Pulau Morotai' => '97771', 'Pulau Taliabu' => '97791',
    ],

    'Papua' => [
        'Jayapura' => '99352', 'Kota Jayapura' => '99111', 'Keerom' => '99461',
        'Sarmi' => '99373', 'Mamberamo Raya' => '99381', 'Jayawijaya' => '99511',
        'Biak Numfor' => '98111', 'Kepulauan Yapen' => '98211', 'Waropen' => '98272',
        'Supiori' => '98164',
    ],

    'Papua Barat' => [
        'Manokwari' => '98311', 'Manokwari Selatan' => '98355', 'Pegunungan Arfak' => '98358',
        'Fakfak' => '98611', 'Kaimana' => '98654', 'Teluk Bintuni' => '98551',
        'Teluk Wondama' => '98591',
    ],

    'Papua Barat Daya' => [
        'Sorong' => '98451', 'Kota Sorong' => '98411', 'Sorong Selatan' => '98454',
        'Raja Ampat' => '98482', 'Tambrauw' => '98475', 'Maybrat' => '98456',
    ],

    'Papua Tengah' => [
        'Nabire' => '98811', 'Mimika' => '99910', 'Paniai' => '98771',
        'Dogiyai' => '98863', 'Deiyai' => '98784', 'Intan Jaya' => '98771',
        'Puncak' => '98981', 'Puncak Jaya' => '98971',
    ],

    'Papua Pegunungan' => [
        'Wamena' => '99511', 'Jayawijaya' => '99511', 'Lanny Jaya' => '99531',
        'Nduga' => '99540', 'Tolikara' => '99581', 'Yahukimo' => '99601',
        'Yalimo' => '99556', 'Mamberamo Tengah' => '99553', 'Pegunungan Bintang' => '99573',
    ],

    'Papua Selatan' => [
        'Merauke' => '99611', 'Boven Digoel' => '99662', 'Mappi' => '99853', 'Asmat' => '99777',
    ],

];
