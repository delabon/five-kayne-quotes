<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class KanyeRestService
{
    CONST string API_URI = 'https://api.kanye.rest';

    /**
     * @throws RuntimeException
     */
    public function fetchRandomUniqueQuotes(int $number): array
    {
        $quotes = [];

        while (count($quotes) !== $number) {
            $response = Http::get(self::API_URI);

            match ($response->getStatusCode()) {
                Response::HTTP_OK => $json = $response->json(),
                Response::HTTP_NOT_FOUND => throw new RuntimeException('The API URI is not found.'),
                Response::HTTP_TOO_MANY_REQUESTS => throw new RuntimeException('Rate limit reached.'),
                Response::HTTP_UNAUTHORIZED => throw new RuntimeException('Unauthorized.'),
                Response::HTTP_FORBIDDEN => throw new RuntimeException('Forbidden.'),
                Response::HTTP_INTERNAL_SERVER_ERROR => throw new RuntimeException('Internal server error.'),
                Response::HTTP_SERVICE_UNAVAILABLE => throw new RuntimeException('Service unavailable.'),
                default => throw new RuntimeException('Not a supported response.'),
            };

            $json = $response->json();

            if (is_array($json) && isset($json['quote']) && !in_array($json['quote'], $quotes)) {
                $quotes[] = $json['quote'];
            }
        }

        return $quotes;
    }
}
