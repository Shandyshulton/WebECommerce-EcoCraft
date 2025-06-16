@extends('seller.dashboard')

@section('content')
<div class="container">
    <h2>Edit Order</h2>
    <form id="editOrderForm">
        <input type="hidden" id="orderIndex" value="{{ $index }}">

        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-4 align-items-start">
                    <!-- KIRI -->
                    <div class="col-md-6 d-flex flex-column gap-3">
                        <div>
                            <label>Name*</label>
                            <input id="name" class="form-control" required>
                        </div>
                        <div>
                            <label>Address*</label>
                            <input id="address" class="form-control" required>
                        </div>
                        <div>
                            <label>State*</label>
                            <input id="state" class="form-control" required>
                        </div>
                        <div>
                            <label>Postal Code*</label>
                            <input id="postal" class="form-control" required>
                        </div>
                        <div>
                            <label>Email*</label>
                            <input id="email" type="email" class="form-control" required>
                        </div>
                        <div>
                            <label>Phone*</label>
                            <input id="phone" class="form-control" required>
                        </div>
                        <div>
                            <label>Product Name*</label>
                            <input id="product" class="form-control" required>
                        </div>
                    </div>

                    <!-- KANAN -->
                    <div class="col-md-6 d-flex flex-column gap-3">
                        <div>
                            <label>Quantity*</label>
                            <input id="quantity" type="number" class="form-control" required>
                        </div>
                        <div>
                            <label>Jenis Pengiriman*</label>
                            <select id="shipping" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <option value="Reguler">Reguler</option>
                                <option value="Express">Express</option>
                                <option value="Sameday">Sameday</option>
                            </select>
                        </div>
                        <div>
                            <label>Metode Pembayaran*</label>
                            <select id="payment" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <option value="COD">COD</option>
                                <option value="Transfer Bank">Transfer Bank</option>
                                <option value="QRIS">QRIS</option>
                            </select>
                        </div>
                        <div>
                            <label>Subtotal*</label>
                            <input id="subtotal" type="number" class="form-control" required>
                        </div>
                        <div>
                            <label>Total*</label>
                            <input id="total" type="number" class="form-control" required>
                        </div>
                        <div>
                            <label>Status*</label>
                            <select id="status" class="form-control" required>
                                <option value="Hold">Hold</option>
                                <option value="Processing">Processing</option>
                                <option value="Shipped">Shipped</option>
                                <option value="Delivered">Delivered</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button class="btn btn-primary">Update Order</button>
        <a href="{{ route('order.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script>
    const index = parseInt(document.getElementById('orderIndex').value);
    const orders = JSON.parse(localStorage.getItem('orders')) || [];
    const order = orders[index];

    // Prefill data
    document.getElementById('name').value = order.name;
    document.getElementById('address').value = order.address;
    document.getElementById('state').value = order.state;
    document.getElementById('postal').value = order.postal;
    document.getElementById('email').value = order.email;
    document.getElementById('phone').value = order.phone;
    document.getElementById('product').value = order.product;
    document.getElementById('quantity').value = order.quantity;
    document.getElementById('shipping').value = order.shipping;
    document.getElementById('payment').value = order.payment;
    document.getElementById('subtotal').value = order.subtotal;
    document.getElementById('total').value = order.total;
    document.getElementById('status').value = order.status || 'Hold';

    // Update event
    document.getElementById('editOrderForm').addEventListener('submit', function (e) {
        e.preventDefault();

        orders[index] = {
            name: document.getElementById('name').value,
            address: document.getElementById('address').value,
            state: document.getElementById('state').value,
            postal: document.getElementById('postal').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            product: document.getElementById('product').value,
            quantity: document.getElementById('quantity').value,
            shipping: document.getElementById('shipping').value,
            payment: document.getElementById('payment').value,
            subtotal: document.getElementById('subtotal').value,
            total: document.getElementById('total').value,
            status: document.getElementById('status').value
        };

        localStorage.setItem('orders', JSON.stringify(orders));
        window.location.href = "{{ route('order.index') }}";
    });
</script>

<style>
    .container {
        max-width: 960px;
        margin: 0 auto;
        padding: 20px;
    }

    .card {
        border-radius: 0.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border: none;
        background-color: #fff;
    }

    .form-control {
        border-radius: 0.375rem;
        padding: 10px;
        border: 1px solid #ced4da;
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
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-secondary {
        margin-left: 10px;
    }
</style>
@endsection
