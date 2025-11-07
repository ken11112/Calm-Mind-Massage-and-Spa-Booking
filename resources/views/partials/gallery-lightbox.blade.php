<div id="gallery-lightbox" class="fixed inset-0 bg-black bg-opacity-80 hidden items-center justify-center z-50">
    <button id="lb-close" class="absolute top-6 right-6 text-white text-2xl p-2">&times;</button>

    <div class="relative max-w-4xl w-full mx-4">
        <img id="lb-image" src="" alt="" class="mx-auto max-h-[80vh] object-contain transition-transform duration-150" style="transform: scale(1);">

        <div class="absolute inset-y-0 left-0 flex items-center">
            <button id="lb-prev" class="text-white bg-black/40 hover:bg-black/60 p-3 rounded-full ml-2">&#9664;</button>
        </div>
        <div class="absolute inset-y-0 right-0 flex items-center">
            <button id="lb-next" class="text-white bg-black/40 hover:bg-black/60 p-3 rounded-full mr-2">&#9654;</button>
        </div>

        <!-- invisible large click areas as fallback for prev/next -->
        <div id="lb-prev-area" aria-hidden="true" style="position:absolute;left:0;top:0;height:100%;width:30%;z-index:49;cursor:pointer;"></div>
        <div id="lb-next-area" aria-hidden="true" style="position:absolute;right:0;top:0;height:100%;width:30%;z-index:49;cursor:pointer;"></div>

        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center space-x-2">
            <button id="lb-zoom-out" class="text-white bg-black/40 hover:bg-black/60 p-2 rounded">-</button>
            <div id="lb-zoom-level" class="text-white text-sm px-2">100%</div>
            <button id="lb-zoom-in" class="text-white bg-black/40 hover:bg-black/60 p-2 rounded">+</button>
        </div>
    </div>
</div>
<script>
    (function(){
        // wire the lightbox prev/next buttons and fallback areas
        document.addEventListener('DOMContentLoaded', function(){
            const lbPrev = document.getElementById('lb-prev');
            const lbNext = document.getElementById('lb-next');
            const lbPrevArea = document.getElementById('lb-prev-area');
            const lbNextArea = document.getElementById('lb-next-area');

            function triggerPrev(){
                try{ if (typeof window.lbPrev === 'function') { window.lbPrev(); } else { lbPrev && lbPrev.click(); } }catch(e){ console.warn('lb prev trigger error',e); }
            }
            function triggerNext(){
                try{ if (typeof window.lbNext === 'function') { window.lbNext(); } else { lbNext && lbNext.click(); } }catch(e){ console.warn('lb next trigger error',e); }
            }

            if (lbPrev) lbPrev.addEventListener('click', (e)=>{ e.preventDefault(); console.log('lb-prev clicked'); });
            if (lbNext) lbNext.addEventListener('click', (e)=>{ e.preventDefault(); console.log('lb-next clicked'); });

            if (lbPrevArea) lbPrevArea.addEventListener('click', (e)=>{ e.preventDefault(); console.log('lb-prev-area clicked'); triggerPrev(); });
            if (lbNextArea) lbNextArea.addEventListener('click', (e)=>{ e.preventDefault(); console.log('lb-next-area clicked'); triggerNext(); });

            // delegated fallback: if someone triggers custom events, listen and pass through
            document.addEventListener('click', function(e){
                const n = e.target.closest && e.target.closest('#lb-next');
                const p = e.target.closest && e.target.closest('#lb-prev');
                if (n) { console.log('delegated lightbox next'); triggerNext(); }
                if (p) { console.log('delegated lightbox prev'); triggerPrev(); }
            });
        });
    })();
</script>
