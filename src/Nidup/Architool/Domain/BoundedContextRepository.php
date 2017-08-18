<?php

declare(strict_types=1);

namespace Nidup\Architool\Domain;

use Nidup\Architool\Domain\Model\BoundedContext;

interface BoundedContextRepository
{
    public function add(BoundedContext $context);
}
