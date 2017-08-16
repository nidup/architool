<?php

declare(strict_types=1);

namespace Nidup\Architool\Application\BoundedContext;

use Nidup\Architool\Application\BoundedContext\CreateBoundedContexts;
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
