<?php

declare(strict_types=1);

namespace WebSupport\JaneOpenApi;

use BadMethodCallException;
use InvalidArgumentException;

/**
 * Captures pagination and sorting from the request.
 * Null size means that all results should be returned.
 */
class Page
{
    /** @var int */
    private $current;

    /** @var int */
    private $limit;

    /** @var int */
    private $pageResultsCount;

    /** @var int */
    private $pageCount;

    /** @var bool */
    private $hasNext;

    /** @var bool */
    private $hasPrevious;

    public function __construct(
        int $current,
        int $limit,
        int $pageResultsCount,
        int $pageCount,
        bool $hasNext,
        bool $hasPrevious
    ) {

        if ($current < 0) {
            throw new InvalidArgumentException('Page index must not be less than zero!');
        }

        $this->pageResultsCount = $pageResultsCount;
        $this->current = $current;
        $this->limit = $limit;
        $this->pageCount = $pageCount;
        $this->hasNext = $hasNext;
        $this->hasPrevious = $hasPrevious;
    }

    public function getPageResultsCount(): int
    {
        return $this->pageResultsCount;
    }

    public function getCurrent(): int
    {
        return $this->current;
    }

    public function getPageCount(): int
    {
        return $this->pageCount;
    }

    public function hasNext(): bool
    {
        return $this->hasNext;
    }

    public function hasPrevious(): bool
    {
        return $this->hasPrevious;
    }

    /**
     * @return int[]
     */
    public function next(): array
    {
        if (!$this->hasNext) {
            throw new BadMethodCallException('There is no next page');
        }

        return [
            'limit' => $this->limit,
            'page' => $this->current + 1,
        ];
    }

    /**
     * @return int[]
     */
    public function previous(): array
    {
        if (!$this->hasPrevious) {
            throw new BadMethodCallException('There is no next page');
        }

        return [
            'limit' => $this->limit,
            'page' => $this->current - 1,
        ];
    }

    /**
     * @return int[]
     */
    public function first(): array
    {
        return [
            'limit' => $this->limit,
            'page' => 0,
        ];
    }

    /**
     * @return int[]
     */
    public function last(): array
    {
        return [
            'limit' => $this->limit,
            'page' => $this->pageCount - 1,
        ];
    }

    /**
     * @param string[][] $headers
     */
    public static function fromHeaders(array $headers): Page
    {
        return new Page(
            (int)$headers['ws-page-current'][0],
            (int)$headers['ws-page-limit'][0],
            (int)$headers['ws-result-count'][0],
            (int)$headers['ws-page-count'][0],
            $headers['ws-page-next'][0] === 'true',
            $headers['ws-page-previous'][0] === 'true'
        );
    }

    /**
     * @param string[][] $headers
     */
    public static function containsPaginationHeaders(array $headers): bool
    {
        return isset(
            $headers['ws-result-count'],
            $headers['ws-page-current'],
            $headers['ws-page-limit'],
            $headers['ws-page-count'],
            $headers['ws-page-next'],
            $headers['ws-page-previous']
        );
    }
}
