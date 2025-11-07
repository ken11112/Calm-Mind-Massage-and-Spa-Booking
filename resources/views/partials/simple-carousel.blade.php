<div id="simple-carousel" class="slide-content">
    @php
        $items = collect();
        if(isset($galleries)){
            foreach($galleries as $g) { $items->push(asset('storage/' . $g->image_path)); }
        }
        if(isset($staticFiles)){
            foreach($staticFiles as $f) { $items->push(asset($f)); }
        }
    @endphp

    <div class="carousel-frame">
        @foreach($items as $i => $url)
            <div class="mySlides" data-index="{{ $i }}">
                <img src="{{ $url }}" alt="Slide {{ $i + 1 }}">
            </div>
        @endforeach

    <button class="arrow arrow-left" id="arrow-left" onclick="window.simpleCarouselPrev && window.simpleCarouselPrev()" aria-label="Previous slide" type="button"></button>
    <button class="arrow arrow-right" id="arrow-right" onclick="window.simpleCarouselNext && window.simpleCarouselNext()" aria-label="Next slide" type="button"></button>

    </div>

    <div class="thumbnail" role="tablist" aria-label="Thumbnails">
        @foreach($items as $i => $url)
            <div class="column">
                <img class="demo cursor" src="{{ $url }}" data-slide="{{ $i }}" alt="Thumbnail {{ $i + 1 }}">
            </div>
        @endforeach
    </div>

    <style>
        /* page-level reset for this component */
        * { box-sizing: border-box; margin:0; padding:0; }
        #simple-carousel { background: #dff9ff; padding: 28px 0; }
        img { vertical-align: middle; width: 100%; height: auto; display:block; }

        /* centered white frame where the slide sits */
    .carousel-frame { max-width: 1024px; margin: 0 auto; background: #ffffff; padding: 12px; border-radius: 6px; box-shadow: 0 6px 18px rgba(0,0,0,0.08); position: relative; }

    /* main slide area: give explicit height so layout doesn't collapse */
    .mySlides { width: 100%; display:none; height:480px; overflow:hidden; border-radius:4px; }
    .mySlides img { width:100%; height:100%; object-fit:cover; display:block; }
    /* CSS fallback: show first slide if JS fails */
    .mySlides:first-child { display:block; }

        /* arrows: large clickable areas with white triangular icons */
    .arrow { position:absolute; top:50%; transform:translateY(-50%); width:56px; height:56px; background:transparent; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; z-index:60; pointer-events:auto; }
        .arrow:focus{ outline:none; }
        .arrow::before { content:''; display:block; width:0; height:0; border-style:solid; }
    .arrow-left { left:12px; }
    .arrow-left::before { border-width:20px 28px 20px 0; border-color: transparent #ffffff transparent transparent; filter: drop-shadow(0 2px 3px rgba(0,0,0,0.4)); }
    .arrow-right { right:12px; }
    .arrow-right::before { border-width:20px 0 20px 28px; border-color: transparent transparent transparent #ffffff; filter: drop-shadow(0 2px 3px rgba(0,0,0,0.4)); }

        /* thumbnail bar */
        .thumbnail { display:flex; gap:10px; max-width:1024px; margin:14px auto 0; padding:8px 4px; }
        .column { flex:1 1 0; min-width:80px; max-width:220px; }
        .column img { width:100%; height:80px; object-fit:cover; border-radius:4px; filter:brightness(0.96); }

        /* overlay effect for inactive thumbnails */
        .demo { opacity:0.6; transition:opacity .18s, transform .18s; cursor:pointer; }
        .demo.active { opacity:1; transform:scale(1.02); box-shadow:0 6px 16px rgba(0,0,0,0.12); }

        /* responsive adjustments */
        @media (max-width: 768px) {
            .carousel-frame { padding:8px; }
            .arrow { width:48px; height:48px; }
            .arrow::before { border-width:12px 18px 12px 0; }
            .column img { height:56px; }
        }
    </style>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function(){
        const container = document.getElementById('simple-carousel');
        if (!container) return;
        const slides = Array.from(container.querySelectorAll('.mySlides'));
        const thumbs = Array.from(container.querySelectorAll('.demo'));
        const left = container.querySelector('#arrow-left');
        const right = container.querySelector('#arrow-right');
        let index = 0;

        function show(n){
            if (slides.length === 0) return;
            index = (n + slides.length) % slides.length;
            slides.forEach((s,i)=> s.style.display = (i===index) ? 'block' : 'none');
            thumbs.forEach((t,i)=> t.classList.toggle('active', i===index));
        }

        function next(){ show(index+1); }
        function prev(){ show(index-1); }

        if (right) {
            right.addEventListener('click', (e)=>{ e.preventDefault(); next(); });
            // also listen in capture phase as a fallback
            right.addEventListener('click', (e)=>{ e.stopPropagation(); next(); }, true);
        }
        if (left) {
            left.addEventListener('click', (e)=>{ e.preventDefault(); prev(); });
            left.addEventListener('click', (e)=>{ e.stopPropagation(); prev(); }, true);
        }

        thumbs.forEach(t => t.addEventListener('click', (e)=>{ e.preventDefault(); const s = parseInt(t.getAttribute('data-slide')); if(!isNaN(s)) show(s); }));

        // keyboard support
        document.addEventListener('keydown', (e)=>{
            if (e.key === 'ArrowRight') next();
            if (e.key === 'ArrowLeft') prev();
        });

        // touch swipe support
        let startX = null;
        const frame = container.querySelector('.carousel-frame');
        if (frame) {
            frame.addEventListener('touchstart', (e)=>{ if (e.touches && e.touches[0]) startX = e.touches[0].clientX; }, {passive:true});
            frame.addEventListener('touchend', (e)=>{ if (!startX || !e.changedTouches || !e.changedTouches[0]) return; const dx = e.changedTouches[0].clientX - startX; if (Math.abs(dx) > 40) { if (dx < 0) next(); else prev(); } startX = null; }, {passive:true});
        }

    // large clickable side areas (fallback) + delegated arrow click fallback
        const leftArea = document.createElement('div');
        leftArea.style.cssText = 'position:absolute;left:0;top:0;height:100%;width:16%;z-index:40;cursor:pointer;';
        const rightArea = document.createElement('div');
        rightArea.style.cssText = 'position:absolute;right:0;top:0;height:100%;width:16%;z-index:40;cursor:pointer;';
        if (frame) { frame.appendChild(leftArea); frame.appendChild(rightArea); leftArea.addEventListener('click', prev); rightArea.addEventListener('click', next); }

        // delegated fallback for arrow buttons (in case direct listeners are blocked)
        document.addEventListener('click', function(e){
            const aRight = e.target.closest && e.target.closest('#arrow-right');
            const aLeft = e.target.closest && e.target.closest('#arrow-left');
            if (aRight) { try { next(); e.preventDefault(); } catch(e){} }
            if (aLeft) { try { prev(); e.preventDefault(); } catch(e){} }
        }, false);

        // ---- DEBUG: visual click tracker (temporary) ----
        // Creates a small overlay to show which element received the last pointerdown/click.
        const clickDebug = document.createElement('div');
        clickDebug.id = 'carousel-click-debug';
        clickDebug.style.cssText = 'position:fixed;left:12px;bottom:12px;z-index:999999;background:#111;color:#fff;padding:8px 10px;border-radius:6px;font-family:monospace;font-size:12px;opacity:0.95;';
        clickDebug.innerText = 'last: none';
        document.body.appendChild(clickDebug);

        const high = document.createElement('div');
        high.id = 'carousel-click-highlighter';
        high.style.cssText = 'position:absolute;pointer-events:none;box-shadow:0 0 0 3px rgba(255,165,0,0.9) inset, 0 8px 24px rgba(255,165,0,0.12);border-radius:6px;transition:all .18s ease;opacity:0;z-index:999998;';
        document.body.appendChild(high);

        function showHighlight(el){
            if(!el) return;
            const r = el.getBoundingClientRect();
            high.style.left = (r.left - 6) + 'px';
            high.style.top = (r.top - 6) + 'px';
            high.style.width = (r.width + 12) + 'px';
            high.style.height = (r.height + 12) + 'px';
            high.style.opacity = '1';
            setTimeout(()=> high.style.opacity = '0', 900);
        }

        document.addEventListener('pointerdown', function(e){
            const tgt = e.target;
            let label = tgt.tagName.toLowerCase();
            if(tgt.id) label += '#' + tgt.id;
            if(tgt.className) label += '.' + tgt.className.toString().split(' ').join('.');
            clickDebug.innerText = 'last: ' + label;
            try{ showHighlight(tgt); }catch(err){}
        }, {capture:true, passive:true});

        // Also log arrow clicks explicitly
        if (right) right.addEventListener('click', (e)=>{ console.log('[debug] arrow-right clicked, calling next()'); });
        if (left) left.addEventListener('click', (e)=>{ console.log('[debug] arrow-left clicked, calling prev()'); });
        // -------------------------------------------------

        // expose for compatibility
        try{ window.simpleCarouselNext = next; window.simpleCarouselPrev = prev; }catch(e){}

        show(0);
    });
    </script>
    @endpush

</div>
