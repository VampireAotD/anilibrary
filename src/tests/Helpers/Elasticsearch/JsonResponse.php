<?php

declare(strict_types=1);

namespace Tests\Helpers\Elasticsearch;

use Elastic\Elasticsearch\Response\Elasticsearch;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Symfony\Component\HttpFoundation\Response as ResponseStatus;

/**
 * Class JsonResponse
 * @package Tests\Helpers\Elascticsearch
 */
final class JsonResponse extends GuzzleResponse
{
    private const ES_HEADERS = [Elasticsearch::HEADER_CHECK => Elasticsearch::PRODUCT_NAME];

    public function __construct(
        $body = null,
        int $status = ResponseStatus::HTTP_OK,
        array $headers = [],
        string $version = '1.1',
        string $reason = null
    ) {
        $headers = array_merge(self::ES_HEADERS, ['Content-Type' => 'application/json'], $headers);

        parent::__construct($status, $headers, $body, $version, $reason);
    }
}
