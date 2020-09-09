<?php

declare(strict_types=1);

namespace WebSupport\JaneOpenApi\Generator;

use Jane\OpenApi3\Generator\EndpointGenerator;
use Jane\OpenApiRuntime\Client\Psr7Endpoint;
use WebSupport\JaneOpenApi\PaginatedPsr7EndpointTrait;

class PaginatedPsr7EndpointGenerator extends EndpointGenerator
{
    protected function getInterface(): array
    {
        return [Psr7Endpoint::class];
    }

    protected function getTrait(): array
    {
        return [PaginatedPsr7EndpointTrait::class];
    }
}
