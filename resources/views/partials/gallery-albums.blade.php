@php
    // When controller passes $albums (Album models with galleries relation), use them directly.
    // Keep $staticFiles fallback for cover images if no gallery images exist.
    $albumCollection = collect();
    if (isset($albums)) {
        // $albums is expected to be a collection of Album models with ->galleries relation
        $albumCollection = collect($albums);
    }
    $staticFiles = $staticFiles ?? [];
@endphp

<style>
    :root {
        --primary: #7c3aed;
        --accent: #06b6d4;
        --blur-strength: 12px;
    }
    
    /* Modern Glass Effect */
    .glass-morph {
        backdrop-filter: blur(calc(var(--blur-strength) / 2));
        background: linear-gradient(180deg, rgba(255,255,255,0.04), rgba(255,255,255,0.02));
        border: 1px solid rgba(255,255,255,0.06);
        box-shadow: 0 6px 22px rgba(2,6,23,0.08);
        position: relative;
        overflow: hidden;
    }

    /* Album card base */
    .album-card {
        position: relative;
        transition: transform 0.36s cubic-bezier(.2,.9,.2,1), box-shadow 0.36s;
        border-radius: 1rem;
        overflow: hidden;
        will-change: transform;
    }

    /* Accent border glow (hover) */
    .album-card::after {
        content: '';
        position: absolute;
        inset: -6px;
        border-radius: inherit;
        background: linear-gradient(90deg, rgba(34,197,94,0.25), rgba(16,185,129,0.18));
        filter: blur(10px);
        opacity: 0;
        transition: opacity 0.28s ease;
        z-index: 0;
        pointer-events: none;
    }

    .album-card:hover::after {
        opacity: 1;
    }

    /* Image polish */
    .album-card figure img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(.2,.9,.2,1), filter 0.4s;
        will-change: transform;
    }

    .album-card:hover figure img {
        transform: scale(1.04);
        filter: saturate(1.05) contrast(1.02);
    }

    /* Strong caption overlay with backdrop for readability */
    .album-card .caption-wrap {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        padding: 1rem 1rem;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: linear-gradient(180deg, rgba(0,0,0,0.0), rgba(0,0,0,0.5));
        backdrop-filter: blur(6px);
    }

    .album-card .caption-wrap .title {
        color: #ffffff;
        text-shadow: 0 2px 8px rgba(0,0,0,0.6);
        font-weight: 800;
        font-size: 1.05rem;
        line-height: 1.05;
    }

    .album-card .caption-wrap .meta {
        color: rgba(255,255,255,0.85);
        font-size: 0.85rem;
    }
    
    /* Entrance Animations */
    .slide-up {
        animation: slideUp 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) both;
        animation-delay: calc(var(--animation-order) * 100ms);
    }
    
    @keyframes slideUp {
        from {
            transform: translateY(30px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    /* Hover Effects */
    .hover-lift {
        transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
    }
    
    .hover-lift:hover {
        transform: translateY(-8px) scale(1.01);
        box-shadow: 0 20px 40px rgba(0,0,0,0.12);
    }
    
    /* Modal Animation */
    #album-modal.show {
        animation: modalShow 0.3s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
    }
    
    @keyframes modalShow {
        from {
            opacity: 0;
            transform: scale(0.98);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    /* Glow Effects */
    .glow-effect {
        position: relative;
        cursor: pointer;
    }
    
    .glow-effect::before {
        content: '';
        background: radial-gradient(circle at var(--mouse-x, 50%) var(--mouse-y, 50%), 
                                  rgba(124, 58, 237, 0.15),
                                  rgba(6, 182, 212, 0.15),
                                  transparent 50%);
        border-radius: inherit;
        position: absolute;
        inset: -20px;
        opacity: 0;
        transition: opacity 0.3s;
        pointer-events: none;
        z-index: 2;
    }
    
    .glow-effect:hover::before {
        opacity: 1;
    }
    
    /* Cursor glow */
    .cursor-glow {
        position: fixed;
        width: 80px;
        height: 80px;
        background: radial-gradient(circle, 
                                  rgba(124, 58, 237, 0.15),
                                  rgba(6, 182, 212, 0.15),
                                  transparent 50%);
        border-radius: 50%;
        pointer-events: none;
        transform: translate(-50%, -50%);
        opacity: 0.8;
        z-index: 9999;
        mix-blend-mode: screen;
    }
    
    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: rgba(0,0,0,0.1);
    }
    
    ::-webkit-scrollbar-thumb {
        background: var(--primary);
        border-radius: 4px;
    }
</style>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($albumCollection as $album)
        @php
            $slug = \Illuminate\Support\Str::slug($album->name);
            $first = optional(collect($album->galleries ?? [])->first());
            $cover = $first && $first->image_path ? asset('storage/' . $first->image_path) : ($staticFiles[$slug] ?? asset('images/placeholder.png'));
            $count = count($album->galleries ?? []);
        @endphp
        <div class="album-card glass-morph hover-lift slide-up cursor-pointer rounded-2xl overflow-hidden glow-effect" 
             data-album-slug="{{ $slug }}" 
             style="--animation-order: {{ $loop->index }}">
            <figure class="relative overflow-hidden h-64">
                <img src="{{ $cover }}" alt="{{ $album->name }} cover" loading="lazy" 
                     class="w-full h-full object-cover transition-all duration-500 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                <div class="caption-wrap" role="group" aria-label="Album info">
                    <div>
                        <div class="title">{{ $album->name }}</div>
                        <div class="meta">{{ $count }} photos</div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="text-sm text-white/90 hidden sm:block">Open album</div>
                        <svg class="w-5 h-5 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </figure>
            <!-- bottom area hidden (caption shown above on image) but left as sr-only for accessibility -->
            <div class="sr-only" aria-hidden="true">View {{ $album->name }} album</div>
        </div>
    @endforeach
</div>

<div id="album-view" class="hidden mt-8 transform transition-all duration-300">
    <div class="flex items-center justify-between mb-8 bg-gradient-to-r from-white/10 to-transparent p-6 rounded-xl backdrop-blur-sm">
        <div class="space-y-1">
            <h2 id="album-title" class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-primary to-accent">Album</h2>
            <div id="album-count" class="text-sm text-gray-600">0 photos</div>
        </div>
        <div>
            <button id="albums-back" class="px-6 py-3 bg-white/10 hover:bg-white/20 border border-white/20 rounded-lg backdrop-blur-sm transition-all duration-200 hover:scale-105 active:scale-95 text-gray-800 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                <span>Back to albums</span>
            </button>
        </div>
    </div>
    <div id="album-photos" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6"></div>
</div>

<div id="album-modal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm items-center justify-center z-50">
    <div class="relative max-w-5xl w-full mx-auto p-4">
        <button id="album-modal-close" aria-label="Close" class="absolute -top-12 right-4 text-white hover:text-white/80 bg-black/50 hover:bg-black/70 rounded-full p-3 transition-all duration-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <button id="album-modal-prev" aria-label="Previous" class="absolute left-4 top-1/2 -translate-y-1/2 text-white hover:text-white/80 bg-black/50 hover:bg-black/70 rounded-full p-4 transition-all duration-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        <button id="album-modal-next" aria-label="Next" class="absolute right-4 top-1/2 -translate-y-1/2 text-white hover:text-white/80 bg-black/50 hover:bg-black/70 rounded-full p-4 transition-all duration-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
        <div class="rounded-2xl overflow-hidden shadow-2xl">
            <img id="album-modal-img" src="" class="w-full h-auto" />
        </div>
        <div id="album-modal-index" class="absolute top-4 left-4 text-white text-sm bg-black/60 px-4 py-2 rounded-full">1 / 1</div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    // Handle mouse movement for glow effect
    document.addEventListener('mousemove', (e) => {
        document.querySelectorAll('.album-card').forEach(card => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            card.style.setProperty('--mouse-x', `${x}px`);
            card.style.setProperty('--mouse-y', `${y}px`);
        });
    });

    const albumGrid = document.querySelector('.grid');
    const albumView = document.getElementById('album-view');
    const albumPhotos = document.getElementById('album-photos');
    const albumTitle = document.getElementById('album-title');
    const albumCount = document.getElementById('album-count');
    const albumsBack = document.getElementById('albums-back');

    const modal = document.getElementById('album-modal');
    const modalImg = document.getElementById('album-modal-img');
    const modalClose = document.getElementById('album-modal-close');
    const modalNext = document.getElementById('album-modal-next');
    const modalPrev = document.getElementById('album-modal-prev');

    // Build JS array from server-side albums collection
    const itemsRaw = @json($albumCollection->map(function($album){
        $items = collect($album->galleries ?? [])->map(function($g){ return asset('storage/' . $g->image_path); })->toArray();
        return ['album' => \Illuminate\Support\Str::slug($album->name), 'name' => $album->name, 'imgs' => $items];
    })->toArray());

    function buildPhotos(items){
        albumPhotos.innerHTML = '';
        items.forEach((src,i) => {
            const btn = document.createElement('button');
            btn.className = 'focus:outline-none group';
            btn.style.cursor = 'pointer';
            btn.innerHTML = `
                <div class="glass-morph hover-lift overflow-hidden rounded-lg glow-effect">
                    <img src="${src}" loading="lazy" class='w-full h-44 object-cover transition-all duration-500 group-hover:scale-105'>
                </div>`;
            const glowElement = btn.querySelector('.glow-effect');
            glowElement.addEventListener('mousemove', (e) => {
                const rect = glowElement.getBoundingClientRect();
                const x = ((e.clientX - rect.left) / glowElement.offsetWidth) * 100;
                const y = ((e.clientY - rect.top) / glowElement.offsetHeight) * 100;
                glowElement.style.setProperty('--mouse-x', `${x}%`);
                glowElement.style.setProperty('--mouse-y', `${y}%`);
            });
            btn.addEventListener('click', (e) => { e.preventDefault(); openModal(i, items); });
            albumPhotos.appendChild(btn);
        });
    }

    function openModal(index, items){
        modal.dataset.index = index;
        modal.dataset.items = JSON.stringify(items);
        modalImg.src = items[index];
        document.getElementById('album-modal-index').textContent = (index+1) + ' / ' + items.length;
        modal.classList.remove('hidden');
        modal.classList.add('show');
        modal.style.display = 'flex';
    }

    function closeModal(){ 
        modal.classList.add('hidden'); 
        modal.classList.remove('show');
        modal.style.display = 'none'; 
        modalImg.src = ''; 
    }

    function modalNextFn(){
        const items = JSON.parse(modal.dataset.items || '[]');
        if(!items.length) return;
        let idx = parseInt(modal.dataset.index || '0');
        idx = (idx + 1) % items.length;
        modal.dataset.index = idx; 
        modalImg.src = items[idx];
        document.getElementById('album-modal-index').textContent = (idx+1) + ' / ' + items.length;
    }

    function modalPrevFn(){
        const items = JSON.parse(modal.dataset.items || '[]');
        if(!items.length) return;
        let idx = parseInt(modal.dataset.index || '0');
        idx = (idx - 1 + items.length) % items.length;
        modal.dataset.index = idx; 
        modalImg.src = items[idx];
        document.getElementById('album-modal-index').textContent = (idx+1) + ' / ' + items.length;
    }

    // Album card click -> show album view with animation
    document.querySelectorAll('.album-card').forEach(card => {
        card.addEventListener('click', () => {
            const slug = card.getAttribute('data-album-slug');
            const entry = itemsRaw.find(x => x.album === slug);
            const items = (entry && entry.imgs) ? entry.imgs : [];
            albumTitle.textContent = entry ? entry.name : 'Album';
            albumCount.textContent = (items.length) + ' photos';
            buildPhotos(items);
            albumGrid.classList.add('hidden');
            albumView.classList.remove('hidden');
            albumView.classList.add('slide-up');
            window.scrollTo({top: albumView.getBoundingClientRect().top + window.scrollY - 60, behavior:'smooth'});
        });
    });

    albumsBack.addEventListener('click', (e) => {
        e.preventDefault();
        albumView.classList.add('hidden');
        albumView.classList.remove('slide-up');
        albumGrid.classList.remove('hidden');
        albumPhotos.innerHTML = '';
        window.scrollTo({top: albumGrid.getBoundingClientRect().top + window.scrollY - 60, behavior:'smooth'});
    });

    // modal controls with animations
    modalClose.addEventListener('click', closeModal);
    modalNext.addEventListener('click', (e)=>{ e.preventDefault(); modalNextFn(); });
    modalPrev.addEventListener('click', (e)=>{ e.preventDefault(); modalPrevFn(); });
    document.addEventListener('keydown', (e) => { 
        if(e.key==='Escape') closeModal(); 
        if(e.key==='ArrowRight') modalNextFn(); 
        if(e.key==='ArrowLeft') modalPrevFn(); 
    });
});
</script>
@endpush
</div>
