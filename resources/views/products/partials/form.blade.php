<div class="space-y-6">
    <div>
        <x-input-label for="title" value="Title" />
        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $product->title)" required />
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="description" value="Description" />
        <textarea id="description" name="description"
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            rows="4">{{ old('description', $product->description) }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="starting_price" value="Starting Price" />
        <x-text-input id="starting_price" name="starting_price" type="number" step="0.01" class="mt-1 block w-full"
            :value="old('starting_price', $product->starting_price)" required />
        <x-input-error :messages="$errors->get('starting_price')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="start_time" value="Start Time" />
        <x-text-input id="start_time" name="start_time" type="datetime-local" class="mt-1 block w-full"
            :value="old('start_time', $product->start_time ? $product->start_time->format('Y-m-d\TH:i') : '')" required />
        <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="end_time" value="End Time" />
        <x-text-input id="end_time" name="end_time" type="datetime-local" class="mt-1 block w-full" :value="old('end_time', $product->end_time ? $product->end_time->format('Y-m-d\TH:i') : '')"
            required />
        <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="video_url" value="YouTube Video URL" />
        <x-text-input id="video_url" name="video_url" type="url" class="mt-1 block w-full" :value="old('video_url', $product->video_url)" />
        <x-input-error :messages="$errors->get('video_url')" class="mt-2" />
    </div>

    <div class="pt-4">
        <x-primary-button>
            {{ isset($product->id) ? 'Update' : 'Create' }}
        </x-primary-button>
    </div>
</div>
