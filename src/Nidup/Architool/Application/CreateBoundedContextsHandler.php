<?php

namespace Nidup\Architool\Application;

use Nidup\Architool\Domain\BoundedContext;
use Nidup\Architool\Domain\BoundedContextRepository;

final class CreateBoundedContextsHandler
{
    private $repository;

    public function __construct(BoundedContextRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(CreateBoundedContexts $command): void
    {
        foreach ($command->getNames() as $name) {
            $this->repository->create(new BoundedContext($name));
        }
    }
}