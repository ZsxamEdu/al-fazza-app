@extends('layouts.main')

@section('content')
    <main class="container-detail">
        <section class="main-info">
            <div class="product-gallery">
                <img id="product-img" src="{{ asset($kue->gambar) }}" alt="{{ $kue->nama }}">
            </div>

            <div class="product-order">
                <div class="title-row">
                    <h1>{{ $kue->nama }}</h1>
                </div>
                <p class="tagline">{{ $kue->nama }} - {{ $kue->tipe }}</p>
                <h2 class="price">Rp {{ number_format($kue->harga, 0, ',', '.') }}</h2>

                <div class="order-controls">
                    <!-- Tombol beli mengambil QTY dari input -->
                    <button class="btn-primary-cart" onclick="let qty = parseInt(document.getElementById('qty').value) || 1; addToCart('{{ $kue->id }}', '{{ $kue->nama }}', {{ $kue->harga }}, '{{ asset($kue->gambar) }}', qty)">Tambahkan ke Keranjang</button>
                    <div class="qty-input">
                        <button onclick="changeQty(-1)">-</button>
                        <input type="number" id="qty" value="1" min="1" readonly>
                        <button onclick="changeQty(1)">+</button>
                    </div>
                </div>

                <div class="info-dropdown">
                    <details open>
                        <summary>Informasi Produk <i class="fas fa-chevron-down arrow-icon"></i></summary>
                        <hr class="info-divider">
                        <div class="content">
                            <p><strong><i class="fa-solid fa-wheat-awn"></i> Bahan Utama:</strong> <span>{{ $kue->bahan ?? 'Informasi bahan belum tersedia.' }}</span></p>
                            <hr class="info-divider">
                            <p><strong><i class="fa-solid fa-jar"></i> Saran Penyimpanan:</strong> Simpan dalam wadah tertutup rapat. Tahan 2 hari di suhu ruang, 4-5 hari di dalam lemari pendingin.</p>
                            <hr class="info-divider">
                        </div>
                    </details>
                    <details open>
                        <summary>Tentang Produk <i class="fas fa-chevron-down arrow-icon"></i></summary>
                        <div class="content">
                            <p>{{ $kue->deskripsi ?? 'Deskripsi lengkap belum tersedia.' }}</p>
                        </div>
                    </details>
                </div>
            </div>
        </section>

        <section class="review-section">
            <div class="review-layout">
                <div class="review-left">
                    <h3>Penilaian & Ulasan</h3>
                    <div class="score-display">
                        <span class="big-score">4,9</span><span class="max-score">/5</span>
                    </div>
                    <p class="total-reviews">(100 Ulasan)</p>
                </div>
        
                <div class="review-middle">
                    <div class="bar-row">
                        <span class="star-num">5 <i class="fas fa-star"></i></span>
                        <div class="progress-bg"><div class="progress-fill" style="width: 99%;"></div></div>
                        <span class="bar-count">(99 ulasan)</span>
                    </div>
                    <div class="bar-row">
                        <span class="star-num">4 <i class="fas fa-star"></i></span>
                        <div class="progress-bg"><div class="progress-fill" style="width: 1%;"></div></div>
                        <span class="bar-count">(1 ulasan)</span>
                    </div>
                    <div class="bar-row">
                        <span class="star-num">3 <i class="fas fa-star"></i></span>
                        <div class="progress-bg"><div class="progress-fill" style="width: 0%;"></div></div>
                        <span class="bar-count">(0 ulasan)</span>
                    </div>
                    <div class="bar-row">
                        <span class="star-num">2 <i class="fas fa-star"></i></span>
                        <div class="progress-bg"><div class="progress-fill" style="width: 0%;"></div></div>
                        <span class="bar-count">(0 ulasan)</span>
                    </div>
                    <div class="bar-row">
                        <span class="star-num">1 <i class="fas fa-star"></i></span>
                        <div class="progress-bg"><div class="progress-fill" style="width: 0%;"></div></div>
                        <span class="bar-count">(0 ulasan)</span>
                    </div>
                </div>
        
                <div class="review-right">
                    <h3 class="review-title">Ulasan</h3>
                    
                    <div class="review-item">
                        <div class="stars-list">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <div class="review-meta">
                            <span>Oleh Mel**i</span>
                            <span>20 Juni 2024</span>
                        </div>
                        <div class="review-tag">Kualitas sangat baik</div>
                        <div class="review-variant">Adminnya ramah banget dan ada banyak jenis untuk bakery pastrynya, sangat recommended banget buat kalian.</div>
                    </div>
                    
                    <hr class="review-divider">
                    
                    <div class="review-item">
                        <div class="stars-list">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <div class="review-meta">
                            <span>Oleh Nu**l</span>
                            <span>23 Juni 2024</span>
                        </div>
                        <div class="review-tag">Kualitas sangat baik</div>
                        <div class="review-variant">Kuenya mantap dan teksturnya juga lembut, cocok untuk dinikmati di akhir pekan. </div>
                    </div>
                    <hr class="review-divider">
                </div>
            </div>
        </section>

        <hr class="section-divider">

        <section class="recommendation">
            <h1 class="recommendation-title">Mungkin Anda Suka</h1>
            <div id="recommendation-grid">
                @foreach($rekomendasi as $item)
                    <div class="card">
                        <div class="card-header">
                            <div class="title-cat">
                                <h3>{{ $item->nama }}</h3>
                                <span>{{ $item->tipe }}</span>
                            </div>
                        </div>
                        <div class="card-img-wrapper">
                            <div class="rating"><i class="fa-solid fa-star"></i>{{ $item->rating }}</div>
                            <img src="{{ asset($item->gambar) }}" alt="{{ $item->nama }}">
                        </div>
                        <div class="card-footer">
                            <p>Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                            <div>
                                <a href="{{ url('/detail/' . $item->id) }}" class="btn-brown" style="width: 100%; display: block; text-align: center;">Detail</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </main>
@endsection