{{-- Section Rekomendasi wisata --}}
<div class="py-12 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        {{-- Header dan Tombol LIHAT SEMUA untuk Desktop --}}
        <div class="hidden md:flex items-center justify-between mb-8">
            <div>
                <p class="text-lg font-medium text-teal-600">Rekomendasi Wisata</p>
                <h2 class="text-3xl font-bold text-gray-900">Wisata Pilihan Di Jogja</h2>
            </div>
            <a href="{{ route('wisata.list') }}" class="px-6 py-2 text-white transition bg-teal-600 rounded-lg hover:bg-teal-700">
                LIHAT SEMUA →
            </a>
        </div>

        {{-- Header dan Tombol LIHAT SEMUA untuk Mobile --}}
        <div class="md:hidden mb-6 px-4 sm:px-0">
            <p class="text-base font-medium text-teal-600">Rekomendasi Wisata</p>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-gray-900">Wisata Pilihan Di Jogja</h2>
                <a href="{{ route('wisata.list') }}" class="px-4 py-2 text-white transition bg-teal-600 rounded-lg text-sm hover:bg-teal-700">
                    LIHAT SEMUA →
                </a>
            </div>
        </div>

        {{-- Carousel Responsif --}}
        @if(isset($wisataRekomendasi) && $wisataRekomendasi->count() > 0)
            <div 
                x-data="{
                    currentSlide: 0,
                    itemsPerSlide: 1,
                    totalItems: {{ $wisataRekomendasi->count() }},
                    maxSlide: 0, 
                    isDragging: false,
                    hasDragged: false,
                    startX: 0,
                    startY: 0,
                    currentX: 0,
                    currentY: 0,
                    dragOffset: 0,
                    containerWidth: 0,
                    threshold: 80,
                    clickThreshold: 15,
                    verticalThreshold: 20,
                    dragStarted: false,
                    
                    init() {
                        this.updateResponsive();
                        window.addEventListener('resize', () => this.updateResponsive());
                    },

                    updateResponsive() {
                        const width = window.innerWidth;
                        // Logika itemsPerSlide sesuai preferensi 1 item di mobile
                        if (width < 768) { 
                            this.itemsPerSlide = 1;
                        } else if (width < 1024) {
                            this.itemsPerSlide = 3;
                        } else {
                            this.itemsPerSlide = 4;
                        }
                        
                        // maxSlide = Total halaman yang bisa digeser (index 0 hingga maxSlide)
                        this.maxSlide = Math.max(0, Math.ceil(this.totalItems / this.itemsPerSlide) - 1);
                        
                        // Sesuaikan currentSlide agar tidak melebihi batas (untuk indikator)
                        if (this.currentSlide > this.maxSlide) {
                            this.currentSlide = this.maxSlide;
                        }
                        this.updateContainerWidth();
                    },

                    updateContainerWidth() {
                        this.containerWidth = this.$refs.container?.offsetWidth || window.innerWidth;
                    },

                    startDrag(e) {
                        this.updateContainerWidth();
                        this.isDragging = true;
                        this.hasDragged = false;
                        this.dragStarted = false;
                        this.startX = e.type === 'mousedown' ? e.clientX : e.touches[0].clientX;
                        this.startY = e.type === 'mousedown' ? e.clientY : e.touches[0].clientY;
                        this.currentX = this.startX;
                        this.currentY = this.startY;
                        this.dragOffset = 0;
                    },

                    onDrag(e) {
                        if (!this.isDragging) return;

                        this.currentX = e.type === 'mousemove' ? e.clientX : e.touches[0].clientX;
                        this.currentY = e.type === 'mousemove' ? e.clientY : e.touches[0].clientY;

                        const deltaX = this.currentX - this.startX;
                        const deltaY = this.currentY - this.startY;
                        const absX = Math.abs(deltaX);
                        const absY = Math.abs(deltaY);

                        if (!this.dragStarted) {
                            if (absX > this.clickThreshold && absX > absY && absY < this.verticalThreshold) {
                                this.dragStarted = true;
                                this.hasDragged = true; 
                                e.preventDefault();
                                document.body.style.userSelect = 'none';
                                document.body.style.cursor = 'grabbing';
                            } else if (absY > this.verticalThreshold) {
                                this.endDrag(e);
                                return;
                            }
                        }

                        if (this.dragStarted) {
                            e.preventDefault();
                            this.dragOffset = deltaX;
                        }
                    },

                    endDrag(e) {
                        if (!this.isDragging) return;

                        this.isDragging = false;
                        document.body.style.userSelect = '';
                        document.body.style.cursor = '';

                        const isClick = !this.dragStarted && Math.abs(this.currentX - this.startX) < this.clickThreshold;
                        
                        if (this.dragStarted && Math.abs(this.dragOffset) > this.threshold) {
                            if (this.dragOffset > 0) {
                                // Modulo untuk perulangan 'prev'
                                this.currentSlide = (this.currentSlide - 1 + (this.maxSlide + 1)) % (this.maxSlide + 1);
                            } else if (this.dragOffset < 0) {
                                // Modulo untuk perulangan 'next'
                                this.currentSlide = (this.currentSlide + 1) % (this.maxSlide + 1);
                            }
                        }
                        
                        this.dragOffset = 0;
                        this.dragStarted = false;

                        setTimeout(() => {
                            this.hasDragged = false;
                        }, isClick ? 0 : 50); 
                    },

                    getTransform() {
                        const baseTransform = -this.currentSlide * 100;
                        const dragTransform = this.dragStarted && this.isDragging
                            ? (this.dragOffset / this.containerWidth) * 100
                            : 0;
                        return baseTransform + dragTransform;
                    },

                    getItemWidth() {
                        return (100 / this.itemsPerSlide) + '%';
                    },

                    nextSlide() {
                        // Logika perulangan (carousel loop)
                        this.currentSlide = (this.currentSlide + 1) % (this.maxSlide + 1);
                    },

                    prevSlide() {
                        // Logika perulangan (carousel loop)
                        this.currentSlide = (this.currentSlide - 1 + (this.maxSlide + 1)) % (this.maxSlide + 1);
                    },

                    goToSlide(index) {
                        this.currentSlide = index;
                    }
                }" 
                class="relative"
            >
                
                {{-- Tombol Navigasi PREV (LATAR BELAKANG ABU-ABU & Posisi Desktop) --}}
                <button 
                    @click="prevSlide()"
                    x-show="maxSlide > 0"
                    {{-- 💡 PERUBAHAN POSISI DESKTOP: sm:left-[-1rem] --}}
                    class="absolute -left-2 sm:left-[-1rem] z-10 flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 text-teal-600 transition-all duration-200 transform -translate-y-1/2 bg-gray-200 rounded-full shadow-lg top-1/2 hover:scale-110 active:bg-teal-600 active:text-white focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                
                {{-- Tombol Navigasi NEXT (LATAR BELAKANG ABU-ABU & Posisi Desktop) --}}
                <button 
                    @click="nextSlide()"
                    x-show="maxSlide > 0"
                    {{-- 💡 PERUBAHAN POSISI DESKTOP: sm:right-[-1rem] --}}
                    class="absolute -right-2 sm:right-[-1rem] z-10 flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 text-teal-600 transition-all duration-200 transform -translate-y-1/2 bg-gray-200 rounded-full shadow-lg top-1/2 hover:scale-110 active:bg-teal-600 active:text-white focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

                {{-- Kontainer Kartu --}}
                <div class="overflow-hidden select-none sm:px-0 group"
                    x-ref="container"
                    :class="{ 'cursor-grabbing': dragStarted }"
                    @mousedown="startDrag($event)"
                    @mousemove="onDrag($event)" 
                    @mouseup="endDrag($event)"
                    @mouseleave="endDrag($event)"
                    @touchstart="startDrag($event)"
                    @touchmove="onDrag($event)"
                    @touchend="endDrag($event)"
                    @dragstart.prevent
                    style="pointer-events: auto;"
                >
                    <div class="relative">
                        <div class="flex will-change-transform" 
                            :class="{ 'transition-transform duration-500 ease-out': !isDragging }"
                            :style="{ transform: `translateX(${getTransform()}%)` }"
                            style="pointer-events: none;" 
                        >
                            @foreach($wisataRekomendasi as $wisata)
                                <div class="flex-shrink-0 px-2 sm:px-3 cursor-default"
                                    :style="{ width: getItemWidth() }"
                                    style="pointer-events: auto;">
                                    {{-- Asumsi Anda memiliki komponen wisata.card --}}
                                    <x-wisata.card 
                                        :wisata="$wisata" 
                                        :prevent-drag="true" 
                                    />
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                {{-- Indikator Slide --}}
                <div class="flex justify-center mt-6 space-x-2" x-show="maxSlide > 0">
                    <template x-for="i in (maxSlide + 1)" :key="i">
                        <button 
                            @click="goToSlide(i - 1)"
                            {{-- Modulo pada currentSlide untuk perulangan --}}
                            :class="{ 'bg-teal-600 scale-110': (currentSlide % (maxSlide + 1)) === (i - 1), 'bg-gray-300': (currentSlide % (maxSlide + 1)) !== (i - 1) }"
                            class="w-3 h-3 transition-all duration-300 rounded-full hover:bg-teal-500 hover:scale-105">
                        </button>
                    </template>
                </div>
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <p>Tidak ada wisata yang direkomendasikan saat ini.</p>
            </div>
        @endif
    </div>
</div>