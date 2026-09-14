@php($storyUrl = $story->slug ? route('community.show', $story->slug) : route('community.index'))
<article class="community-story-card {{ $hidden ? 'is-hidden' : '' }}">
    <a class="community-story-card-link" href="{{ $storyUrl }}">
        <img
            class="community-story-card-image"
            src="{{ $story->image ? asset(str_replace(' ', '%20', $story->image)) : asset('assets/images/collection/arrivals1.png') }}"
            alt="{{ $story->title }}"
            loading="lazy"
        >
    </a>
    <div class="community-story-card-body">
        @if($story->label)
            <small class="eyebrow" style="color:var(--accent)">{{ $story->label }}</small>
        @endif
        <h3><a href="{{ $storyUrl }}">{{ $story->title }}</a></h3>
        <p>{{ $story->excerpt }}</p>
        <a href="{{ $storyUrl }}">Baca cerita <span aria-hidden="true">&rarr;</span></a>
    </div>
</article>
