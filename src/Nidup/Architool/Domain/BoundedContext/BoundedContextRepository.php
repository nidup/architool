<?php

declare(strict_types=1);

namespace Nidup\Architool\Domain\BoundedContext;

interface BoundedContextRepository
{
    public function add(BoundedContext $context);
}
