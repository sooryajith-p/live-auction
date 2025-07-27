<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['bids' => function ($q) {
            $q->orderByDesc('amount');
        }, 'bids.user'])->latest('id')->get();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $product = new Product();
        return view('products.create', compact('product'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'starting_price' => 'required|numeric|min:0',
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'required|date|after:start_time',
            'video_url' => 'nullable|url',
        ]);

        $validated['current_price'] = $validated['starting_price'];

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit($encryptedId)
    {
        $product = Product::findOrFail(Crypt::decrypt($encryptedId));
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, $encryptedId)
    {
        $product = Product::findOrFail(Crypt::decrypt($encryptedId));

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'starting_price' => 'required|numeric|min:0',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'video_url' => 'nullable|url',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy($encryptedId)
    {
        $product = Product::findOrFail(Crypt::decrypt($encryptedId));
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
