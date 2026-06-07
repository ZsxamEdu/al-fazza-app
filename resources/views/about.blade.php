@extends('layouts.main')

@section('content')
    <div class="max-w-6xl mx-auto py-16 px-5">
        <div class="flex flex-col-reverse md:flex-row items-center gap-10 mb-16">
            <div class="flex-1">
                <h2 class="text-4xl font-bold text-dark-brown mb-5">Sejarah Al-Fazza Bakery</h2>
                <p class="text-base text-text-medium leading-[1.7] mb-4 text-justify">Al-Fazza Bakery berawal dari kecintaan keluarga kami terhadap dunia *baking* dan komitmen untuk menyajikan hidangan berkualitas di meja makan. Berawal dari dapur rumah tangga yang sederhana, kami mulai memproduksi aneka roti dan kue dengan resep andalan keluarga.</p>
                <p class="text-base text-text-medium leading-[1.7] mb-4 text-justify">Setiap produk Al-Fazza Bakery lahir dari tangan terampil sang Ayah, yang mendedikasikan waktunya di dapur untuk meracik adonan, memilih bahan-bahan premium, dan memanggangnya hingga sempurna. Kami percaya bahwa kualitas rasa berawal dari proses pembuatan yang jujur dan penuh dedikasi.</p>
                <p class="text-base text-text-medium leading-[1.7] mb-4 text-justify">Kini, melalui manajemen pemasaran yang dikelola langsung oleh sang Ibu, Al-Fazza terus berkembang untuk menjangkau lebih banyak keluarga, memastikan setiap pesanan diantarkan dengan pelayanan yang hangat dan penuh senyum.</p>
            </div>
            <div class="flex-1 w-full">
                <div class="rounded-xl overflow-hidden shadow-[0_8px_20px_rgba(166,124,82,0.15)] border-4 border-border-cream">
                    <img loading="lazy" src="{{ asset('assets/img/tempat.png') }}" alt="Dapur Al-Fazza" class="w-full h-auto block">
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row items-center gap-10 mb-16">
            <div class="flex-1 w-full">
                <div class="rounded-xl overflow-hidden shadow-[0_8px_20px_rgba(166,124,82,0.15)] border-4 border-border-cream">
                    <img loading="lazy" src="{{ asset('assets/img/pisangbolen 1.png') }}" alt="Produk Al-Fazza" class="w-full h-auto block">
                </div>
            </div>
            <div class="flex-1">
                <h2 class="text-4xl font-bold text-dark-brown mb-5">Visi & Misi Kami</h2>
                <p class="text-base text-text-medium leading-[1.7] text-justify">Visi kami adalah menjadikan produk Al-Fazza Bakery sebagai pilihan utama dan sajian favorit di setiap momen istimewa keluarga Anda.</p>
                <br>
                <p class="text-base text-text-medium leading-[1.7] text-justify mb-2.5"><strong>Misi kami adalah:</strong></p>
                <ul class="list-disc pl-5 text-base text-text-medium leading-[1.7] flex flex-col gap-2.5">
                    <li>Terus berinovasi mengembangkan produk roti dan kue yang berkualitas tinggi, bergizi, dan sehat dengan cita rasa autentik.</li>
                    <li>Menggunakan bahan-bahan premium pilihan tanpa kompromi untuk menjaga standar rasa.</li>
                    <li>Memberikan pelayanan pelanggan yang ramah, hangat, dan responsif layaknya keluarga sendiri.</li>
                </ul>
            </div>
        </div>
    </div>
@endsection