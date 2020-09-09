<?php

declare(strict_types=1);

namespace WebSupport\JaneOpenApi;

use Jane\OpenApiRuntime\Client\Client;
use Jane\OpenApiRuntime\Client\Exception\InvalidFetchModeException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Serializer\SerializerInterface;

// phpcs:ignore SlevomatCodingStandard.Classes.SuperfluousTraitNaming.SuperfluousSuffix
trait PaginatedPsr7EndpointTrait
{
    abstract protected function transformResponseBody(string $body, int $status, SerializerInterface $serializer, ?string $contentType = null);

    public function parsePSR7Response(ResponseInterface $response, SerializerInterface $serializer, string $fetchMode = Client::FETCH_OBJECT)
    {
        if ($fetchMode === Client::FETCH_OBJECT) {
            $contentType = $response->hasHeader('Content-Type')
                ? current($response->getHeader('Content-Type'))
                : null;

            $transformedResponse = $this->transformResponseBody((string) $response->getBody(), $response->getStatusCode(), $serializer, $contentType);

            if (is_array($transformedResponse) && $this->responseContainsPaginationHeaders($response)) {
                return new PaginatedCollection($transformedResponse, $this->extractPageAndSortFromHeaders($response));
            }

            return $transformedResponse;
        }

        if ($fetchMode === Client::FETCH_RESPONSE) {
            return $response;
        }

        throw new InvalidFetchModeException(sprintf('Fetch mode %s is not supported', $fetchMode));
    }

    private function responseContainsPaginationHeaders(ResponseInterface $response): bool
    {
        return Page::containsPaginationHeaders($response->getHeaders());
    }

    private function extractPageAndSortFromHeaders(ResponseInterface $response): Page
    {
        return Page::fromHeaders($response->getHeaders());
    }
}
