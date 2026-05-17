<?php

namespace App\Http\Controllers;

use App\Models\ShopProduct;
use App\Models\ShopSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function index()
    {
        $products = ShopProduct::paginate(10);
        $totalProducts = ShopProduct::count();
        $totalRevenue = ShopSale::sum('total_price');
        $totalSales = ShopSale::count();
        return view('shop.index', compact('products', 'totalProducts', 'totalRevenue', 'totalSales'));
    }

    public function createSale()
    {
        $products = ShopProduct::where('quantity_in_stock', '>', 0)->get();
        return view('shop.create_sale', compact('products'));
    }

    public function storeSale(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:shop_products,id',
            'quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string',
        ]);

        $product = ShopProduct::find($validated['product_id']);
        
        if ($product->quantity_in_stock < $validated['quantity']) {
            return back()->with('error', 'Insufficient stock');
        }

        $totalPrice = $product->unit_price * $validated['quantity'];

        $sale = ShopSale::create([
            'shop_product_id' => $product->id,
            'quantity' => $validated['quantity'],
            'total_price' => $totalPrice,
            'payment_method' => $validated['payment_method'],
            'sold_by' => Auth::id(),
            'sale_date' => now(),
        ]);

        $product->decrement('quantity_in_stock', $validated['quantity']);

        return redirect()->route('shop.sales')->with('success', 'Sale recorded successfully');
    }

    public function salesHistory()
    {
        $sales = ShopSale::with(['product', 'seller'])->latest()->paginate(10);
        return view('shop.sales', compact('sales'));
    }
}
