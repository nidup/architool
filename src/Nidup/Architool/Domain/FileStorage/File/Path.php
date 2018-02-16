<?php

declare(strict_types=1);

namespace Nidup\Architool\Domain\FileStorage\File;

class Path
{
    private $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
