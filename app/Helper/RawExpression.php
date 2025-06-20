<?php

declare(strict_types=1);

namespace App\Helper;

class RawExpression
{
    public function __construct(private string $value)
    {
    }

    public function __toString(): string
    {
        return $this->value;
    }
}