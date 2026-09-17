@extends('layouts.courier')

@section('title', 'Tugas Saya | Kurir EcoCraft')

@section('content')
<div class="c-card">
    <h2>Halo, {{ $courier->name }}</h2>
    <p class="hint mb-0">{{ $courier->courier?->name }} &middot; {{ $tasks->count() }} paket menunggu diantar.</p>
</div>

<div class="c-section-title">Tugas saya</div>
@forelse($tasks as $task)
    <a class="c-task" href="{{ route('courier.tasks.show', $task) }}">
        <div class="c-task-top">
            <span class="c-task-no">#{{ $task->order?->order_number }}</span>
            <span class="c-badge {{ $task->statusClass() }}">{{ $task->status }}</span>
        </div>
        <div class="c-task-name">{{ $task->order?->customer_name }}</div>
        <div class="c-task-meta">{{ $task->order?->shipping_address }}, {{ $task->order?->shipping_city }}</div>
    </a>
@empty
    <div class="c-card"><p class="c-empty mb-0">Belum ada tugas. Ambil paket dari daftar di bawah.</p></div>
@endforelse

<div class="c-section-title">Tersedia untuk diambil</div>
@forelse($available as $item)
    <div class="c-task">
        <div class="c-task-top">
            <span class="c-task-no">#{{ $item->order?->order_number }}</span>
            <span class="c-badge warn">Siap diantar</span>
        </div>
        <div class="c-task-name">{{ $item->order?->customer_name }}</div>
        <div class="c-task-meta mb-3">{{ $item->order?->shipping_address }}, {{ $item->order?->shipping_city }}</div>
        <form action="{{ route('courier.tasks.claim', $item) }}" method="POST">
            @csrf
            <button class="c-btn" type="submit"><i class="fas fa-hand-holding-box"></i> Ambil tugas</button>
        </form>
    </div>
@empty
    <div class="c-card"><p class="c-empty mb-0">Belum ada paket yang siap diambil.</p></div>
@endforelse

@if($finished->isNotEmpty())
    <div class="c-section-title">Selesai baru-baru ini</div>
    @foreach($finished as $done)
        <div class="c-task">
            <div class="c-task-top">
                <span class="c-task-no">#{{ $done->order?->order_number }}</span>
                <span class="c-badge ok">Terkirim</span>
            </div>
            <div class="c-task-meta">{{ $done->receiver_name ?: 'Penerima' }} &middot; {{ $done->delivered_at?->translatedFormat('d M Y, H:i') }}</div>
        </div>
    @endforeach
@endif
@endsection
