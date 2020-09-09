<?php

declare(strict_types=1);

namespace WebSupport\JaneOpenApi;

use ArrayIterator;

class PaginatedCollection extends ArrayIterator
{
    /** @var Page */
    private $page;

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingTraversableParameterTypeHintSpecification
     */
    public function __construct(array $data, Page $page)
    {
        parent::__construct($data);
        $this->page = $page;
    }

    public function getPage(): Page
    {
        return $this->page;
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingTraversableParameterTypeHintSpecification
     */
    public static function isPaginated(iterable $collection): bool
    {
        return $collection instanceof PaginatedCollection;
    }
}
