{{-- Section Rekomendasi Penginapan --}}
<div class="py-12 bg-white">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        {{-- Header dan Tombol LIHAT SEMUA untuk Desktop --}}
        <div class="hidden md:flex items-center justify-between mb-8">
            <div>
                <p class="text-lg font-medium text-teal-600">Rekomendasi Penginapan</p>
                <h2 class="text-3xl font-bold text-gray-900">Penginapan Pilihan Di Jogja</h2>
            </div>
            <a href="{{ route('penginapan.list') }}" class="px-6 py-2 text-white transition bg-teal-600 rounded-lg hover:bg-teal-700">
                LIHAT SEMUA →
            </a>
        </div>

        {{-- Header dan Tombol LIHAT SEMUA untuk Mobile --}}
        <div class="md:hidden mb-6 px-4 sm:px-0">
            <p class="text-base font-medium text-teal-600">Rekomendasi Penginapan</p>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-gray-900">Penginapan Pilihan Di Jogja</h2>
                <a href="{{ route('penginapan.list') }}" class="px-4 py-2 text-white transition bg-teal-600 rounded-lg text-sm hover:bg-teal-700">
                    LIHAT SEMUA →
                </a>
            </div>
        </div>

        {{-- Carousel Responsif --}}
        @if(isset($penginapanRekomendasi) && $penginapanRekomendasi->count() > 0)
            <div 
                x-data="{
                    currentSlide: 1, 
                    itemsPerSlide: 1,
                    totalItems: {{ $penginapanRekomendasi->count() }},
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
                    transitionEnabled: true, // Kontrol transisi untuk smooth loop

                    init() {
                        this.updateResponsive();
                        window.addEventListener('resize', () => this.updateResponsive());
                    },

                    updateResponsive() {
                        const width = window.innerWidth;
                        if (width < 640) {
                            this.itemsPerSlide = 1;
                        } else if (width < 768) {
                            this.itemsPerSlide = 2;
                        } else if (width < 1024) {
                            this.itemsPerSlide = 3;
                        } else {
                            this.itemsPerSlide = 4;
                        }
                        this.maxSlide = Math.max(0, Math.ceil(this.totalItems / this.itemsPerSlide)); 
                        this.currentSlide = Math.min(Math.max(1, this.currentSlide), this.maxSlide);
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
                        this.transitionEnabled = true; 
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
                        
                        let newSlide = this.currentSlide;

                        if (this.dragStarted && Math.abs(this.dragOffset) > this.threshold) {
                            if (this.dragOffset > 0) {
                                newSlide = this.currentSlide - 1;
                            } else if (this.dragOffset < 0) {
                                newSlide = this.currentSlide + 1;
                            }
                        }

                        // JIKA TIDAK CUKUP GESER, KEMBALI KE SLIDE SAAT INI (BOUNCE BACK)
                        if (newSlide === this.currentSlide && this.dragStarted) {
                             this.goToSlide(this.currentSlide);
                        } else {
                            this.goToSlide(newSlide);
                        }
                        
                        this.dragOffset = 0;
                        this.dragStarted = false;

                        setTimeout(() => {
                            this.hasDragged = false;
                        }, isClick ? 0 : 50); 
                    },
                    
                    goToSlide(index) {
                        this.transitionEnabled = true;
                        
                        // Batas Atas dan Bawah (indeks duplikasi)
                        if (index === 0) {
                            this.currentSlide = 0;
                            // Pindah ke slide ASLI terakhir setelah transisi selesai
                            setTimeout(() => {
                                this.transitionEnabled = false;
                                this.currentSlide = this.maxSlide;
                            }, 500); 
                        } else if (index === this.maxSlide + 1) {
                            this.currentSlide = this.maxSlide + 1;
                            // Pindah ke slide ASLI pertama setelah transisi selesai
                            setTimeout(() => {
                                this.transitionEnabled = false;
                                this.currentSlide = 1;
                            }, 500); 
                        } else {
                             // Perpindahan slide normal
                             this.currentSlide = index;
                        }
                    },

                    nextSlide() {
                        this.goToSlide(this.currentSlide + 1);
                    },

                    prevSlide() {
                        this.goToSlide(this.currentSlide - 1);
                    },

                    getTransform() {
                        // Perhitungan transform yang menggabungkan posisi slide dan offset drag
                        return -this.currentSlide * 100 + (this.dragStarted && this.isDragging ? (this.dragOffset / this.containerWidth) * 100 : 0);
                    },

                    getItemWidth() {
                        return (100 / this.itemsPerSlide) + '%';
                    },

                }" 
                class="relative"
            >
                
                {{-- Tombol Navigasi --}}
                <button 
                    @click="prevSlide()"
                    x-show="maxSlide > 0"
                    class="absolute left-2 sm:left-0 z-10 flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 text-white transition-all duration-200 transform -translate-y-1/2 bg-black rounded-full bg-opacity-60 top-1/2 hover:bg-opacity-80 hover:scale-110">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                
                <button 
                    @click="nextSlide()"
                    x-show="maxSlide > 0"
                    class="absolute right-2 sm:right-0 z-10 flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 text-white transition-all duration-200 transform -translate-y-1/2 bg-black rounded-full bg-opacity-60 top-1/2 hover:bg-opacity-80 hover:scale-110">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

                {{-- Kontainer Kartu --}}
                <div class="overflow-hidden select-none px-4 sm:px-0 group"
                    x-ref="container"
                    :class="{ 'cursor-grabbing': dragStarted, 'cursor-grab': !dragStarted }"
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
                            :class="{ 'transition-transform duration-500 ease-out': transitionEnabled && !isDragging }"
                            :style="{ transform: `translateX(${getTransform()}%)` }"
                            style="pointer-events: none;" 
                        >
                            
                            {{-- DUPLIKASI SLIDE TERAKHIR --}}
                            @php
                                // Mengambil hingga 4 item terakhir untuk duplikasi (safe max)
                                $lastItems = $penginapanRekomendasi->reverse()->take(4)->reverse();
                            @endphp
                            @foreach($lastItems as $penginapan)
                                <div class="flex-shrink-0 px-2 sm:px-3 cursor-default"
                                    :style="{ width: getItemWidth() }"
                                    style="pointer-events: auto;">
                                    <x-penginapan.card :penginapan="$penginapan" :prevent-drag="true" />
                                </div>
                            @endforeach

                            {{-- SLIDE ASLI --}}
                            @foreach($penginapanRekomendasi as $penginapan)
                                <div class="flex-shrink-0 px-2 sm:px-3 cursor-default"
                                    :style="{ width: getItemWidth() }"
                                    style="pointer-events: auto;">
                                    <x-penginapan.card :penginapan="$penginapan" :prevent-drag="true" />
                                </div>
                            @endforeach

                            {{-- DUPLIKASI SLIDE PERTAMA --}}
                            @php
                                // Mengambil hingga 4 item pertama untuk duplikasi (safe max)
                                $firstItems = $penginapanRekomendasi->take(4);
                            @endphp
                            @foreach($firstItems as $penginapan)
                                <div class="flex-shrink-0 px-2 sm:px-3 cursor-default"
                                    :style="{ width: getItemWidth() }"
                                    style="pointer-events: auto;">
                                    <x-penginapan.card :penginapan="$penginapan" :prevent-drag="true" />
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
                
                {{-- Indikator Slide --}}
                <div class="flex justify-center mt-6 space-x-2" x-show="maxSlide > 0">
                    <template x-for="i in maxSlide" :key="i">
                        <button 
                            @click="goToSlide(i)"
                            {{-- Indikator menyesuaikan indeks slide ASLI (1 hingga maxSlide) --}}
                            :class="{ 'bg-teal-600 scale-110': currentSlide === i, 'bg-gray-300': currentSlide !== i }"
                            class="w-3 h-3 transition-all duration-300 rounded-full hover:bg-teal-500 hover:scale-105">
                        </button>
                    </template>
                </div>
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <p>Tidak ada penginapan yang direkomendasikan saat ini.</p>
            </div>
        @endif
    </div>
</div>