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
        </div>
        <div class="page-wrap page-wrap-bleed">
            <div class="community-row-track" id="story-row-featured">
                @foreach($featured as $story)
                    @include('customer.partials.story-card', ['story' => $story, 'hidden' => $loop->index >= 5])
                @endforeach
                @if($featured->count() > 5)
                    <button type="button" class="story-row-more" data-load-more="story-row-featured">Muat lebih banyak<span aria-hidden="true">+</span></button>
                @endif
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
            </div>
            <div class="page-wrap page-wrap-bleed">
                <div class="community-row-track" id="story-row-topic-{{ Str::slug($topicTitle) }}">
                    @foreach($topicStories as $story)
                        @include('customer.partials.story-card', ['story' => $story, 'hidden' => $loop->index >= 5])
                    @endforeach
                    @if($topicStories->count() > 5)
                        <button type="button" class="story-row-more" data-load-more="story-row-topic-{{ Str::slug($topicTitle) }}">Muat lebih banyak<span aria-hidden="true">+</span></button>
                    @endif
                </div>
            </div>
        </section>
    @endforeach
@endif
@endsection

@push('scripts')
<script>
    // "Muat lebih banyak": tampilkan 5 kartu tersembunyi berikutnya di baris terkait
    document.querySelectorAll('[data-load-more]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var row = document.getElementById(btn.getAttribute('data-load-more'));
            if (!row) return;

            var hidden = row.querySelectorAll('.community-story-card.is-hidden');
            var shown = 0;
            while (shown < 5 && shown < hidden.length) {
                hidden[shown].classList.remove('is-hidden');
                shown++;
            }

            if (row.querySelectorAll('.community-story-card.is-hidden').length === 0) {
                btn.style.display = 'none';
            }
        });
    });
</script>
@endpush
