<?php

declare(strict_types=1);

namespace WebSupport\JaneOpenApi;

use InvalidArgumentException;

class Sort
{
    /**
     * Null means unsorted
     *
     * @var string|null
     */
    private $field;

    /** @var string */
    private $direction;

    private const DIRECTION_ASC = 'asc';
    private const DIRECTION_DESC = 'desc';

    public function __construct(?string $field, ?string $direction)
    {
        $direction = $direction ?? self::DIRECTION_ASC;

        if (!in_array($direction, [self::DIRECTION_ASC, self::DIRECTION_DESC])) {
            throw new InvalidArgumentException(
                sprintf('Direction must be one of %s', implode(', ', [self::DIRECTION_ASC, self::DIRECTION_DESC]))
            );
        }

        $this->field = $field;
        $this->direction = $direction;
    }

    /**
     * @return string|null
     */
    public function getField(): ?string
    {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getDirection(): string
    {
        return $this->direction;
    }
}
