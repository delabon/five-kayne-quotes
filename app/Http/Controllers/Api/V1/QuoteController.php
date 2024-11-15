<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\KanyeRestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use RuntimeException;

class QuoteController extends Controller
{
    public function random(KanyeRestService $kanyeRestService): JsonResponse
    {
        try {
            $quotes = $kanyeRestService->fetchRandomUniqueQuotes(config('app.random_quotes_number'));

            return new JsonResponse([
                'quotes' => $quotes
            ]);
        } catch (RuntimeException $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
