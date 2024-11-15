<div>
    <div wire:poll.60s="fetchQuotes" wire:key="{{ $uniqueKey }}" class="mt-8">
        <h1 class="font-bold text-2xl mb-6">Random Kanye West's quotes!</h1>

        <button wire:click.prevent="refreshQuotes" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">Fetch Quotes</button>

        <ul class="mt-6">
            @foreach ($quotes as $quote)
                <li class="text-xl italic font-semibold text-gray-900 mb-2">
                    {{ $quote }}
                    <cite class="font-bold text-red-900">- Kanye West</cite>
                </li>
            @endforeach
        </ul>
    </div>
</div>
