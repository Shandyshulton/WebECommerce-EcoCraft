@extends('layouts.customer')
@section('title','Keranjang | EcoCraft')

@section('content')
<main class="section cart-page">
    <div class="page-wrap">
        <div class="cart-heading">
            <div><div class="eyebrow">Shopping cart</div><h1>Keranjang kamu</h1></div>
            <a class="subtle" href="{{ route('customer.dashboard') }}">Lanjut belanja &rarr;</a>
        </div>

        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-warning">{{ session('error') }}</div>@endif

        @if($items->isEmpty())
            <div class="form-panel text-center py-5">
                <h2>Keranjang masih kosong</h2>
                <p class="text-muted">Pilih karya dari katalog untuk mulai berbelanja.</p>
                <a href="{{ route('customer.dashboard') }}" class="btn btn-brand">Jelajahi katalog</a>
            </div>
        @else
            <div class="cart-layout">
                <div>
                    {{-- Toolbar bulk select --}}
                    <div class="cart-bulkbar">
                        <label class="cart-check"><input type="checkbox" id="cart-select-all"> <span>Pilih semua</span></label>
                        <span class="cart-selected-count" id="cart-selected-count">0 dipilih</span>
                        <form action="{{ route('cart.items.destroySelected') }}" method="POST" id="cart-bulk-form" onsubmit="return confirm('Hapus item yang dipilih?')">
                            @csrf @method('DELETE')
                            <span id="cart-bulk-inputs"></span>
                            <button type="submit" class="btn btn-sm btn-outline-danger" id="cart-bulk-delete" disabled><i class="fa fa-trash"></i> Hapus terpilih</button>
                        </form>
                    </div>

                    {{-- Tabel (desktop) --}}
                    <div class="cart-table">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th style="width:36px"></th>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td><input type="checkbox" class="cart-item-check" value="{{ $item['product']->id_products }}" data-subtotal="{{ $item['subtotal'] }}" @checked(in_array($item['product']->id_products, $selectedIds, true))></td>
                                        <td>
                                            <div class="cart-product">
                                                <img src="{{ $item['product']->image_url ? asset('storage/'.$item['product']->image_url) : asset('assets/images/collection/arrivals1.png') }}" alt="{{ $item['product']->name }}">
                                                <strong>{{ $item['product']->name }}</strong>
                                            </div>
                                        </td>
                                        <td>Rp {{ number_format($item['product']->price,0,',','.') }}</td>
                                        <td>
                                            <form action="{{ route('cart.items.update',$item['product']) }}" method="POST" class="cart-stepper" data-cart-stepper>
                                                @csrf @method('PATCH')
                                                <button type="button" class="qty-btn" data-step="-1" aria-label="Kurangi">&minus;</button>
                                                <input class="qty-input" type="text" inputmode="numeric" name="quantity" value="{{ $item['quantity'] }}" data-qty readonly>
                                                <button type="button" class="qty-btn" data-step="1" aria-label="Tambah">+</button>
                                            </form>
                                        </td>
                                        <td class="price">Rp {{ number_format($item['subtotal'],0,',','.') }}</td>
                                        <td>
                                            <form action="{{ route('cart.items.destroy',$item['product']) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Kartu (mobile) --}}
                    <div class="cart-cards">
                        @foreach($items as $item)
                            <div class="cart-card">
                                <label class="cart-card-check"><input type="checkbox" class="cart-item-check" value="{{ $item['product']->id_products }}" data-subtotal="{{ $item['subtotal'] }}" @checked(in_array($item['product']->id_products, $selectedIds, true))></label>
                                <img class="cart-card-img" src="{{ $item['product']->image_url ? asset('storage/'.$item['product']->image_url) : asset('assets/images/collection/arrivals1.png') }}" alt="{{ $item['product']->name }}">
                                <div class="cart-card-body">
                                    <strong class="cart-card-title">{{ $item['product']->name }}</strong>
                                    <div class="cart-card-price">Rp {{ number_format($item['product']->price,0,',','.') }}</div>
                                    <div class="cart-card-row">
                                        <form action="{{ route('cart.items.update',$item['product']) }}" method="POST" class="cart-stepper" data-cart-stepper>
                                            @csrf @method('PATCH')
                                            <button type="button" class="qty-btn" data-step="-1" aria-label="Kurangi">&minus;</button>
                                            <input class="qty-input" type="text" inputmode="numeric" name="quantity" value="{{ $item['quantity'] }}" data-qty readonly>
                                            <button type="button" class="qty-btn" data-step="1" aria-label="Tambah">+</button>
                                        </form>
                                        <form action="{{ route('cart.items.destroy',$item['product']) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button>
                                        </form>
                                    </div>
                                    <div class="cart-card-subtotal">Subtotal: <strong class="price">Rp {{ number_format($item['subtotal'],0,',','.') }}</strong></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <aside class="cart-summary">
                    <h2>Ringkasan belanja</h2>
                    <div class="cart-total"><span>Total</span><strong class="price" id="cart-total-value">Rp {{ number_format($total,0,',','.') }}</strong></div>
                    <p class="small text-muted mt-2 mb-0" id="cart-total-note">{{ count($selectedIds) }} dari {{ $items->count() }} produk dipilih.</p>
                    @guest('customer')
                        <p class="small text-muted mt-3">Login atau daftar diperlukan sebelum checkout.</p>
                        <a href="{{ route('login') }}" class="btn btn-brand w-100">Login untuk checkout</a>
                    @else
                        <form action="{{ route('cart.checkout') }}" method="POST" id="cart-checkout-form">
                            @csrf
                            <span id="cart-checkout-inputs"></span>
                            <button type="submit" class="btn btn-brand w-100 mt-3">Lanjut ke checkout</button>
                        </form>
                    @endguest
                </aside>
            </div>
        @endif
    </div>
</main>
@endsection

@push('scripts')
<script>
(function(){
    var selectAll = document.getElementById('cart-select-all');
    var checks = Array.prototype.slice.call(document.querySelectorAll('.cart-item-check'));
    var countEl = document.getElementById('cart-selected-count');
    var totalEl = document.getElementById('cart-total-value');
    var noteEl = document.getElementById('cart-total-note');
    var delBtn = document.getElementById('cart-bulk-delete');
    if (!checks.length) return;

    // Tabel (desktop) dan kartu (mobile) memakai value yang sama, jadi jumlah
    // produk unik dihitung dari value, bukan dari jumlah checkbox.
    var totalProducts = Object.keys(checks.reduce(function(acc, c){ acc[c.value] = true; return acc; }, {})).length;

    var subtotals = {};
    checks.forEach(function(c){ subtotals[c.value] = parseFloat(c.getAttribute('data-subtotal')) || 0; });

    function selectedIds(){
        var set = {};
        checks.forEach(function(c){ if(c.checked) set[c.value]=true; });
        return Object.keys(set);
    }
    function rupiah(value){ return 'Rp ' + Math.round(value).toLocaleString('id-ID'); }
    function inject(box, ids){
        if (!box) return;
        box.innerHTML = '';
        ids.forEach(function(id){
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'product_ids[]';
            input.value = id;
            box.appendChild(input);
        });
    }
    function sync(source){
        // Samakan status antar checkbox dengan value sama (desktop <-> mobile)
        if (source){
            checks.forEach(function(c){ if(c.value===source.value) c.checked = source.checked; });
        }
        var ids = selectedIds();
        countEl.textContent = ids.length + ' dipilih';
        delBtn.disabled = ids.length === 0;

        var sum = 0;
        ids.forEach(function(id){ sum += subtotals[id] || 0; });
        if (totalEl) totalEl.textContent = rupiah(sum);
        if (noteEl) noteEl.textContent = ids.length + ' dari ' + totalProducts + ' produk dipilih.';

        if (selectAll) selectAll.checked = ids.length > 0 && ids.length === totalProducts;
    }

    checks.forEach(function(c){ c.addEventListener('change', function(){ sync(c); }); });
    if (selectAll){
        selectAll.addEventListener('change', function(){
            checks.forEach(function(c){ c.checked = selectAll.checked; });
            sync(null);
        });
    }

    // Checkbox berada di dalam tabel, jadi tidak bisa memakai atribut form.
    // Isian dikirim ke form yang sedang disubmit lewat input tersembunyi.
    var bulkForm = document.getElementById('cart-bulk-form');
    if (bulkForm){
        bulkForm.addEventListener('submit', function(e){
            var ids = selectedIds();
            if (!ids.length){ e.preventDefault(); return; }
            inject(document.getElementById('cart-bulk-inputs'), ids);
        });
    }
    var checkoutForm = document.getElementById('cart-checkout-form');
    if (checkoutForm){
        checkoutForm.addEventListener('submit', function(e){
            var ids = selectedIds();
            if (!ids.length){
                e.preventDefault();
                alert('Pilih minimal satu produk untuk di-checkout.');
                return;
            }
            inject(document.getElementById('cart-checkout-inputs'), ids);
        });
    }

    sync(null);
})();

// Stepper qty di cart: ubah nilai lalu submit form update (subtotal & total dihitung server)
document.querySelectorAll('[data-cart-stepper]').forEach(function(form){
    var input = form.querySelector('[data-qty]');
    form.querySelectorAll('.qty-btn').forEach(function(btn){
        btn.addEventListener('click', function(){
            var step = parseInt(btn.getAttribute('data-step'), 10);
            var val = Math.max(1, (parseInt(input.value, 10) || 1) + step);
            if (val === (parseInt(input.value, 10) || 1)) return; // tak berubah (mis. minus di 1)
            input.value = val;
            form.submit();
        });
    });
});
</script>
@endpush
