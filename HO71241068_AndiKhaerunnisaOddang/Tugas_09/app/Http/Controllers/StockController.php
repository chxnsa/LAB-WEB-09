<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $warehouses = Warehouse::all();
        $selectedWarehouse = $request->warehouse_id 
            ? Warehouse::find($request->warehouse_id) 
            : $warehouses->first();

        $stocks = [];
        if ($selectedWarehouse) {
            $stocks = DB::table('product_warehouse')
                ->join('products', 'product_warehouse.product_id', '=', 'products.id')
                ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                ->where('product_warehouse.warehouse_id', $selectedWarehouse->id)
                ->select(
                    'products.id',
                    'products.name as product_name',
                    'categories.name as category_name',
                    'product_warehouse.quantity'
                )
                ->get();
        }

        return view('stocks.index', compact('warehouses', 'selectedWarehouse', 'stocks'));
    }

    public function transfer()
    {
        $warehouses = Warehouse::all();
        $products = Product::all();
        return view('stocks.transfer', compact('warehouses', 'products'));
    }

    public function storeTransfer(Request $request)
    {
        $validated = $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|not_in:0',
        ]);

        $warehouse = Warehouse::findOrFail($request->warehouse_id);
        $product = Product::findOrFail($request->product_id);

        // Cek stok saat ini
        $currentStock = DB::table('product_warehouse')
            ->where('warehouse_id', $warehouse->id)
            ->where('product_id', $product->id)
            ->value('quantity');

        $newQuantity = ($currentStock ?? 0) + $request->quantity;

        // Validasi: stok tidak boleh minus
        if ($newQuantity < 0) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['quantity' => 'Stok tidak mencukupi. Stok saat ini: ' . ($currentStock ?? 0)]);
        }

        // Update atau insert stok
        DB::table('product_warehouse')
            ->updateOrInsert(
                [
                    'warehouse_id' => $warehouse->id,
                    'product_id' => $product->id,
                ],
                ['quantity' => $newQuantity]
            );

        $action = $request->quantity > 0 ? 'masuk' : 'keluar';
        return redirect()->route('stocks.index')
            ->with('success', "Stok berhasil di{$action}kan.");
    }
}