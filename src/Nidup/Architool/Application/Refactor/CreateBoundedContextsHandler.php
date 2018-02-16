<?php

declare(strict_types=1);

namespace Nidup\Architool\Application\Refactor;

use Nidup\Architool\Domain\BoundedContext\BoundedContext;
use Nidup\Architool\Domain\BoundedContext\BoundedContextRepository;

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
            $this->repository->add(new BoundedContext($name));
        }
    }
}
