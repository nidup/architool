<?php

namespace Nidup\Architool\Application;

final class CreateBoundedContexts
{
    private $names;

    public function __construct(array $names)
    {
        $this->names = $names;
    }

    public function getNames(): array
    {
        return $this->names;
    }
}