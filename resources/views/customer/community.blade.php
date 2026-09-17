@extends('layouts.customer')

@section('title', 'EcoCraft | Komunitas')

@section('content')
<section class="reference-hero">
    <div class="page-wrap">
        <div class="eyebrow">Cerita di balik karya</div>
        <h1>Sorotan komunitas EcoCraft</h1>
        <p class="lead">Kenali pengrajin, material, dan proses yang membuat setiap karya punya cerita.</p>
    </div>
</section>

@if($featured->isEmpty())
    <section class="page-wrap">
        <p class="text-muted">Belum ada cerita komunitas tersedia.</p>
    </section>
@else
    <section class="community-row-section">
        <div class="page-wrap">
            <div class="community-row-head">
                <h2>Sorotan Komunitas</h2>
                <span class="community-row-hint">Geser untuk menjelajah</span>
            </div>
            <div class="community-row-track">
                @foreach($featured as $story)
                    @include('customer.partials.story-card', ['story' => $story])
                @endforeach
            </div>
        </div>
    </section>

    @foreach($topics as $topicTitle => $topicStories)
        @continue($topicStories->isEmpty())
        <section class="community-row-section">
            <div class="page-wrap">
                <div class="community-row-head">
                    <h2>{{ $topicTitle }}</h2>
                </div>
                <div class="community-row-track">
                    @foreach($topicStories as $story)
                        @include('customer.partials.story-card', ['story' => $story])
                    @endforeach
                </div>
            </div>
        </section>
    @endforeach
@endif
@endsection
