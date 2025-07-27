<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $product->title }} - Live Auction
            </h2>
            <a href="{{ route('auctions.live.index') }}" class="text-sm text-indigo-600 hover:underline">
                ← Back to Auctions
            </a>
        </div>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto space-y-8 px-4">
            <div class="bg-white shadow rounded-lg p-6 space-y-4">
                <h3 class="text-xl font-bold text-indigo-700">{{ $product->title }}</h3>
                <p class="text-sm text-gray-500">Starting Price: ₹{{ number_format($product->starting_price, 2) }}</p>
                <p class="text-gray-700">{{ $product->description }}</p>

                {{-- Video --}}
                @if ($product->video_url)
                    <div class="w-full h-64 md:h-96">
                        <iframe class="w-full h-full rounded" src="{{ $product->video_url }}" frameborder="0"
                            allowfullscreen></iframe>
                    </div>
                @endif

                {{-- Countdown --}}
                <div class="text-gray-600">
                    <span class="font-semibold">Ends in:</span>
                    <span id="countdown" class="text-red-600 font-bold"></span>
                </div>

                {{-- Bid Form --}}
                <form id="bid-form" action="{{ route('auctions.live.bid.store') }}" method="POST"
                    class="flex flex-wrap gap-3 items-center mt-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="number" name="amount" step="0.01" min="{{ $product->current_price }}"
                        class="border rounded-md p-2 w-40" placeholder="Your Bid" required>
                    <x-primary-button class="px-5">💰 Place Bid</x-primary-button>
                </form>
                <div class="text-sm text-green-600 mt-2" id="bid-notification-{{ $product->id }}"></div>

                {{-- Bid History --}}
                <div class="mt-6">
                    <h4 class="text-md font-semibold mb-2">Recent Bids</h4>
                    <ul id="bids-{{ $product->id }}" class="space-y-1 text-sm">
                        @foreach ($product->bids->sortByDesc('created_at')->take(5) as $bid)
                            <li class="text-gray-700">💸
                                <strong>{{ $bid->user_id === auth()->id() ? 'Me' : $bid->user->name }}</strong> bid
                                ₹{{ $bid->amount }} at
                                {{ $bid->created_at->format('H:i:s') }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Chat Box --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h4 class="text-lg font-semibold mb-2">💬 Live Chat</h4>
                <div id="chat-{{ $product->id }}"
                    class="border rounded h-40 p-4 overflow-y-auto bg-gray-50 text-sm space-y-1 scroll-smooth">
                    @foreach ($product->messages->sortBy('created_at') as $msg)
                        <div class="flex {{ $msg->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                            <div
                                class="inline-block px-3 py-2 rounded-lg 
                                    {{ $msg->user_id === auth()->id() ? 'bg-indigo-100 text-right text-indigo-700' : 'bg-gray-200 text-gray-800' }}">
                                <strong
                                    class="{{ $msg->user_id === auth()->id() ? 'text-indigo-600' : 'text-indigo-700' }}">
                                    {{ $msg->user_id === auth()->id() ? 'Me' : $msg->user->name }}:
                                </strong> {{ $msg->message }}
                            </div>
                        </div>
                    @endforeach
                </div>
                <form id="chat-form" action="{{ route('auctions.live.chat.send') }}" method="POST"
                    class="mt-3 flex gap-2">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="text" name="message" class="flex-1 border rounded px-3 py-2"
                        placeholder="Type your message..." required>
                    <x-primary-button>📨 Send</x-primary-button>
                </form>
            </div>

        </div>
    </div>
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <script>
        const pusherKey = @json(config('broadcasting.connections.pusher.key'));
        const pusherCluster = @json(config('broadcasting.connections.pusher.options.cluster'));
        const userId = @json(auth()->id());
        const userName = @json(auth()->user()->name);
        const productId = @json($product->id);
        const endTime = new Date(@json($product->end_time->toIso8601String())).getTime();
        const redirectUrl = @json(route('auctions.live.index'));
    </script>
    <script src="{{ asset('assets/js/auction.js') }}"></script>
</x-app-layout>
