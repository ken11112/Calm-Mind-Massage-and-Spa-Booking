<div class="thumb-carousel max-w-5xl mx-auto">
    <div class="relative">
        <!-- Always visible controls (better UX on small screens) -->
        <button id="thumb-prev" class="thumb-control absolute left-0 top-1/2 -translate-y-1/2 z-40 bg-black bg-opacity-70 text-white rounded-full p-2" aria-label="Prev thumbnails">&#9664;</button>

        <div id="thumbs-container" class="overflow-x-auto scrollbar-hide px-4">
            <div id="thumbs" class="flex items-center gap-3 py-3">
                @php $index = 0; @endphp
                @foreach($galleries as $gallery)
                    <button class="thumb-item w-20 h-16 rounded overflow-hidden border-2 border-transparent focus:outline-none" 
                            data-index="{{ $index }}" 
                            onclick="window.showImage({{ $index }})"
                            aria-label="Thumbnail {{ $index + 1 }}" 
                            tabindex="0">
                        <img loading="lazy" src="{{ asset('storage/' . $gallery->image_path) }}" alt="{{ $gallery->title }}" class="w-full h-full object-cover">
                    </button>
                    @php $index++; @endphp
                @endforeach
                @isset($staticFiles)
                    @foreach($staticFiles as $file)
                        <button type="button" class="thumb-item w-20 h-16 rounded overflow-hidden border-2 border-transparent focus:outline-none" data-index="{{ $index }}" onclick="if(window.showImage) window.showImage({{ $index }});" aria-label="Thumbnail {{ $index + 1 }}" tabindex="0">
                            <img loading="lazy" src="{{ asset($file) }}" alt="Gallery image {{ $index + 1 }}" class="w-full h-full object-cover">
                        </button>
                        @php $index++; @endphp
                    @endforeach
                @endisset
            </div>
        </div>

        <button id="thumb-next" class="thumb-control absolute right-0 top-1/2 -translate-y-1/2 z-40 bg-black bg-opacity-70 text-white rounded-full p-2" aria-label="Next thumbnails">&#9654;</button>
    </div>

    <style>
        /* hide default scrollbar for a cleaner look */
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

        .thumb-item {
            transition: transform .22s cubic-bezier(.4,2,.3,1), box-shadow .22s, border-color .22s, background .22s;
            border-radius: 16px;
            background: rgba(30,30,40,0.45);
            box-shadow: 0 2px 12px rgba(0,0,0,0.18);
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }
        .thumb-item img {
            filter: brightness(0.98) saturate(1.15) drop-shadow(0 2px 8px rgba(0,0,0,0.18));
            border-radius: 14px;
        }
        .thumb-item:hover, .thumb-item:focus {
            transform: scale(1.12) translateY(-4px);
            box-shadow: 0 8px 24px 0 rgba(0,255,255,0.18), 0 2px 12px rgba(0,0,0,0.22);
            border-color: #00eaff;
            background: rgba(0,40,60,0.22);
            z-index: 2;
        }
        .thumb-item.active {
            box-shadow: 0 12px 32px 0 rgba(0,255,255,0.32), 0 6px 18px rgba(0,0,0,0.32);
            border-color: #00eaff;
            background: rgba(0,40,60,0.32);
            transform: scale(1.16) translateY(-6px);
        }
        .thumb-item:focus {
            outline: 3px solid #00eaff44;
        }
        .thumb-control {
            backdrop-filter: blur(8px);
            background: linear-gradient(135deg, rgba(0,40,60,0.7) 60%, rgba(0,255,255,0.18) 100%);
            box-shadow: 0 2px 8px rgba(0,0,0,0.22);
            border: none;
            transition: background .18s, box-shadow .18s;
        }
        .thumb-control:hover, .thumb-control:focus {
            background: linear-gradient(135deg, #00eaff 60%, #0077ff 100%);
            color: #fff;
            box-shadow: 0 4px 16px rgba(0,255,255,0.22);
        }
    </style>
    <script>
        (function(){
            // If a global showImage is already provided by the page (modal or carousel), don't overwrite it.
            if(typeof window.showImage === 'function') return;

            // Build an array of image URLs from the thumbs inside this carousel.
            var thumbImgs = document.querySelectorAll('.thumb-carousel #thumbs .thumb-item img');
            var urls = Array.prototype.map.call(thumbImgs, function(i){ return i && i.src ? i.src : null; }).filter(Boolean);

            // Provide a minimal fallback: open the image in a new tab.
            window.showImage = function(index){
                index = Number(index) || 0;
                var url = urls[index];
                if(!url){
                    console.warn('showImage: image not found for index', index);
                    return;
                }
                try{
                    window.open(url, '_blank');
                }catch(e){
                    // fallback to setting location
                    window.location.href = url;
                }
            };
        })();
    </script>
</div>
