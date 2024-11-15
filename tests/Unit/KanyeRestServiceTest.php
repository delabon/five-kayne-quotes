<?php

namespace Tests\Unit;

use App\Services\KanyeRestService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use RuntimeException;
use Tests\TestCase;
use Tests\Traits\AssertsRandomUniqueQuotes;

class KanyeRestServiceTest extends TestCase
{
    use AssertsRandomUniqueQuotes;

    #[Test]
    public function method_fetchRandomQuotes_fetches_random_unique_quotes(): void
    {
        Http::fake([
            KanyeRestService::API_URI => Http::sequence()
                ->push(['quote' => 'I am the best'], 200)
                ->push(['quote' => 'I am the greatest'], 200),
        ]);

        $kanyeRestService = new KanyeRestService();

        $quotes = $kanyeRestService->fetchRandomUniqueQuotes(2);
        $this->assertRandomUniqueQuotes($quotes, 2);
    }

    #[DataProvider('responseDataProvider')]
    #[Test]
    public function fails_when(int $code, string $exceptionMessage): void
    {
        Http::fake([
            KanyeRestService::API_URI => Http::response('', $code)
        ]);

        $kanyeRestService = new KanyeRestService();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage($exceptionMessage);

        $kanyeRestService->fetchRandomUniqueQuotes(1);
    }

    public static function responseDataProvider(): array
    {
        return [
            'Unknown response' => [
                'code' => Response::HTTP_INSUFFICIENT_STORAGE,
                'exceptionMessage' => 'Not a supported response.'
            ],
            'Not found' => [
                'code' => Response::HTTP_NOT_FOUND,
                'exceptionMessage' => 'The API URI is not found.'
            ],
            'Rate limit reached' => [
                'code' => Response::HTTP_TOO_MANY_REQUESTS,
                'exceptionMessage' => 'Rate limit reached.'
            ],
            'Unauthorized' => [
                'code' => Response::HTTP_UNAUTHORIZED,
                'exceptionMessage' => 'Unauthorized.'
            ],
            'Forbidden' => [
                'code' => Response::HTTP_FORBIDDEN,
                'exceptionMessage' => 'Forbidden.'
            ],
            'Internal server error' => [
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'exceptionMessage' => 'Internal server error.'
            ],
            'Service unavailable' => [
                'code' => Response::HTTP_SERVICE_UNAVAILABLE,
                'exceptionMessage' => 'Service unavailable.'
            ],
        ];
    }
}
