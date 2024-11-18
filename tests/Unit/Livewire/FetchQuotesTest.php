<?php

namespace Unit\Livewire;

use App\Livewire\FetchQuotes;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Livewire\Component;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\AssertsRandomUniqueQuotes;

class FetchQuotesTest extends TestCase
{
    use AssertsRandomUniqueQuotes;

    private ?FetchQuotes $fetchQuotes;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fetchQuotes = new FetchQuotes();
    }

    #[Test]
    public function create_instance_correctly(): void
    {
        $this->assertInstanceOf(Component::class, $this->fetchQuotes);
    }

    #[Test]
    public function method_render_returns_correct_view():void
    {
        $this->assertInstanceOf(View::class, $this->fetchQuotes->render());
    }

    #[Test]
    public function method_fetchQuotes_fetches_quotes(): void
    {
        $this->prepareFakeHttpResponse();

        $this->assertIsArray($this->fetchQuotes->quotes);
        $this->assertEmpty($this->fetchQuotes->quotes);

        $this->fetchQuotes->fetchQuotes();

        $this->assertRandomUniqueQuotes($this->fetchQuotes->quotes, 2);
    }

    #[Test]
    public function method_mount_fetches_quotes(): void
    {
        $this->prepareFakeHttpResponse();

        $this->assertSame('', $this->fetchQuotes->uniqueKey);

        $this->fetchQuotes->mount();

        $this->assertNotEmpty($this->fetchQuotes->uniqueKey);
    }

    #[Test]
    public function method_refreshQuotes_fetches_quotes(): void
    {
        $this->prepareFakeHttpResponse();

        $this->assertSame('', $this->fetchQuotes->uniqueKey);

        $this->fetchQuotes->refreshQuotes();

        $this->assertNotEmpty($this->fetchQuotes->uniqueKey);
    }

    private function prepareFakeHttpResponse(): void
    {
        session()->put('auth_token', '12345');
        Config::set('app.random_quotes_number', 2);
        Http::fake([
            config('app.url') . '/api/quotes' => Http::response(['quotes' => ['I am the best', 'I am the greatest']], 200),
        ]);
    }
}
