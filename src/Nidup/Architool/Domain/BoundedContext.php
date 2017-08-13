<?php

namespace Nidup\Architool\Domain;

final class BoundedContext
{
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}