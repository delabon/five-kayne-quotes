<div>
    <div wire:poll.60s="fetchQuotes" wire:key="{{ $uniqueKey }}">
        <h1>Random Kanye West's quotes!</h1>

        <button wire:click.prevent="refreshQuotes">Fetch Quotes</button>

        <ul>
            @foreach ($quotes as $quote)
                <li>"{{ $quote }}" - Kanye West</li>
            @endforeach
        </ul>
    </div>
</div>
