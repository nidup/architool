<?php

declare(strict_types=1);

namespace Nidup\Architool\Domain\FileStorage\File;

class Name
{
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
