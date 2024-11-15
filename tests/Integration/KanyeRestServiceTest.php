<?php

namespace Tests\Integration;

use App\Services\KanyeRestService;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\AssertsRandomUniqueQuotes;

class KanyeRestServiceTest extends TestCase
{
    use AssertsRandomUniqueQuotes;

    #[Test]
    public function fetches_5_random_unique_kanye_quotes_from_kanye_rest_website_successfully(): void
    {
        $kanyeRestService = new KanyeRestService();

        $quotes = $kanyeRestService->fetchRandomUniqueQuotes(5);

        $this->assertRandomUniqueQuotes($quotes, 5);
    }
}
