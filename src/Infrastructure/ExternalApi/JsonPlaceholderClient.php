<?php

namespace App\Infrastructure\ExternalApi;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class JsonPlaceholderClient
{
    public function __construct(
        private HttpClientInterface $httpClient
    ) {}

    public function fetchUsers(): array
    {
        try {
            $response = $this->httpClient->request('GET', 'https://jsonplaceholder.typicode.com/users');

            if (200 !== $response->getStatusCode()) {
                throw new \RuntimeException('API returned an error: ' . $response->getStatusCode());
            }

            return $response->toArray();

        } catch (TransportExceptionInterface $e) {
            throw new \RuntimeException('Network error when requesting JSONPlaceholder: ' . $e->getMessage());
        }
    }
}
