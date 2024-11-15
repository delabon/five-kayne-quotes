<?php

namespace Tests\Feature;

use App\Services\KanyeRestService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\AssertsRandomUniqueQuotes;

class FetchFiveRandomQuotesTest extends TestCase
{
    use AssertsRandomUniqueQuotes;

    #[Test]
    public function fetches_five_random_unique_quotes(): void
    {
        $response = $this->get('/api/quotes')
            ->assertStatus(200);

        $json = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('quotes', $json);
        $this->assertRandomUniqueQuotes($json['quotes'], 5);
    }

    #[Test]
    public function quotes_are_dynamic(): void
    {
        $responseOne = $this->get('/api/quotes')
            ->assertStatus(200);

        $responseTwo = $this->get('/api/quotes')
            ->assertStatus(200);

        $jsonOne = json_decode($responseOne->getContent(), true);
        $jsonTwo = json_decode($responseTwo->getContent(), true);

        $this->assertNotEquals($jsonOne, $jsonTwo);
    }

    /**
     * It is ok here to fake the response, because we don't have control over the API server, and we need to handle some of its responses.
     */
    #[DataProvider('responseDataProvider')]
    #[Test]
    public function fails_when(int $code, string $errorMessage): void
    {
        Http::fake([
            KanyeRestService::API_URI => Http::response('', $code)
        ]);

        $response = $this->get('/api/quotes')
            ->assertInternalServerError();

        $json = $response->json();

        $response->assertJson($json);
        $this->assertArrayHasKey('error', $json);
        $this->assertSame($errorMessage, $json['error']);
    }

    public static function responseDataProvider(): array
    {
        return [
            'Unknown response' => [
                'code' => Response::HTTP_INSUFFICIENT_STORAGE,
                'errorMessage' => 'Not a supported response.'
            ],
            'Not found' => [
                'code' => Response::HTTP_NOT_FOUND,
                'errorMessage' => 'The API URI is not found.'
            ],
            'Rate limit reached' => [
                'code' => Response::HTTP_TOO_MANY_REQUESTS,
                'errorMessage' => 'Rate limit reached.'
            ],
            'Unauthorized' => [
                'code' => Response::HTTP_UNAUTHORIZED,
                'errorMessage' => 'Unauthorized.'
            ],
            'Forbidden' => [
                'code' => Response::HTTP_FORBIDDEN,
                'errorMessage' => 'Forbidden.'
            ],
            'Internal server error' => [
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'errorMessage' => 'Internal server error.'
            ],
            'Service unavailable' => [
                'code' => Response::HTTP_SERVICE_UNAVAILABLE,
                'errorMessage' => 'Service unavailable.'
            ],
        ];
    }
}
