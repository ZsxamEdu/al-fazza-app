@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-xl lg:text-3xl font-bold m-0 mb-2">Manajemen Pesanan</h1>
            <p>Kelola dan periksa detail pesanan pelanggan.</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white p-5 rounded-lg mb-5">
        <form action="{{ route('admin.pesanan.index') }}" method="GET" class="flex gap-4 items-end flex-wrap text-sm lg:text-base">
            
            <div class="flex-1 min-w-40">
                <label>Cari (Nama / Invoice):</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik kata kunci..." class="border border-border-medium rounded w-full p-2">
            </div>
            <div class="flex-1 min-w-40">
                <label>Filter Status:</label>
                <select name="status" class="border border-border-medium rounded w-full p-2">
                    <option value="">Semua Status</option>
                    <option value="baru" {{ request('status') == 'baru' ? 'selected' : '' }}>Pesanan Baru</option>
                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Sedang Dibuat</option>
                    <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>Sedang Dikirim</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <div class="flex-1 min-w-40">
                <label>Jenis Pesanan:</label>
                <select name="jenis" class="border border-border-medium rounded w-full p-2">
                    <option value="">Semua Jenis</option>
                    <option value="online" {{ request('jenis') == 'online' ? 'selected' : '' }}>Online (Roti Biasa)</option>
                    <option value="custom-order" {{ request('jenis') == 'custom-order' ? 'selected' : '' }}>Custom Cake</option>
                    <option value="kasir" {{ request('jenis') == 'kasir' ? 'selected' : '' }}>Kasir (Offline)</option>
                </select>
            </div>

            <div>
                <button type="submit" class="bg-success text-white py-2.5 px-5 rounded-md font-bold hover:bg-green-700 transition inline-block"><i class="fa-solid fa-filter"></i> Filter</button>
                <a href="{{ route('admin.pesanan.index') }}" class="bg-slate-200 text-text-dark py-2.5 px-5 rounded-md font-bold hover:bg-slate-300 transition inline-block no-underline">Reset</a>
            </div>
        </form>
    </div>

    <div class="bg-white p-5 rounded-lg shadow-sm overflow-x-auto">
        <table class="w-full border-collapse text-left [&_th]:bg-primary-brown [&_th]:text-white [&_th]:py-3 [&_th]:px-4 [&_td]:py-3 [&_td]:px-4 [&_td]:border-b [&_td]:border-border-light [&_td]:align-middle [&_tr:hover]:bg-gray-50 text-xs lg:text-base whitespace-nowrap lg:whitespace-normal">
            <thead>
                <tr class="hover:bg-gray-50">
                    <th class="bg-primary-brown text-white py-3 px-4">Invoice & Waktu</th>
                    <th class="bg-primary-brown text-white py-3 px-4">Info Pelanggan & Pengiriman</th>
                    <th class="bg-primary-brown text-white py-3 px-4">Detail Item / Custom Cake</th>
                    <th class="bg-primary-brown text-white py-3 px-4">Status</th>
                    <th class="bg-primary-brown text-white py-3 px-4">Ubah Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanan as $p)
                <tr class="hover:bg-gray-50">
                    <!-- Kolom 1: Invoice -->
                    <td class="align-top py-3 px-4 border-b border-border-light align-middle">
                        <strong>{{ $p->invoice_number }}</strong><br>
                        <small>Dipesan: {{ $p->created_at->format('d M Y') }}</small><br>
                        
                        <!-- Tampilkan Tanggal Harus Dikirim -->
                        @if($p->delivery_date)
                            <div class="inline-block mt-1 p-1 bg-bg-warning-light border border-orange-200 rounded text-xs text-orange-600">
                                <i class="fa-regular fa-calendar-check"></i> Krm: <strong>{{ \Carbon\Carbon::parse($p->delivery_date)->format('d M Y') }}</strong>
                            </div><br>
                        @endif

                        <span class="inline-block mt-1 py-1 px-1.5 rounded text-[11px] font-bold 
                            {{ $p->order_type == 'custom-order' ? 'bg-purple-500 text-white' : ($p->order_type == 'kasir' ? 'bg-primary-brown text-white' : 'bg-blue-500 text-white') }}">
                            {{ strtoupper($p->order_type) }}
                        </span>
                    </td>
                    
                    <!-- Kolom 2: Info Pengiriman -->
                    <td class="align-top min-w-[200px] whitespace-normal break-words py-3 px-4 border-b border-border-light align-middle">
                        <strong>{{ $p->customer_name }}</strong><br>
                        <i class="fa-solid fa-phone"></i> {{ $p->customer_phone ?? '-' }}<br>
                        <hr class="my-1 border-t border-border-light">
                        <span class="text-xs text-text-medium">{{ $p->delivery_address ?? 'Beli di Tempat (Kasir)' }}</span>
                        @if($p->notes && $p->notes != '-')
                            <div class="mt-1 text-xs text-orange-600 bg-orange-50 p-1 rounded">
                                <strong>Catatan:</strong> {{ $p->notes }}
                            </div>
                        @endif
                    </td>
                    
                    <!-- Kolom 3: Detail Produk -->
                    <td class="align-top min-w-[250px] whitespace-normal break-words py-3 px-4 border-b border-border-light align-middle">
                        @if($p->order_type == 'custom-order')
                            <!-- Tampilkan Detail Custom Cake -->
                            <div class="text-sm text-text-dark bg-gray-50 p-2 rounded-md">
                                {!! str_replace(', ', '<br>', $p->custom_details) !!}
                            </div>
                        @else
                            <!-- Tampilkan List Roti Biasa -->
                            <ul class="pl-5 m-0 text-sm">
                                @foreach($p->details as $detail)
                                    <li>
                                        {{ $detail->product->nama}} 
                                        @if($detail->product && $detail->product->trashed())
                                            <span class="text-xs text-red-500 font-normal italic ml-1">(Dihapus)</span>
                                        @endif
                                        (x{{ $detail->qty }})
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </td>

                    <!-- Kolom 4: Badge Status -->
                    <td class="py-3 px-4 border-b border-border-light align-middle">
                        @if($p->order_status == 'baru')
                            <span class="bg-red-500 text-white py-1 px-2 rounded text-xs">Pesanan Baru</span>
                        @elseif($p->order_status == 'diproses')
                            <span class="bg-warning text-white py-1 px-2 rounded text-xs">Diproses/Dibuat</span>
                        @elseif($p->order_status == 'dikirim')
                            <span class="bg-blue-500 text-white py-1 px-2 rounded text-xs">Sedang Dikirim</span>
                        @else
                            <span class="bg-success text-white py-1 px-2 rounded text-xs">Selesai</span>
                        @endif
                    </td>

                    <!-- Kolom 5: Form Ubah Status -->
                    <td class="align-top py-3 px-4 border-b border-border-light align-middle">
                        <form action="{{ route('admin.pesanan.updateStatus', $p->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select name="order_status" onchange="confirmStatusChange(this)" class="p-1.5 rounded w-full border border-border-medium">
                                <option value="baru" {{ $p->order_status == 'baru' ? 'selected' : '' }}>Baru</option>
                                <option value="diproses" {{ $p->order_status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="dikirim" {{ $p->order_status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                <option value="selesai" {{ $p->order_status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </form>
                    </td>
                </tr>
                @empty
                <tr class="hover:bg-gray-50">
                    <td colspan="5" class="text-center p-8">Tidak ada pesanan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Navigasi Halaman --}}
    <div class="mt-4">
        {{ $pesanan->links('vendor.pagination.admin') }}
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmStatusChange(selectEl) {
    const label = selectEl.options[selectEl.selectedIndex].text;
    
    Swal.fire({
        title: 'Konfirmasi Perubahan',
        text: 'Ubah status pesanan menjadi "' + label + '"?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#1976d2',
        cancelButtonColor: '#888',
        confirmButtonText: 'Ya, Ubah',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            showLoader();
            selectEl.form.submit();
        } else {
            // Kembalikan ke nilai sebelumnya (sebelum user geser dropdown)
            selectEl.value = selectEl.dataset.original ?? selectEl.value;
        }
    });
}

// Simpan nilai awal setiap dropdown agar bisa di-reset jika batal
document.querySelectorAll('select[name="order_status"]').forEach(function(el) {
    el.dataset.original = el.value;
});
</script>
@endpush