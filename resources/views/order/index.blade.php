@extends('seller.dashboard')

@section('content')
<div class="container">
    <h2>Order List</h2>
    <a href="{{ route('order.create') }}" class="btn btn-primary mb-3">Add Order</a>
    
    <table class="table table-bordered" id="order-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Name</th>
                <th>Address</th>
                <th>Email</th>
                <th>Phone</th>
                <th>State</th>
                <th>Postal</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Jenis Pengiriman</th>
                <th>Metode Pembayaran</th>
                <th>Subtotal</th>
                <th>Total</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="order-body"></tbody>
    </table>
</div>

<script>
    let orders = JSON.parse(localStorage.getItem('orders')) || [];

    function getStatusBadgeClass(status) {
        switch (status) {
            case 'Processing':
                return 'bg-info';
            case 'Shipped':
                return 'bg-primary';
            case 'Delivered':
                return 'bg-success';
            case 'Cancelled':
                return 'bg-danger';
            default:
                return 'bg-secondary'; // Default to Hold
        }
    }

    function renderOrders() {
        const tbody = document.getElementById('order-body');
        tbody.innerHTML = '';
        orders.forEach((order, index) => {
            const statusClass = getStatusBadgeClass(order.status);
            const statusText = order.status || 'Hold';

            tbody.innerHTML += `
                <tr>
                    <td>ORD-${index + 1}</td>
                    <td>${order.name}</td>
                    <td>${order.address}</td>
                    <td>${order.email}</td>
                    <td>${order.phone}</td>
                    <td>${order.state}</td>
                    <td>${order.postal}</td>
                    <td>${order.product}</td>
                    <td>${order.quantity}</td>
                    <td>${order.shipping}</td>
                    <td>${order.payment}</td>
                    <td>${order.subtotal}</td>
                    <td>${order.total}</td>
                    <td><span class="badge ${statusClass}">${statusText}</span></td>
                    <td>
                        <a href="/order/edit/${index}" class="btn btn-sm btn-warning">Edit</a>
                        <button onclick="deleteOrder(${index})" class="btn btn-sm btn-danger">Delete</button>
                    </td>
                </tr>`;
        });
    }

    function deleteOrder(index) {
        if (confirm('Yakin ingin menghapus order ini?')) {
            orders.splice(index, 1);
            localStorage.setItem('orders', JSON.stringify(orders));
            renderOrders();
        }
    }

    renderOrders();
</script>
@endsection
