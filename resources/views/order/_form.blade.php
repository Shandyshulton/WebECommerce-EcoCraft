<style>
.seller-form-card{padding:24px;background:#fff;border:1px solid var(--line);border-radius:14px}.seller-form-card .form-label{color:var(--ink);font-size:11px;font-weight:800;margin-bottom:7px}.seller-form-card .form-control,.seller-form-card .form-select{min-height:44px;border:1px solid var(--line);border-radius:8px;background:#f8faf8;font-size:12px}.seller-form-card .form-control:focus,.seller-form-card .form-select:focus{border-color:var(--brand);box-shadow:0 0 0 3px rgba(30,75,56,.12)}.seller-form-card textarea{min-height:92px}.seller-form-card .btn{border-radius:7px;font-size:11px;font-weight:800;padding:9px 14px}.seller-form-card .btn-primary{background:var(--brand);border-color:var(--brand)}.seller-form-card .btn-secondary{background:#f1f4f1;border-color:var(--line);color:var(--ink)}@media(max-width:640px){.seller-form-card{padding:16px}.seller-form-card .btn{width:100%;margin-top:8px!important}}
</style>
<div class="seller-form-card">
@if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif

@php($item = $order?->items->first())
<form action="{{ $action }}" method="POST">
    @csrf
    @if($method !== 'POST') @method($method) @endif

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Product</label>
            <select name="product_id" class="form-select" required>
                <option value="">Choose product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id_products }}" @selected(old('product_id', $item?->product_id) == $product->id_products)>
                        {{ $product->name }} - Rp {{ number_format($product->price, 0, ',', '.') }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Quantity</label>
            <input type="number" name="quantity" min="1" class="form-control" value="{{ old('quantity', $item?->quantity ?? 1) }}" required>
        </div>
        <div class="col-md-6"><label class="form-label">Customer Name</label><input name="customer_name" class="form-control" value="{{ old('customer_name', $order?->customer_name) }}" required></div>
        <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="customer_email" class="form-control" value="{{ old('customer_email', $order?->customer_email) }}" required></div>
        <div class="col-md-6"><label class="form-label">Phone</label><input name="customer_phone" class="form-control" value="{{ old('customer_phone', $order?->customer_phone) }}" required></div>
        <div class="col-md-6"><label class="form-label">Postal Code</label><input name="shipping_postal_code" class="form-control" value="{{ old('shipping_postal_code', $order?->shipping_postal_code) }}" required></div>
        <div class="col-12"><label class="form-label">Address</label><textarea name="shipping_address" class="form-control" required>{{ old('shipping_address', $order?->shipping_address) }}</textarea></div>
        <div class="col-md-6"><label class="form-label">City</label><input name="shipping_city" class="form-control" value="{{ old('shipping_city', $order?->shipping_city) }}" required></div>
        <div class="col-md-6"><label class="form-label">Province</label><input name="shipping_province" class="form-control" value="{{ old('shipping_province', $order?->shipping_province) }}" required></div>
        <div class="col-md-4"><label class="form-label">Shipping</label><select name="shipping_method" class="form-select" required>@foreach(['Reguler','Express','Sameday'] as $value)<option value="{{ $value }}" @selected(old('shipping_method', $order?->shipping_method) === $value)>{{ $value }}</option>@endforeach</select></div>
        <div class="col-md-4"><label class="form-label">Payment</label><select name="payment_method" class="form-select" required>@foreach(['COD','Transfer Bank','QRIS'] as $value)<option value="{{ $value }}" @selected(old('payment_method', $order?->payment_method) === $value)>{{ $value }}</option>@endforeach</select></div>
        @if($order)
            <div class="col-md-4"><label class="form-label">Status</label><select name="status" class="form-select">@foreach(['Hold','Processing','Shipped','Delivered','Cancelled'] as $value)<option value="{{ $value }}" @selected(old('status', $order->status) === $value)>{{ $value }}</option>@endforeach</select></div>
        @endif
    </div>

    <button class="btn btn-primary mt-4">Save</button>
    <a href="{{ route('order.index') }}" class="btn btn-secondary mt-4">Cancel</a>
</form>
</div>
