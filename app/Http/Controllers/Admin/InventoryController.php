<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\InventoryLog;
use App\Http\Requests\StoreInventoryRequest;
use App\Services\InventoryService;

class InventoryController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function stokIndex()
    {
        $logs = InventoryLog::with(['product' => function($q) {
            $q->withTrashed();
        }])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.stok.index', compact('logs'));
    }

    public function stokCreate()
    {
        $products = Product::orderBy('nama', 'asc')->get();
        return view('admin.stok.create', compact('products'));
    }

    public function stokStore(StoreInventoryRequest $request)
    {
        $result = $this->inventoryService->updateStockManual($request->validated());

        if (!$result['success']) {
            return redirect()->back()->with('error', $result['message']);
        }

        return redirect()->route('admin.stok.index')->with('success', $result['message']);
    }
}
