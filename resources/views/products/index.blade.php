<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('products.create') }}" class="mb-4 inline-block bg-blue-500 px-4 py-2 rounded">
                + Add Product
            </a>

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left">Title</th>
                            <th class="px-4 py-2 text-left">Start Price</th>
                            <th class="px-4 py-2 text-left">Highest Bidder</th>
                            <th class="px-4 py-2 text-left">Highest Bid Price</th>
                            <th class="px-4 py-2 text-left">Start Time</th>
                            <th class="px-4 py-2 text-left">End Time</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $product->title }}</td>
                                <td class="px-4 py-2">₹{{ $product->starting_price }}</td>
                                <td class="px-4 py-2">
                                    {{ $product->bids->sortByDesc('amount')->first()->user->name ?? '—' }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ $product->bids->sortByDesc('amount')->first()?->amount ? '₹' . number_format($product->bids->sortByDesc('amount')->first()->amount, 2) : '—' }}
                                </td>
                                <td class="px-4 py-2">{{ $product->start_time }}</td>
                                <td class="px-4 py-2">{{ $product->end_time }}</td>
                                <td class="px-4 py-2">
                                    @php
                                        $now = now();
                                        if ($product->start_time > $now) {
                                            $status = 'Upcoming';
                                        } elseif ($product->end_time < $now) {
                                            $status = 'Ended';
                                        } else {
                                            $status = 'Live';
                                        }
                                    @endphp
                                    <span
                                        class="inline-block px-2 py-1 text-xs font-semibold rounded 
                                        {{ $status === 'Live' ? 'bg-green-100 text-green-800' : ($status === 'Ended' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ $status }}
                                    </span>
                                </td>

                                <td class="px-4 py-2">
                                    <a href="{{ route('products.edit', encrypt($product->id)) }}"
                                        class="text-blue-600 hover:underline">Edit</a>
                                    <form method="POST"
                                        action="{{ route('products.destroy', encrypt($product->id)) }}"
                                        class="inline-block ml-2">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Delete this product?')"
                                            class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
