@extends('layouts.main')

@section('content')
<div class="review-container">
    <div class="review-card">
        <div class="review-header">
            <h2>Beri Penilaian</h2>
            <p>Bagaimana pendapat Anda tentang produk ini?</p>
        </div>

        <div class="product-info">
            @if($product->gambar)
                <img src="{{ asset($product->gambar) }}" alt="{{ $product->nama }}">
            @endif
            <div>
                <h3>{{ $product->nama }}</h3>
                <span>Kategori: {{ $product->kategori }}</span>
            </div>
        </div>

        <form action="{{ route('review.store', ['invoice' => $transaction->invoice_number, 'product_id' => $product->id]) }}" method="POST">
            @csrf
            
            <div class="rating-section">
                <label>Pilih Bintang</label>
                <div class="stars">
                    <input type="radio" id="star5" name="rating" value="5" required />
                    <label for="star5" title="Sangat Bagus"><i class="fa-solid fa-star"></i></label>
                    <input type="radio" id="star4" name="rating" value="4" />
                    <label for="star4" title="Bagus"><i class="fa-solid fa-star"></i></label>
                    <input type="radio" id="star3" name="rating" value="3" />
                    <label for="star3" title="Cukup"><i class="fa-solid fa-star"></i></label>
                    <input type="radio" id="star2" name="rating" value="2" />
                    <label for="star2" title="Kurang"><i class="fa-solid fa-star"></i></label>
                    <input type="radio" id="star1" name="rating" value="1" />
                    <label for="star1" title="Sangat Kurang"><i class="fa-solid fa-star"></i></label>
                </div>
            </div>

            <div class="form-group">
                <label for="comment">Ulasan (Opsional)</label>
                <textarea id="comment" name="comment" rows="4" placeholder="Ceritakan pengalaman Anda dengan produk ini..."></textarea>
            </div>

            <button type="submit" class="submit-btn"><i class="fa-solid fa-paper-plane"></i> Kirim Penilaian</button>
        </form>
    </div>
</div>

<style>
    .review-container {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 60px 20px;
        background-color: #f8f9fa;
        min-height: calc(100vh - 200px);
    }
    
    .review-card {
        background: white;
        border-radius: 20px;
        padding: 40px;
        max-width: 500px;
        width: 100%;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .review-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .review-header h2 {
        color: #333;
        font-weight: 700;
        margin-bottom: 10px;
        font-family: 'Inter', sans-serif;
    }

    .review-header p {
        color: #777;
        font-size: 0.95rem;
    }

    .product-info {
        display: flex;
        align-items: center;
        gap: 15px;
        background: #fafafa;
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 30px;
        border: 1px solid #eee;
    }

    .product-info img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
    }

    .product-info h3 {
        margin: 0 0 5px 0;
        font-size: 1.1rem;
        color: #333;
    }

    .product-info span {
        font-size: 0.85rem;
        color: #888;
    }

    .rating-section {
        margin-bottom: 25px;
        text-align: center;
    }

    .rating-section label {
        display: block;
        margin-bottom: 15px;
        font-weight: 600;
        color: #444;
    }

    /* CSS for interactive stars */
    .stars {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
        gap: 10px;
    }
    
    .stars input {
        display: none;
    }

    .stars label {
        font-size: 2.5rem;
        color: #ddd;
        cursor: pointer;
        transition: 0.2s ease-in-out;
        margin: 0;
    }

    .stars input:checked ~ label,
    .stars label:hover,
    .stars label:hover ~ label {
        color: #f39c12;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #444;
    }

    .form-group textarea {
        width: 100%;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 12px;
        font-family: inherit;
        font-size: 0.95rem;
        resize: vertical;
        transition: border-color 0.3s;
        box-sizing: border-box;
    }

    .form-group textarea:focus {
        outline: none;
        border-color: #a67c52;
    }

    .submit-btn {
        width: 100%;
        padding: 15px;
        background: #a67c52;
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .submit-btn:hover {
        background: #8b6844;
        transform: translateY(-2px);
    }
</style>
@endsection
