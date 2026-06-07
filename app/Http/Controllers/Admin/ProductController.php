<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ProductService;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function produkIndex(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->has('stok_menipis') && $request->stok_menipis == '1') {
            $query->where('stok', '<', 10);
        }

        $products = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
        return view('admin.produk.index', compact('products'));
    }

    public function produkCreate()
    {
        return view('admin.produk.create');
    }

    public function produkStore(StoreProductRequest $request)
    {
        $this->productService->storeProduct($request->validated(), $request->file('gambar'));
        return redirect()->route('admin.produk.index')->with('success', 'Produk baru berhasil ditambahkan!');
    }

    public function produkEdit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.produk.edit', compact('product'));
    }

    public function produkUpdate(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $this->productService->updateProduct($product, $request->validated(), $request->file('gambar'));
        return redirect()->route('admin.produk.index')->with('success', 'Data produk berhasil diperbarui!');
    }

    public function produkDestroy($id)
    {
        $product = Product::findOrFail($id);
        $this->productService->deleteProduct($product);
        return redirect()->route('admin.produk.index')->with('success', 'Produk beserta fotonya berhasil dihapus!');
    }
}
