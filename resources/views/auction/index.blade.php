<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Live Auctions</h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if ($products->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($products as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                        <div class="p-4 space-y-2">
                            <h3 class="text-lg font-bold text-gray-800">{{ $product->title }}</h3>

                            <div class="text-sm text-gray-500">
                                <p class="line-clamp-2">{{ Str::limit($product->description, 80) }}</p>
                            </div>

                            <div class="text-sm text-gray-700">
                                <strong>Starting:</strong> ₹{{ number_format($product->starting_price, 2) }}<br>
                                <strong>Current:</strong>
                                ₹{{ number_format($product->current_price ?? $product->starting_price, 2) }}
                            </div>

                            <div class="text-xs text-gray-500">
                                <span id="countdown-{{ $product->id }}"></span>
                            </div>

                            <div class="pt-3">
                                <a href="{{ route('auctions.live.show', $product->slug) }}"
                                    class="inline-block bg-indigo-600 hover:bg-indigo-700 text-sm font-semibold px-4 py-2 rounded">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white p-6 text-center rounded shadow text-gray-600">
                No live auctions at the moment.
            </div>
        @endif
    </div>

    {{-- Countdown Timer --}}
    <script>
        @foreach ($products as $product)
            (function() {
                const countdownEl = document.getElementById("countdown-{{ $product->id }}");
                const endTime = new Date("{{ $product->end_time->toIso8601String() }}").getTime();

                const timer = setInterval(() => {
                    const now = new Date().getTime();
                    const diff = endTime - now;

                    if (diff <= 0) {
                        countdownEl.innerText = "⏱️ Auction Ended";
                        clearInterval(timer);
                        return;
                    }

                    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                    let timeStr = "⏱️ Ends in: ";
                    if (days > 0) timeStr += `${days}d `;
                    if (hours > 0 || days > 0) timeStr += `${hours}h `;
                    timeStr += `${minutes}m ${seconds}s`;

                    countdownEl.innerText = timeStr;
                }, 1000);
            })();
        @endforeach
    </script>
</x-app-layout>
