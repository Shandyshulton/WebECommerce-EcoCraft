@extends('seller.dashboard')
<style>.fa-grid-2::before{content:"\f00a";font-family:"Font Awesome 6 Free";font-weight:900}</style>

@section('content')
<div class="container-fluid"><div class="page-heading"><div><div class="eyebrow">Operasional toko</div><h1 style="font:600 38px/1 'EB Garamond',serif">Edit pesanan</h1><p class="subtle mb-0">{{ $order->order_number }}</p></div></div>
    @include('order._form', ['action' => route('order.update', $order), 'method' => 'PUT', 'order' => $order])
</div>
@endsection
