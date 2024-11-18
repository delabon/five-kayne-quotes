<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Livewire\Component;

class FetchQuotes extends Component
{
    public array $quotes = [];
    public string $uniqueKey = '';

    public function fetchQuotes(): void
    {
        $token = session()->get('auth_token');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->get(config('app.url') . '/api/quotes');

        if ($response->successful()) {
            $this->quotes = $response->json()['quotes'];
        } else {
            $this->quotes = ['Error fetching quotes'];
        }
    }

    public function refreshQuotes(): void
    {
        $this->fetchQuotes();
        $this->uniqueKey = uniqid();
    }

    public function mount(): void
    {
        $this->fetchQuotes();
        $this->uniqueKey = uniqid();
    }

    public function render(): View
    {
        return view('livewire.fetch-quotes');
    }
}
