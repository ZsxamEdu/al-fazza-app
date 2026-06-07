@extends('layouts.main')

@section('content')
    <main class="max-w-6xl my-10 mx-auto px-5 font-sans">
        <section class="flex flex-col lg:flex-row gap-12 items-stretch lg:items-start mb-16">
            <div class="flex-1 bg-bg-gray-light rounded-xl flex justify-center items-center overflow-hidden w-full">
                <img loading="lazy" id="product-img" src="{{ asset($kue->gambar) }}" alt="{{ $kue->nama }}" class="w-full max-h-125 object-cover rounded-xl">
            </div>

            <div class="flex-1 w-full">
                <div class="flex items-center gap-4 mb-1.5">
                    <h1 class="m-0 text-4xl text-black">{{ $kue->nama }}</h1>
                </div>
                <p class="text-text-muted mb-4 text-bas e">{{ $kue->nama }} - {{ $kue->tipe }}</p>
                <h2 class="text-3xl font-bold mb-6 text-black">Rp {{ number_format($kue->harga, 0, ',', '.') }}</h2>

                <div class="flex flex-col md:flex-row gap-4 mb-8 justify-between w-full lg:items-center">
                    <!-- Tombol beli mengambil QTY dari input -->
                    <button class="bg-text-darker text-white py-3 md:px-[25%] lg:px-10 border-none rounded-lg font-bold text-base cursor-pointer transition-colors duration-300 hover:bg-neutral-700 w-full md:w-auto" onclick="let qty = parseInt(document.getElementById('qty').value) || 1; addToCart('{{ $kue->id }}', '{{ $kue->nama }}', {{ $kue->harga }}, '{{ asset($kue->gambar) }}', qty)">Tambahkan ke Keranjang</button>
                    <div class="flex items-center border border-border-dark rounded-lg overflow-hidden w-full md:w-auto justify-center md:justify-start">
                        <button class="bg-white border-none py-3 px-4 cursor-pointer text-lg" onclick="changeQty(-1)">-</button>
                        <input type="number" id="qty" value="1" min="1" readonly class="w-10 h-12 text-center border-none text-lg outline-none pointer-events-none">
                        <button class="bg-white border-none py-3 px-4 cursor-pointer text-lg" onclick="changeQty(1)">+</button>
                    </div>
                </div>

                <div class="info-dropdown">
                    <details open class="border-t border-border-light py-4">
                        <summary class="font-bold text-lg cursor-pointer list-none flex justify-between items-center [&::-webkit-details-marker]:hidden">Informasi Produk <i class="fas fa-chevron-down arrow-icon"></i></summary>
                        <hr class="my-5">
                        <div class="mt-3.5 text-text-medium leading-[1.6] text-base">
                            <p><strong><i class="fa-solid fa-wheat-awn"></i> Bahan Utama:</strong> <span>{{ $kue->bahan ?? 'Informasi bahan belum tersedia.' }}</span></p>
                            <hr class="my-5">
                            <p><strong><i class="fa-solid fa-jar"></i> Saran Penyimpanan:</strong> Simpan dalam wadah tertutup rapat. Tahan 2 hari di suhu ruang, 4-5 hari di dalam lemari pendingin.</p>
                            <hr class="my-5">
                        </div>
                    </details>
                    <details open class="border-t border-b border-border-light py-4">
                        <summary class="font-bold text-lg cursor-pointer list-none flex justify-between items-center [&::-webkit-details-marker]:hidden">Tentang Produk <i class="fas fa-chevron-down arrow-icon"></i></summary>
                        <div class="mt-3.5 text-text-medium leading-[1.6] text-base">
                            <p>{{ $kue->deskripsi ?? 'Deskripsi lengkap belum tersedia.' }}</p>
                        </div>
                    </details>
                </div>
            </div>
        </section>

        <section class="border-t border-border-medium pt-10 mt-10 font-sans">
            <div class="flex flex-col lg:flex-row gap-10 items-stretch lg:items-start">
                <div class="flex-1 text-center">
                    <h3 class="mb-2.5 text-xl text-black">Penilaian & Ulasan</h3>
                    <div class="flex justify-center items-baseline">
                        <span class="text-7xl font-bold text-black">{{ number_format($kue->rating, 1, ',', '.') }}</span><span class="text-2xl text-text-light ml-1.5">/5</span>
                    </div>
                    <p class="text-text-light mt-1.5">({{ $totalReviews }} Ulasan)</p>
                </div>
        
                <div class="flex-[1.5] flex flex-col gap-3 lg:pt-11">
                    @for($i = 5; $i >= 1; $i--)
                        @php
                            $percentage = $totalReviews > 0 ? ($ratingCounts[$i] / $totalReviews) * 100 : 0;
                        @endphp
                        <div class="flex items-center gap-2.5 text-sm text-text-medium">
                            <span class="w-8 text-right">{{ $i }} <i class="fas fa-star text-star text-xs"></i></span>
                            <div class="flex-1 h-2.5 bg-bg-gray-progress rounded overflow-hidden"><div class="h-full bg-star rounded" style="width: {{ $percentage }}%;"></div></div>
                            <span class="w-16 text-xs">({{ $ratingCounts[$i] }} ulasan)</span>
                        </div>
                    @endfor
                </div>
        
                <div class="flex-2 w-full mt-5 md:mt-0 px-2 lg:px-0">
                    <h3 class="font-bold text-xl text-black mb-4">Ulasan</h3>
                    
                    @forelse($kue->reviews as $review)
                        <div class="mb-3.5">
                            <div class="stars-list">
                                @for($j = 1; $j <= 5; $j++)
                                    @if($j <= $review->rating)
                                        <i class="fas fa-star text-star text-lg mr-[3px] mb-2.5"></i>
                                    @else
                                        <i class="far fa-star text-text-light text-lg mr-[3px] mb-2.5"></i>
                                    @endif
                                @endfor
                            </div>
                            <div class="flex justify-between text-text-light text-sm mb-2.5">
                                @php
                                    // Sembunyikan nama (contoh: Ahmad -> Ah**d)
                                    $name = $review->transaction->customer_name ?? 'Pelanggan';
                                    if(strlen($name) > 3) {
                                        $hiddenName = substr($name, 0, 2) . str_repeat('*', max(1, strlen($name)-3)) . substr($name, -1);
                                    } else {
                                        $hiddenName = $name;
                                    }
                                @endphp
                                <span>Oleh {{ $hiddenName }}</span>
                                <span>{{ $review->created_at->translatedFormat('d F Y') }}</span>
                            </div>
                            @if($review->rating >= 4)
                                <div class="inline-block bg-bg-info text-text-info py-1.5 px-2.5 rounded text-sm font-bold mb-2.5">Sangat Baik</div>
                            @elseif($review->rating == 3)
                                <div class="inline-block bg-bg-warning-light text-text-warning-dark py-1.5 px-2.5 rounded text-sm font-bold mb-2.5">Cukup Baik</div>
                            @else
                                <div class="inline-block bg-bg-danger-light text-text-danger-dark py-1.5 px-2.5 rounded text-sm font-bold mb-2.5">Kurang Memuaskan</div>
                            @endif
                            
                            @if($review->comment)
                                <div class="text-text-lighter text-sm">{{ $review->comment }}</div>
                            @endif
                        </div>
                        <hr class="border-0 border-t border-border-light my-5">
                    @empty
                        <div class="text-text-light text-sm text-center py-5 italic border border-dashed border-border-medium rounded-lg">
                            Belum ada ulasan untuk produk ini.<br>Jadilah yang pertama untuk memberikan ulasan!
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <hr class="border-0 border-t border-border-light my-5 hidden lg:block">

        <section class="recommendation">
            <h1 class="text-center py-10 font-bold text-3xl">Mungkin Anda Suka</h1>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-5 mt-5" id="recommendation-grid">
                @foreach($rekomendasi as $item)
                    <div class="bg-white rounded-xl shadow-sm border border-border-cream flex flex-col h-full">
                        <div class="flex justify-between pt-3 md:pt-5 px-3 md:px-8 pb-0">
                            <div class="title-cat w-full">
                                <h3 class="text-sm md:text-xl text-text-darker mb-1 font-extrabold truncate">{{ $item->nama }}</h3>
                                <span class="text-xs md:text-base text-text-slate font-semibold">{{ $item->tipe }}</span>
                            </div>
                        </div>
                        <div class="relative w-full mb-2 md:mb-4 flex-1">
                            <div class="absolute top-3 left-3 md:left-9 bg-white/95 py-0.5 px-1.5 md:py-1.5 md:px-2.5 rounded text-xs md:text-sm font-extrabold text-text-dark flex items-center gap-1 shadow-[0_2px_5px_rgba(0,0,0,0.1)] z-10"><i class="fa-solid fa-star text-star"></i>{{ $item->rating }}</div>
                            <img loading="lazy" src="{{ asset($item->gambar) }}" alt="{{ $item->nama }}" class="w-full h-32 md:h-48 rounded px-2 md:px-8 object-cover block mt-2">
                        </div>
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center px-3 md:px-8 pb-3 md:pb-5 mt-auto gap-2">
                            <p class="text-sm md:text-xl font-bold mb-0">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                            <div class="flex flex-row w-full md:w-auto">
                                <a href="{{ url('/detail/' . $item->id) }}" class="flex-1 md:flex-none bg-primary-brown text-white border-none py-1.5 md:py-2.5 px-0 md:px-5 rounded font-bold cursor-pointer no-underline block text-center hover:bg-dark-brown text-xs md:text-base">Detail</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </main>
@endsection