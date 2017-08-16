<?php

declare(strict_types=1);

namespace Nidup\Architool\Domain;

interface BoundedContextRepository
{
    public function create(BoundedContext $context);
}
