{{--
    Lightbox gambar — dipakai untuk bukti pengiriman di halaman pembeli,
    pengrajin, dan kurir. Sengaja dibuat mandiri (markup + gaya + skrip dalam
    satu file) supaya cukup di-@include dan tidak bergantung pada layout mana pun.

    Pemakaian: bungkus gambar dengan <a data-lightbox-trigger href="...">
    lalu @include('partials.image-lightbox') di halaman tersebut.
--}}
<div class="img-lightbox" data-lightbox hidden>
    <button type="button" class="img-lightbox-close" data-lightbox-close aria-label="Tutup gambar">&times;</button>
    <img class="img-lightbox-img" data-lightbox-img src="" alt="">
</div>

<style>
    .img-lightbox { position:fixed; inset:0; z-index:9999; display:flex; align-items:center; justify-content:center; padding:24px; background:rgba(20,28,24,.84); }
    /* `hidden` dari browser kalah oleh display:flex di atas, jadi perlu ditegaskan. */
    .img-lightbox[hidden] { display:none; }
    .img-lightbox-img { max-width:100%; max-height:calc(100vh - 48px); border-radius:12px; box-shadow:0 24px 60px -12px rgba(0,0,0,.55); background:#fff; }
    .img-lightbox-close { position:absolute; top:16px; right:18px; width:42px; height:42px; display:grid; place-items:center; border:0; border-radius:50%; background:rgba(255,255,255,.94); color:#1b2520; font-size:26px; line-height:1; cursor:pointer; }
    .img-lightbox-close:hover { background:#fff; }
    [data-lightbox-trigger] { cursor:zoom-in; display:inline-block; }
</style>

<script>
(function () {
    var box = document.querySelector('[data-lightbox]');
    if (!box) return;

    var img = box.querySelector('[data-lightbox-img]');
    var triggers = document.querySelectorAll('[data-lightbox-trigger]');
    if (!triggers.length) return;

    function close() {
        box.hidden = true;
        img.removeAttribute('src');
        document.body.style.overflow = '';
    }

    function open(trigger) {
        img.src = trigger.getAttribute('href') || trigger.getAttribute('data-src');
        img.alt = trigger.getAttribute('data-alt') || 'Gambar';
        box.hidden = false;
        document.body.style.overflow = 'hidden';
        box.querySelector('[data-lightbox-close]').focus();
    }

    triggers.forEach(function (trigger) {
        trigger.addEventListener('click', function (event) {
            event.preventDefault();
            open(trigger);
        });
    });

    box.addEventListener('click', function (event) {
        // Klik di area gelap atau di tombol tutup sama-sama menutup.
        if (event.target === box || event.target.hasAttribute('data-lightbox-close')) close();
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && !box.hidden) close();
    });
})();
</script>
