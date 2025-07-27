<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Message;
use App\Events\BidPlaced;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LiveAuctionController extends Controller
{
    public function index()
    {
        $products = Product::where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->orderByDesc('id')
            ->get();

        return view('auction.index', compact('products'));
    }

    public function show($slug)
    {
        $product = Product::where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->where('slug', $slug)
            ->with(['bids.user', 'messages.user'])
            ->firstOrFail();

        return view('auction.show', compact('product'));
    }

    public function placeBid(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $product = Product::findOrFail($request->product_id);
        $highestBid = $product->bids()->max('amount') ?? $product->starting_price;

        if ($request->amount <= $highestBid) {
            return response()->json([
                'error' => 'Your bid must be greater than the current bid (₹' . number_format($highestBid, 2) . ').'
            ], 422);
        }

        $bid = $product->bids()->create([
            'user_id' => Auth::id(),
            'amount' => $request->amount,
        ]);

        // Auto-extend if within last 30 seconds
        if (now()->diffInSeconds($product->end_time) < 30) {
            $product->end_time = now()->addSeconds(30);
        }

        $product->current_price = $request->amount;
        $product->save();

        broadcast(new BidPlaced($bid->load('user')))->toOthers();

        return response()->json(['success' => true]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'message' => 'required|string|max:255',
        ]);

        $message = Message::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'message' => $request->message,
        ]);

        broadcast(new MessageSent($message->load('user')))->toOthers();

        return response()->json(['success' => true]);
    }
}
