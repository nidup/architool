<?php

namespace Nidup\Architool\Domain;

interface BoundedContextRepository
{
    public function create(BoundedContext $context);
}
