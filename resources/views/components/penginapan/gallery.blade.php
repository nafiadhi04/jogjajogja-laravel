<section class="gallery-section">
    <div class="gallery-container">
        {{-- LOGIKA ALPINE.JS INFINITE LOOP CAROUSEL DENGAN TRANSISI HALUS --}}
        <div x-data="{
            // Jumlah slide ASLI: Thumbnail + semua gambar galeri
            originalSlides: {{ $penginapan->gambar->count() + 1 }}, 
            // Total slide di DOM: originalSlides + 2 kloningan
            totalSlides: {{ $penginapan->gambar->count() + 1 + 2 }}, 
            activeSlide: 1, // Dimulai dari index 1 (slide ASLI pertama)
            isDragging: false,
            isTransitioning: true, // Kontrol transisi untuk 'teleport'
            startX: 0,
            currentX: 0,
            translateX: -100, // Mulai dari slide index 1 (posisi -100%)
            threshold: 100, // Threshold untuk menggeser ke slide berikutnya (100px)

            // Untuk thumbnail scroll
            isDraggingThumb: false,
            thumbStartX: 0,
            thumbScrollLeft: 0,

            // Fungsi untuk menggeser thumbnail agar slide aktif terlihat
            scrollToActiveThumbnail(originalIndex) {
                this.$nextTick(() => {
                    const container = this.$refs.thumbContainer;
                    const activeThumb = container.children[originalIndex]; 
                    if (activeThumb && container) {
                        const containerWidth = container.offsetWidth;
                        const thumbLeft = activeThumb.offsetLeft;
                        const thumbWidth = activeThumb.offsetWidth;
                        
                        const scrollLeft = thumbLeft - (containerWidth / 2) + (thumbWidth / 2);
                        
                        container.scrollTo({
                            left: scrollLeft,
                            behavior: 'smooth'
                        });
                    }
                });
            },
            
            // Pindah slide dari tombol/thumbnail/bounce drag
            setActiveSlide(index) {
                this.isTransitioning = true;
                this.activeSlide = index + 1; 
                this.translateX = -this.activeSlide * 100;
                this.scrollToActiveThumbnail(index); 
                this.handleLoopTransition();
            },
            
            // Mengelola teleport (lompatan tanpa transisi) untuk smooth loop
            handleLoopTransition() {
                const TRANSITION_DURATION = 400; 
                let targetSlide = -1;

                if (this.activeSlide === this.totalSlides - 1) { 
                    targetSlide = 1; 
                } else if (this.activeSlide === 0) { 
                    targetSlide = this.originalSlides; 
                } 
                
                if (targetSlide !== -1) {
                    setTimeout(() => {
                        this.isTransitioning = false; // 1. Matikan transisi
                        this.activeSlide = targetSlide; // 2. Teleport posisi data
                        this.translateX = -this.activeSlide * 100; // 3. Terapkan posisi CSS
                        
                        let originalIndex = targetSlide - 1;
                        if (originalIndex === this.originalSlides) originalIndex = 0; 
                        this.scrollToActiveThumbnail(originalIndex);
                        
                        // 4. Nyalakan kembali transisi
                        this.$nextTick(() => {
                            // Waktu tunggu 50ms setelah DOM repaint untuk mengaktifkan kembali transisi.
                            setTimeout(() => this.isTransitioning = true, 50); 
                        });
                    }, TRANSITION_DURATION); // Tunggu sampai transisi ke kloningan selesai (400ms)
                } else {
                    this.isTransitioning = true;
                }
            },

            // ===================================
            // DRAG EVENTS (GESER)
            // ===================================

            startDrag(e) {
                this.isDragging = true;
                this.startX = e.type === 'mousedown' ? e.clientX : e.touches[0].clientX;
                this.currentX = this.startX;
                document.body.style.userSelect = 'none';
                this.isTransitioning = false; // Matikan transisi saat drag
            },
            
            onDrag(e) {
                if (!this.isDragging) return;
                e.preventDefault();
                this.currentX = e.type === 'mousemove' ? e.clientX : e.touches[0].clientX;
                const diffX = this.currentX - this.startX;
                
                const baseTranslate = -this.activeSlide * 100;
                const containerWidth = this.$refs.slider.offsetWidth; 
                this.translateX = baseTranslate + (diffX / containerWidth) * 100;
            },
            
            endDrag() {
                if (!this.isDragging) return;
                
                this.isDragging = false;
                document.body.style.userSelect = '';
                this.isTransitioning = true; // Aktifkan transisi untuk animasi bounce/pindah
                
                const diffX = this.currentX - this.startX;
                let finalSlide = this.activeSlide;
                
                if (Math.abs(diffX) > this.threshold) {
                    if (diffX > 0) {
                        finalSlide--; // Geser ke kiri (Prev)
                    } else if (diffX < 0) {
                        finalSlide++; // Geser ke kanan (Next)
                    }
                }
                
                // Klamping slide akhir (0 hingga totalSlides - 1)
                finalSlide = Math.max(0, Math.min(finalSlide, this.totalSlides - 1));

                this.activeSlide = finalSlide;
                this.translateX = -this.activeSlide * 100; 
                
                let originalIndex = this.activeSlide - 1;
                
                if (originalIndex >= this.originalSlides) { 
                    originalIndex = 0;
                } else if (originalIndex < 0) { 
                    originalIndex = this.originalSlides - 1;
                }

                this.scrollToActiveThumbnail(originalIndex);
                this.handleLoopTransition(); 
            },

            // ===================================
            // TOMBOL NAVIGASI
            // ===================================

            nextSlide() {
                this.isTransitioning = true; 
                this.activeSlide++;
                this.translateX = -this.activeSlide * 100;
                
                let originalIndex = this.activeSlide - 1;
                if (originalIndex >= this.originalSlides) {
                    originalIndex = 0;
                }
                this.scrollToActiveThumbnail(originalIndex);
                this.handleLoopTransition();
            },
            
            prevSlide() {
                this.isTransitioning = true; 
                this.activeSlide--;
                this.translateX = -this.activeSlide * 100;
                
                let originalIndex = this.activeSlide - 1;
                if (originalIndex < 0) {
                    originalIndex = this.originalSlides - 1;
                }
                this.scrollToActiveThumbnail(originalIndex);
                this.handleLoopTransition();
            },

            // ===================================
            // THUMBNAIL DRAG SCROLL
            // ===================================

            startThumbDrag(e) {
                this.isDraggingThumb = true;
                const container = this.$refs.thumbContainer;
                this.thumbStartX = e.type === 'mousedown' ? e.pageX : e.touches[0].pageX;
                this.thumbScrollLeft = container.scrollLeft;
                container.style.cursor = 'grabbing';
            },
            
            onThumbDrag(e) {
                if (!this.isDraggingThumb) return;
                const x = e.type === 'mousemove' ? e.pageX : e.touches[0].pageX;
                const walk = (x - this.thumbStartX) * 1.5; 
                this.$refs.thumbContainer.scrollLeft = this.thumbScrollLeft - walk;
            },
            
            endThumbDrag(e) {
                this.isDraggingThumb = false;
                this.$refs.thumbContainer.style.cursor = 'grab';
            },
            
        }" x-init="scrollToActiveThumbnail(activeSlide - 1)" class="mb-6">
            
            {{-- Kontainer Slider Gambar Utama --}}
            <div class="relative overflow-hidden rounded-lg shadow-md">
                <div class="cursor-grab"
                    :class="{ 'cursor-grabbing': isDragging }"
                    @mousedown="startDrag($event)"
                    @mousemove="onDrag($event)" 
                    @mouseup="endDrag()"
                    @mouseleave="endDrag()"
                    @touchstart="startDrag($event)"
                    @touchmove="onDrag($event)"
                    @touchend="endDrag()">
                    <div x-ref="slider"
                        {{-- Transisi CSS yang dikontrol oleh isTransitioning --}}
                        class="flex transition-transform duration-400 ease-in-out" 
                        :class="{ 'transition-none': isDragging || !isTransitioning }"
                        :style="{ transform: `translateX(${translateX}%)` }">
                        
                        {{-- 1. SLIDE KLONINGAN TERAKHIR (Index 0) --}}
                        <div class="flex-shrink-0 w-full main-image-container">
                            <img src="{{ asset('storage/' . ($penginapan->gambar->last()->path_gambar ?? $penginapan->thumbnail)) }}"
                                alt="Galeri Kloning Terakhir" 
                                class="object-cover w-full h-full"
                                draggable="false">
                        </div>

                        {{-- 2. Thumbnail sebagai slide ASLI pertama (Index 1) --}}
                        <div class="flex-shrink-0 w-full main-image-container">
                            <img src="{{ asset('storage/' . $penginapan->thumbnail) }}"
                                alt="{{ $penginapan->nama }}" 
                                class="object-cover w-full h-full"
                                draggable="false">
                        </div>
                        
                        {{-- 3. Loop untuk gambar galeri ASLI (Index 2 sampai N) --}}
                        @foreach($penginapan->gambar as $gambar)
                            <div class="flex-shrink-0 w-full main-image-container">
                                <img src="{{ asset('storage/' . $gambar->path_gambar) }}"
                                    alt="Galeri {{ $penginapan->nama }}" 
                                    class="object-cover w-full h-full"
                                    draggable="false">
                            </div>
                        @endforeach

                        {{-- 4. SLIDE KLONINGAN PERTAMA (Index Terakhir) --}}
                        <div class="flex-shrink-0 w-full main-image-container">
                            <img src="{{ asset('storage/' . $penginapan->thumbnail) }}"
                                alt="{{ $penginapan->nama }} (Kloning)" 
                                class="object-cover w-full h-full"
                                draggable="false">
                        </div>
                    </div>
                </div>
                
                {{-- Tombol Navigasi Gambar (Infinite Loop) --}}
                <button @click="prevSlide()"
                    class="absolute left-4 top-1/2 transform -translate-y-1/2 z-10 p-2 text-white transition-all duration-200 bg-black bg-opacity-60 rounded-full hover:bg-opacity-80 hover:scale-110">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <button @click="nextSlide()"
                    class="absolute right-4 top-1/2 transform -translate-y-1/2 z-10 p-2 text-white transition-all duration-200 bg-black bg-opacity-60 rounded-full hover:bg-opacity-80 hover:scale-110">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>

            {{-- Thumbnail Gallery --}}
            <div class="relative mt-4">
                <div x-ref="thumbContainer"
                    class="flex gap-2 overflow-x-auto pb-2 scroll-smooth cursor-grab scrollbar-hide"
                    :class="{ 'cursor-grabbing': isDraggingThumb }"
                    @mousedown="startThumbDrag($event)"
                    @mousemove="onThumbDrag($event)"
                    @mouseup="endThumbDrag($event)"
                    @mouseleave="endThumbDrag($event)"
                    @touchstart="startThumbDrag($event)"
                    @touchmove="onThumbDrag($event)"
                    @touchend="endThumbDrag($event)">
                    
                    {{-- Thumbnail pertama (Index 0 ASLI) --}}
                    <div class="flex-shrink-0 w-24 h-16 md:w-32 md:h-20 cursor-pointer rounded-lg overflow-hidden border-2 transition-all duration-200"
                        :class="(activeSlide === 1 || activeSlide === totalSlides - 1) ? 'border-teal-600 opacity-100' : 'border-gray-300 opacity-60 hover:opacity-80'"
                        @click="setActiveSlide(0)">
                        <img src="{{ asset('storage/' . $penginapan->thumbnail) }}" 
                            alt="Thumbnail" 
                            class="w-full h-full object-cover pointer-events-none"
                            draggable="false">
                    </div>
                    
                    {{-- Loop thumbnail galeri (Index 1 sampai originalSlides - 1 ASLI) --}}
                    @foreach($penginapan->gambar as $index => $gambar)
                        <div class="flex-shrink-0 w-24 h-16 md:w-32 md:h-20 cursor-pointer rounded-lg overflow-hidden border-2 transition-all duration-200"
                            :class="activeSlide === {{ $index + 2 }} ? 'border-teal-600 opacity-100' : 'border-gray-300 opacity-60 hover:opacity-80'"
                            @click="setActiveSlide({{ $index + 1 }})">
                            <img src="{{ asset('storage/' . $gambar->path_gambar) }}" 
                                alt="Thumbnail {{ $index + 2 }}"
                                class="w-full h-full object-cover pointer-events-none"
                                draggable="false">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>