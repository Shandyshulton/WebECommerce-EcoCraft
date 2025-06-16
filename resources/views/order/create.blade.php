@extends('seller.dashboard')

@section('content')
<div class="container">
    <h1>Add Order</h1>
    <form onsubmit="saveOrder(event)">
        <div class="card">
            <div class="card-body">
                <div class="row" style="display: flex; gap: 40px; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 300px;">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input name="name" placeholder="Name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input name="address" placeholder="Address" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="state">State</label>
                            <input name="state" placeholder="State" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="posta">Postal Code</label>
                            <input name="posta" placeholder="Postal Code" class="form-control" required>
                        </div>
                    </div>
                    <div style="flex: 1; min-width: 300px;">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input name="email" placeholder="Email" type="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input name="phone" placeholder="Phone" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="product">Product Name</label>
                            <input name="product" placeholder="Product Name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input name="quantity" type="number" placeholder="Quantity" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-group">
            <div class="card card-price">
                <div class="card-body">
                    <div class="form-group">
                        <label for="subtotal">Subtotal</label>
                        <input name="subtotal" placeholder="Subtotal" type="number" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="total">Total</label>
                        <input name="total" placeholder="Total" type="number" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="card card-other">
                <div class="card-body">
                    <div class="form-group">
                        <label for="shipping">Jenis Pengiriman</label>
                        <select name="shipping" class="form-control" required>
                            <option value="">-- Pilih Jenis Pengiriman --</option>
                            <option value="Reguler">Reguler</option>
                            <option value="Express">Express</option>
                            <option value="Sameday">Sameday</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="payment">Metode Pembayaran</label>
                        <select name="payment" class="form-control" required>
                            <option value="">-- Pilih Metode Pembayaran --</option>
                            <option value="COD">COD</option>
                            <option value="Transfer Bank">Transfer Bank</option>
                            <option value="QRIS">QRIS</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <button class="btn btn-primary mt-3">Save Order</button>
    </form>
</div>

<script>
    function saveOrder(e) {
        e.preventDefault();
        const form = e.target;
        const newOrder = {
            name: form.name.value,
            address: form.address.value,
            state: form.state.value,
            postal: form.posta.value,
            email: form.email.value,
            phone: form.phone.value,
            product: form.product.value,
            quantity: form.quantity.value,
            subtotal: form.subtotal.value,
            total: form.total.value,
            shipping: form.shipping.value,
            payment: form.payment.value
        };

        const orders = JSON.parse(localStorage.getItem('orders')) || [];
        orders.push(newOrder);
        localStorage.setItem('orders', JSON.stringify(orders));
        window.location.href = "{{ route('order.index') }}";
    }
</script>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
    }

    .container {
        padding: 20px;
        max-width: 960px;
        margin: 0 auto;
    }

    .card {
        border-radius: 0.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        background-color: #fff;
        border: none;
        margin-bottom: 50px;
    }

    .card-body {
        padding: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: 600;
        display: block;
        margin-bottom: 6px;
    }

    .form-control {
        border-radius: 0.375rem;
        padding: 10px;
        border: 1px solid #ced4da;
        width: 100%;
        box-sizing: border-box;
        transition: 0.3s ease-in-out;
    }

    .form-control:focus {
        border-color: #5bc0de;
        box-shadow: 0 0 8px rgba(91, 188, 222, 0.5);
        outline: none;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 0.375rem;
        cursor: pointer;
        color: #fff;
    }

    .btn-primary:hover {
        background-color: #0069d9;
    }

    .card-group {
        display: flex;
        gap: 40px;
        margin-bottom: 50px;
        flex-wrap: wrap;
    }

    .card-price {
        flex: 0 0 35%;
        min-width: 150px;
    }

    .card-other {
        flex: 1;
        min-width: 100px;
    }
</style>
@endsection
