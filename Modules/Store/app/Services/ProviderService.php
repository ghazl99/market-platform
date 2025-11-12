<?php

namespace Modules\Store\Services;

use Modules\Store\Models\Provider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProviderService
{
    /**
     * Get products from provider API
     */
    public function getProductsFromProvider(Provider $provider): array
    {
        try {
            // Use api_url directly as stored in database
            $apiUrl = trim($provider->api_url);
            $apiToken = trim($provider->api_token);

            Log::info('Fetching products from provider', [
                'provider_id' => $provider->id,
                'provider_name' => $provider->name,
                'api_url' => $apiUrl,
                'token_length' => strlen($apiToken),
                'token_preview' => substr($apiToken, 0, 10) . '...'
            ]);

            // Try the api_url as is first (most common case - full URL)
            $urlsToTry = [$apiUrl];

            // If api_url doesn't look like a complete products endpoint, try adding common paths
            if (!str_contains($apiUrl, '/products')) {
                $baseUrl = rtrim($apiUrl, '/');
                // Try common API endpoint patterns
                $urlsToTry[] = $baseUrl . '/api/products';
                $urlsToTry[] = $baseUrl . '/products';
            }

            $response = null;
            $lastError = null;
            $successfulUrl = null;

            foreach ($urlsToTry as $url) {
                Log::info('Attempting to fetch products', [
                    'provider_id' => $provider->id,
                    'url' => $url
                ]);

                try {
                    // Use Guzzle directly to match Postman behavior exactly
                    $client = new \GuzzleHttp\Client([
                        'timeout' => 30,
                        'verify' => true,
                        'allow_redirects' => true,
                        'headers' => [
                            'token' => $apiToken,
                            'Accept' => 'application/json',
                            'User-Agent' => 'PostmanRuntime/7.32.3', // Match Postman user agent
                        ],
                    ]);

                    $response = $client->request('GET', $url);
                    $statusCode = $response->getStatusCode();
                    $body = $response->getBody()->getContents();

                    // Convert to Laravel Http Response format for compatibility
                    $response = new \Illuminate\Http\Client\Response(
                        new \GuzzleHttp\Psr7\Response($statusCode, [], $body)
                    );

                    if ($response->successful()) {
                        $successfulUrl = $url;
                        Log::info('Successfully fetched products', [
                            'provider_id' => $provider->id,
                            'url' => $url,
                            'status' => $response->status(),
                            'response_size' => strlen($response->body())
                        ]);
                        break; // Success, exit loop
                    } else {
                        $responseBody = $response->body();
                        $lastError = [
                            'url' => $url,
                            'status' => $response->status(),
                            'body' => substr($responseBody, 0, 500), // First 500 chars
                            'is_cloudflare' => str_contains($responseBody, 'Checking your browser') || str_contains($responseBody, 'cloudflare'),
                        ];
                        Log::warning('Failed to fetch products', $lastError);

                        // If Cloudflare protection detected, log a helpful message
                        if ($lastError['is_cloudflare']) {
                            Log::error('Cloudflare protection detected!', [
                                'provider_id' => $provider->id,
                                'message' => 'The API endpoint is protected by Cloudflare. You may need to: 1) Whitelist your server IP, 2) Use a proxy service, or 3) Contact the API provider to disable Cloudflare for API endpoints.'
                            ]);
                        }
                    }
                } catch (\Exception $e) {
                    $lastError = [
                        'url' => $url,
                        'error' => $e->getMessage(),
                        'trace' => substr($e->getTraceAsString(), 0, 500)
                    ];
                    Log::warning('Exception while fetching products', $lastError);
                }
            }

            if (!$response || !$response->successful()) {
                Log::error('All URL attempts failed', [
                    'provider_id' => $provider->id,
                    'provider_name' => $provider->name,
                    'api_url_from_db' => $apiUrl,
                    'attempts' => $urlsToTry,
                    'last_error' => $lastError
                ]);
                return [];
            }

            // Process successful response
            $responseBody = $response->body();
            $data = json_decode($responseBody, true);

            // If json_decode failed, try to get the response as array
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::warning('JSON decode error', [
                    'provider_id' => $provider->id,
                    'error' => json_last_error_msg(),
                    'response_preview' => substr($responseBody, 0, 200)
                ]);
                return [];
            }

            Log::info('Provider API response received', [
                'provider_id' => $provider->id,
                'url' => $successfulUrl,
                'response_type' => gettype($data),
                'has_data_key' => isset($data['data']),
                'is_array' => is_array($data),
                'count' => is_array($data) ? (isset($data['data']) ? count($data['data']) : count($data)) : 0,
                'sample' => is_array($data) && !empty($data) ? (isset($data[0]) ? $data[0] : (isset($data['data'][0]) ? $data['data'][0] : null)) : null
            ]);

            // Handle different response formats
            if (isset($data['data']) && is_array($data['data']) && !empty($data['data'])) {
                Log::info('Returning products from data.data key', [
                    'provider_id' => $provider->id,
                    'count' => count($data['data'])
                ]);
                return $data['data'];
            } elseif (is_array($data) && !empty($data)) {
                // Check if it's a direct array of products (has numeric keys and product-like structure)
                // From Postman: response is direct array like [{"id": 7, "name": "...", ...}, ...]
                if (isset($data[0])) {
                    // Check if first element is an array (product object)
                    if (is_array($data[0]) && (isset($data[0]['id']) || isset($data[0]['name']))) {
                        Log::info('Returning products as direct array', [
                            'provider_id' => $provider->id,
                            'count' => count($data),
                            'first_product' => $data[0]
                        ]);
                        return $data;
                    }
                    // Also check if it's a simple array with numeric indices
                    elseif (is_numeric(array_key_first($data))) {
                        Log::info('Returning products as numeric array', [
                            'provider_id' => $provider->id,
                            'count' => count($data),
                            'first_item' => $data[0]
                        ]);
                        return $data;
                    }
                }
            }

            Log::warning('Unexpected response format', [
                'provider_id' => $provider->id,
                'data_type' => gettype($data),
                'data_keys' => is_array($data) ? array_keys($data) : 'not_array',
                'data_sample' => is_array($data) ? array_slice($data, 0, 2) : $data
            ]);

            return [];
        } catch (\Exception $e) {
            Log::error('Error fetching products from provider', [
                'provider_id' => $provider->id,
                'provider_name' => $provider->name,
                'api_url' => $provider->api_url ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => substr($e->getTraceAsString(), 0, 1000)
            ]);

            return [];
        }
    }
}

